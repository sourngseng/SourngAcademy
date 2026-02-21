<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\GuestReview;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display guest's bookings
     */
    public function index()
    {
        $bookings = Auth::user()->bookings()
            ->with(['rooms.roomType'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('guest.bookings.index', compact('bookings'));
    }

    /**
     * Show booking details
     */
    public function show(Booking $booking)
    {
        // Ensure guest can only view their own bookings
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        
        $booking->load(['rooms.roomType', 'review']);
        
        // Get hotel info for invoice
        $hotelName = Setting::get('hotel_name', 'Hotel MIS');
        $hotelLogo = Setting::get('hotel_logo');
        $hotelAddress = Setting::get('hotel_address');
        $hotelPhone = Setting::get('hotel_phone');
        $hotelEmail = Setting::get('hotel_email');
        
        return view('guest.bookings.show', compact(
            'booking', 
            'hotelName', 
            'hotelLogo', 
            'hotelAddress', 
            'hotelPhone', 
            'hotelEmail'
        ));
    }

    /**
     * Show review form for a booking
     */
    public function reviewForm(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        
        if ($booking->status !== 'checked_out') {
            return back()->with('error', 'You can only review after checkout.');
        }
        
        if ($booking->review) {
            return back()->with('error', 'You have already reviewed this booking.');
        }
        
        return view('guest.bookings.review', compact('booking'));
    }

    /**
     * Submit a review
     */
    public function submitReview(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'overall_rating' => 'required|integer|min:1|max:5',
            'staff_rating' => 'nullable|integer|min:1|max:5',
            'cleanliness_rating' => 'nullable|integer|min:1|max:5',
            'comfort_rating' => 'nullable|integer|min:1|max:5',
            'location_rating' => 'nullable|integer|min:1|max:5',
            'value_rating' => 'nullable|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'review_text' => 'required|string|min:10',
        ]);
        
        GuestReview::create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'overall_rating' => $request->overall_rating,
            'staff_rating' => $request->staff_rating,
            'cleanliness_rating' => $request->cleanliness_rating,
            'comfort_rating' => $request->comfort_rating,
            'location_rating' => $request->location_rating,
            'value_rating' => $request->value_rating,
            'title' => $request->title,
            'review_text' => $request->review_text,
            'status' => 'pending',
        ]);
        
        return redirect()->route('guest.bookings.index')
            ->with('success', 'Thank you for your review! It will be published after moderation.');
    }

    /**
     * Show booking creation form
     */
    public function create(Request $request)
    {
        $roomId = $request->query('room');
        $checkIn = $request->query('check_in');
        $checkOut = $request->query('check_out');
        $guests = $request->query('guests', 2);

        $room = null;
        $roomType = null;
        $nights = 1;
        $totalPrice = 0;
        $isAuthenticated = Auth::check();
        $isGuest = $isAuthenticated && Auth::user()->role === 'guest';

        if ($roomId) {
            $room = Room::with('roomType')->find($roomId);
            if ($room) {
                $roomType = $room->roomType;
                
                if ($checkIn && $checkOut) {
                    $nights = Carbon::parse($checkIn)->diffInDays(Carbon::parse($checkOut));
                    if ($nights < 1) $nights = 1;
                    $totalPrice = $room->price_per_night * $nights;
                }
            }
        }

        // Get all room types for selection
        $roomTypes = RoomType::withCount('rooms')->get();

        // Get hotel settings for display
        $hotelName = Setting::get('hotel_name', 'Hotel MIS');

        return view('guest.bookings.create', compact(
            'room',
            'roomType',
            'roomTypes',
            'checkIn',
            'checkOut',
            'guests',
            'nights',
            'totalPrice',
            'hotelName',
            'isAuthenticated',
            'isGuest'
        ));
    }

    /**
     * Store a new booking
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        $room = Room::with('roomType')->findOrFail($request->room_id);
        
        // Calculate nights and total
        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $nights = $checkIn->diffInDays($checkOut);
        if ($nights < 1) $nights = 1;
        
        $totalPrice = $room->price_per_night * $nights;

        // Check room availability
        $isOccupied = Booking::where(function($q) use ($checkIn, $checkOut) {
            $q->whereBetween('check_in', [$checkIn, $checkOut->subDay()])
              ->orWhereBetween('check_out', [$checkIn->addDay(), $checkOut])
              ->orWhere(function($q) use ($checkIn, $checkOut) {
                   $q->where('check_in', '<=', $checkIn)
                     ->where('check_out', '>=', $checkOut);
              });
        })
        ->whereHas('rooms', function($q) use ($room) {
            $q->where('rooms.id', $room->id);
        })
        ->where('status', '!=', 'cancelled')
        ->exists();

        if ($isOccupied) {
            return back()->withInput()->with('error', 'Sorry, this room is not available for the selected dates.');
        }

        // Create the booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'special_requests' => $request->special_requests,
        ]);

        // Attach room to booking
        $booking->rooms()->attach($room->id, ['price' => $room->price_per_night]);

        // Redirect to payment page
        return redirect()->route('guest.payments.create', $booking)
            ->with('success', 'Booking created successfully! Please complete your payment.');
    }
}

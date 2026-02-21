<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings for staff
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Booking::with(['user', 'rooms']);
            
            if ($request->has('status') && $request->status != 'all') {
                $data->where('status', $request->status);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('staff.bookings.show', $row->id).'" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>';
                    return $btn;
                })
                ->addColumn('guest_name', function($row){
                    return $row->user->name ?? 'N/A';
                })
                ->addColumn('rooms', function($row){
                    return $row->rooms->pluck('room_number')->implode(', ');
                })
                ->editColumn('status', function($row){
                    $colors = [
                        'pending' => 'badge-warning',
                        'confirmed' => 'badge-primary',
                        'checked_in' => 'badge-success',
                        'checked_out' => 'badge-secondary',
                        'cancelled' => 'badge-danger',
                    ];
                    $color = $colors[$row->status] ?? 'badge-secondary';
                    return '<span class="badge '.$color.'">'.ucfirst(str_replace('_', ' ', $row->status)).'</span>';
                })
                ->editColumn('total_price', function($row){
                    return '$' . number_format($row->total_price, 2);
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('staff.bookings.index');
    }

    /**
     * Show booking details
     */
    public function show(Booking $booking)
    {
        $booking->load(['user.guestProfile', 'rooms.roomType']);
        return view('staff.bookings.show', compact('booking'));
    }

    /**
     * Check-in a guest
     */
    public function checkIn(Booking $booking)
    {
        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'Only confirmed bookings can be checked in.');
        }
        
        $booking->update(['status' => 'checked_in']);
        
        // Update room status
        foreach ($booking->rooms as $room) {
            $room->update(['status' => 'occupied']);
        }
        
        return back()->with('success', 'Guest checked in successfully!');
    }

    /**
     * Check-out a guest
     */
    public function checkOut(Booking $booking)
    {
        if ($booking->status !== 'checked_in') {
            return back()->with('error', 'Only checked-in guests can be checked out.');
        }
        
        $booking->update(['status' => 'checked_out']);
        
        // Update room status
        foreach ($booking->rooms as $room) {
            $room->update(['status' => 'available']);
        }
        
        return back()->with('success', 'Guest checked out successfully!');
    }

    /**
     * Today's arrivals
     */
    public function arrivals(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();
        
        $arrivals = Booking::with(['user.guestProfile', 'rooms.roomType'])
            ->whereDate('check_in', $date)
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('staff.bookings.arrivals', compact('arrivals', 'date'));
    }

    /**
     * Today's departures
     */
    public function departures(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();
        
        $departures = Booking::with(['user.guestProfile', 'rooms.roomType'])
            ->whereDate('check_out', $date)
            ->whereIn('status', ['checked_in', 'checked_out'])
            ->orderBy('check_out', 'asc')
            ->get();
            
        return view('staff.bookings.departures', compact('departures', 'date'));
    }
}

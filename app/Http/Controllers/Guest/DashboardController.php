<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $stats = [
            'total_bookings' => $user->bookings()->count(),
            'upcoming_stays' => $user->bookings()
                ->where('check_in', '>=', now())
                ->whereIn('status', ['pending', 'confirmed'])
                ->count(),
            'completed_stays' => $user->bookings()
                ->where('status', 'checked_out')
                ->count(),
            'total_spent' => $user->bookings()
                ->where('payment_status', 'paid')
                ->sum('total_price'),
        ];
        
        // Upcoming bookings
        $upcomingBookings = $user->bookings()
            ->with(['rooms.roomType'])
            ->where('check_in', '>=', now())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('check_in', 'asc')
            ->take(5)
            ->get();
        
        // Current stay (if any)
        $currentStay = $user->bookings()
            ->with(['rooms.roomType'])
            ->where('status', 'checked_in')
            ->first();
        
        return view('guest.dashboard', compact('stats', 'upcomingBookings', 'currentStay'));
    }
}

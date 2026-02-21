@extends('guest.layouts.master')

@section('title', 'My Dashboard')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-home mr-2"></i>Welcome back, {{ Auth::user()->name }}!</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')

<!-- Current Stay Alert -->
@if($currentStay)
<div class="alert alert-success mb-4">
    <div class="d-flex align-items-center">
        <div class="mr-3">
            <i class="fas fa-hotel fa-2x"></i>
        </div>
        <div class="flex-grow-1">
            <h5 class="alert-heading mb-1">You're currently checked in!</h5>
            <p class="mb-0">
                Room(s): <strong>{{ $currentStay->rooms->pluck('room_number')->implode(', ') }}</strong> | 
                Check-out: <strong>{{ $currentStay->check_out->format('D, M d, Y') }}</strong>
            </p>
        </div>
        <a href="{{ route('guest.bookings.show', $currentStay->id) }}" class="btn btn-light">
            View Details
        </a>
    </div>
</div>
@endif

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 col-6 mb-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <i class="fas fa-calendar-check icon"></i>
            <h3>{{ $stats['total_bookings'] }}</h3>
            <p>Total Bookings</p>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <i class="fas fa-plane-arrival icon"></i>
            <h3>{{ $stats['upcoming_stays'] }}</h3>
            <p>Upcoming Stays</p>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <i class="fas fa-check-circle icon"></i>
            <h3>{{ $stats['completed_stays'] }}</h3>
            <p>Completed Stays</p>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <i class="fas fa-dollar-sign icon"></i>
            <h3>${{ number_format($stats['total_spent'], 0) }}</h3>
            <p>Total Spent</p>
        </div>
    </div>
</div>

<!-- Upcoming Bookings -->
<div class="card guest-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-calendar-alt text-primary mr-2"></i> Upcoming Bookings</span>
        <a href="{{ route('guest.bookings.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body">
        @if($upcomingBookings->count() > 0)
            @foreach($upcomingBookings as $booking)
                <div class="booking-card">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center mb-3 mb-md-0">
                            <div class="bg-light rounded py-3">
                                <h4 class="mb-0 text-primary">{{ $booking->check_in->format('d') }}</h4>
                                <small class="text-muted">{{ $booking->check_in->format('M Y') }}</small>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h5 class="mb-1">
                                @foreach($booking->rooms as $room)
                                    <span class="badge badge-light">{{ $room->roomType->room_type_name ?? 'Room' }} - #{{ $room->room_number }}</span>
                                @endforeach
                            </h5>
                            <p class="text-muted mb-0">
                                <i class="fas fa-calendar"></i> 
                                {{ $booking->check_in->format('M d') }} - {{ $booking->check_out->format('M d, Y') }}
                                <span class="mx-2">|</span>
                                <i class="fas fa-moon"></i> {{ $booking->check_in->diffInDays($booking->check_out) }} night(s)
                            </p>
                        </div>
                        <div class="col-md-2 text-center mb-3 mb-md-0">
                            <span class="badge badge-{{ $booking->status == 'confirmed' ? 'success' : 'warning' }} px-3 py-2">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                        <div class="col-md-2 text-right">
                            <a href="{{ route('guest.bookings.show', $booking->id) }}" class="btn btn-gradient btn-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No upcoming bookings</h5>
                <p class="text-muted">Ready for your next adventure?</p>
            </div>
        @endif
    </div>
</div>

<!-- Quick Links -->
<div class="row mt-4">
    <div class="col-md-4 mb-3">
        <div class="card guest-card h-100">
            <div class="card-body text-center py-4">
                <i class="fas fa-user-edit fa-3x text-primary mb-3"></i>
                <h5>Update Profile</h5>
                <p class="text-muted">Keep your information up to date</p>
                <a href="{{ route('guest.profile.edit') }}" class="btn btn-outline-primary">Edit Profile</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card guest-card h-100">
            <div class="card-body text-center py-4">
                <i class="fas fa-history fa-3x text-success mb-3"></i>
                <h5>Booking History</h5>
                <p class="text-muted">View your past stays</p>
                <a href="{{ route('guest.bookings.index') }}" class="btn btn-outline-success">View History</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card guest-card h-100">
            <div class="card-body text-center py-4">
                <i class="fas fa-headset fa-3x text-info mb-3"></i>
                <h5>Need Help?</h5>
                <p class="text-muted">Contact our support team</p>
                <a href="mailto:{{ App\Models\Setting::get('hotel_email', 'support@hotel.com') }}" class="btn btn-outline-info">Contact Us</a>
            </div>
        </div>
    </div>
</div>
@endsection

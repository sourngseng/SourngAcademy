@extends('guest.layouts.master')

@section('title', 'Booking Details')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-calendar-check mr-2"></i>Booking #{{ $booking->id }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('guest.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guest.bookings.index') }}">My Bookings</a></li>
                    <li class="breadcrumb-item active">Booking #{{ $booking->id }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')

<div class="row">
    <div class="col-md-8">
        <!-- Booking Status Card -->
        <div class="card guest-card mb-4">
            <div class="card-body">
                @php
                    $statusColors = [
                        'pending' => ['bg' => '#fff3cd', 'text' => '#856404'],
                        'confirmed' => ['bg' => '#d1ecf1', 'text' => '#0c5460'],
                        'checked_in' => ['bg' => '#d4edda', 'text' => '#155724'],
                        'checked_out' => ['bg' => '#e2e3e5', 'text' => '#383d41'],
                        'cancelled' => ['bg' => '#f8d7da', 'text' => '#721c24']
                    ];
                    $color = $statusColors[$booking->status] ?? ['bg' => '#e2e3e5', 'text' => '#383d41'];
                @endphp
                <div class="d-flex justify-content-between align-items-center p-3 rounded" style="background: {{ $color['bg'] }}; color: {{ $color['text'] }};">
                    <div>
                        <h5 class="mb-0">
                            <i class="fas fa-{{ $booking->status == 'checked_in' ? 'check-circle' : ($booking->status == 'cancelled' ? 'times-circle' : 'clock') }}"></i>
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </h5>
                    </div>
                    <div>
                        @if($booking->payment_status == 'paid')
                            <span class="badge badge-success px-3 py-2"><i class="fas fa-check"></i> Paid</span>
                        @elseif($booking->payment_status == 'partial')
                            <span class="badge badge-warning px-3 py-2"><i class="fas fa-exclamation"></i> Partial Payment</span>
                        @else
                            <span class="badge badge-danger px-3 py-2"><i class="fas fa-times"></i> Unpaid</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Stay Details -->
        <div class="card guest-card mb-4">
            <div class="card-header">
                <strong><i class="fas fa-calendar-alt text-primary"></i> Stay Details</strong>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center border-right">
                        <small class="text-muted">Check-in</small>
                        <h4 class="mb-0">{{ $booking->check_in->format('d') }}</h4>
                        <p class="mb-0 text-muted">{{ $booking->check_in->format('M Y') }}</p>
                        <small>{{ $booking->check_in->format('l') }}</small>
                    </div>
                    <div class="col-md-4 text-center border-right">
                        <small class="text-muted">Duration</small>
                        <h4 class="mb-0">{{ $booking->check_in->diffInDays($booking->check_out) }}</h4>
                        <p class="mb-0 text-muted">Night(s)</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <small class="text-muted">Check-out</small>
                        <h4 class="mb-0">{{ $booking->check_out->format('d') }}</h4>
                        <p class="mb-0 text-muted">{{ $booking->check_out->format('M Y') }}</p>
                        <small>{{ $booking->check_out->format('l') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Room Details -->
        <div class="card guest-card mb-4">
            <div class="card-header">
                <strong><i class="fas fa-bed text-primary"></i> Room Details</strong>
            </div>
            <div class="card-body p-0">
                @foreach($booking->rooms as $room)
                    <div class="p-3 border-bottom">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="mb-1">{{ $room->roomType->room_type_name ?? 'Room' }}</h5>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-door-open"></i> Room #{{ $room->room_number }}
                                </p>
                            </div>
                            <div class="col-md-4 text-right">
                                <h5 class="text-primary mb-0">${{ number_format($room->pivot->price ?? $room->price_per_night, 2) }}<small class="text-muted">/night</small></h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Special Requests -->
        @if($booking->special_requests)
        <div class="card guest-card mb-4">
            <div class="card-header">
                <strong><i class="fas fa-comment-alt text-primary"></i> Special Requests</strong>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $booking->special_requests }}</p>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <!-- Price Summary -->
        <div class="card guest-card mb-4">
            <div class="card-header bg-primary text-white">
                <strong><i class="fas fa-receipt"></i> Price Summary</strong>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    @php
                        $nights = $booking->check_in->diffInDays($booking->check_out);
                        $subtotal = 0;
                        foreach($booking->rooms as $room) {
                            $subtotal += ($room->pivot->price ?? $room->price_per_night) * $nights;
                        }
                    @endphp
                    @foreach($booking->rooms as $room)
                        <tr>
                            <td>{{ $room->roomType->room_type_name ?? 'Room' }} × {{ $nights }} nights</td>
                            <td class="text-right">${{ number_format(($room->pivot->price ?? $room->price_per_night) * $nights, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="border-top">
                        <td><strong>Total</strong></td>
                        <td class="text-right"><strong class="h4 text-primary">${{ number_format($booking->total_price, 2) }}</strong></td>
                    </tr>
                    @if($booking->paid_amount > 0)
                    <tr>
                        <td class="text-success">Paid</td>
                        <td class="text-right text-success">${{ number_format($booking->paid_amount, 2) }}</td>
                    </tr>
                    @endif
                    @if($booking->remaining_balance > 0)
                    <tr class="border-top">
                        <td><strong class="text-danger">Balance Due</strong></td>
                        <td class="text-right"><strong class="h5 text-danger">${{ number_format($booking->remaining_balance, 2) }}</strong></td>
                    </tr>
                    @endif
                </table>
                
                @if($booking->remaining_balance > 0 && !in_array($booking->status, ['cancelled', 'checked_out']))
                <div class="mt-3">
                    <a href="{{ route('guest.payments.create', $booking->id) }}" class="btn btn-gradient btn-block">
                        <i class="fas fa-credit-card"></i> Pay Now
                    </a>
                </div>
                @elseif($booking->payment_status === 'paid')
                <div class="alert alert-success mt-3 mb-0 text-center">
                    <i class="fas fa-check-circle"></i> <strong>Fully Paid</strong>
                </div>
                @endif
                
                @if($booking->payments->count() > 0)
                <div class="mt-3 text-center">
                    <a href="{{ route('guest.payments.index', $booking->id) }}" class="text-muted">
                        <i class="fas fa-history"></i> View Payment History
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Hotel Contact -->
        <div class="card guest-card">
            <div class="card-header">
                <strong><i class="fas fa-phone"></i> Need Help?</strong>
            </div>
            <div class="card-body text-center">
                <h5 class="mb-3">{{ $hotelName }}</h5>
                @if($hotelPhone)
                    <p class="mb-2"><i class="fas fa-phone text-primary"></i> {{ $hotelPhone }}</p>
                @endif
                @if($hotelEmail)
                    <p class="mb-0"><i class="fas fa-envelope text-primary"></i> {{ $hotelEmail }}</p>
                @endif
            </div>
        </div>

        <!-- Write Review -->
        @if($booking->status == 'checked_out' && !$booking->review)
        <div class="card guest-card mt-4 border-warning">
            <div class="card-body text-center">
                <i class="fas fa-star fa-3x text-warning mb-3"></i>
                <h5>How was your stay?</h5>
                <p class="text-muted">Share your experience with us</p>
                <a href="{{ route('guest.bookings.review', $booking->id) }}" class="btn btn-gradient">
                    Write a Review
                </a>
            </div>
        </div>
        @elseif($booking->review)
        <div class="card guest-card mt-4">
            <div class="card-body text-center">
                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                <h5>Thank you for your review!</h5>
                <p class="text-muted mb-0">Your rating: 
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= $booking->review->overall_rating ? 'text-warning' : 'text-muted' }}"></i>
                    @endfor
                </p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@extends('guest.layouts.master')

@section('title', 'My Bookings')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-calendar-alt mr-2"></i>My Bookings</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('guest.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">My Bookings</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')

<!-- Booking Filters -->
<div class="card guest-card mb-4">
    <div class="card-body py-3">
        <div class="row align-items-center">
            <div class="col-md-8">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('guest.bookings.index') }}">All</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('guest.bookings.index') }}?status=upcoming">Upcoming</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('guest.bookings.index') }}?status=completed">Completed</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Bookings List -->
@if($bookings->count() > 0)
    @foreach($bookings as $booking)
        <div class="card guest-card mb-3">
            <div class="card-body">
                <div class="row align-items-center">
                    <!-- Date Block -->
                    <div class="col-md-2 text-center mb-3 mb-md-0">
                        <div class="bg-gradient-primary text-white rounded py-3 px-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <h3 class="mb-0">{{ $booking->check_in->format('d') }}</h3>
                            <small>{{ $booking->check_in->format('M Y') }}</small>
                        </div>
                    </div>
                    
                    <!-- Booking Details -->
                    <div class="col-md-5 mb-3 mb-md-0">
                        <h5 class="mb-2">
                            @foreach($booking->rooms as $room)
                                <span class="badge badge-light mr-1">
                                    {{ $room->roomType->room_type_name ?? 'Room' }} #{{ $room->room_number }}
                                </span>
                            @endforeach
                        </h5>
                        <p class="text-muted mb-1">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ $booking->check_in->format('D, M d') }} → {{ $booking->check_out->format('D, M d, Y') }}
                        </p>
                        <p class="text-muted mb-0">
                            <i class="fas fa-moon mr-1"></i> {{ $booking->check_in->diffInDays($booking->check_out) }} night(s)
                            <span class="mx-2">|</span>
                            <i class="fas fa-dollar-sign mr-1"></i> ${{ number_format($booking->total_price, 2) }}
                        </p>
                    </div>
                    
                    <!-- Status -->
                    <div class="col-md-2 text-center mb-3 mb-md-0">
                        @php
                            $statusColors = [
                                'pending' => 'warning',
                                'confirmed' => 'primary',
                                'checked_in' => 'success',
                                'checked_out' => 'secondary',
                                'cancelled' => 'danger'
                            ];
                            $color = $statusColors[$booking->status] ?? 'secondary';
                        @endphp
                        <span class="badge badge-{{ $color }} px-3 py-2">
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>
                        @if($booking->payment_status == 'paid')
                            <br><small class="text-success mt-1"><i class="fas fa-check-circle"></i> Paid</small>
                        @elseif($booking->payment_status == 'partial')
                            <br><small class="text-warning mt-1"><i class="fas fa-exclamation-circle"></i> Partial</small>
                        @else
                            <br><small class="text-danger mt-1"><i class="fas fa-times-circle"></i> Unpaid</small>
                        @endif
                    </div>
                    
                    <!-- Actions -->
                    <div class="col-md-3 text-right">
                        <a href="{{ route('guest.bookings.show', $booking->id) }}" class="btn btn-gradient btn-sm mb-2">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        @if($booking->status == 'checked_out' && !$booking->review)
                            <br>
                            <a href="{{ route('guest.bookings.review', $booking->id) }}" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-star"></i> Write Review
                            </a>
                        @elseif($booking->review)
                            <br>
                            <span class="badge badge-success"><i class="fas fa-check"></i> Reviewed</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    
    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $bookings->links() }}
    </div>
@else
    <div class="card guest-card">
        <div class="card-body text-center py-5">
            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No bookings found</h4>
            <p class="text-muted">You haven't made any reservations yet.</p>
        </div>
    </div>
@endif
@endsection

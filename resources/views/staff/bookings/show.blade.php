@extends('staff.layouts.master')

@section('title', 'Booking Details')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-eye"></i> Booking #{{ $booking->id }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('staff.bookings.index') }}">Bookings</a></li>
                    <li class="breadcrumb-item active">#{{ $booking->id }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Guest Information -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user"></i> Guest Information</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Name:</th>
                                <td>{{ $booking->user->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $booking->user->email ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td>{{ $booking->user->guestProfile->phone ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Address:</th>
                                <td>{{ $booking->user->guestProfile->address ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>ID Type:</th>
                                <td>{{ ucfirst(str_replace('_', ' ', $booking->user->guestProfile->id_type ?? 'N/A')) }}</td>
                            </tr>
                            <tr>
                                <th>ID Number:</th>
                                <td>{{ $booking->user->guestProfile->id_number ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Room Information -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-bed"></i> Room Information</h3>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Room #</th>
                            <th>Room Type</th>
                            <th>Price/Night</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($booking->rooms as $room)
                            <tr>
                                <td><strong>{{ $room->room_number }}</strong></td>
                                <td>{{ $room->roomType->room_type_name ?? 'N/A' }}</td>
                                <td>${{ number_format($room->pivot->price ?? $room->price_per_night, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Special Requests -->
        @if($booking->special_requests)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-comment-alt"></i> Special Requests</h3>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $booking->special_requests }}</p>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <!-- Booking Summary -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title"><i class="fas fa-info-circle"></i> Booking Summary</h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th>Status:</th>
                        <td>
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'confirmed' => 'primary',
                                    'checked_in' => 'success',
                                    'checked_out' => 'secondary',
                                    'cancelled' => 'danger'
                                ];
                            @endphp
                            <span class="badge badge-{{ $statusColors[$booking->status] ?? 'secondary' }}">
                                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Check-in:</th>
                        <td>{{ $booking->check_in->format('D, M d, Y') }}</td>
                    </tr>
                    <tr>
                        <th>Check-out:</th>
                        <td>{{ $booking->check_out->format('D, M d, Y') }}</td>
                    </tr>
                    <tr>
                        <th>Nights:</th>
                        <td>{{ $booking->check_in->diffInDays($booking->check_out) }}</td>
                    </tr>
                    <tr>
                        <th>Payment:</th>
                        <td>
                            @if($booking->payment_status == 'paid')
                                <span class="badge badge-success">Paid</span>
                            @elseif($booking->payment_status == 'partial')
                                <span class="badge badge-warning">Partial</span>
                            @else
                                <span class="badge badge-danger">Unpaid</span>
                            @endif
                        </td>
                    </tr>
                    <tr class="border-top">
                        <th><h5 class="mb-0">Total:</h5></th>
                        <td><h5 class="mb-0 text-primary">${{ number_format($booking->total_price, 2) }}</h5></td>
                    </tr>
                    @if($booking->paid_amount > 0)
                    <tr>
                        <th class="text-success">Paid:</th>
                        <td class="text-success">${{ number_format($booking->paid_amount, 2) }}</td>
                    </tr>
                    @endif
                    @if($booking->remaining_balance > 0)
                    <tr>
                        <th class="text-danger">Balance:</th>
                        <td><strong class="text-danger">${{ number_format($booking->remaining_balance, 2) }}</strong></td>
                    </tr>
                    @endif
                </table>
                
                @if($booking->remaining_balance > 0)
                <a href="{{ route('staff.payments.create', $booking->id) }}" class="btn btn-success btn-block">
                    <i class="fas fa-dollar-sign"></i> Record Payment
                </a>
                @endif
                
                @if($booking->payments->count() > 0)
                <a href="{{ route('staff.payments.index', $booking->id) }}" class="btn btn-outline-info btn-block mt-2">
                    <i class="fas fa-history"></i> Payment History ({{ $booking->payments->count() }})
                </a>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-cogs"></i> Actions</h3>
            </div>
            <div class="card-body">
                @if($booking->status == 'confirmed')
                    <form action="{{ route('staff.bookings.check-in', $booking->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block mb-2">
                            <i class="fas fa-sign-in-alt"></i> Check In Guest
                        </button>
                    </form>
                @elseif($booking->status == 'checked_in')
                    <form action="{{ route('staff.bookings.check-out', $booking->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-info btn-block mb-2">
                            <i class="fas fa-sign-out-alt"></i> Check Out Guest
                        </button>
                    </form>
                @endif
                
                <a href="{{ route('staff.bookings.index') }}" class="btn btn-outline-secondary btn-block">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

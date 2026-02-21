@extends('admin.layouts.master')

@section('title', 'Record Payment')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-cash-register mr-2"></i>Record Payment</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.bookings.show', $booking->id) }}">Booking #{{ $booking->id }}</a></li>
                    <li class="breadcrumb-item active">Payment</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-dollar-sign"></i> Record Payment for Booking #{{ $booking->id }}</h5>
            </div>
            <form action="{{ route('admin.payments.store', $booking->id) }}" method="POST">
                @csrf
                <div class="card-body">
                    <!-- Guest Info -->
                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-6">
                                <strong><i class="fas fa-user"></i> Guest:</strong> {{ $booking->user->name ?? 'N/A' }}
                            </div>
                            <div class="col-md-6">
                                <strong><i class="fas fa-calendar"></i> Stay:</strong> 
                                {{ $booking->check_in->format('M d') }} - {{ $booking->check_out->format('M d, Y') }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-dollar-sign text-success"></i> Amount (USD) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" name="amount" 
                                           class="form-control form-control-lg @error('amount') is-invalid @enderror" 
                                           value="{{ old('amount', $booking->remaining_balance) }}"
                                           min="0.01" max="{{ $booking->remaining_balance }}" step="0.01" required>
                                </div>
                                @error('amount')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Balance due: ${{ number_format($booking->remaining_balance, 2) }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-wallet text-primary"></i> Payment Method <span class="text-danger">*</span></label>
                                <select name="payment_method" class="form-control form-control-lg @error('payment_method') is-invalid @enderror" required>
                                    @foreach($paymentMethods as $key => $name)
                                        <option value="{{ $key }}" {{ old('payment_method') == $key ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('payment_method')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-hashtag"></i> Transaction ID (Optional)</label>
                                <input type="text" name="transaction_id" class="form-control" 
                                       value="{{ old('transaction_id') }}" placeholder="External reference number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-sticky-note"></i> Notes (Optional)</label>
                                <input type="text" name="notes" class="form-control" 
                                       value="{{ old('notes') }}" placeholder="Any additional notes">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-check"></i> Record Payment
                    </button>
                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-secondary float-right">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Payment Summary -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-calculator"></i> Payment Summary</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td>Total Price:</td>
                        <td class="text-right"><strong>${{ number_format($booking->total_price, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Already Paid:</td>
                        <td class="text-right text-success"><strong>${{ number_format($booking->paid_amount, 2) }}</strong></td>
                    </tr>
                    <tr class="border-top">
                        <td><strong>Balance Due:</strong></td>
                        <td class="text-right"><strong class="h4 text-danger">${{ number_format($booking->remaining_balance, 2) }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Room Details -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bed"></i> Room(s)</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @foreach($booking->rooms as $room)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $room->roomType->room_type_name ?? 'Room' }} #{{ $room->room_number }}</span>
                        <strong>${{ number_format($room->pivot->price ?? $room->price_per_night, 2) }}/night</strong>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('staff.layouts.master')

@section('title', 'Payment Receipt')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-receipt mr-2"></i>Payment Receipt</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('staff.bookings.show', $booking->id) }}">Booking #{{ $booking->id }}</a></li>
                    <li class="breadcrumb-item active">Receipt</li>
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
                <h5 class="mb-0"><i class="fas fa-receipt"></i> Payment Receipt</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Reference Number</h6>
                        <h4><strong>{{ $payment->reference_number }}</strong></h4>
                    </div>
                    <div class="col-md-6 text-md-right">
                        <h6 class="text-muted">Payment Date</h6>
                        <h4>{{ $payment->paid_at ? $payment->paid_at->format('M d, Y h:i A') : '-' }}</h4>
                    </div>
                </div>

                <hr>

                <table class="table table-borderless">
                    <tr>
                        <th width="30%">Booking ID</th>
                        <td>#{{ $booking->id }}</td>
                    </tr>
                    <tr>
                        <th>Guest</th>
                        <td>{{ $booking->user->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Stay Dates</th>
                        <td>{{ $booking->check_in->format('M d') }} - {{ $booking->check_out->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <th>Payment Method</th>
                        <td>
                            <i class="fas fa-{{ $payment->payment_method == 'credit_card' ? 'credit-card' : ($payment->payment_method == 'cash' ? 'money-bill' : ($payment->payment_method == 'khqr' ? 'qrcode' : 'university')) }} mr-2"></i>
                            {{ $payment->payment_method_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge badge-{{ $payment->status_badge_color }} px-3 py-2">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                    </tr>
                    @if($payment->transaction_id)
                    <tr>
                        <th>Transaction ID</th>
                        <td>{{ $payment->transaction_id }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>Processed By</th>
                        <td>{{ $payment->processedBy->name ?? 'System' }}</td>
                    </tr>
                    @if($payment->notes)
                    <tr>
                        <th>Notes</th>
                        <td>{{ $payment->notes }}</td>
                    </tr>
                    @endif
                </table>

                <hr>

                <div class="text-center py-4">
                    <h6 class="text-muted">Amount</h6>
                    <h1 class="{{ $payment->amount >= 0 ? 'text-success' : 'text-danger' }}">
                        ${{ number_format(abs($payment->amount), 2) }}
                        @if($payment->amount < 0)
                            <small class="text-muted">(Refund)</small>
                        @endif
                    </h1>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('staff.bookings.show', $booking->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Booking
                </a>
                <button onclick="window.print()" class="btn btn-primary float-right">
                    <i class="fas fa-print"></i> Print Receipt
                </button>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Booking Summary</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td>Total Price:</td>
                        <td class="text-right">${{ number_format($booking->total_price, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Paid:</td>
                        <td class="text-right text-success">${{ number_format($booking->paid_amount, 2) }}</td>
                    </tr>
                    <tr class="border-top">
                        <td><strong>Balance:</strong></td>
                        <td class="text-right"><strong class="{{ $booking->remaining_balance > 0 ? 'text-danger' : 'text-success' }}">${{ number_format($booking->remaining_balance, 2) }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

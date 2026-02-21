@extends('guest.layouts.master')

@section('title', 'Payment Receipt')

@push('styles')
<style>
    .receipt-card {
        border: 2px solid #28a745;
        border-radius: 15px;
    }
    .receipt-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 30px;
        text-align: center;
        border-radius: 12px 12px 0 0;
    }
    .receipt-body {
        padding: 30px;
    }
    .receipt-footer {
        background: #f8f9fa;
        padding: 20px;
        text-align: center;
        border-radius: 0 0 12px 12px;
    }
</style>
@endpush

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-receipt mr-2"></i>Payment Receipt</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('guest.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guest.bookings.show', $booking->id) }}">Booking #{{ $booking->id }}</a></li>
                    <li class="breadcrumb-item active">Receipt</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="receipt-card">
            <div class="receipt-header">
                <i class="fas fa-check-circle fa-4x mb-3"></i>
                <h2 class="mb-0">Payment Successful</h2>
                <p class="mb-0 mt-2">Thank you for your payment!</p>
            </div>
            
            <div class="receipt-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Reference Number</h6>
                        <h5><strong>{{ $payment->reference_number }}</strong></h5>
                    </div>
                    <div class="col-md-6 text-md-right">
                        <h6 class="text-muted">Payment Date</h6>
                        <h5>{{ $payment->paid_at ? $payment->paid_at->format('M d, Y h:i A') : '-' }}</h5>
                    </div>
                </div>

                <hr>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Booking ID</h6>
                        <p>#{{ $booking->id }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Stay Dates</h6>
                        <p>{{ $booking->check_in->format('M d') }} - {{ $booking->check_out->format('M d, Y') }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Payment Method</h6>
                        <p>
                            <i class="fas fa-{{ $payment->payment_method == 'credit_card' ? 'credit-card' : ($payment->payment_method == 'cash' ? 'money-bill' : ($payment->payment_method == 'khqr' ? 'qrcode' : 'university')) }} mr-2"></i>
                            {{ $payment->payment_method_name }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Status</h6>
                        <span class="badge badge-{{ $payment->status_badge_color }} px-3 py-2">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>
                </div>

                @if($payment->transaction_id)
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted">Transaction ID</h6>
                        <p>{{ $payment->transaction_id }}</p>
                    </div>
                </div>
                @endif

                <hr>

                <div class="text-center">
                    <h6 class="text-muted">Amount Paid</h6>
                    <h1 class="text-success">${{ number_format($payment->amount, 2) }}</h1>
                </div>

                @if($payment->notes)
                <div class="mt-4">
                    <h6 class="text-muted">Notes</h6>
                    <p class="text-muted">{{ $payment->notes }}</p>
                </div>
                @endif
            </div>

            <div class="receipt-footer">
                <p class="text-muted mb-0">
                    <i class="fas fa-print mr-1"></i> Save this receipt for your records
                </p>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('guest.bookings.show', $booking->id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Booking
            </a>
            <button onclick="window.print()" class="btn btn-primary ml-2">
                <i class="fas fa-print"></i> Print Receipt
            </button>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-print receipt on load if coming from successful payment
        window.print();
    });
</script>
@endpush

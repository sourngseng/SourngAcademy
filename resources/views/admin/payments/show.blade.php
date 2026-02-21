@extends('admin.layouts.master')

@section('title', 'Payment Details')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-receipt mr-2"></i>Payment Details</h1>
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
            <div class="card-header bg-{{ $payment->status_badge_color }} text-white">
                <h5 class="mb-0"><i class="fas fa-receipt"></i> Payment {{ ucfirst($payment->status) }}</h5>
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
                        <td>{{ $booking->user->name ?? 'N/A' }} ({{ $booking->user->email ?? '' }})</td>
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
                    <tr>
                        <th>Created At</th>
                        <td>{{ $payment->created_at->format('M d, Y h:i A') }}</td>
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
                <a href="{{ route('admin.payments.index', $booking->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Payments
                </a>
                <div class="float-right">
                    @if($payment->status === 'completed')
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#refundModal">
                        <i class="fas fa-undo"></i> Refund
                    </button>
                    @endif
                    <button onclick="window.print()" class="btn btn-primary ml-2">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
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

        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-outline-primary btn-block">
            <i class="fas fa-calendar-check"></i> View Booking
        </a>
    </div>
</div>

<!-- Refund Modal -->
@if($payment->status === 'completed')
<div class="modal fade" id="refundModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.payments.refund', [$booking->id, $payment->id]) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-undo"></i> Refund Payment</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to refund this payment?</p>
                    <p><strong>Amount:</strong> ${{ number_format($payment->amount, 2) }}</p>
                    <p><strong>Reference:</strong> {{ $payment->reference_number }}</p>
                    <div class="form-group">
                        <label>Refund Reason <span class="text-danger">*</span></label>
                        <textarea name="refund_reason" class="form-control" rows="3" required placeholder="Enter reason for refund..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-undo"></i> Confirm Refund
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

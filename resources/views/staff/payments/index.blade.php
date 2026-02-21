@extends('staff.layouts.master')

@section('title', 'Payment History')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-history mr-2"></i>Payment History</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('staff.bookings.show', $booking->id) }}">Booking #{{ $booking->id }}</a></li>
                    <li class="breadcrumb-item active">Payments</li>
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
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-list"></i> Payments for Booking #{{ $booking->id }}</h5>
                @if($booking->remaining_balance > 0)
                <a href="{{ route('staff.payments.create', $booking->id) }}" class="btn btn-success btn-sm float-right">
                    <i class="fas fa-plus"></i> Record Payment
                </a>
                @endif
            </div>
            <div class="card-body p-0">
                @if($payments->count() > 0)
                <table class="table table-hover mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Reference</th>
                            <th>Date</th>
                            <th>Method</th>
                            <th>Amount</th>
                            <th>Processed By</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                        <tr>
                            <td>
                                <a href="{{ route('staff.payments.show', [$booking->id, $payment->id]) }}">
                                    <strong>{{ $payment->reference_number }}</strong>
                                </a>
                            </td>
                            <td>{{ $payment->paid_at ? $payment->paid_at->format('M d, Y H:i') : '-' }}</td>
                            <td>
                                <i class="fas fa-{{ $payment->payment_method == 'credit_card' ? 'credit-card' : ($payment->payment_method == 'cash' ? 'money-bill' : ($payment->payment_method == 'khqr' ? 'qrcode' : 'university')) }} text-muted mr-1"></i>
                                {{ $payment->payment_method_name }}
                            </td>
                            <td>
                                @if($payment->amount >= 0)
                                    <strong class="text-success">${{ number_format($payment->amount, 2) }}</strong>
                                @else
                                    <strong class="text-danger">${{ number_format($payment->amount, 2) }}</strong>
                                @endif
                            </td>
                            <td>{{ $payment->processedBy->name ?? 'System' }}</td>
                            <td>
                                <span class="badge badge-{{ $payment->status_badge_color }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <td colspan="3" class="text-right"><strong>Total Completed:</strong></td>
                            <td><strong class="text-success h5 mb-0">${{ number_format($payments->where('status', 'completed')->sum('amount'), 2) }}</strong></td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-receipt fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No payments recorded yet</h5>
                    @if($booking->remaining_balance > 0)
                    <a href="{{ route('staff.payments.create', $booking->id) }}" class="btn btn-success mt-3">
                        <i class="fas fa-plus"></i> Record Payment
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Payment Summary -->
        <div class="card bg-gradient-info text-white">
            <div class="card-body">
                <h5><i class="fas fa-calculator"></i> Payment Summary</h5>
                <hr style="border-color: rgba(255,255,255,0.3);">
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Price</span>
                    <strong>${{ number_format($booking->total_price, 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Paid</span>
                    <strong>${{ number_format($booking->paid_amount, 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="h5 mb-0">Remaining</span>
                    <strong class="h4 mb-0">${{ number_format($booking->remaining_balance, 2) }}</strong>
                </div>
                
                @if($booking->remaining_balance > 0)
                <a href="{{ route('staff.payments.create', $booking->id) }}" class="btn btn-light btn-block mt-3">
                    <i class="fas fa-plus"></i> Record Payment
                </a>
                @else
                <div class="alert alert-light mt-3 mb-0 text-center">
                    <i class="fas fa-check-circle text-success"></i> <strong class="text-success">Fully Paid!</strong>
                </div>
                @endif
            </div>
        </div>

        <!-- Guest Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user"></i> Guest</h5>
            </div>
            <div class="card-body">
                <strong>{{ $booking->user->name ?? 'N/A' }}</strong>
                <p class="text-muted mb-0">{{ $booking->user->email ?? '' }}</p>
            </div>
        </div>

        <a href="{{ route('staff.bookings.show', $booking->id) }}" class="btn btn-secondary btn-block">
            <i class="fas fa-arrow-left"></i> Back to Booking
        </a>
    </div>
</div>
@endsection

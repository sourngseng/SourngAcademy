@extends('admin.layouts.master')

@section('title', 'Payments Report')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-chart-bar mr-2"></i>Payments Report</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Payments Report</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<!-- Quick Stats -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-success">
            <div class="inner">
                <h3>${{ number_format($totalAmount, 2) }}</h3>
                <p>Total Revenue (Filtered)</p>
            </div>
            <div class="icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-info">
            <div class="inner">
                <h3>{{ $payments->total() }}</h3>
                <p>Total Payments</p>
            </div>
            <div class="icon">
                <i class="fas fa-receipt"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-warning">
            <div class="inner">
                <h3>${{ $payments->total() > 0 ? number_format($totalAmount / $payments->total(), 2) : '0.00' }}</h3>
                <p>Average Payment</p>
            </div>
            <div class="icon">
                <i class="fas fa-calculator"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-primary">
            <div class="inner">
                <h3>{{ $payments->where('payment_method', 'khqr')->count() }}</h3>
                <p>KHQR Payments</p>
            </div>
            <div class="icon">
                <i class="fas fa-qrcode"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-filter"></i> Filter Payments</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.payments.report') }}" method="GET" class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Start Date</label>
                    <div class="input-group date" id="start_date_picker" data-target-input="nearest">
                        <input type="text" name="start_date" class="form-control datetimepicker-input" data-target="#start_date_picker" value="{{ request('start_date') }}" placeholder="Select date">
                        <div class="input-group-append" data-target="#start_date_picker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>End Date</label>
                    <div class="input-group date" id="end_date_picker" data-target-input="nearest">
                        <input type="text" name="end_date" class="form-control datetimepicker-input" data-target="#end_date_picker" value="{{ request('end_date') }}" placeholder="Select date">
                        <div class="input-group-append" data-target="#end_date_picker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Payment Method</label>
                    <select name="payment_method" class="form-control">
                        <option value="">All Methods</option>
                        @foreach($paymentMethods as $key => $name)
                            <option value="{{ $key }}" {{ request('payment_method') == $key ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.payments.report') }}" class="btn btn-secondary">
                            <i class="fas fa-undo"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Payment Method Distribution -->
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie"></i> By Payment Method</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @php
                        $methodTotals = $payments->groupBy('payment_method')->map(function($items) {
                            return $items->sum('amount');
                        });
                    @endphp
                    @foreach($paymentMethods as $key => $name)
                        @php $amount = $methodTotals[$key] ?? 0; @endphp
                        @if($amount > 0)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fas fa-{{ $key == 'cash' ? 'money-bill' : ($key == 'credit_card' ? 'credit-card' : ($key == 'khqr' ? 'qrcode' : 'university')) }} text-primary mr-2"></i>
                                {{ $name }}
                            </span>
                            <span class="badge badge-success badge-pill">${{ number_format($amount, 2) }}</span>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list"></i> All Payments</h3>
                <div class="card-tools">
                    <span class="badge badge-info">{{ $payments->total() }} payment(s)</span>
                </div>
            </div>
            <div class="card-body p-0">
                @if($payments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>Reference</th>
                                <th>Booking</th>
                                <th>Guest</th>
                                <th>Method</th>
                                <th>Date</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.payments.show', [$payment->booking_id, $payment->id]) }}">
                                        <strong>{{ $payment->reference_number }}</strong>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.bookings.show', $payment->booking_id) }}">
                                        #{{ $payment->booking_id }}
                                    </a>
                                </td>
                                <td>{{ $payment->booking->user->name ?? 'N/A' }}</td>
                                <td>
                                    <i class="fas fa-{{ $payment->payment_method == 'cash' ? 'money-bill' : ($payment->payment_method == 'credit_card' ? 'credit-card' : ($payment->payment_method == 'khqr' ? 'qrcode' : 'university')) }} text-muted mr-1"></i>
                                    {{ $payment->payment_method_name }}
                                </td>
                                <td>{{ $payment->paid_at ? $payment->paid_at->format('M d, Y H:i') : '-' }}</td>
                                <td>
                                    <strong class="{{ $payment->amount >= 0 ? 'text-success' : 'text-danger' }}">
                                        ${{ number_format($payment->amount, 2) }}
                                    </strong>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $payments->withQueryString()->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-receipt fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No payments found</h5>
                    <p class="text-muted">Try adjusting your filters</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    // Initialize DateTime Pickers for filters
    $('#start_date_picker, #end_date_picker').datetimepicker({
        format: 'YYYY-MM-DD',
        icons: {
            time: 'fas fa-clock',
            date: 'fas fa-calendar',
            up: 'fas fa-arrow-up',
            down: 'fas fa-arrow-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'fas fa-calendar-check',
            clear: 'fas fa-trash',
            close: 'fas fa-times'
        }
    });

    // Link date pickers - end date should be after start date
    $('#start_date_picker').on('change.datetimepicker', function(e) {
        $('#end_date_picker').datetimepicker('minDate', e.date);
    });

    $('#end_date_picker').on('change.datetimepicker', function(e) {
        $('#start_date_picker').datetimepicker('maxDate', e.date);
    });
});
</script>
@endpush


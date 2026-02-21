@extends('admin.layouts.master')

@section('title', 'Financial Summary')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-file-invoice-dollar mr-2"></i>Financial Summary</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
                    <li class="breadcrumb-item active">Financial Summary</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<!-- Summary Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-info">
            <div class="inner">
                <h3>${{ number_format($grossRevenue, 2) }}</h3>
                <p>Gross Revenue</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-success">
            <div class="inner">
                <h3>${{ number_format($paymentsReceived, 2) }}</h3>
                <p>Payments Received</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-danger">
            <div class="inner">
                <h3>${{ number_format(abs($refundsIssued), 2) }}</h3>
                <p>Refunds Issued</p>
            </div>
            <div class="icon">
                <i class="fas fa-undo"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-warning">
            <div class="inner">
                <h3>${{ number_format($outstandingPayments, 2) }}</h3>
                <p>Outstanding</p>
            </div>
            <div class="icon">
                <i class="fas fa-hourglass-half"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-filter"></i> Filter Options</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.reports.financial') }}" method="GET" class="row align-items-end">
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <label>Year</label>
                    <select name="year" class="form-control">
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <label>Month (Optional)</label>
                    <select name="month" class="form-control">
                        <option value="">All Year</option>
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ $month == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sync-alt"></i> Update Report
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Period Info -->
<div class="alert alert-info">
    <i class="fas fa-calendar"></i>
    <strong>Report Period:</strong> {{ $periodLabel }}
    ({{ $startDate->format('M d, Y') }} — {{ $endDate->format('M d, Y') }})
</div>

<div class="row">
    <!-- Financial Summary Card -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title"><i class="fas fa-calculator"></i> Financial Summary</h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td><i class="fas fa-chart-line text-info mr-2"></i>Gross Revenue (Bookings)</td>
                            <td class="text-right"><strong>${{ number_format($grossRevenue, 2) }}</strong></td>
                        </tr>
                        <tr class="table-success">
                            <td><i class="fas fa-check-circle text-success mr-2"></i>Payments Received</td>
                            <td class="text-right"><strong class="text-success">+ ${{ number_format($paymentsReceived, 2) }}</strong></td>
                        </tr>
                        <tr class="table-danger">
                            <td><i class="fas fa-undo text-danger mr-2"></i>Refunds Issued</td>
                            <td class="text-right"><strong class="text-danger">- ${{ number_format(abs($refundsIssued), 2) }}</strong></td>
                        </tr>
                        <tr class="border-top">
                            <td><strong><i class="fas fa-equals text-primary mr-2"></i>Net Revenue</strong></td>
                            <td class="text-right"><strong class="h4 text-primary">${{ number_format($netRevenue, 2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr class="table-warning">
                            <td><i class="fas fa-hourglass-half text-warning mr-2"></i>Outstanding Payments</td>
                            <td class="text-right"><strong class="text-warning">${{ number_format($outstandingPayments, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-percentage text-muted mr-2"></i>Collection Rate</td>
                            <td class="text-right">
                                <strong class="{{ ($grossRevenue > 0 ? ($paymentsReceived / $grossRevenue) * 100 : 0) >= 80 ? 'text-success' : 'text-warning' }}">
                                    {{ $grossRevenue > 0 ? round(($paymentsReceived / $grossRevenue) * 100, 1) : 0 }}%
                                </strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment Method Breakdown -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-credit-card"></i> Payment Methods</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Method</th>
                            <th class="text-center">Transactions</th>
                            <th class="text-right">Amount</th>
                            <th class="text-right">Share</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paymentMethodBreakdown as $method)
                        <tr>
                            <td>
                                <i class="fas fa-{{ $method->payment_method == 'cash' ? 'money-bill' : ($method->payment_method == 'credit_card' ? 'credit-card' : ($method->payment_method == 'khqr' ? 'qrcode' : 'university')) }} text-primary mr-2"></i>
                                {{ ucfirst(str_replace('_', ' ', $method->payment_method)) }}
                            </td>
                            <td class="text-center">
                                <span class="badge badge-info">{{ $method->count }}</span>
                            </td>
                            <td class="text-right text-success">${{ number_format($method->total, 2) }}</td>
                            <td class="text-right">
                                {{ $paymentsReceived > 0 ? round(($method->total / $paymentsReceived) * 100, 1) : 0 }}%
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Invoice Statistics & Charts -->
    <div class="col-md-6">
        <!-- Invoice Stats -->
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h3 class="card-title"><i class="fas fa-file-invoice"></i> Invoice Statistics</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="info-box bg-light mb-3">
                            <span class="info-box-icon bg-info"><i class="fas fa-file-alt"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Invoices</span>
                                <span class="info-box-number">{{ $invoiceStats['total'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-box bg-light mb-3">
                            <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Paid</span>
                                <span class="info-box-number">{{ $invoiceStats['paid'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-box bg-light mb-3">
                            <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Pending</span>
                                <span class="info-box-number">{{ $invoiceStats['pending'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-box bg-light mb-3">
                            <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Overdue</span>
                                <span class="info-box-number">{{ $invoiceStats['overdue'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between border-top pt-3">
                    <span>Total Invoice Value:</span>
                    <strong class="h5 text-primary">${{ number_format($invoiceStats['total_amount'], 2) }}</strong>
                </div>
            </div>
        </div>

        <!-- Payment Methods Pie Chart -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie"></i> Payment Distribution</h3>
            </div>
            <div class="card-body">
                <canvas id="paymentChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Breakdown (if viewing full year) -->
@if(count($monthlyData) > 0)
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-calendar-alt"></i> Monthly Breakdown - {{ $year }}</h3>
    </div>
    <div class="card-body">
        <canvas id="monthlyChart" height="100"></canvas>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-table"></i> Monthly Financial Data</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th>Month</th>
                        <th class="text-center">Bookings</th>
                        <th class="text-right">Gross Revenue</th>
                        <th class="text-right">Payments Received</th>
                        <th class="text-right">Collection Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthlyData as $data)
                    <tr>
                        <td><strong>{{ $data['month'] }}</strong></td>
                        <td class="text-center">
                            <span class="badge badge-info">{{ $data['bookings'] }}</span>
                        </td>
                        <td class="text-right">${{ number_format($data['gross_revenue'], 2) }}</td>
                        <td class="text-right text-success">${{ number_format($data['payments_received'], 2) }}</td>
                        <td class="text-right">
                            @php
                                $rate = $data['gross_revenue'] > 0 ? ($data['payments_received'] / $data['gross_revenue']) * 100 : 0;
                            @endphp
                            <span class="badge badge-{{ $rate >= 80 ? 'success' : ($rate >= 50 ? 'warning' : 'danger') }}">
                                {{ round($rate, 1) }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-light">
                    <tr>
                        <th>Total</th>
                        <th class="text-center">{{ collect($monthlyData)->sum('bookings') }}</th>
                        <th class="text-right">${{ number_format(collect($monthlyData)->sum('gross_revenue'), 2) }}</th>
                        <th class="text-right text-success">${{ number_format(collect($monthlyData)->sum('payments_received'), 2) }}</th>
                        <th class="text-right">
                            @php
                                $totalGross = collect($monthlyData)->sum('gross_revenue');
                                $totalPayments = collect($monthlyData)->sum('payments_received');
                                $totalRate = $totalGross > 0 ? ($totalPayments / $totalGross) * 100 : 0;
                            @endphp
                            <span class="badge badge-{{ $totalRate >= 80 ? 'success' : ($totalRate >= 50 ? 'warning' : 'danger') }}">
                                {{ round($totalRate, 1) }}%
                            </span>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endif

<div class="mb-3">
    <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(function() {
    // Payment Methods Pie Chart
    const paymentCtx = document.getElementById('paymentChart').getContext('2d');
    new Chart(paymentCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($paymentMethodBreakdown->pluck('payment_method')->map(fn($m) => ucfirst(str_replace('_', ' ', $m)))) !!},
            datasets: [{
                data: {!! json_encode($paymentMethodBreakdown->pluck('total')) !!},
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(0, 123, 255, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(220, 53, 69, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return '$' + context.raw.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    @if(count($monthlyData) > 0)
    // Monthly Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(collect($monthlyData)->pluck('month')) !!},
            datasets: [{
                label: 'Gross Revenue',
                data: {!! json_encode(collect($monthlyData)->pluck('gross_revenue')) !!},
                backgroundColor: 'rgba(23, 162, 184, 0.7)',
                borderColor: 'rgba(23, 162, 184, 1)',
                borderWidth: 1
            }, {
                label: 'Payments Received',
                data: {!! json_encode(collect($monthlyData)->pluck('payments_received')) !!},
                backgroundColor: 'rgba(40, 167, 69, 0.7)',
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
    @endif
});
</script>
@endpush

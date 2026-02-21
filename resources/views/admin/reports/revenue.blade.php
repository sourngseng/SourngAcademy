@extends('admin.layouts.master')

@section('title', 'Revenue Report')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-dollar-sign mr-2"></i>Revenue Report</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
                    <li class="breadcrumb-item active">Revenue</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<!-- Summary Cards -->
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-gradient-success">
            <div class="inner">
                <h3>${{ number_format($totalRevenue, 2) }}</h3>
                <p>Total Revenue ({{ ucfirst($period) }})</p>
            </div>
            <div class="icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-gradient-info">
            <div class="inner">
                <h3>{{ $totalPayments }}</h3>
                <p>Total Payments</p>
            </div>
            <div class="icon">
                <i class="fas fa-receipt"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-gradient-warning">
            <div class="inner">
                <h3>${{ $totalPayments > 0 ? number_format($totalRevenue / $totalPayments, 2) : '0.00' }}</h3>
                <p>Average Payment</p>
            </div>
            <div class="icon">
                <i class="fas fa-calculator"></i>
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
        <form action="{{ route('admin.reports.revenue') }}" method="GET" class="row align-items-end">
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <label>Period</label>
                    <select name="period" class="form-control">
                        <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="weekly" {{ $period == 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    </select>
                </div>
            </div>
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
            <div class="col-md-3" id="monthSelect" style="{{ $period != 'daily' ? 'display:none;' : '' }}">
                <div class="form-group mb-0">
                    <label>Month</label>
                    <select name="month" class="form-control">
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

<div class="row">
    <!-- Revenue Chart -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-line"></i> Revenue Trend</h3>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Payment Method Breakdown -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie"></i> By Payment Method</h3>
            </div>
            <div class="card-body">
                <canvas id="methodChart" height="200"></canvas>
                <hr>
                <ul class="list-group list-group-flush">
                    @foreach($methodBreakdown as $method)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>
                            <i class="fas fa-{{ $method->payment_method == 'cash' ? 'money-bill' : ($method->payment_method == 'credit_card' ? 'credit-card' : ($method->payment_method == 'khqr' ? 'qrcode' : 'university')) }} text-primary mr-2"></i>
                            {{ ucfirst(str_replace('_', ' ', $method->payment_method)) }}
                        </span>
                        <span>
                            <span class="badge badge-success">${{ number_format($method->total, 2) }}</span>
                            <small class="text-muted">({{ $method->count }})</small>
                        </span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-table"></i> Revenue Data</h3>
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
                        <th>{{ $period == 'daily' ? 'Date' : ($period == 'weekly' ? 'Week' : 'Month') }}</th>
                        <th class="text-right">Revenue</th>
                        <th class="text-right">Payments</th>
                        <th class="text-right">Average</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($revenueData as $data)
                    @if($data['amount'] > 0 || $data['count'] > 0)
                    <tr>
                        <td>{{ $data[$period == 'daily' ? 'date' : ($period == 'weekly' ? 'week' : 'month')] ?? 'N/A' }}</td>
                        <td class="text-right"><strong class="text-success">${{ number_format($data['amount'], 2) }}</strong></td>
                        <td class="text-right">{{ $data['count'] }}</td>
                        <td class="text-right">${{ $data['count'] > 0 ? number_format($data['amount'] / $data['count'], 2) : '0.00' }}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
                <tfoot class="bg-light">
                    <tr>
                        <th>Total</th>
                        <th class="text-right text-success">${{ number_format($totalRevenue, 2) }}</th>
                        <th class="text-right">{{ $totalPayments }}</th>
                        <th class="text-right">${{ $totalPayments > 0 ? number_format($totalRevenue / $totalPayments, 2) : '0.00' }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

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
    // Show/hide month selector based on period
    $('select[name="period"]').on('change', function() {
        if ($(this).val() === 'daily') {
            $('#monthSelect').show();
        } else {
            $('#monthSelect').hide();
        }
    });

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Revenue ($)',
                data: {!! json_encode(array_column($revenueData, 'amount')) !!},
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
            },
            plugins: {
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

    // Payment Method Chart
    const methodCtx = document.getElementById('methodChart').getContext('2d');
    new Chart(methodCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($methodBreakdown->pluck('payment_method')->map(fn($m) => ucfirst(str_replace('_', ' ', $m)))) !!},
            datasets: [{
                data: {!! json_encode($methodBreakdown->pluck('total')) !!},
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
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endpush

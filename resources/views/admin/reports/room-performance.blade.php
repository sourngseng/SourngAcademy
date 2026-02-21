@extends('admin.layouts.master')

@section('title', 'Room Performance Report')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-door-open mr-2"></i>Room Performance Report</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
                    <li class="breadcrumb-item active">Room Performance</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<!-- Summary Cards -->
@php
    $totalRevenue = collect($roomTypePerformance)->sum('revenue');
    $totalNightsSold = collect($roomTypePerformance)->sum('nights_sold');
    $totalRooms = collect($roomTypePerformance)->sum('total_rooms');
    $avgOccupancy = $totalRooms * $periodDays > 0 ? ($totalNightsSold / ($totalRooms * $periodDays)) * 100 : 0;
    $avgADR = $totalNightsSold > 0 ? $totalRevenue / $totalNightsSold : 0;
    $revPAR = $totalRooms * $periodDays > 0 ? $totalRevenue / ($totalRooms * $periodDays) : 0;
@endphp

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-success">
            <div class="inner">
                <h3>${{ number_format($totalRevenue, 2) }}</h3>
                <p>Total Revenue</p>
            </div>
            <div class="icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-info">
            <div class="inner">
                <h3>${{ number_format($avgADR, 2) }}</h3>
                <p>Avg. Daily Rate (ADR)</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-warning">
            <div class="inner">
                <h3>${{ number_format($revPAR, 2) }}</h3>
                <p>RevPAR</p>
            </div>
            <div class="icon">
                <i class="fas fa-bed"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-primary">
            <div class="inner">
                <h3>{{ round($avgOccupancy, 1) }}%</h3>
                <p>Avg. Occupancy</p>
            </div>
            <div class="icon">
                <i class="fas fa-percentage"></i>
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
        <form action="{{ route('admin.reports.room-performance') }}" method="GET" class="row align-items-end">
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
    <i class="fas fa-info-circle"></i>
    <strong>Report Period:</strong> {{ $startDate->format('M d, Y') }} — {{ $endDate->format('M d, Y') }} ({{ $periodDays }} days)
</div>

<div class="row">
    <!-- Room Type Performance Chart -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar"></i> Revenue by Room Type</h3>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h3 class="card-title"><i class="fas fa-info-circle"></i> Key Metrics Explained</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong><i class="fas fa-chart-line text-info"></i> ADR (Average Daily Rate)</strong>
                    <p class="small text-muted mb-0">Total Room Revenue ÷ Number of Rooms Sold</p>
                    <p class="text-info mb-0">Current: <strong>${{ number_format($avgADR, 2) }}</strong></p>
                </div>
                <hr>
                <div class="mb-3">
                    <strong><i class="fas fa-bed text-warning"></i> RevPAR (Revenue Per Available Room)</strong>
                    <p class="small text-muted mb-0">Total Room Revenue ÷ (Available Rooms × Days)</p>
                    <p class="text-warning mb-0">Current: <strong>${{ number_format($revPAR, 2) }}</strong></p>
                </div>
                <hr>
                <div>
                    <strong><i class="fas fa-percentage text-primary"></i> Occupancy Rate</strong>
                    <p class="small text-muted mb-0">Rooms Sold ÷ Total Available Rooms</p>
                    <p class="text-primary mb-0">Current: <strong>{{ round($avgOccupancy, 1) }}%</strong></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Room Type Performance Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-table"></i> Performance by Room Type</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th>Room Type</th>
                        <th class="text-center">Rooms</th>
                        <th class="text-center">Bookings</th>
                        <th class="text-center">Nights Sold</th>
                        <th class="text-center">Occupancy</th>
                        <th class="text-right">Revenue</th>
                        <th class="text-right">ADR</th>
                        <th class="text-right">RevPAR</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roomTypePerformance as $type)
                    <tr>
                        <td><strong>{{ $type['name'] }}</strong></td>
                        <td class="text-center">{{ $type['total_rooms'] }}</td>
                        <td class="text-center">
                            <span class="badge badge-info">{{ $type['bookings'] }}</span>
                        </td>
                        <td class="text-center">{{ $type['nights_sold'] }}</td>
                        <td class="text-center">
                            <span class="badge badge-{{ $type['occupancy_rate'] >= 75 ? 'success' : ($type['occupancy_rate'] >= 50 ? 'warning' : 'danger') }}">
                                {{ $type['occupancy_rate'] }}%
                            </span>
                        </td>
                        <td class="text-right text-success">${{ number_format($type['revenue'], 2) }}</td>
                        <td class="text-right">${{ number_format($type['avg_daily_rate'], 2) }}</td>
                        <td class="text-right">${{ number_format($type['revpar'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-light">
                    <tr>
                        <th>Total / Average</th>
                        <th class="text-center">{{ $totalRooms }}</th>
                        <th class="text-center">{{ collect($roomTypePerformance)->sum('bookings') }}</th>
                        <th class="text-center">{{ $totalNightsSold }}</th>
                        <th class="text-center">
                            <span class="badge badge-{{ $avgOccupancy >= 75 ? 'success' : ($avgOccupancy >= 50 ? 'warning' : 'danger') }}">
                                {{ round($avgOccupancy, 1) }}%
                            </span>
                        </th>
                        <th class="text-right text-success">${{ number_format($totalRevenue, 2) }}</th>
                        <th class="text-right">${{ number_format($avgADR, 2) }}</th>
                        <th class="text-right">${{ number_format($revPAR, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Individual Room Performance -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-door-open"></i> Individual Room Performance</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0" id="roomTable">
                <thead class="thead-dark">
                    <tr>
                        <th>Room Number</th>
                        <th>Room Type</th>
                        <th class="text-center">Nights Sold</th>
                        <th class="text-center">Occupancy</th>
                        <th class="text-right">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roomPerformance->take(20) as $room)
                    <tr>
                        <td><strong>{{ $room['room_number'] }}</strong></td>
                        <td>{{ $room['room_type'] }}</td>
                        <td class="text-center">{{ $room['nights_sold'] }}</td>
                        <td class="text-center">
                            <div class="progress" style="height: 20px; width: 100px;">
                                <div class="progress-bar bg-{{ $room['occupancy_rate'] >= 75 ? 'success' : ($room['occupancy_rate'] >= 50 ? 'warning' : 'danger') }}" 
                                     style="width: {{ $room['occupancy_rate'] }}%">
                                    {{ $room['occupancy_rate'] }}%
                                </div>
                            </div>
                        </td>
                        <td class="text-right text-success">${{ number_format($room['revenue'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if($roomPerformance->count() > 20)
    <div class="card-footer text-muted">
        Showing top 20 rooms by revenue. Total rooms: {{ $roomPerformance->count() }}
    </div>
    @endif
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
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(collect($roomTypePerformance)->pluck('name')) !!},
            datasets: [{
                label: 'Revenue ($)',
                data: {!! json_encode(collect($roomTypePerformance)->pluck('revenue')) !!},
                backgroundColor: 'rgba(40, 167, 69, 0.7)',
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 1,
                yAxisID: 'y'
            }, {
                label: 'Occupancy (%)',
                data: {!! json_encode(collect($roomTypePerformance)->pluck('occupancy_rate')) !!},
                type: 'line',
                backgroundColor: 'rgba(0, 123, 255, 0.2)',
                borderColor: 'rgba(0, 123, 255, 1)',
                borderWidth: 2,
                fill: false,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    position: 'left',
                    title: { display: true, text: 'Revenue ($)' },
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                },
                y1: {
                    beginAtZero: true,
                    max: 100,
                    position: 'right',
                    title: { display: true, text: 'Occupancy (%)' },
                    grid: { drawOnChartArea: false },
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush

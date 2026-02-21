@extends('admin.layouts.master')

@section('title', 'Occupancy Report')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-bed mr-2"></i>Occupancy Report</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
                    <li class="breadcrumb-item active">Occupancy</li>
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
        <div class="small-box bg-gradient-primary">
            <div class="inner">
                <h3>{{ round($avgOccupancy, 1) }}%</h3>
                <p>Average Occupancy</p>
            </div>
            <div class="icon">
                <i class="fas fa-percentage"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-info">
            <div class="inner">
                <h3>{{ $totalRooms }}</h3>
                <p>Total Rooms</p>
            </div>
            <div class="icon">
                <i class="fas fa-door-open"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-success">
            <div class="inner">
                <h3>{{ $daysInMonth }}</h3>
                <p>Days in Period</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-warning">
            <div class="inner">
                <h3>{{ $totalRooms * $daysInMonth }}</h3>
                <p>Room Nights Available</p>
            </div>
            <div class="icon">
                <i class="fas fa-moon"></i>
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
        <form action="{{ route('admin.reports.occupancy') }}" method="GET" class="row align-items-end">
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
    <!-- Occupancy Chart -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-area"></i> Daily Occupancy Rate</h3>
            </div>
            <div class="card-body">
                <canvas id="occupancyChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Room Type Breakdown -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar"></i> By Room Type</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @foreach($roomTypeOccupancy as $type)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>{{ $type['name'] }}</strong>
                            <span class="badge badge-{{ $type['rate'] >= 75 ? 'success' : ($type['rate'] >= 50 ? 'warning' : 'danger') }}">
                                {{ $type['rate'] }}%
                            </span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-{{ $type['rate'] >= 75 ? 'success' : ($type['rate'] >= 50 ? 'warning' : 'danger') }}" 
                                 style="width: {{ $type['rate'] }}%"></div>
                        </div>
                        <small class="text-muted">{{ $type['total_rooms'] }} rooms · {{ $type['occupied_nights'] }} nights sold</small>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Occupancy Summary -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title"><i class="fas fa-info-circle"></i> Summary</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Period:</span>
                    <strong>{{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Room Nights:</span>
                    <strong>{{ $totalRooms * $daysInMonth }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Occupied Nights:</span>
                    <strong>{{ collect($dailyOccupancy)->sum('occupied') }}</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span class="h5 mb-0">Avg. Occupancy:</span>
                    <span class="h4 mb-0 text-{{ $avgOccupancy >= 75 ? 'success' : ($avgOccupancy >= 50 ? 'warning' : 'danger') }}">
                        {{ round($avgOccupancy, 1) }}%
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Daily Occupancy Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-table"></i> Daily Occupancy Data</h3>
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
                        <th>Date</th>
                        <th class="text-center">Occupied</th>
                        <th class="text-center">Available</th>
                        <th class="text-center">Occupancy Rate</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyOccupancy as $day)
                    <tr>
                        <td>{{ $day['date'] }}</td>
                        <td class="text-center">{{ $day['occupied'] }}</td>
                        <td class="text-center">{{ $day['available'] }}</td>
                        <td class="text-center">
                            <strong class="text-{{ $day['rate'] >= 75 ? 'success' : ($day['rate'] >= 50 ? 'warning' : 'danger') }}">
                                {{ $day['rate'] }}%
                            </strong>
                        </td>
                        <td>
                            <div class="progress" style="height: 15px; width: 100px;">
                                <div class="progress-bar bg-{{ $day['rate'] >= 75 ? 'success' : ($day['rate'] >= 50 ? 'warning' : 'danger') }}" 
                                     style="width: {{ $day['rate'] }}%"></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
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
    const ctx = document.getElementById('occupancyChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Occupancy Rate (%)',
                data: {!! json_encode(array_column($dailyOccupancy, 'rate')) !!},
                fill: true,
                backgroundColor: 'rgba(0, 123, 255, 0.2)',
                borderColor: 'rgba(0, 123, 255, 1)',
                borderWidth: 2,
                tension: 0.3,
                pointRadius: 3,
                pointBackgroundColor: 'rgba(0, 123, 255, 1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw + '%';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush

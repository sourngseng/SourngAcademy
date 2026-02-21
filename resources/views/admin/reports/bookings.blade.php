@extends('admin.layouts.master')

@section('title', 'Bookings Report')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-calendar-check mr-2"></i>Bookings Report</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
                    <li class="breadcrumb-item active">Bookings</li>
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
                <h3>{{ $totalBookings }}</h3>
                <p>Total Bookings</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
    </div>
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
        <div class="small-box bg-gradient-warning">
            <div class="inner">
                <h3>{{ round($avgStayDuration, 1) }}</h3>
                <p>Avg. Stay (Nights)</p>
            </div>
            <div class="icon">
                <i class="fas fa-moon"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-primary">
            <div class="inner">
                <h3>{{ round($avgLeadTime, 1) }}</h3>
                <p>Avg. Lead Time (Days)</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
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
        <form action="{{ route('admin.reports.bookings') }}" method="GET" class="row align-items-end">
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <label>Start Date</label>
                    <div class="input-group date" id="start_date_picker" data-target-input="nearest">
                        <input type="text" name="start_date" class="form-control datetimepicker-input" 
                               data-target="#start_date_picker" value="{{ $startDate->format('Y-m-d') }}" placeholder="Select date">
                        <div class="input-group-append" data-target="#start_date_picker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <label>End Date</label>
                    <div class="input-group date" id="end_date_picker" data-target-input="nearest">
                        <input type="text" name="end_date" class="form-control datetimepicker-input" 
                               data-target="#end_date_picker" value="{{ $endDate->format('Y-m-d') }}" placeholder="Select date">
                        <div class="input-group-append" data-target="#end_date_picker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="checked_in" {{ $status == 'checked_in' ? 'selected' : '' }}>Checked In</option>
                        <option value="checked_out" {{ $status == 'checked_out' ? 'selected' : '' }}>Checked Out</option>
                        <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
    <!-- Booking Trend Chart -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-line"></i> Booking Trend</h3>
            </div>
            <div class="card-body">
                <canvas id="bookingChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Status Breakdown -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie"></i> By Status</h3>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="200"></canvas>
                <hr>
                <ul class="list-group list-group-flush">
                    @foreach($statusBreakdown as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>
                            <span class="badge badge-{{ $item->status == 'confirmed' ? 'success' : ($item->status == 'checked_in' ? 'info' : ($item->status == 'cancelled' ? 'danger' : ($item->status == 'checked_out' ? 'secondary' : 'warning'))) }} mr-2">
                                {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                            </span>
                        </span>
                        <span>
                            <strong>{{ $item->count }}</strong> bookings
                            <br><small class="text-muted">${{ number_format($item->revenue, 2) }}</small>
                        </span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Room Type Popularity -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-star"></i> Popular Room Types</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Room Type</th>
                            <th class="text-center">Bookings</th>
                            <th class="text-right">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roomTypePopularity as $type)
                        <tr>
                            <td><strong>{{ $type->room_type_name }}</strong></td>
                            <td class="text-center">
                                <span class="badge badge-info">{{ $type->bookings }}</span>
                            </td>
                            <td class="text-right text-success">${{ number_format($type->revenue, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h3 class="card-title"><i class="fas fa-chart-bar"></i> Key Metrics</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="info-box bg-light mb-3">
                            <span class="info-box-icon bg-success"><i class="fas fa-dollar-sign"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Avg. Booking Value</span>
                                <span class="info-box-number">${{ $totalBookings > 0 ? number_format($totalRevenue / $totalBookings, 2) : '0.00' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-box bg-light mb-3">
                            <span class="info-box-icon bg-warning"><i class="fas fa-moon"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Avg. Stay Duration</span>
                                <span class="info-box-number">{{ round($avgStayDuration, 1) }} nights</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-box bg-light mb-3">
                            <span class="info-box-icon bg-primary"><i class="fas fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Avg. Lead Time</span>
                                <span class="info-box-number">{{ round($avgLeadTime, 1) }} days</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-box bg-light mb-3">
                            <span class="info-box-icon bg-info"><i class="fas fa-calendar-day"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Daily Avg. Bookings</span>
                                <span class="info-box-number">{{ $startDate->diffInDays($endDate) > 0 ? round($totalBookings / $startDate->diffInDays($endDate), 1) : $totalBookings }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-list"></i> Recent Bookings</h3>
        <div class="card-tools">
            <span class="badge badge-info">{{ $recentBookings->total() }} bookings</span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Guest</th>
                        <th>Rooms</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Nights</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentBookings as $booking)
                    <tr>
                        <td>
                            <a href="{{ route('admin.bookings.show', $booking->id) }}">#{{ $booking->id }}</a>
                        </td>
                        <td>{{ $booking->user->name ?? 'N/A' }}</td>
                        <td>{{ $booking->rooms->count() }} room(s)</td>
                        <td>{{ $booking->check_in->format('M d, Y') }}</td>
                        <td>{{ $booking->check_out->format('M d, Y') }}</td>
                        <td>{{ $booking->check_in->diffInDays($booking->check_out) }}</td>
                        <td class="text-success">${{ number_format($booking->total_price, 2) }}</td>
                        <td>
                            <span class="badge badge-{{ $booking->status == 'confirmed' ? 'success' : ($booking->status == 'checked_in' ? 'info' : ($booking->status == 'cancelled' ? 'danger' : ($booking->status == 'checked_out' ? 'secondary' : 'warning'))) }}">
                                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $recentBookings->withQueryString()->links() }}
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
    // Initialize DateTime Pickers
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

    // Booking Trend Chart
    const bookingCtx = document.getElementById('bookingChart').getContext('2d');
    new Chart(bookingCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dailyBookings->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'))) !!},
            datasets: [{
                label: 'Bookings',
                data: {!! json_encode($dailyBookings->pluck('count')) !!},
                backgroundColor: 'rgba(23, 162, 184, 0.2)',
                borderColor: 'rgba(23, 162, 184, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }, {
                label: 'Revenue ($)',
                data: {!! json_encode($dailyBookings->pluck('revenue')) !!},
                backgroundColor: 'rgba(40, 167, 69, 0.2)',
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3,
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
                    title: { display: true, text: 'Bookings' }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    title: { display: true, text: 'Revenue ($)' },
                    grid: { drawOnChartArea: false }
                }
            }
        }
    });

    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($statusBreakdown->pluck('status')->map(fn($s) => ucfirst(str_replace('_', ' ', $s)))) !!},
            datasets: [{
                data: {!! json_encode($statusBreakdown->pluck('count')) !!},
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(23, 162, 184, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(220, 53, 69, 0.8)',
                    'rgba(108, 117, 125, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
});
</script>
@endpush

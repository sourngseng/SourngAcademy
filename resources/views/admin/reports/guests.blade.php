@extends('admin.layouts.master')

@section('title', 'Guest Report')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-users mr-2"></i>Guest Report</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
                    <li class="breadcrumb-item active">Guests</li>
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
                <h3>{{ number_format($totalGuests) }}</h3>
                <p>Total Guests</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-success">
            <div class="inner">
                <h3>{{ number_format($newGuestsThisYear) }}</h3>
                <p>New Guests ({{ $year }})</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-plus"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-warning">
            <div class="inner">
                <h3>{{ number_format($repeatGuests) }}</h3>
                <p>Repeat Guests</p>
            </div>
            <div class="icon">
                <i class="fas fa-redo"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-primary">
            <div class="inner">
                <h3>{{ $totalGuests > 0 ? round(($repeatGuests / $totalGuests) * 100, 1) : 0 }}%</h3>
                <p>Repeat Rate</p>
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
        <form action="{{ route('admin.reports.guests') }}" method="GET" class="row align-items-end">
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
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sync-alt"></i> Update Report
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <!-- New Guest Trend -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-line"></i> New Guest Acquisition ({{ $year }})</h3>
            </div>
            <div class="card-body">
                <canvas id="guestChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Guest Breakdown -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie"></i> Guest Loyalty</h3>
            </div>
            <div class="card-body">
                <canvas id="loyaltyChart" height="200"></canvas>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span><i class="fas fa-user text-info mr-2"></i>First-time Guests</span>
                    <strong>{{ number_format($singleBookingGuests) }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span><i class="fas fa-medal text-warning mr-2"></i>Repeat Guests</span>
                    <strong>{{ number_format($repeatGuests) }}</strong>
                </div>
            </div>
        </div>

        <!-- Geographic Distribution -->
        @if($guestDistribution->count() > 0)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-globe"></i> By Country</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @foreach($guestDistribution as $country)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $country->country }}</span>
                        <span class="badge badge-primary badge-pill">{{ $country->count }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="row">
    <!-- Top Guests by Revenue -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h3 class="card-title"><i class="fas fa-trophy"></i> Top Guests by Revenue</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Guest Name</th>
                            <th class="text-center">Bookings</th>
                            <th class="text-right">Total Spent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topGuestsByRevenue as $index => $guest)
                        <tr>
                            <td>
                                @if($index < 3)
                                    <span class="badge badge-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'danger') }}">
                                        <i class="fas fa-medal"></i> {{ $index + 1 }}
                                    </span>
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </td>
                            <td>
                                <strong>{{ $guest->name }}</strong>
                                <br><small class="text-muted">{{ $guest->email }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-info">{{ $guest->bookings_count }}</span>
                            </td>
                            <td class="text-right">
                                <strong class="text-success">${{ number_format($guest->bookings_sum_total_price ?? 0, 2) }}</strong>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No guest data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top Guests by Bookings -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h3 class="card-title"><i class="fas fa-star"></i> Top Guests by Bookings</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Guest Name</th>
                            <th class="text-center">Bookings</th>
                            <th class="text-right">Total Spent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topGuestsByBookings as $index => $guest)
                        <tr>
                            <td>
                                @if($index < 3)
                                    <span class="badge badge-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'danger') }}">
                                        <i class="fas fa-medal"></i> {{ $index + 1 }}
                                    </span>
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </td>
                            <td>
                                <strong>{{ $guest->name }}</strong>
                                <br><small class="text-muted">{{ $guest->email }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-success">{{ $guest->bookings_count }}</span>
                            </td>
                            <td class="text-right">
                                <strong class="text-success">${{ number_format($guest->bookings_sum_total_price ?? 0, 2) }}</strong>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No guest data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
    // Monthly New Guests Chart
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const monthlyData = @json($monthlyNewGuests);
    const guestData = months.map((_, i) => monthlyData[i + 1]?.count || 0);

    const guestCtx = document.getElementById('guestChart').getContext('2d');
    new Chart(guestCtx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'New Guests',
                data: guestData,
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
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Loyalty Chart
    const loyaltyCtx = document.getElementById('loyaltyChart').getContext('2d');
    new Chart(loyaltyCtx, {
        type: 'doughnut',
        data: {
            labels: ['First-time Guests', 'Repeat Guests'],
            datasets: [{
                data: [{{ $singleBookingGuests }}, {{ $repeatGuests }}],
                backgroundColor: [
                    'rgba(23, 162, 184, 0.8)',
                    'rgba(255, 193, 7, 0.8)'
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

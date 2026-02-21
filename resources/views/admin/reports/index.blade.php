@extends('admin.layouts.master')

@section('title', 'Reports Dashboard')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-chart-line mr-2"></i>Reports & Analytics</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Reports</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<!-- Quick Stats Overview -->
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-gradient-success">
            <div class="inner">
                <h3>${{ number_format($stats['today_revenue'], 2) }}</h3>
                <p>Today's Revenue</p>
            </div>
            <div class="icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <a href="{{ route('admin.reports.revenue') }}" class="small-box-footer">
                View Details <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-gradient-info">
            <div class="inner">
                <h3>${{ number_format($stats['month_revenue'], 2) }}</h3>
                <p>This Month's Revenue</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-bar"></i>
            </div>
            <a href="{{ route('admin.reports.revenue') }}" class="small-box-footer">
                View Details <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-gradient-primary">
            <div class="inner">
                <h3>{{ $stats['current_occupancy'] }}%</h3>
                <p>Current Occupancy</p>
            </div>
            <div class="icon">
                <i class="fas fa-bed"></i>
            </div>
            <a href="{{ route('admin.reports.occupancy') }}" class="small-box-footer">
                View Details <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-gradient-warning">
            <div class="inner">
                <h3>{{ $stats['today_bookings'] }}</h3>
                <p>Today's Bookings</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <a href="{{ route('admin.reports.bookings') }}" class="small-box-footer">
                View Details <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-gradient-secondary">
            <div class="inner">
                <h3>{{ $stats['month_bookings'] }}</h3>
                <p>This Month's Bookings</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <a href="{{ route('admin.reports.bookings') }}" class="small-box-footer">
                View Details <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-gradient-danger">
            <div class="inner">
                <h3>${{ number_format($stats['pending_payments'], 2) }}</h3>
                <p>Pending Payments</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <a href="{{ route('admin.reports.financial') }}" class="small-box-footer">
                View Details <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Report Cards -->
<div class="row">
    <div class="col-12">
        <h4 class="mb-3"><i class="fas fa-file-alt mr-2"></i>Available Reports</h4>
    </div>
</div>

<div class="row">
    <!-- Revenue Report -->
    <div class="col-lg-4 col-md-6">
        <div class="card card-outline card-success h-100">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-dollar-sign text-success mr-2"></i>Revenue Report</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">Analyze revenue trends with daily, weekly, and monthly breakdowns. View payment method distribution and identify peak revenue periods.</p>
                <ul class="text-muted small">
                    <li>Daily/Weekly/Monthly view</li>
                    <li>Payment method breakdown</li>
                    <li>Revenue trends chart</li>
                    <li>Year-over-year comparison</li>
                </ul>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.reports.revenue') }}" class="btn btn-success btn-block">
                    <i class="fas fa-chart-line"></i> View Report
                </a>
            </div>
        </div>
    </div>

    <!-- Occupancy Report -->
    <div class="col-lg-4 col-md-6">
        <div class="card card-outline card-primary h-100">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-bed text-primary mr-2"></i>Occupancy Report</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">Track room occupancy rates across different periods. Monitor room type performance and optimize pricing strategies.</p>
                <ul class="text-muted small">
                    <li>Daily occupancy tracking</li>
                    <li>Room type comparison</li>
                    <li>Occupancy rate trends</li>
                    <li>Availability analysis</li>
                </ul>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.reports.occupancy') }}" class="btn btn-primary btn-block">
                    <i class="fas fa-chart-pie"></i> View Report
                </a>
            </div>
        </div>
    </div>

    <!-- Bookings Report -->
    <div class="col-lg-4 col-md-6">
        <div class="card card-outline card-info h-100">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-calendar-check text-info mr-2"></i>Bookings Report</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">Comprehensive booking analytics including trends, cancellations, and room type popularity. Understand booking patterns.</p>
                <ul class="text-muted small">
                    <li>Booking status breakdown</li>
                    <li>Room type popularity</li>
                    <li>Average stay duration</li>
                    <li>Lead time analysis</li>
                </ul>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.reports.bookings') }}" class="btn btn-info btn-block">
                    <i class="fas fa-chart-bar"></i> View Report
                </a>
            </div>
        </div>
    </div>

    <!-- Guest Report -->
    <div class="col-lg-4 col-md-6">
        <div class="card card-outline card-warning h-100">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-users text-warning mr-2"></i>Guest Report</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">Analyze guest demographics, identify top customers, and track new vs returning guests. Build loyalty programs.</p>
                <ul class="text-muted small">
                    <li>Top guests by revenue</li>
                    <li>Repeat guest analysis</li>
                    <li>Guest acquisition trends</li>
                    <li>Geographic distribution</li>
                </ul>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.reports.guests') }}" class="btn btn-warning btn-block">
                    <i class="fas fa-user-friends"></i> View Report
                </a>
            </div>
        </div>
    </div>

    <!-- Room Performance Report -->
    <div class="col-lg-4 col-md-6">
        <div class="card card-outline card-secondary h-100">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-door-open text-secondary mr-2"></i>Room Performance</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">Evaluate individual room and room type performance. Calculate key metrics like RevPAR and ADR for pricing optimization.</p>
                <ul class="text-muted small">
                    <li>Revenue per room</li>
                    <li>Average Daily Rate (ADR)</li>
                    <li>RevPAR calculation</li>
                    <li>Room type comparison</li>
                </ul>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.reports.room-performance') }}" class="btn btn-secondary btn-block">
                    <i class="fas fa-hotel"></i> View Report
                </a>
            </div>
        </div>
    </div>

    <!-- Financial Summary Report -->
    <div class="col-lg-4 col-md-6">
        <div class="card card-outline card-danger h-100">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-file-invoice-dollar text-danger mr-2"></i>Financial Summary</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">Complete financial overview including gross revenue, payments, refunds, and outstanding balances. Perfect for accounting.</p>
                <ul class="text-muted small">
                    <li>Gross vs Net revenue</li>
                    <li>Payment collection rate</li>
                    <li>Invoice statistics</li>
                    <li>Monthly P&L breakdown</li>
                </ul>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.reports.financial') }}" class="btn btn-danger btn-block">
                    <i class="fas fa-calculator"></i> View Report
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

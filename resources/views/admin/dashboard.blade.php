@extends('admin.layouts.master')

@section('title', __('Dashboard'))

@push('styles')
<style>
    .quick-date.active {
        background-color: #007bff !important;
        color: white !important;
        border-color: #007bff !important;
    }
    .bootstrap-datetimepicker-widget {
        z-index: 10000 !important;
    }
    .input-group-text {
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('Dashboard') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Dashboard') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Filter Section -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card card-outline card-primary collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-filter"></i> {{ __('Filter Data') }}</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body" style="display: none;">
                        <form action="{{ route('admin.dashboard') }}" method="GET" id="filterForm">
                            <div class="row">
                                <!-- Quick Date Presets -->
                                <div class="col-md-12 mb-3">
                                    <label>{{ __('Quick Date Range') }}</label>
                                    <div class="btn-group btn-group-sm d-flex" role="group">
                                        <button type="button" class="btn btn-outline-primary quick-date" data-range="today">{{ __('Today') }}</button>
                                        <button type="button" class="btn btn-outline-primary quick-date" data-range="week">{{ __('This Week') }}</button>
                                        <button type="button" class="btn btn-outline-primary quick-date" data-range="month">{{ __('This Month') }}</button>
                                        <button type="button" class="btn btn-outline-primary quick-date" data-range="last_month">{{ __('Last Month') }}</button>
                                        <button type="button" class="btn btn-outline-primary quick-date" data-range="year">{{ __('This Year') }}</button>
                                        <button type="button" class="btn btn-outline-secondary" id="clearDates">{{ __('Clear') }}</button>
                                    </div>
                                </div>

                                <!-- Custom Date Range -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="date_from">{{ __('Date From') }}</label>
                                        <div class="input-group input-group-sm date" id="datetimepicker_from" data-target-input="nearest">
                                            <input type="text" class="form-control form-control-sm datetimepicker-input" id="date_from" name="date_from" value="{{ request('date_from') }}" data-target="#datetimepicker_from" autocomplete="off" placeholder="YYYY-MM-DD"/>
                                            <div class="input-group-append" data-target="#datetimepicker_from" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="date_to">{{ __('Date To') }}</label>
                                        <div class="input-group input-group-sm date" id="datetimepicker_to" data-target-input="nearest">
                                            <input type="text" class="form-control form-control-sm datetimepicker-input" id="date_to" name="date_to" value="{{ request('date_to') }}" data-target="#datetimepicker_to" autocomplete="off" placeholder="YYYY-MM-DD"/>
                                            <div class="input-group-append" data-target="#datetimepicker_to" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Booking Status Filter -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="booking_status">{{ __('Booking Status') }}</label>
                                        <select class="form-control form-control-sm" id="booking_status" name="booking_status">
                                            <option value="">{{ __('All Status') }}</option>
                                            <option value="pending" {{ request('booking_status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                            <option value="confirmed" {{ request('booking_status') == 'confirmed' ? 'selected' : '' }}>{{ __('Confirmed') }}</option>
                                            <option value="checked_in" {{ request('booking_status') == 'checked_in' ? 'selected' : '' }}>{{ __('Checked In') }}</option>
                                            <option value="checked_out" {{ request('booking_status') == 'checked_out' ? 'selected' : '' }}>{{ __('Checked Out') }}</option>
                                            <option value="cancelled" {{ request('booking_status') == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Room Type Filter -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="room_type">{{ __('Room Type') }}</label>
                                        <select class="form-control form-control-sm" id="room_type" name="room_type">
                                            <option value="">{{ __('All Room Types') }}</option>
                                            @foreach($roomTypes as $type)
                                                <option value="{{ $type->id }}" {{ request('room_type') == $type->id ? 'selected' : '' }}>
                                                    {{ $type->room_type_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-search"></i> {{ __('Apply Filter') }}
                                    </button>
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-redo"></i> {{ __('Reset') }}
                                    </a>
                                    @if(request()->hasAny(['date_from', 'date_to', 'booking_status', 'room_type']))
                                        <span class="badge badge-info ml-2 p-2">
                                            <i class="fas fa-info-circle"></i> {{ __('Filters Active') }}:
                                            @if(request('date_from'))
                                                <span class="badge badge-light ml-1">{{ request('date_from') }}</span>
                                            @endif
                                            @if(request('date_to'))
                                                <span class="badge badge-light ml-1">{{ __('To') }}: {{ request('date_to') }}</span>
                                            @endif
                                            @if(request('booking_status'))
                                                <span class="badge badge-light ml-1">{{ ucfirst(str_replace('_', ' ', request('booking_status'))) }}</span>
                                            @endif
                                            @if(request('room_type'))
                                                <span class="badge badge-light ml-1">{{ $roomTypes->find(request('room_type'))->room_type_name ?? 'Room Type' }}</span>
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if(request()->hasAny(['date_from', 'date_to', 'booking_status', 'room_type']))
        <!-- Active Filter Summary - Compact Version -->
        <div class="row mb-2">
            <div class="col-12">
                <div class="callout callout-info py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-filter mr-2"></i>
                            <strong>{{ __('Active Filters') }}:</strong>
                            @if(request('date_from') || request('date_to'))
                                <span class="badge badge-primary ml-2">
                                    <i class="far fa-calendar-alt"></i>
                                    {{ request('date_from') ?: '...' }} <i class="fas fa-arrow-right mx-1"></i> {{ request('date_to') ?: '...' }}
                                </span>
                            @endif
                            @if(request('booking_status'))
                                <span class="badge badge-success ml-2">
                                    <i class="fas fa-info-circle"></i>
                                    {{ __(ucfirst(str_replace('_', ' ', request('booking_status')))) }}
                                </span>
                            @endif
                            @if(request('room_type'))
                                <span class="badge badge-warning ml-2">
                                    <i class="fas fa-door-open"></i>
                                    {{ $roomTypes->find(request('room_type'))->room_type_name ?? 'N/A' }}
                                </span>
                            @endif
                        </div>
                        <div>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary" title="{{ __('Clear All Filters') }}">
                                <i class="fas fa-times-circle"></i> {{ __('Clear') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Statistics Cards Row 1 - Rooms -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $stats['total_rooms'] }}</h3>
                        <p>{{ __('Total Rooms') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-door-open"></i>
                    </div>
                    <a href="{{ route('admin.rooms.index') }}" class="small-box-footer">
                        {{ __('More info') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['available_rooms'] }}</h3>
                        <p>{{ __('Available Rooms') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <a href="{{ route('admin.rooms.index') }}" class="small-box-footer">
                        {{ __('More info') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['occupied_rooms'] }}</h3>
                        <p>{{ __('Occupied Rooms') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-bed"></i>
                    </div>
                    <a href="{{ route('admin.rooms.index') }}" class="small-box-footer">
                        {{ __('More info') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $stats['occupancy_rate'] }}%</h3>
                        <p>{{ __('Occupancy Rate') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <a href="{{ route('admin.reports.occupancy') }}" class="small-box-footer">
                        {{ __('More info') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards Row 2 - Bookings & Revenue -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-primary"><i class="fas fa-calendar-check"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ __('Total Bookings') }}</span>
                        <span class="info-box-number">{{ $stats['total_bookings'] }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-sign-in-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">
                            @if(request('date_from') || request('date_to'))
                                {{ __('Check-ins') }} ({{ __('Filtered') }})
                            @else
                                {{ __('Today Check-ins') }}
                            @endif
                        </span>
                        <span class="info-box-number">{{ $stats['today_checkins'] }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fas fa-sign-out-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">
                            @if(request('date_from') || request('date_to'))
                                {{ __('Check-outs') }} ({{ __('Filtered') }})
                            @else
                                {{ __('Today Check-outs') }}
                            @endif
                        </span>
                        <span class="info-box-number">{{ $stats['today_checkouts'] }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-dollar-sign"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">
                            @if(request('date_from') || request('date_to'))
                                {{ __('Period Revenue') }}
                            @else
                                {{ __('Month Revenue') }}
                            @endif
                        </span>
                        <span class="info-box-number">${{ number_format($stats['month_revenue'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Row -->
        <div class="row">
            <!-- Left Column - Recent Bookings -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-list mr-1"></i>
                            {{ __('Recent Bookings') }}
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> {{ __('View All') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>{{ __('Guest') }}</th>
                                        <th>{{ __('Room') }}</th>
                                        <th>{{ __('Check-in') }}</th>
                                        <th>{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentBookings as $booking)
                                        <tr>
                                            <td>{{ $booking->guest->name ?? 'N/A' }}</td>
                                            <td>{{ $booking->rooms->pluck('room_number')->implode(', ') ?: 'N/A' }}</td>
                                            <td>{{ $booking->check_in ? $booking->check_in->format('M d, Y') : 'N/A' }}</td>
                                            <td>
                                                @if($booking->status == 'confirmed')
                                                    <span class="badge badge-success">{{ __('Confirmed') }}</span>
                                                @elseif($booking->status == 'pending')
                                                    <span class="badge badge-warning">{{ __('Pending') }}</span>
                                                @elseif($booking->status == 'cancelled')
                                                    <span class="badge badge-danger">{{ __('Cancelled') }}</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ ucfirst($booking->status) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">{{ __('No bookings found') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Upcoming Check-ins -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-calendar-day mr-1"></i>
                            {{ __('Upcoming Check-ins') }}
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-info">{{ __('Next 7 Days') }}</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>{{ __('Guest') }}</th>
                                        <th>{{ __('Room') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Nights') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($upcomingCheckins as $booking)
                                        <tr>
                                            <td>{{ $booking->guest->name ?? 'N/A' }}</td>
                                            <td>{{ $booking->rooms->pluck('room_number')->implode(', ') ?: 'N/A' }}</td>
                                            <td>{{ $booking->check_in ? $booking->check_in->format('M d') : 'N/A' }}</td>
                                            <td>{{ $booking->check_in && $booking->check_out ? $booking->check_in->diffInDays($booking->check_out) : 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">{{ __('No upcoming check-ins') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Chart Row -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-1"></i>
                                {{ __('Revenue Trend') }}
                            </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-users mr-1"></i>
                            {{ __('Guest Statistics') }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="info-box mb-3 bg-info">
                            <span class="info-box-icon"><i class="fas fa-user-friends"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('Total Guests') }}</span>
                                <span class="info-box-number">{{ $stats['total_guests'] }}</span>
                            </div>
                        </div>

                        <div class="info-box mb-3 bg-success">
                            <span class="info-box-icon"><i class="fas fa-star"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('Total Reviews') }}</span>
                                <span class="info-box-number">{{ $stats['total_reviews'] }}</span>
                            </div>
                        </div>

                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('Pending Reviews') }}</span>
                                <span class="info-box-number">{{ $stats['pending_reviews'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bolt mr-1"></i>
                            {{ __('Quick Actions') }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-6">
                                <a href="{{ route('admin.bookings.create') }}" class="btn btn-app bg-primary">
                                    <i class="fas fa-plus"></i> {{ __('New Booking') }}
                                </a>
                            </div>
                            <div class="col-md-3 col-6">
                                <a href="{{ route('admin.guests.index') }}" class="btn btn-app bg-success">
                                    <i class="fas fa-user-plus"></i> {{ __('Add Guest') }}
                                </a>
                            </div>
                            <div class="col-md-3 col-6">
                                <a href="{{ route('admin.bookings.calendar') }}" class="btn btn-app bg-info">
                                    <i class="fas fa-calendar"></i> {{ __('Calendar') }}
                                </a>
                            </div>
                            <div class="col-md-3 col-6">
                                <a href="{{ route('admin.reports.index') }}" class="btn btn-app bg-warning">
                                    <i class="fas fa-chart-bar"></i> {{ __('Reports') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DateTime Pickers
    $('#datetimepicker_from').datetimepicker({
        format: 'YYYY-MM-DD',
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar',
            up: 'fas fa-arrow-up',
            down: 'fas fa-arrow-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'fas fa-calendar-check',
            clear: 'far fa-trash-alt',
            close: 'far fa-times-circle'
        },
        buttons: {
            showToday: true,
            showClear: true,
            showClose: true
        }
    });
    
    $('#datetimepicker_to').datetimepicker({
        format: 'YYYY-MM-DD',
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar',
            up: 'fas fa-arrow-up',
            down: 'fas fa-arrow-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'fas fa-calendar-check',
            clear: 'far fa-trash-alt',
            close: 'far fa-times-circle'
        },
        buttons: {
            showToday: true,
            showClear: true,
            showClose: true
        },
        useCurrent: false
    });
    
    // Link date pickers (date_to must be after date_from)
    $('#datetimepicker_from').on('change.datetimepicker', function (e) {
        $('#datetimepicker_to').datetimepicker('minDate', e.date);
    });
    
    $('#datetimepicker_to').on('change.datetimepicker', function (e) {
        $('#datetimepicker_from').datetimepicker('maxDate', e.date);
    });

    // Quick Date Range Buttons
    $('.quick-date').click(function() {
        var range = $(this).data('range');
        var today = new Date();
        var dateFrom, dateTo;

        switch(range) {
            case 'today':
                dateFrom = dateTo = moment().format('YYYY-MM-DD');
                break;
            case 'week':
                var weekStart = moment().startOf('week');
                dateFrom = weekStart.format('YYYY-MM-DD');
                dateTo = moment().format('YYYY-MM-DD');
                break;
            case 'month':
                dateFrom = moment().startOf('month').format('YYYY-MM-DD');
                dateTo = moment().format('YYYY-MM-DD');
                break;
            case 'last_month':
                dateFrom = moment().subtract(1, 'month').startOf('month').format('YYYY-MM-DD');
                dateTo = moment().subtract(1, 'month').endOf('month').format('YYYY-MM-DD');
                break;
            case 'year':
                dateFrom = moment().startOf('year').format('YYYY-MM-DD');
                dateTo = moment().format('YYYY-MM-DD');
                break;
        }

        $('#datetimepicker_from').datetimepicker('date', dateFrom);
        $('#datetimepicker_to').datetimepicker('date', dateTo);
        
        // Highlight active button
        $('.quick-date').removeClass('active');
        $(this).addClass('active');
    });

    // Clear Dates Button
    $('#clearDates').click(function() {
        $('#datetimepicker_from').datetimepicker('clear');
        $('#datetimepicker_to').datetimepicker('clear');
        $('.quick-date').removeClass('active');
    });

    // Auto-expand filter card if filters are active
    @if(request()->hasAny(['date_from', 'date_to', 'booking_status', 'room_type']))
        $('.card').CardWidget('expand');
        
        // Highlight active quick date button if applicable
        var dateFrom = '{{ request("date_from") }}';
        var dateTo = '{{ request("date_to") }}';
        var today = moment().format('YYYY-MM-DD');
        var weekStart = moment().startOf('week').format('YYYY-MM-DD');
        var monthStart = moment().startOf('month').format('YYYY-MM-DD');
        var lastMonthStart = moment().subtract(1, 'month').startOf('month').format('YYYY-MM-DD');
        var lastMonthEnd = moment().subtract(1, 'month').endOf('month').format('YYYY-MM-DD');
        var yearStart = moment().startOf('year').format('YYYY-MM-DD');
        
        if (dateFrom === today && dateTo === today) {
            $('.quick-date[data-range="today"]').addClass('active');
        } else if (dateFrom === weekStart && dateTo === today) {
            $('.quick-date[data-range="week"]').addClass('active');
        } else if (dateFrom === monthStart && dateTo === today) {
            $('.quick-date[data-range="month"]').addClass('active');
        } else if (dateFrom === lastMonthStart && dateTo === lastMonthEnd) {
            $('.quick-date[data-range="last_month"]').addClass('active');
        } else if (dateFrom === yearStart && dateTo === today) {
            $('.quick-date[data-range="year"]').addClass('active');
        }
    @endif
});
</script>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueData = @json($monthlyRevenue);
    
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueData.map(item => item.month),
            datasets: [{
                label: '{{ __("Revenue") }} ($)',
                data: revenueData.map(item => item.revenue),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
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
</script>
@endpush


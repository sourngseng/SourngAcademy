@extends('staff.layouts.master')

@section('title', 'Today\'s Arrivals')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-plane-arrival text-success"></i> Today's Arrivals</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Arrivals</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<!-- Date Selector -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="form-inline">
            <label class="mr-2"><i class="fas fa-calendar"></i> Select Date:</label>
            <div class="input-group date mr-2" id="date_picker" data-target-input="nearest">
                <input type="text" name="date" class="form-control datetimepicker-input" data-target="#date_picker" value="{{ $date->format('Y-m-d') }}">
                <div class="input-group-append" data-target="#date_picker" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> View
            </button>
        </form>
    </div>
</div>

<!-- Arrivals List -->
<div class="card">
    <div class="card-header bg-success text-white">
        <h3 class="card-title">
            <i class="fas fa-users"></i> 
            {{ $arrivals->count() }} Guest(s) arriving on {{ $date->format('D, M d, Y') }}
        </h3>
    </div>
    <div class="card-body p-0">
        @if($arrivals->count() > 0)
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Guest</th>
                        <th>Room(s)</th>
                        <th>Nights</th>
                        <th>Check-out</th>
                        <th>Payment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($arrivals as $booking)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($booking->user && ($booking->user->profile_image || ($booking->user->guestProfile && $booking->user->guestProfile->photo)))
                                        <img src="{{ asset($booking->user->profile_image ?: $booking->user->guestProfile->photo) }}" class="rounded-circle mr-2" width="40" height="40" style="object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-primary text-white mr-2 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                            {{ strtoupper(substr($booking->user->name ?? 'G', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $booking->user->name ?? 'Guest' }}</strong>
                                        @if($booking->user && $booking->user->guestProfile && $booking->user->guestProfile->vip_status != 'regular')
                                            <span class="badge badge-{{ $booking->user->guestProfile->vip_status == 'platinum' ? 'dark' : ($booking->user->guestProfile->vip_status == 'gold' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($booking->user->guestProfile->vip_status) }}
                                            </span>
                                        @endif
                                        <br>
                                        <small class="text-muted">{{ $booking->user->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @foreach($booking->rooms as $room)
                                    <span class="badge badge-info">{{ $room->room_number }} - {{ $room->roomType->room_type_name ?? '' }}</span>
                                @endforeach
                            </td>
                            <td>{{ $booking->check_in->diffInDays($booking->check_out) }}</td>
                            <td>{{ $booking->check_out->format('M d, Y') }}</td>
                            <td>
                                @if($booking->payment_status == 'paid')
                                    <span class="badge badge-success">Paid</span>
                                @elseif($booking->payment_status == 'partial')
                                    <span class="badge badge-warning">Partial</span>
                                @else
                                    <span class="badge badge-danger">Unpaid</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->status == 'confirmed')
                                    <form action="{{ route('staff.bookings.check-in', $booking->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-sign-in-alt"></i> Check In
                                        </button>
                                    </form>
                                @else
                                    <span class="badge badge-success px-3 py-2">
                                        <i class="fas fa-check"></i> Checked In
                                    </span>
                                @endif
                                <a href="{{ route('staff.bookings.show', $booking->id) }}" class="btn btn-outline-info btn-sm ml-1">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-check fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No arrivals for {{ $date->format('M d, Y') }}</h5>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        $('#date_picker').datetimepicker({
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
    });
</script>
@endpush

@extends('admin.layouts.master')

@section('title', 'Edit Booking')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Booking #{{ $booking->id }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.bookings.index') }}">Bookings</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title">Update Booking Status</h3>
        </div>
        <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Check In</label>
                            <input type="text" class="form-control" value="{{ $booking->check_in->format('Y-m-d') }}" disabled>
                            <small>Dates cannot be changed here. Cancel and re-book if needed.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Check Out</label>
                            <input type="text" class="form-control" value="{{ $booking->check_out->format('Y-m-d') }}" disabled>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Booking Status</label>
                            <select class="form-control" name="status">
                                @foreach(['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'] as $status)
                                    <option value="{{ $status }}" {{ $booking->status == $status ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Payment Status</label>
                            <select class="form-control" name="payment_status">
                                @foreach(['unpaid', 'paid', 'partial'] as $status)
                                    <option value="{{ $status }}" {{ $booking->payment_status == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Special Requests</label>
                    <textarea class="form-control" name="special_requests" rows="3">{{ $booking->special_requests }}</textarea>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Update Booking</button>
                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-default float-right">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

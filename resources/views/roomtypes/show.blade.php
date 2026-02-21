@extends('admin.layouts.master')

@section('title', 'View Room Type')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Room Type Details</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.roomtypes.index') }}">Room Types</a></li>
                    <li class="breadcrumb-item active">View</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ $roomType->room_type_name }}</h3>
        <div class="card-tools">
            <a href="{{ route('admin.roomtypes.edit', $roomType->id) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <dl class="row">
                    <dt class="col-sm-4">ID:</dt>
                    <dd class="col-sm-8">{{ $roomType->id }}</dd>

                    <dt class="col-sm-4">Room Type Name:</dt>
                    <dd class="col-sm-8">{{ $roomType->room_type_name }}</dd>

                    <dt class="col-sm-4">Price Per Night:</dt>
                    <dd class="col-sm-8">
                        <span class="badge badge-success" style="font-size: 1.1em;">
                            ${{ number_format($roomType->price_per_night, 2) }}
                        </span>
                    </dd>

                    <dt class="col-sm-4">Max Occupancy:</dt>
                    <dd class="col-sm-8">
                        <span class="badge badge-info">
                            <i class="fas fa-users"></i> {{ $roomType->max_occupancy }} {{ $roomType->max_occupancy > 1 ? 'Guests' : 'Guest' }}
                        </span>
                    </dd>
                </dl>
            </div>

            <div class="col-md-6">
                <dl class="row">
                    <dt class="col-sm-4">Created At:</dt>
                    <dd class="col-sm-8">{{ $roomType->created_at ? $roomType->created_at->format('M d, Y h:i A') : 'N/A' }}</dd>

                    <dt class="col-sm-4">Updated At:</dt>
                    <dd class="col-sm-8">{{ $roomType->updated_at ? $roomType->updated_at->format('M d, Y h:i A') : 'N/A' }}</dd>
                </dl>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <h5>Description:</h5>
                <p class="text-muted">
                    {{ $roomType->description ?? 'No description available.' }}
                </p>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <a href="{{ route('admin.roomtypes.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        <a href="{{ route('admin.roomtypes.edit', $roomType->id) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Room Type
        </a>
    </div>
</div>
@endsection
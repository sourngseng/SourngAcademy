@extends('admin.layouts.master')

@section('title', 'Create Room Type')

@push('styles')
<!-- SweetAlert2 -->
<link rel="stylesheet" href="{{asset('backend')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
@endpush

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Room Type</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.roomtypes.index') }}">Room Types</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Room Type Information</h3>
    </div>
    <!-- /.card-header -->
    <form action="{{route('admin.roomtypes.store')}}" method="POST" id="roomTypeForm">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="room_type_name">Room Type Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('room_type_name') is-invalid @enderror" 
                               id="room_type_name" 
                               name="room_type_name" 
                               placeholder="Enter room type name"
                               value="{{ old('room_type_name') }}"
                               required>
                        @error('room_type_name')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="max_occupancy">Max Occupancy <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('max_occupancy') is-invalid @enderror" 
                               id="max_occupancy" 
                               name="max_occupancy" 
                               placeholder="Enter maximum occupancy"
                               value="{{ old('max_occupancy', 1) }}"
                               min="1"
                               required>
                        @error('max_occupancy')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="price_per_night">Price Per Night ($) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            </div>
                            <input type="number" 
                                   class="form-control @error('price_per_night') is-invalid @enderror" 
                                   id="price_per_night" 
                                   name="price_per_night" 
                                   placeholder="0.00"
                                   value="{{ old('price_per_night') }}"
                                   step="0.01"
                                   min="0"
                                   required>
                            @error('price_per_night')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <!-- Placeholder for alignment -->
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" 
                          name="description" 
                          rows="4"
                          placeholder="Enter room type description">{{ old('description') }}</textarea>
                @error('description')
                    <span class="error invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Create Room Type
            </button>
            <a href="{{ route('admin.roomtypes.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<!-- SweetAlert2 -->
<script src="{{asset('backend')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

<script>
$(document).ready(function() {
    // Show success message if exists
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000
        });
    @endif

    // Show error message if exists
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            confirmButtonText: 'OK'
        });
    @endif
});
</script>
@endpush
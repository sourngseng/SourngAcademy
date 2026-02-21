@extends('admin.layouts.master')

@section('title', 'Add Permission')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Permission</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.permissions.index') }}">Permissions</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">New Permission</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="{{ route('admin.permissions.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="route_select">Select from Routes (Optional)</label>
                            <select class="form-control select2" id="route_select" style="width: 100%;">
                                <option value="">-- Select a Route to populate --</option>
                                @foreach($routes as $route)
                                    <option value="{{ $route }}">{{ $route }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">You can pick a route name to use as a permission, or type a custom one below.</small>
                        </div>

                        <div class="form-group">
                            <label for="name">Permission Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="e.g. manage-users" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" id="description" name="description" placeholder="Description of the permission" value="{{ old('description') }}">
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Create Permission</button>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-default float-right">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('route_select').addEventListener('change', function() {
        var selectedRoute = this.value;
        if(selectedRoute) {
            document.getElementById('name').value = selectedRoute;
            // Attempt to generate a description
            var desc = 'Access ' + selectedRoute.replace(/\./g, ' ');
            document.getElementById('description').value = desc.charAt(0).toUpperCase() + desc.slice(1);
        }
    });
</script>
@endpush

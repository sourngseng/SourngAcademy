@extends('admin.layouts.master')

@section('title', 'Roles & Permissions')

@push('styles')
<style>
    .role-card {
        border-radius: 15px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }
    .role-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }
    .role-admin { border-left: 5px solid #dc3545; }
    .role-staff { border-left: 5px solid #17a2b8; }
    .role-guest { border-left: 5px solid #28a745; }
    .permission-badge {
        display: inline-block;
        padding: 5px 10px;
        margin: 3px;
        border-radius: 20px;
        font-size: 12px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
    }
    .permission-badge i {
        margin-right: 5px;
    }
    .role-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    .permission-category {
        background: #f8f9fa;
        padding: 10px 15px;
        border-radius: 8px;
        margin-bottom: 15px;
    }
    .permission-category h6 {
        margin-bottom: 10px;
        color: #495057;
        font-weight: 600;
    }
</style>
@endpush

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-user-shield"></i> Roles & Permissions</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Roles & Permissions</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create New Role
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Stats Overview -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="info-box bg-gradient-primary">
                <span class="info-box-icon"><i class="fas fa-users-cog"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Roles</span>
                    <span class="info-box-number">{{ $roles->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box bg-gradient-success">
                <span class="info-box-icon"><i class="fas fa-key"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Permissions</span>
                    <span class="info-box-number">{{ App\Models\Permission::count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box bg-gradient-info">
                <span class="info-box-icon"><i class="fas fa-user-tag"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Users Assigned</span>
                    <span class="info-box-number">{{ App\Models\User::count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Role Cards -->
    <div class="row">
        @foreach($roles as $role)
        <div class="col-md-4">
            <div class="card role-card role-{{ $role->name }}">
                <div class="card-header bg-light">
                    <div class="d-flex align-items-center">
                        <div class="role-icon bg-{{ $role->name == 'admin' ? 'danger' : ($role->name == 'staff' ? 'info' : 'success') }} text-white mr-3">
                            <i class="fas fa-{{ $role->name == 'admin' ? 'user-shield' : ($role->name == 'staff' ? 'user-tie' : 'user') }}"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ ucfirst($role->name) }}</h4>
                            <small class="text-muted">{{ $role->description }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">
                            <i class="fas fa-key"></i> {{ $role->permissions->count() }} Permissions
                        </span>
                        <span class="badge badge-{{ $role->name == 'admin' ? 'danger' : ($role->name == 'staff' ? 'info' : 'success') }}">
                            {{ App\Models\User::where('role', $role->name)->count() }} Users
                        </span>
                    </div>
                    
                    <div class="permissions-preview" style="max-height: 150px; overflow-y: auto;">
                        @foreach($role->permissions->take(8) as $permission)
                            <span class="permission-badge">
                                <i class="fas fa-check text-success"></i>
                                {{ str_replace('-', ' ', ucfirst($permission->name)) }}
                            </span>
                        @endforeach
                        @if($role->permissions->count() > 8)
                            <span class="permission-badge bg-secondary text-white">
                                +{{ $role->permissions->count() - 8 }} more
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-primary btn-block">
                        <i class="fas fa-edit"></i> Manage Permissions
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- All Permissions Reference -->
    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list"></i> All Available Permissions</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            @php
                $permissionCategories = [
                    'Core System' => ['manage_campuses', 'manage_users', 'view_reports'],
                    'Academic' => ['manage_classes', 'take_attendance'],
                    'Finance' => ['manage_feestructures'],
                    'Student/General' => ['view_own_records'],
                ];
            @endphp
            
            <div class="row">
                @foreach($permissionCategories as $category => $permNames)
                <div class="col-md-6">
                    <div class="permission-category">
                        <h6><i class="fas fa-folder-open"></i> {{ $category }}</h6>
                        @foreach(App\Models\Permission::whereIn('name', $permNames)->get() as $perm)
                            <span class="permission-badge">
                                <i class="fas fa-key text-warning"></i>
                                {{ str_replace('-', ' ', ucfirst($perm->name)) }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Role Comparison Table -->
    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-table"></i> Role Comparison Matrix</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>Permission</th>
                        @foreach($roles as $role)
                            <th class="text-center">
                                <span class="badge badge-{{ $role->name == 'admin' ? 'danger' : ($role->name == 'staff' ? 'info' : 'success') }} px-3 py-2">
                                    {{ ucfirst($role->name) }}
                                </span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach(App\Models\Permission::orderBy('name')->get() as $permission)
                    <tr>
                        <td>
                            <strong>{{ str_replace('-', ' ', ucfirst($permission->name)) }}</strong>
                            <br><small class="text-muted">{{ $permission->description }}</small>
                        </td>
                        @foreach($roles as $role)
                            <td class="text-center">
                                @if($role->permissions->contains($permission->id))
                                    <i class="fas fa-check-circle text-success fa-lg"></i>
                                @else
                                    <i class="fas fa-times-circle text-danger fa-lg"></i>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

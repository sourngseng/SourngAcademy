@extends('admin.layouts.master')

@section('title', 'Edit Role Permissions')

@push('styles')
<style>
    .permission-card {
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    .permission-card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    .permission-category {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .permission-category h5 {
        color: #495057;
        border-bottom: 2px solid #dee2e6;
        padding-bottom: 10px;
        margin-bottom: 15px;
    }
    .permission-item {
        background: white;
        border-radius: 8px;
        padding: 12px 15px;
        margin-bottom: 10px;
        border: 1px solid #e9ecef;
        transition: all 0.2s ease;
    }
    .permission-item:hover {
        border-color: #007bff;
        background: #f8f9ff;
    }
    .permission-item.selected {
        border-color: #28a745;
        background: #f0fff4;
    }
    .role-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
    }
    .role-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
    }
    .select-all-btn {
        cursor: pointer;
    }
</style>
@endpush

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-user-cog"></i> Edit Role Permissions</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
                    <li class="breadcrumb-item active">Edit {{ ucfirst($role->name) }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="container-fluid">
    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- Role Header -->
        <div class="role-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="role-icon">
                        <i class="fas fa-{{ $role->name == 'admin' ? 'user-shield' : ($role->name == 'staff' ? 'user-tie' : 'user') }}"></i>
                    </div>
                </div>
                <div class="col">
                    <h2 class="mb-1">{{ ucfirst($role->name) }} Role</h2>
                    <p class="mb-0 opacity-75">{{ $role->description }}</p>
                </div>
                <div class="col-auto">
                    <div class="text-right">
                        <span class="d-block" style="font-size: 36px; font-weight: bold;" id="selectedCount">{{ $role->permissions->count() }}</span>
                        <small>Permissions Selected</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <button type="button" class="btn btn-outline-primary mr-2" id="selectAll">
                            <i class="fas fa-check-double"></i> Select All
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="deselectAll">
                            <i class="fas fa-times"></i> Deselect All
                        </button>
                    </div>
                    <div>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-light mr-2">
                            <i class="fas fa-arrow-left"></i> Back to Roles
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permission Categories -->
        @php
            $permissionCategories = [
                'Dashboard & Reports' => ['view-dashboard', 'view-reports', 'export-reports'],
                'Booking Management' => ['view-bookings', 'create-bookings', 'edit-bookings', 'delete-bookings', 'manage-checkin', 'manage-checkout'],
                'Room Management' => ['view-rooms', 'create-rooms', 'edit-rooms', 'delete-rooms', 'view-room-types', 'manage-room-types'],
                'Guest Management' => ['view-guests', 'create-guests', 'edit-guests', 'delete-guests'],
                'User & Access Control' => ['view-users', 'create-users', 'edit-users', 'delete-users', 'manage-users', 'view-roles', 'manage-roles'],
                'Reviews & Feedback' => ['view-reviews', 'manage-reviews', 'submit-reviews'],
                'Settings & Configuration' => ['view-settings', 'manage-settings', 'manage-amenities', 'manage-countries'],
                'Guest Self-Service' => ['view-own-bookings', 'manage-own-profile'],
            ];
            
            $categoryIcons = [
                'Dashboard & Reports' => 'fas fa-chart-bar',
                'Booking Management' => 'fas fa-calendar-check',
                'Room Management' => 'fas fa-bed',
                'Guest Management' => 'fas fa-users',
                'User & Access Control' => 'fas fa-user-lock',
                'Reviews & Feedback' => 'fas fa-star',
                'Settings & Configuration' => 'fas fa-cogs',
                'Guest Self-Service' => 'fas fa-user-circle',
            ];
        @endphp

        <div class="row">
            @foreach($permissionCategories as $category => $permNames)
                <div class="col-md-6">
                    <div class="permission-category">
                        <h5>
                            <i class="{{ $categoryIcons[$category] ?? 'fas fa-folder' }}"></i> 
                            {{ $category }}
                            <span class="float-right">
                                <button type="button" class="btn btn-sm btn-outline-primary select-category" data-category="{{ Str::slug($category) }}">
                                    <i class="fas fa-check"></i> All
                                </button>
                            </span>
                        </h5>
                        
                        @foreach($permissions->whereIn('name', $permNames) as $permission)
                            <div class="permission-item {{ $role->permissions->contains($permission->id) ? 'selected' : '' }}" data-category="{{ Str::slug($category) }}">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input permission-checkbox" 
                                           type="checkbox" 
                                           id="perm_{{ $permission->id }}" 
                                           name="permissions[]" 
                                           value="{{ $permission->id }}"
                                           {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                                    <label for="perm_{{ $permission->id }}" class="custom-control-label d-block">
                                        <strong>{{ str_replace('-', ' ', ucfirst($permission->name)) }}</strong>
                                        <small class="text-muted d-block">{{ $permission->description }}</small>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Submit Button (Bottom) -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save"></i> Save Permissions
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    // Update count
    function updateCount() {
        var count = $('.permission-checkbox:checked').length;
        $('#selectedCount').text(count);
    }
    
    // Toggle selected class on permission items
    $('.permission-checkbox').on('change', function() {
        $(this).closest('.permission-item').toggleClass('selected', this.checked);
        updateCount();
    });
    
    // Select All
    $('#selectAll').on('click', function() {
        $('.permission-checkbox').prop('checked', true);
        $('.permission-item').addClass('selected');
        updateCount();
    });
    
    // Deselect All
    $('#deselectAll').on('click', function() {
        $('.permission-checkbox').prop('checked', false);
        $('.permission-item').removeClass('selected');
        updateCount();
    });
    
    // Select Category
    $('.select-category').on('click', function() {
        var category = $(this).data('category');
        var $items = $('.permission-item[data-category="' + category + '"]');
        var $checkboxes = $items.find('.permission-checkbox');
        
        // Check if all are already checked
        var allChecked = $checkboxes.length === $checkboxes.filter(':checked').length;
        
        $checkboxes.prop('checked', !allChecked);
        $items.toggleClass('selected', !allChecked);
        updateCount();
    });
});
</script>
@endpush

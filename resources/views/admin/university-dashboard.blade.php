@extends('admin.layouts.master')

@section('title', __('Dashboard'))

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('University Dashboard') }}</h1>
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
        <!-- Statistics Cards Row 1 - Users -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $stats['total_students'] }}</h3>
                        <p>{{ __('Total Students') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        {{ __('More info') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['total_teachers'] }}</h3>
                        <p>{{ __('Total Teachers') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        {{ __('More info') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['total_staff'] }}</h3>
                        <p>{{ __('Total Staff') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        {{ __('More info') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $stats['total_users'] }}</h3>
                        <p>{{ __('Total Users') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="small-box-footer">
                        {{ __('More info') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards Row 2 - Content -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-primary"><i class="fas fa-blog"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ __('Blog Posts') }}</span>
                        <span class="info-box-number">{{ $stats['total_posts'] }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ __('Published') }}</span>
                        <span class="info-box-number">{{ $stats['published_posts'] }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-file-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ __('Pages') }}</span>
                        <span class="info-box-number">{{ $stats['total_pages'] }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fas fa-concierge-bell"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ __('Services') }}</span>
                        <span class="info-box-number">{{ $stats['total_services'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Row -->
        <div class="row">
            <!-- Left Column - Recent Blog Posts -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-newspaper mr-1"></i>
                            {{ __('Recent Blog Posts') }}
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Author') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentPosts as $post)
                                        <tr>
                                            <td>{{ Str::limit($post->title, 40) }}</td>
                                            <td>{{ $post->author->name ?? 'N/A' }}</td>
                                            <td>
                                                @if($post->is_published)
                                                    <span class="badge badge-success">{{ __('Published') }}</span>
                                                @else
                                                    <span class="badge badge-warning">{{ __('Draft') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $post->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">{{ __('No blog posts found') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Recent Users -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-users mr-1"></i>
                            {{ __('Recent Users') }}
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Role') }}</th>
                                        <th>{{ __('Joined') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentUsers as $userItem)
                                        <tr>
                                            <td>{{ $userItem->name }}</td>
                                            <td>{{ $userItem->email }}</td>
                                            <td>
                                                @if($userItem->role == 'student')
                                                    <span class="badge badge-info">{{ __('Student') }}</span>
                                                @elseif($userItem->role == 'teacher')
                                                    <span class="badge badge-success">{{ __('Teacher') }}</span>
                                                @elseif($userItem->role == 'staff')
                                                    <span class="badge badge-warning">{{ __('Staff') }}</span>
                                                @elseif($userItem->role == 'super_admin')
                                                    <span class="badge badge-danger">{{ __('Super Admin') }}</span>
                                                @elseif($userItem->role == 'campus_admin')
                                                    <span class="badge badge-primary">{{ __('Campus Admin') }}</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ ucfirst(str_replace('_', ' ', $userItem->role)) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $userItem->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">{{ __('No users found') }}</td>
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
                                {{ __('Content Activity Trend') }}
                            </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="activityChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-bolt mr-1"></i>
                            {{ __('Quick Actions') }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-block mb-2">
                                <i class="fas fa-user-plus"></i> {{ __('Add New User') }}
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-success btn-block mb-2">
                                <i class="fas fa-users"></i> {{ __('Manage Users') }}
                            </a>
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-info btn-block mb-2">
                                <i class="fas fa-user-shield"></i> {{ __('Manage Roles') }}
                            </a>
                            <a href="{{ route('admin.permissions.index') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-key"></i> {{ __('Manage Permissions') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Activity Chart
    const activityCtx = document.getElementById('activityChart').getContext('2d');
    const activityData = @json($monthlyRevenue);
    
    new Chart(activityCtx, {
        type: 'line',
        data: {
            labels: activityData.map(item => item.month),
            datasets: [{
                label: '{{ __("Activity Score") }}',
                data: activityData.map(item => item.revenue),
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
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush

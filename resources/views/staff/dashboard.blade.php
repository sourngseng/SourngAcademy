@extends('staff.layouts.master')

@section('title', __('Staff Dashboard'))

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('Staff Dashboard') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Dashboard') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Statistics Cards -->
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
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['total_services'] }}</h3>
                        <p>{{ __('Services') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-concierge-bell"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $stats['my_assigned_tasks'] }}</h3>
                        <p>{{ __('My Tasks') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Row -->
        <div class="row">
            <!-- Recent News/Announcements -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-newspaper mr-1"></i>
                            {{ __('Recent Announcements') }}
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Author') }}</th>
                                        <th>{{ __('Date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentPosts as $post)
                                        <tr>
                                            <td>{{ Str::limit($post->title, 40) }}</td>
                                            <td>{{ $post->author->name ?? 'N/A' }}</td>
                                            <td>{{ $post->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">{{ __('No announcements found') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Users -->
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
                                        <th>{{ __('Role') }}</th>
                                        <th>{{ __('Joined') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentUsers as $userItem)
                                        <tr>
                                            <td>{{ $userItem->name }}</td>
                                            <td>
                                                <span class="badge badge-{{ $userItem->role == 'student' ? 'info' : ($userItem->role == 'teacher' ? 'success' : 'secondary') }}">
                                                    {{ __(ucfirst(str_replace('_', ' ', $userItem->role))) }}
                                                </span>
                                            </td>
                                            <td>{{ $userItem->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">{{ __('No users found') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
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
                                <a href="{{ route('profile.edit') }}" class="btn btn-app bg-primary">
                                    <i class="fas fa-user"></i> {{ __('My Profile') }}
                                </a>
                            </div>
                            <div class="col-md-3 col-6">
                                <a href="#" class="btn btn-app bg-success">
                                    <i class="fas fa-tasks"></i> {{ __('My Tasks') }}
                                </a>
                            </div>
                            <div class="col-md-3 col-6">
                                <a href="#" class="btn btn-app bg-info">
                                    <i class="fas fa-calendar"></i> {{ __('Schedule') }}
                                </a>
                            </div>
                            <div class="col-md-3 col-6">
                                <a href="#" class="btn btn-app bg-warning">
                                    <i class="fas fa-envelope"></i> {{ __('Messages') }}
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

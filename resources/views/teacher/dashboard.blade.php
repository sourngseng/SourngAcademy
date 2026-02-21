@extends('teacher.layouts.master')

@section('title', __('Teacher Dashboard'))

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('Teacher Dashboard') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">{{ __('Home') }}</a></li>
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
                        <h3>{{ $stats['total_students'] ?? 0 }}</h3>
                        <p>{{ __('My Students') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['total_classes'] ?? 0 }}</h3>
                        <p>{{ __('My Classes') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chalkboard"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['pending_assignments'] ?? 0 }}</h3>
                        <p>{{ __('Pending Assignments') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-signature"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $stats['upcoming_exams'] ?? 0 }}</h3>
                        <p>{{ __('Upcoming Exams') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Courses & Session Plans -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-0 d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-book mr-1"></i>
                            {{ __('My Courses & Session Plans') }}
                        </h3>
                        <div class="card-tools ml-auto">
                            <a href="{{ route('teacher.session-plans.index') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-layer-group mr-1"></i> {{ __('Manage All Plans') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-valign-middle">
                                <thead>
                                    <tr>
                                        <th>{{ __('Class Name') }}</th>
                                        <th>{{ __('Subject') }}</th>
                                        <th class="text-right">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($classes as $item)
                                        <tr>
                                            <td>
                                                <strong>{{ $item->class->name ?? 'N/A' }}</strong>
                                                <small class="d-block text-muted">{{ $item->class->campus->name ?? '' }}</small>
                                            </td>
                                            <td>
                                                <span class="badge badge-info mr-1">{{ $item->subject->code }}</span>
                                                {{ $item->subject->getTranslatedName() }}
                                            </td>
                                            <td class="text-right">
                                                <div class="btn-group">
                                                    <a href="{{ route('teacher.myclasses.show', $item->class_id) }}" class="btn btn-sm btn-info mx-1" title="{{ __('Manage Students') }}">
                                                        <i class="fas fa-users mr-1"></i> {{ __('Students') }}
                                                    </a>
                                                    <a href="{{ route('teacher.session-plans.index', ['subject_id' => $item->subject_id]) }}" class="btn btn-sm btn-default" title="{{ __('Manage Roadmap') }}">
                                                        <i class="fas fa-tasks mr-1"></i> {{ __('Structure') }}
                                                    </a>
                                                    @if($item->subject->sessionPlans->whereNull('parent_id')->count() > 0)
                                                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle mx-1" data-toggle="dropdown">
                                                            <i class="fas fa-eye mr-1"></i> {{ __('Read') }}
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right shadow border-0">
                                                            @foreach($item->subject->sessionPlans->whereNull('parent_id') as $plan)
                                                                <a class="dropdown-item py-2" href="{{ route('teacher.session-plans.show', $plan->id) }}">
                                                                    <i class="fas fa-file-alt mr-2 text-muted"></i> {{ $plan->title }}
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    <a href="{{ route('teacher.session-plans.create', ['subject_id' => $item->subject_id, 'campus_id' => $item->class->campus_id, 'program_id' => $item->subject->program_id]) }}" class="btn btn-sm btn-success" title="{{ __('Add Session') }}">
                                                        <i class="fas fa-plus"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4 text-muted">
                                                {{ __('You are not assigned to any classes yet.') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            {{ __('My Class Schedule') }}
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-valign-middle">
                                <thead>
                                    <tr>
                                        <th>{{ __('Time') }}</th>
                                        <th>{{ __('Subject') }}</th>
                                        <th>{{ __('Room') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            {{ __('No classes scheduled for today.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bullhorn mr-1"></i>
                            {{ __('Announcements') }}
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                            @forelse($recentPosts ?? [] as $post)
                                <li class="item">
                                    <div class="product-info ml-2">
                                        <a href="javascript:void(0)" class="product-title">{{ $post->title }}
                                            <span class="badge badge-info float-right">{{ $post->created_at->format('M d') }}</span>
                                        </a>
                                        <span class="product-description">
                                            {{ Str::limit(strip_tags($post->content), 100) }}
                                        </span>
                                    </div>
                                </li>
                            @empty
                                <li class="item text-center py-3 text-muted">
                                    {{ __('No new announcements.') }}
                                </li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="card-footer text-center">
                        <a href="javascript:void(0)" class="uppercase text-sm">{{ __('View All Announcements') }}</a>
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
                        <div class="row text-center">
                            <div class="col-md-2 col-4 mb-3">
                                <a href="#" class="text-primary d-block">
                                    <i class="fas fa-plus-circle fa-2x mb-1"></i>
                                    <p class="mb-0">{{ __('Attendance') }}</p>
                                </a>
                            </div>
                            <div class="col-md-2 col-4 mb-3">
                                <a href="#" class="text-success d-block">
                                    <i class="fas fa-upload fa-2x mb-1"></i>
                                    <p class="mb-0">{{ __('Post Grade') }}</p>
                                </a>
                            </div>
                            <div class="col-md-2 col-4 mb-3">
                                <a href="#" class="text-info d-block">
                                    <i class="fas fa-file-upload fa-2x mb-1"></i>
                                    <p class="mb-0">{{ __('Assignment') }}</p>
                                </a>
                            </div>
                            <div class="col-md-2 col-4 mb-3">
                                <a href="#" class="text-warning d-block">
                                    <i class="fas fa-envelope fa-2x mb-1"></i>
                                    <p class="mb-0">{{ __('Messages') }}</p>
                                </a>
                            </div>
                            <div class="col-md-2 col-4 mb-3">
                                <a href="#" class="text-danger d-block">
                                    <i class="fas fa-user-edit fa-2x mb-1"></i>
                                    <p class="mb-0">{{ __('My Profile') }}</p>
                                </a>
                            </div>
                            <div class="col-md-2 col-4 mb-3">
                                <a href="#" class="text-secondary d-block">
                                    <i class="fas fa-book-open fa-2x mb-1"></i>
                                    <p class="mb-0">{{ __('Library') }}</p>
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

@extends('student.layouts.master')

@section('title', __('Student Dashboard'))

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('Student Dashboard') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">{{ __('Home') }}</a></li>
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
                        <h3>{{ $stats['enrollments'] ?? 0 }}</h3>
                        <p>{{ __('My Courses') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-book"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['attendance_rate'] ?? '0%' }}</h3>
                        <p>{{ __('Attendance') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['gpa'] ?? '0.00' }}</h3>
                        <p>{{ __('GPA') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $stats['outstanding_fees'] ?? '$0.00' }}</h3>
                        <p>{{ __('Balance Due') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column: Timetable -->
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-clock mr-1"></i>
                            {{ __('My Timetable') }}
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-valign-middle">
                                <thead>
                                    <tr>
                                        <th>{{ __('Time') }}</th>
                                        <th>{{ __('Course') }}</th>
                                        <th>{{ __('Lecturer') }}</th>
                                        <th>{{ __('Room') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            {{ __('No classes today.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- My Courses & Session Plans -->
                <div class="card mb-3">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-book-reader mr-1"></i>
                            {{ __('My Courses & Session Plans') }}
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-valign-middle">
                                <thead>
                                    <tr>
                                        <th>{{ __('Subject') }}</th>
                                        <th>{{ __('Session Plans') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($classes as $class)
                                        <tr>
                                            <td>
                                                <span class="badge badge-info mr-1">{{ $class->subject->code }}</span>
                                                {{ $class->subject->getTranslatedName() }}
                                                <small class="d-block text-muted">{{ $class->name }}</small>
                                            </td>
                                            <td>
                                                @if($class->subject->sessionPlans->count() > 0)
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-toggle="dropdown">
                                                            <i class="fas fa-book-open mr-1"></i> {{ __('Read') }}
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right shadow border-0">
                                                            @foreach($class->subject->sessionPlans as $plan)
                                                                <a class="dropdown-item py-2" href="{{ route('student.session-plans.show', $plan->id) }}">
                                                                    <i class="fas fa-file-alt mr-2 text-muted text-sm"></i> {{ $plan->title }}
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted small italic">{{ __('No plans') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center py-4 text-muted">
                                                {{ __('You are not enrolled in any classes yet.') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Recent Grades -->
                <div class="card mt-3">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-award mr-1"></i>
                            {{ __('Recent Grades') }}
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>{{ __('Course') }}</th>
                                        <th>{{ __('Assignment') }}</th>
                                        <th>{{ __('Grade') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">
                                            {{ __('No grades posted yet.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Activities & News -->
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bullhorn mr-1"></i>
                            {{ __('Campus News') }}
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
                                            {{ Str::limit(strip_tags($post->content), 80) }}
                                        </span>
                                    </div>
                                </li>
                            @empty
                                <li class="item text-center py-3 text-muted">
                                    {{ __('No new news.') }}
                                </li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="card-footer text-center">
                        <a href="javascript:void(0)" class="uppercase text-sm">{{ __('View All News') }}</a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Quick Links') }}</h3>
                    </div>
                    <div class="card-body">
                        <a href="#" class="btn btn-block btn-outline-primary">{{ __('Course Registration') }}</a>
                        <a href="#" class="btn btn-block btn-outline-info">{{ __('Exam Results') }}</a>
                        <a href="#" class="btn btn-block btn-outline-success">{{ __('Financial Statement') }}</a>
                        <a href="#" class="btn btn-block btn-outline-warning">{{ __('Learning Materials') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

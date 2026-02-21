@extends('teacher.layouts.master')

@section('title', __('My Classes'))

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('My Classes') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('My Classes') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            @forelse($classSubjects as $classId => $subjects)
                @php $class = $subjects->first()->class; @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="card card-outline card-primary shadow-sm hover-shadow transition-all">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-university mr-2 text-primary"></i>
                                {{ $class->name }}
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-primary">{{ $class->campus->name ?? '' }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <p class="text-muted mb-2"><i class="fas fa-graduation-cap mr-2"></i>{{ $class->program->name ?? 'N/A' }}</p>
                                <p class="text-muted mb-0"><i class="fas fa-users mr-2"></i>{{ $class->students_count ?? $class->students()->count() }} {{ __('Students') }}</p>
                            </div>
                            
                            <h6 class="font-weight-bold text-sm text-uppercase text-muted border-bottom pb-2 mb-2">{{ __('Assigned Subjects') }}</h6>
                            <ul class="list-unstyled mb-0">
                                @foreach($subjects as $item)
                                    <li class="d-flex justify-content-between align-items-center py-1">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded p-2 mr-2">
                                                <i class="fas fa-book text-info"></i>
                                            </div>
                                            <div>
                                                <span class="d-block font-weight-bold text-sm">{{ $item->subject->name }}</span>
                                                <small class="text-muted">{{ $item->subject->code }}</small>
                                            </div>
                                        </div>
                                        @if($item->classroom)
                                            <span class="badge badge-light border">{{ $item->classroom->name }}</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="card-footer bg-white border-top-0 pt-0">
                            <div class="row">
                                <div class="col-6">
                                    <a href="{{ route('teacher.myclasses.show', $classId) }}" class="btn btn-primary btn-block btn-sm">
                                        <i class="fas fa-users-cog mr-1"></i> {{ __('Manage Students') }}
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('teacher.myclasses.timetable', $classId) }}" target="_blank" class="btn btn-outline-secondary btn-block btn-sm">
                                        <i class="fas fa-calendar-alt mr-1"></i> {{ __('Schedule') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card p-5 text-center shadow-sm">
                        <div class="mb-3">
                            <i class="fas fa-chalkboard-teacher fa-4x text-light"></i>
                        </div>
                        <h4 class="text-muted">{{ __('No classes assigned yet.') }}</h4>
                        <p class="text-muted mb-0">{{ __('When you are assigned to a class, it will appear here.') }}</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<style>
    .transition-all {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important;
    }
</style>
@endsection

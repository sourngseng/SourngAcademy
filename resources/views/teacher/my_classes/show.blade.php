@extends('teacher.layouts.master')

@section('title', __('Class Details') . ' - ' . $class->name)

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ $class->name }}</h1>
                <p class="text-muted mb-0">{{ $class->program->name ?? '' }} | {{ $class->campus->name ?? '' }}</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher.myclasses.index') }}">{{ __('My Classes') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Details') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Class Info Card -->
            <div class="col-md-3">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center mb-3">
                            <div class="bg-primary d-inline-block rounded-circle p-4 mb-2 shadow-sm">
                                <i class="fas fa-university fa-3x"></i>
                            </div>
                        </div>
                        <h3 class="profile-username text-center font-weight-bold">{{ $class->name }}</h3>
                        <p class="text-muted text-center">{{ $class->code }}</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item d-flex justify-content-between">
                                <b>{{ __('Students') }}</b> <span class="badge badge-info">{{ $class->students->count() }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <b>{{ __('Max Capacity') }}</b> <span class="badge badge-secondary">{{ $class->max_students }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <b>{{ __('Campus') }}</b> <span>{{ $class->campus->name ?? 'N/A' }}</span>
                            </li>
                        </ul>

                        <a href="{{ route('teacher.myclasses.timetable', $class->id) }}" target="_blank" class="btn btn-info btn-block mb-2">
                            <i class="fas fa-calendar-alt mr-1"></i> {{ __('View Schedule') }}
                        </a>
                        <a href="{{ route('teacher.myclasses.index') }}" class="btn btn-outline-primary btn-block">
                            <i class="fas fa-arrow-left mr-1"></i> {{ __('Back to My Classes') }}
                        </a>
                    </div>
                </div>

                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('My Subjects') }}</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($class->classSubjects->where('teacher_id', Auth::id()) as $item)
                                <div class="list-group-item py-3">
                                    <h6 class="font-weight-bold mb-1">{{ $item->subject->name }}</h6>
                                    <small class="text-muted d-block mb-2">{{ $item->subject->code }}</small>
                                    @if($item->classroom)
                                        <span class="badge badge-light border"><i class="fas fa-door-open mr-1"></i>{{ $item->classroom->name }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students List -->
            <div class="col-md-9">
                <div class="card card-outline card-info">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bold">
                            <i class="fas fa-user-graduate mr-2 text-info"></i>
                            {{ __('Student List') }}
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle mb-0">
                                <thead class="bg-light text-muted text-uppercase text-xs font-weight-bold">
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>{{ __('Student Name') }}</th>
                                        <th>{{ __('Contact') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th class="text-center">{{ __('Grade') }}</th>
                                        <th class="text-right">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($class->students as $index => $student)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($student->avatar)
                                                        <img src="{{ asset($student->avatar) }}" class="img-circle mr-2 border shadow-sm" style="width: 35px; height: 35px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-secondary rounded-circle mr-2 d-flex align-items-center justify-content-center text-xs" style="width: 35px; height: 35px;">
                                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <span class="d-block font-weight-bold">{{ $student->name }}</span>
                                                        <small class="text-muted">{{ $student->student_id ?? 'STD-' . $student->id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <small class="d-block"><i class="fas fa-envelope mr-1 text-muted"></i>{{ $student->email }}</small>
                                                <small class="d-block"><i class="fas fa-phone mr-1 text-muted"></i>{{ $student->phone ?? 'N/A' }}</small>
                                            </td>
                                            <td>
                                                @php $status = $student->pivot->status ?? 'active'; @endphp
                                                <span class="badge badge-{{ $status === 'active' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($status) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-pill badge-primary px-3 py-2">
                                                    {{ $student->pivot->grade_letter ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="text-right">
                                                <div class="btn-group">
                                                    <a href="#" class="btn btn-sm btn-default" title="{{ __('View Profile') }}">
                                                        <i class="fas fa-eye text-primary"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-sm btn-default" title="{{ __('Attendance') }}">
                                                        <i class="fas fa-calendar-check text-info"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-sm btn-default" title="{{ __('Grades') }}">
                                                        <i class="fas fa-graduation-cap text-success"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <i class="fas fa-users-slash fa-2x mb-3 d-block"></i>
                                                {{ __('No students enrolled in this class yet.') }}
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
    </div>
</section>
@endsection

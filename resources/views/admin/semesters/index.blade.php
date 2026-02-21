@extends('admin.layouts.master')

@section('title', 'Manage Semesters')

@push('styles')
<style>
    .semester-timeline {
        border-left: 3px solid #e9ecef;
        padding-left: 20px;
        margin-left: 10px;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 30px;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -29px;
        top: 2px;
        width: 15px;
        height: 15px;
        background: white;
        border: 3px solid #007bff;
        border-radius: 50%;
        z-index: 1;
    }
    .timeline-item.active::before {
        background: #007bff;
        box-shadow: 0 0 0 3px rgba(0,123,255,0.2);
    }
    .semester-card {
        transition: all 0.3s ease;
        border-radius: 12px;
    }
    .semester-card:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    .status-pulse {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #28a745;
        display: inline-block;
        margin-right: 5px;
        animation: pulse-green 2s infinite;
    }
    @keyframes pulse-green {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 5px rgba(40, 167, 69, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
    }
</style>
@endpush

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-clock mr-2"></i> Semesters Management</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.semesters.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus-circle mr-1"></i> Add Semester
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Enhanced Filter Bar -->
    <div class="card shadow-sm mb-4 border-0 rounded-lg">
        <div class="card-body bg-light">
            <form action="{{ route('admin.semesters.index') }}" method="GET" class="row align-items-end">
                <div class="col-md-5">
                    <div class="form-group mb-0">
                        <label class="font-weight-bold small text-muted uppercase">Filter by Academic Year</label>
                        <select name="academic_year_id" class="form-control select2 shadow-sm" onchange="this.form.submit()">
                            @foreach($years as $year)
                                <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }} ({{ $year->start_date->format('Y') }} - {{ $year->end_date->format('Y') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-7 text-right">
                     <span class="text-muted small italic">Tip: Click on a year to see its academic timeline.</span>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="semester-timeline">
                @forelse($semesters as $semester)
                <div class="timeline-item {{ $semester->is_active ? 'active' : '' }}">
                    <div class="card semester-card border-0 shadow-sm mb-0">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="font-weight-bold text-dark mb-1">{{ $semester->name }}</h5>
                                    <div class="text-muted small mb-3">
                                        <i class="far fa-calendar-alt mr-1"></i> 
                                        {{ $semester->start_date->format('M d, Y') }} — {{ $semester->end_date->format('M d, Y') }}
                                    </div>
                                    
                                    <div class="d-flex gap-3">
                                        <div class="badge badge-light border px-3 py-2 rounded-pill">
                                            <i class="fas fa-layer-group mr-1 text-primary"></i>
                                            {{ $semester->classes_count ?? 0 }} Classes
                                        </div>
                                        <div class="badge badge-light border px-3 py-2 rounded-pill">
                                            <i class="fas fa-book mr-1 text-info"></i>
                                            Program Curriculum Ready
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($semester->is_active)
                                        <span class="badge badge-success px-3 py-2 rounded-pill mb-2">
                                            <span class="status-pulse"></span> CURRENT ACTIVE
                                        </span>
                                    @else
                                        <span class="badge badge-light border text-muted px-3 py-2 rounded-pill mb-2">Scheduled</span>
                                    @endif
                                    
                                    <div class="mt-2 text-right">
                                        <a href="{{ route('admin.semesters.edit', $semester->id) }}" class="btn btn-sm btn-link text-info pr-0">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.semesters.destroy', $semester->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this semester?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-link text-danger">
                                                <i class="fas fa-trash-alt mr-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="alert alert-light border shadow-sm rounded-lg">
                    <i class="fas fa-info-circle mr-2"></i> No semesters found for this academic year. 
                    <a href="{{ route('admin.semesters.create') }}" class="font-weight-bold ml-1">Create one now.</a>
                </div>
                @endforelse
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-outline card-info shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold small uppercase">Academic Year Overview</h3>
                </div>
                @php $activeYear = $years->where('id', $selectedYearId)->first(); @endphp
                @if($activeYear)
                <div class="card-body">
                    <p class="text-muted small">Viewing timeline for <strong>{{ $activeYear->name }}</strong>.</p>
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex align-items-center">
                            <div class="bg-primary-soft rounded p-2 mr-3 text-primary text-center" style="width: 40px; background: rgba(0,123,255,0.1)">
                                <i class="fas fa-flag"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Year Starts</small>
                                <span class="font-weight-bold">{{ $activeYear->start_date->format('M d, Y') }}</span>
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-center">
                            <div class="bg-primary-soft rounded p-2 mr-3 text-danger text-center" style="width: 40px; background: rgba(220,53,69,0.1)">
                                <i class="fas fa-stop-circle"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Year Ends</small>
                                <span class="font-weight-bold">{{ $activeYear->end_date->format('M d, Y') }}</span>
                            </div>
                        </li>
                    </ul>
                    <hr>
                    <div class="text-center">
                        <a href="{{ route('admin.academic-years.index') }}" class="btn btn-sm btn-outline-secondary btn-block">
                            View All Academic Years
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({ theme: 'bootstrap4' });
    });
</script>
@endpush

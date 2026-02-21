@extends('admin.layouts.master')

@section('title', 'Manage Academic Years')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-calendar-alt mr-2"></i> Academic Years</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.academic-years.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus-circle mr-1"></i> Add New Year
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        @foreach($years as $year)
        <div class="col-md-6 col-lg-4">
            <div class="card card-outline card-primary shadow-sm h-100 mb-4">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold">{{ $year->name }}</h3>
                    <div class="card-tools">
                        <span class="badge {{ $year->status ? 'badge-success' : 'badge-secondary' }}">
                            {{ $year->status ? 'Active' : 'Archived' }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Campus</small>
                        <span class="font-weight-500">{{ $year->campus->name_en ?? 'All Campuses' }}</span>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted d-block">Start Date</small>
                            <span class="font-weight-500">{{ $year->start_date->format('M d, Y') }}</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">End Date</small>
                            <span class="font-weight-500">{{ $year->end_date->format('M d, Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="bg-light p-3 rounded-lg border mb-3">
                        <h6 class="font-weight-bold mb-2 small text-primary uppercase">Semesters (Required: 2)</h6>
                        <div class="list-group list-group-flush bg-transparent">
                            @foreach($year->semesters as $semester)
                            <div class="list-group-item bg-transparent px-0 py-1 d-flex justify-content-between align-items-center">
                                <span class="small">{{ $semester->name }}</span>
                                <span class="badge badge-outline-secondary small border">{{ $semester->start_date->format('M Y') }} - {{ $semester->end_date->format('M Y') }}</span>
                            </div>
                            @endforeach
                            @if($year->semesters->count() < 2)
                                <div class="text-danger small mt-1 italic"><i class="fas fa-exclamation-triangle mr-1"></i> Missing required semesters</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white text-right">
                    <a href="{{ route('admin.academic-years.edit', $year->id) }}" class="btn btn-sm btn-outline-info">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                    <form action="{{ route('admin.academic-years.destroy', $year->id) }}" method="POST" class="d-inline ml-1" onsubmit="return confirm('Delete this year and all semesters?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

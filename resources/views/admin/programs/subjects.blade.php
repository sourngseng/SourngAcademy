@extends('admin.layouts.master')

@section('title', __('Program Subjects Mapping'))

@push('styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('backend/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
    .curriculum-container {
        display: flex;
        overflow-x: auto;
        padding-bottom: 20px;
        gap: 20px;
    }
    .level-column {
        min-width: 350px;
        background: #f4f6f9;
        border-radius: 12px;
        padding: 15px;
        border: 1px solid #dee2e6;
    }
    .semester-block {
        background: white;
        border-radius: 8px;
        margin-bottom: 15px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-left: 4px solid #007bff;
    }
    .semester-header {
        padding: 10px 15px;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fafafa;
    }
    .subject-list {
        padding: 10px;
    }
    .subject-item {
        background: #fff;
        border: 1px solid #e9ecef;
        padding: 8px 12px;
        border-radius: 6px;
        margin-bottom: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s;
    }
    .subject-item:hover {
        border-color: #007bff;
        background: #f8f9ff;
    }
    .subject-info {
        flex: 1;
    }
    .subject-name {
        font-weight: 600;
        font-size: 0.9rem;
        display: block;
    }
    .subject-code {
        font-size: 0.75rem;
        color: #6c757d;
    }
    .btn-remove-subject {
        color: #dc3545;
        padding: 2px 6px;
        opacity: 0.5;
        transition: opacity 0.2s;
    }
    .subject-item:hover .btn-remove-subject {
        opacity: 1;
    }
    .empty-state {
        text-align: center;
        padding: 20px;
        color: #adb5bd;
        font-style: italic;
        font-size: 0.85rem;
    }
</style>
@endpush

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-project-diagram mr-2"></i> {{ __('Curriculum Management') }}</h1>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Filter Bar -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.program-subjects.index') }}" method="GET" id="filterForm">
                <div class="row items-center">
                    <div class="col-md-5">
                        <div class="form-group mb-0">
                            <label class="small font-weight-bold">{{ __('Select Program') }}</label>
                            <select name="program_id" class="form-control select2" onchange="this.form.submit()">
                                @foreach($programs as $program)
                                    <option value="{{ $program->id }}" {{ $selectedProgramId == $program->id ? 'selected' : '' }}>
                                        {{ $program->name }} ({{ $program->degreeLevel ? $program->degreeLevel->name : 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label class="small font-weight-bold">{{ __('Academic Year') }}</label>
                            <select name="academic_year_id" class="form-control select2" onchange="this.form.submit()">
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                                        {{ $year->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 text-right pt-4">
                        <button type="button" class="btn btn-outline-secondary" onclick="window.location.reload()">
                            <i class="fas fa-sync-alt mr-1"></i> {{ __('Refresh') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($selectedProgramId && $selectedYearId)
    <!-- Curriculum View -->
    <div class="curriculum-container custom-scrollbar">
        @php
            $duration = $programs->find($selectedProgramId)->duration_years ?? 4;
        @endphp

        @for($i = 1; $i <= $duration; $i++)
        <div class="level-column">
            <h5 class="font-weight-bold mb-4 flex justify-between items-center text-secondary">
                <span>{{ __('YEAR') }} {{ $i }}</span>
            </h5>

            @foreach($semesters as $semester)
            <div class="semester-block">
                <div class="semester-header">
                    <span class="font-weight-bold text-dark small uppercase tracking-wider">{{ $semester->name }}</span>
                    <button class="btn btn-xs btn-primary shadow-sm rounded-circle px-2" 
                            onclick="openAssignModal({{ $i }}, {{ $semester->id }}, '{{ $semester->name }}', {{ $i }})">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <div class="subject-list">
                    @php
                        $assignedSubjects = isset($mappings[$i][$semester->id]) ? $mappings[$i][$semester->id] : [];
                    @endphp

                    @forelse($assignedSubjects as $mapping)
                    <div class="subject-item" id="mapping-{{ $mapping->id }}">
                        <div class="subject-info">
                            <span class="subject-name">{{ $mapping->subject->name }}</span>
                            <span class="subject-code">{{ $mapping->subject->code }} • {{ $mapping->subject->credit }} Credits</span>
                        </div>
                        <button class="btn btn-link btn-remove-subject" onclick="removeSubject({{ $mapping->id }})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                    @empty
                    <div class="empty-state">{{ __('No subjects assigned') }}</div>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>
        @endfor
    </div>
    @else
    <div class="alert alert-info shadow-sm border-0 rounded-lg">
        <i class="fas fa-info-circle mr-2"></i> {{ __('Please select a Program and Academic Year to manage the curriculum.') }}
    </div>
    @endif
</div>

<!-- Assign Subject Modal -->
<div class="modal fade" id="assignModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold">{{ __('Assign Subject') }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="assignForm">
                @csrf
                <input type="hidden" name="program_id" value="{{ $selectedProgramId }}">
                <input type="hidden" name="academic_year_id" value="{{ $selectedYearId }}">
                <input type="hidden" name="semester_id" id="modal_semester_id">
                <input type="hidden" name="level" id="modal_level">
                
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small uppercase">{{ __('Target') }}</span>
                            <span class="badge badge-light px-3 py-2 border" id="modal_target_label">Year 1 - Semester 1</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">{{ __('Choose Subject') }} <span class="text-danger">*</span></label>
                        <select name="subject_id" class="form-control select2" style="width: 100%" required>
                            <option value="">{{ __('Search by name or code...') }}</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">[{{ $subject->code }}] {{ $subject->name }} ({{ $subject->credit }} Cr)</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm font-weight-bold">
                        <i class="fas fa-check-circle mr-1"></i> {{ __('Confirm Assignment') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Select2 -->
<script src="{{ asset('backend/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        $('#assignForm').submit(function(e) {
            e.preventDefault();
            let data = $(this).serialize();
            
            $.ajax({
                url: "{{ route('admin.program-subjects.assign') }}",
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        $('#assignModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON?.message || 'Something went wrong';
                    Swal.fire('Error!', msg, 'error');
                }
            });
        });
    });

    function openAssignModal(level, semesterId, semesterName, year) {
        $('#modal_semester_id').val(semesterId);
        $('#modal_level').val(level);
        $('#modal_target_label').text('Year ' + level + ' - ' + semesterName);
        $('#assignModal').modal('show');
    }

    function removeSubject(id) {
        Swal.fire({
            title: 'Remove from Curriculum?',
            text: "This will detach the subject from this specific semester/program mapping.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('admin.program-subjects.destroy', ':id') }}".replace(':id', id),
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if (response.success) {
                            $('#mapping-' + id).fadeOut(300, function() {
                                $(this).remove();
                                // Check if list is empty now
                                // Option: reload to refresh UI complexity
                                window.location.reload();
                            });
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Could not remove subject.', 'error');
                    }
                });
            }
        });
    }
</script>
@endpush

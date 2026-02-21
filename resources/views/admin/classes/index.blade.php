@extends('admin.layouts.master')

@section('title', 'Class Management')

@section('content')
<div class="container-fluid pt-4">
    <div class="row">
        <!-- Left Sidebar: Filters -->
        <div class="col-md-3">
            <div class="card shadow-sm sticky-top border-0 mb-4" style="top: 20px;">
                <div class="card-header bg-white font-weight-bold border-bottom-0 pb-0 pt-4">
                    <h5 class="mb-0 text-dark"><i class="fas fa-filter mr-2 text-primary"></i> Filter Options</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.classes.index') }}" method="GET" id="filterForm">
                        <div class="form-group mb-3">
                            <label class="small text-uppercase text-muted font-weight-bold mb-1">Campus</label>
                            <select name="campus_id" class="form-control select2" onchange="this.form.submit()">
                                @foreach($campuses as $campus)
                                    <option value="{{ $campus->id }}" {{ $selectedCampusId == $campus->id ? 'selected' : '' }}>
                                        {{ $campus->getTranslatedName() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="small text-uppercase text-muted font-weight-bold mb-1">Academic Year</label>
                            <select name="academic_year_id" class="form-control select2" onchange="this.form.submit()">
                                @foreach($years as $year)
                                    <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                                        {{ $year->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="small text-uppercase text-muted font-weight-bold mb-1">Semester</label>
                            <select name="semester_id" class="form-control select2" onchange="this.form.submit()">
                                @foreach($semesters as $semester)
                                    <option value="{{ $semester->id }}" {{ $selectedSemesterId == $semester->id ? 'selected' : '' }}>
                                        {{ $semester->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="small text-uppercase text-muted font-weight-bold mb-1">Shift</label>
                            <select name="shift_id" class="form-control select2" onchange="this.form.submit()">
                                <option value="">All Shifts</option>
                                @foreach($shifts as $shift)
                                    <option value="{{ $shift->id }}" {{ $selectedShiftId == $shift->id ? 'selected' : '' }}>
                                        {{ $shift->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="small text-uppercase text-muted font-weight-bold mb-1">Program</label>
                            <select name="program_id" class="form-control select2" onchange="this.form.submit()">
                                <option value="">All Programs</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->id }}" {{ $selectedProgramId == $program->id ? 'selected' : '' }}>
                                        {{ $program->getTranslatedName() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                         <div class="form-group mb-4">
                            <label class="small text-uppercase text-muted font-weight-bold mb-1">Classroom</label>
                            <select name="classroom_id" class="form-control select2" onchange="this.form.submit()">
                                <option value="">All Classrooms</option>
                                @foreach($classrooms as $room)
                                    <option value="{{ $room->id }}" {{ $selectedClassroomId == $room->id ? 'selected' : '' }}>
                                        {{ $room->room_number }} ({{ $room->type }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <a href="{{ route('admin.classes.index') }}" class="btn btn-outline-secondary btn-block shadow-sm">
                            <i class="fas fa-sync-alt mr-1"></i> Reset All Filters
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Content: Classes List -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="font-weight-bold mb-0 text-dark">Class Groups</h3>
                    <p class="text-muted mb-0 small">Manage your class schedules and assignments</p>
                </div>
                <a href="{{ route('admin.classes.create') }}" class="btn btn-primary shadow-sm px-4">
                    <i class="fas fa-plus-circle mr-2"></i> Create New Class
                </a>
            </div>

            @if($classes->count() > 0)
                <div class="card border-0 shadow-sm bg-transparent">
                    <div class="list-group list-group-flush bg-transparent">
                        @foreach($classes as $class)
                            <div class="list-group-item border-0 mb-3 bg-white rounded shadow-sm p-0 class-card-item">
                                <div class="p-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
                                    <!-- Left: Info -->
                                    <div class="d-flex align-items-center w-100 cursor-pointer class-info-trigger" 
                                         data-class='@json($class)' 
                                         data-subjects='@json($class->classSubjects)'
                                         role="button">
                                        
                                        <div class="d-flex flex-column align-items-center justify-content-center bg-light-primary text-primary rounded mr-4" style="width: 70px; height: 70px; min-width: 70px;">
                                            <span class="h4 font-weight-bold mb-0">{{ $class->classSubjects->count() }}</span>
                                            <span class="small font-weight-bold text-uppercase" style="font-size: 0.6rem;">Subjects</span>
                                        </div>

                                        <div class="w-100">
                                            <div class="d-flex justify-content-between mb-1">
                                                <h5 class="font-weight-bold text-dark mb-0">{{ $class->name }}</h5>
                                                <div class="d-none d-md-block">
                                                    @php
                                                        $statusConfig = [
                                                            'Scheduled' => ['color' => 'info', 'icon' => 'fa-clock'],
                                                            'Ongoing' => ['color' => 'success', 'icon' => 'fa-play-circle'],
                                                            'Completed' => ['color' => 'secondary', 'icon' => 'fa-check-circle'],
                                                            'Cancelled' => ['color' => 'danger', 'icon' => 'fa-times-circle'],
                                                        ];
                                                        $status = $statusConfig[$class->status] ?? ['color' => 'secondary', 'icon' => 'fa-circle'];
                                                    @endphp
                                                    <span class="badge badge-subtle-{{ $status['color'] }} px-3 py-1 rounded-pill">
                                                        <i class="fas {{ $status['icon'] }} mr-1"></i> {{ $class->status }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="row no-gutters mb-2">
                                                <div class="col-md-8">
                                                    <div class="text-muted small">
                                                        <span class="mr-3"><i class="fas fa-barcode mr-1 text-secondary"></i> {{ $class->code }}</span>
                                                        <span class="mr-3"><i class="fas fa-graduation-cap mr-1 text-secondary"></i> {{ $class->program ? $class->program->getTranslatedName() : 'N/A' }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center mt-2">
                                                <div class="progress flex-grow-1 mr-3 rounded-pill" style="height: 6px; background-color: #e9ecef;">
                                                    @php
                                                        $percent = $class->max_students > 0 ? ($class->students_count / $class->max_students) * 100 : 0;
                                                        $percent = min(100, $percent);
                                                    @endphp
                                                    <div class="progress-bar bg-primary rounded-pill" role="progressbar" style="width: {{ $percent }}%" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <small class="text-muted font-weight-bold text-nowrap">
                                                    {{ $class->students_count }} / {{ $class->max_students }} Students
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right: Actions -->
                                    <div class="d-flex align-items-center mt-3 mt-md-0 ml-md-4 pl-md-4 border-left-md action-panel">
                                        <button class="btn btn-light text-primary btn-sm mr-2 shadow-sm class-info-trigger" 
                                                data-class='@json($class)' 
                                                data-subjects='@json($class->classSubjects)'
                                                title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        <button class="btn btn-light text-dark btn-sm mr-2 shadow-sm" onclick="showQrCode('{{ $class->id }}', '{{ addslashes($class->name) }}', '{{ $class->code }}')" title="QR Code">
                                            <i class="fas fa-qrcode"></i>
                                        </button>

                                        <div class="dropdown">
                                            <button class="btn btn-light text-secondary btn-sm shadow-sm" type="button" data-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right shadow border-0">
                                                <a class="dropdown-item" href="{{ route('admin.classes.edit', $class->id) }}">
                                                    <i class="fas fa-edit mr-2 text-warning"></i> Edit Class
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger delete-trigger" href="#" onclick="confirmDelete('{{ $class->id }}')">
                                                    <i class="fas fa-trash-alt mr-2"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                        <form id="delete-form-{{ $class->id }}" action="{{ route('admin.classes.destroy', $class->id) }}" method="POST" style="display: none;">
                                            @csrf @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-5 bg-white rounded shadow-sm">
                    <div class="mb-3">
                        <span class="fa-stack fa-2x text-muted">
                            <i class="fas fa-circle fa-stack-2x text-light"></i>
                            <i class="fas fa-chalkboard fa-stack-1x"></i>
                        </span>
                    </div>
                    <h5 class="text-muted">No classes found</h5>
                    <p class="text-muted small">Try adjusting your filters or create a new class.</p>
                    <a href="{{ route('admin.classes.create') }}" class="btn btn-primary btn-sm px-4 mt-2">Create New Class</a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Class Details Modal -->
<div class="modal fade" id="classDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header bg-primary text-white border-0" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                <div>
                    <h5 class="modal-title font-weight-bold" id="modalClassTitle">Class Details</h5>
                    <small id="modalClassCode" class="opacity-75"></small>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                     <div>
                        <small class="text-uppercase text-muted font-weight-bold">Program</small>
                        <div class="font-weight-bold text-dark" id="modalClassProgram"></div>
                     </div>
                     <div id="modalClassStatus"></div>
                </div>
                
                <div class="p-4" style="max-height: 60vh; overflow-y: auto;">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                        <h6 class="font-weight-bold text-primary text-uppercase small mb-0">
                            <i class="fas fa-book-reader mr-2"></i> Subjects & Teachers
                        </h6>
                        <span class="badge badge-light" id="modalSubjectCount">0 Subjects</span>
                    </div>

                    <div id="modalSubjectsList">
                        <!-- Content populated by JS -->
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0" style="border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
                <button type="button" class="btn btn-light text-secondary font-weight-bold" data-dismiss="modal">Close</button>
                <a href="#" id="modalPrintBtn" class="btn btn-info shadow-sm px-4" target="_blank"><i class="fas fa-print mr-1"></i> Print Timetable</a>
                <a href="#" id="modalEditBtn" class="btn btn-primary shadow-sm px-4"><i class="fas fa-edit mr-1"></i> Edit Class</a>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<div class="modal fade" id="qrCodeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-qrcode mr-2"></i> Enrollment QR</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center py-5">
                <div id="qr-display">
                    <img id="qr-image" src="" alt="Class QR Code" class="img-fluid mb-3 rounded shadow-sm border p-2" style="max-width: 250px;">
                    <h5 class="font-weight-bold mb-1 text-dark" id="qr-class-name"></h5>
                    <p class="text-muted small" id="qr-class-code"></p>
                    <div class="alert alert-light border small mt-3 mb-0 text-muted mx-4">
                        <i class="fas fa-info-circle mr-1"></i> Students can scan this code to self-enroll.
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                <a href="#" id="qr-download" class="btn btn-primary btn-sm shadow-sm" download>
                    <i class="fas fa-download mr-1"></i> Download Image
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .cursor-pointer { cursor: pointer; }
    .bg-light-primary { background-color: rgba(52, 144, 220, 0.1); }
    .class-card-item { transition: transform 0.2s, box-shadow 0.2s; }
    .class-card-item:hover { transform: translateY(-3px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.1) !important; z-index: 10; }
    
    .badge-subtle-info { background-color: rgba(23, 162, 184, 0.1); color: #17a2b8; }
    .badge-subtle-success { background-color: rgba(40, 167, 69, 0.1); color: #28a745; }
    .badge-subtle-secondary { background-color: rgba(108, 117, 125, 0.1); color: #6c757d; }
    .badge-subtle-danger { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; }
    
    @media (min-width: 768px) {
        .border-left-md { border-left: 1px solid #dee2e6; }
    }
</style>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({ theme: 'bootstrap4', width: '100%' });
    });

    function showQrCode(classId, className, classCode) {
        $('#qr-class-name').text(className);
        $('#qr-class-code').text(classCode);
        var qrData = "ENROLL:" + classId; 
        var qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" + encodeURIComponent(qrData);

        $('#qr-image').attr('src', qrUrl);
        $('#qr-download').attr('href', qrUrl);
        
        $('#qrCodeModal').modal('show');
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Delete this Class?',
            text: "This will remove the class and all subjects!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#secondary',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    // Modal Details Logic
    $(document).on('click', '.class-info-trigger', function() {
        let classData = $(this).data('class');
        let subjects = $(this).data('subjects');
        
        $('#modalClassTitle').text(classData.name);
        $('#modalClassCode').text(classData.code);
        $('#modalSubjectCount').text((subjects ? subjects.length : 0) + ' Subjects');
        
        // Status Badge
        let statusBadge = '';
        if(classData.status === 'Scheduled') statusBadge = '<span class="badge badge-subtle-info px-3 py-1">Scheduled</span>';
        else if(classData.status === 'Ongoing') statusBadge = '<span class="badge badge-subtle-success px-3 py-1">Ongoing</span>';
        else if(classData.status === 'Completed') statusBadge = '<span class="badge badge-subtle-secondary px-3 py-1">Completed</span>';
        else statusBadge = '<span class="badge badge-subtle-danger px-3 py-1">'+classData.status+'</span>';
        $('#modalClassStatus').html(statusBadge);

        // Program Name
        let programName = classData.program ? (classData.program.name_en || classData.program.name) : 'N/A'; 
        $('#modalClassProgram').text(programName);

        let subjectsHtml = '';
        if(subjects && subjects.length > 0) {
            subjectsHtml = '<div class="list-group list-group-flush">';
            subjects.forEach(function(item) {
                let subjectName = item.subject ? (item.subject.name) : 'Unknown Subject';
                let subjectCode = item.subject ? item.subject.code : '-';
                let teacherName = item.teacher ? item.teacher.name : '<span class="text-muted font-italic">No Teacher</span>';
                let room = item.classroom ? (item.classroom.room_number) : '<span class="text-muted">-</span>';
                
                subjectsHtml += `
                    <div class="list-group-item px-0 py-3 border-bottom-0 bg-transparent">
                        <div class="d-flex w-100 justify-content-between align-items-start">
                            <div class="d-flex">
                                <div class="mr-3 mt-1 text-primary">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 font-weight-bold text-dark">${subjectName}</h6>
                                    <p class="mb-0 small text-muted font-weight-bold">${subjectCode}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-dark small font-weight-bold mb-1">
                                    <i class="fas fa-user-tie mr-1 text-info"></i> ${teacherName}
                                </div>
                                <div class="text-muted small">
                                    <i class="fas fa-door-open mr-1"></i> Room: ${room}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            subjectsHtml += '</div>';
        } else {
            subjectsHtml = `
                <div class="text-center py-5">
                    <i class="fas fa-book-open fa-3x text-light mb-3"></i>
                    <p class="text-muted">No subjects assigned to this class yet.</p>
                </div>
            `;
        }
        
        $('#modalSubjectsList').html(subjectsHtml);
        
        // Update Edit Button - Fix for JS/Blade mixing
        // We construct the URL in JS because we can't use blade route() with JS variables directly in specific ways without placeholders
        let baseUrl = "{{ route('admin.classes.edit', ':id') }}";
        let editUrl = baseUrl.replace(':id', classData.id);
        $('#modalEditBtn').attr('href', editUrl);
        
        // Update Print Button
        let printUrl = "{{ route('admin.classes.timetable.print', ':id') }}";
        let finalPrintUrl = printUrl.replace(':id', classData.id);
        $('#modalPrintBtn').attr('href', finalPrintUrl);

        $('#classDetailsModal').modal('show');
    });
</script>
@endpush

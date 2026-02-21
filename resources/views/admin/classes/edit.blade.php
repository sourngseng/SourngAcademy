@extends('admin.layouts.master')

@section('title', 'Edit Class Group')

@push('styles')
<style>
    .select2-container .select2-selection--single { height: 38px !important; }
    .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered { line-height: 38px !important; }
</style>
@endpush

@section('content')
<div class="container-fluid pt-4">
    <form action="{{ route('admin.classes.update', $class->id) }}" method="POST" id="edit-class-form">
        @csrf
        @method('PUT')
        
        <div class="row justify-content-center">
            <div class="col-md-12">
                <!-- Class Group Details -->
                <div class="card card-primary card-outline shadow mb-4">
                    <div class="card-header bg-white">
                        <h3 class="card-title font-weight-bold"><i class="fas fa-edit mr-2 text-primary"></i> Edit Class Group</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Campus <span class="text-danger">*</span></label>
                                    <select name="campus_id" id="campus_id" class="form-control select2 curriculum-trigger" required>
                                        @foreach($campuses as $campus)
                                            <option value="{{ $campus->id }}" {{ (old('campus_id') ?? $class->campus_id) == $campus->id ? 'selected' : '' }}>
                                                {{ $campus->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Academic Program <span class="text-danger">*</span></label>
                                    <select name="program_id" id="program_id" class="form-control select2 curriculum-trigger" required>
                                        <option value="" disabled>Select Program</option>
                                        @foreach($programs as $program)
                                            <option value="{{ $program->id }}" {{ (old('program_id') ?? $class->program_id) == $program->id ? 'selected' : '' }}>
                                                {{ $program->getTranslatedName() }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Semester <span class="text-danger">*</span></label>
                                    <select name="semester_id" id="semester_id" class="form-control select2 curriculum-trigger" required>
                                        @foreach($semesters as $semester)
                                            <option value="{{ $semester->id }}" {{ (old('semester_id') ?? $class->semester_id) == $semester->id ? 'selected' : '' }}>
                                                {{ $semester->academicYear->name }} - {{ $semester->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Shift <span class="text-danger">*</span></label>
                                    <select name="shift_id" id="shift_id" class="form-control select2" required>
                                        <option value="" disabled selected>Select Shift</option>
                                        @foreach($shifts as $shift)
                                            <option value="{{ $shift->id }}" {{ (old('shift_id') ?? $class->shift_id) == $shift->id ? 'selected' : '' }}>
                                                {{ $shift->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Group Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $class->name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Max Students <span class="text-danger">*</span></label>
                                    <input type="number" name="max_students" class="form-control" value="{{ old('max_students', $class->max_students) }}" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control" required>
                                        @php $status = old('status', $class->status); @endphp
                                        <option value="Scheduled" {{ $status == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                                        <option value="Ongoing" {{ $status == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                        <option value="Completed" {{ $status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="Cancelled" {{ $status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subjects & Schedule -->
                <div class="card card-secondary card-outline shadow mb-4">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title font-weight-bold"><i class="fas fa-book mr-2 text-secondary"></i> Subjects, Teachers & Schedule</h3>
                            <button type="button" class="btn btn-success btn-sm shadow-sm" id="add-subject-btn">
                                <i class="fas fa-plus mr-1"></i> Add Subject
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-bordered table-hover mb-0" id="subjects-table">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 35%;">Subject & Teacher <span class="text-danger">*</span></th>
                                    <th style="width: 15%;">Classroom</th>
                                    <th style="width: 45%;">Schedule (Session & Days)</th>
                                    <th style="width: 5%;"></th>
                                </tr>
                            </thead>
                            <tbody id="subjects-container">
                                {{-- Rows passed from controller or old input --}}
                            </tbody>
                        </table>
                        <div class="p-3 text-center text-muted" id="no-subjects-msg" style="display: none;">
                            <i class="fas fa-info-circle mr-1"></i> No subjects assigned. Click "Add Subject" to continue.
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light text-right mb-5">
                    <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary px-4 shadow-sm">Cancel</a>
                    <button type="submit" class="btn btn-info px-5 shadow shadow-sm font-weight-bold ml-2">
                        Update Class Group
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Template for New Row --}}
<script type="text/template" id="subject-row-template">
    <tr class="subject-row">
        <td class="align-top">
            <div class="form-group mb-2">
                <select name="subjects[INDEX][subject_id]" class="form-control select2 subject-select" required data-placeholder="Select Subject">
                    <option value="">Select Subject</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">[{{ $subject->code }}] {{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-0">
                <select name="subjects[INDEX][teacher_id]" class="form-control select2 teacher-select" required data-placeholder="Select Teacher">
                    <option value="">Select Teacher</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                    @endforeach
                </select>
            </div>
        </td>
        <td class="align-top">
            <select name="subjects[INDEX][classroom_id]" class="form-control select2 classroom-select" data-placeholder="Select Classroom">
                <option value="">-- Campus First --</option>
                {{-- Populated via JS --}}
            </select>
        </td>
        <td class="align-top">
            <div class="schedule-builder bg-light p-3 rounded border">
                <!-- Session/Time Selection -->
                <div class="form-group mb-3">
                     <label class="small font-weight-bold text-muted text-uppercase mb-1">Time Slot / Session</label>
                     <div class="d-flex flex-column">
                        <select class="form-control session-select select2 mb-2" multiple data-placeholder="Select One or More Sessions">
                            <!-- Populated by JS -->
                        </select>
                        
                        <!-- Selected Sessions Ranges container -->
                        <div class="session-ranges-hidden">
                            {{-- Populated via JS with hidden inputs: subjects[INDEX][time_ranges][] --}}
                        </div>

                        <!-- Active Times Display -->
                        <div class="selected-sessions-display d-flex flex-wrap gap-1">
                            {{-- Badges like: <span class="badge badge-info mr-1">08:00 - 09:30</span> --}}
                        </div>
                     </div>
                     
                     <!-- Custom Time Toggle -->
                     <div class="mt-2">
                        <div class="custom-control custom-checkbox small">
                            <input type="checkbox" class="custom-control-input custom-time-toggle" id="custom-toggle-INDEX">
                            <label class="custom-control-label text-muted" for="custom-toggle-INDEX">Manual / Custom Time</label>
                        </div>
                     </div>

                     <!-- Hidden Inputs by default, shown if Custom Toggle is active -->
                     <div class="custom-time-inputs row mt-2" style="display: none;">
                        <div class="col-6">
                            <input type="text" name="subjects[INDEX][custom_start]" class="form-control form-control-sm custom-start" placeholder="Start (e.g. 08:00 AM)">
                        </div>
                        <div class="col-6">
                            <input type="text" name="subjects[INDEX][custom_end]" class="form-control form-control-sm custom-end" placeholder="End (e.g. 09:30 AM)">
                        </div>
                     </div>
                </div>

                <!-- Days Selection -->
                <div>
                     <label class="small font-weight-bold text-muted text-uppercase mb-1">Days</label>
                     <div class="btn-group btn-group-sm btn-group-toggle w-100 d-flex" data-toggle="buttons">
                        <label class="btn btn-outline-secondary flex-fill day-label">
                            <input type="checkbox" name="subjects[INDEX][days][]" value="monday" autocomplete="off"> Mon
                        </label>
                        <label class="btn btn-outline-secondary flex-fill day-label">
                            <input type="checkbox" name="subjects[INDEX][days][]" value="tuesday" autocomplete="off"> Tue
                        </label>
                        <label class="btn btn-outline-secondary flex-fill day-label">
                            <input type="checkbox" name="subjects[INDEX][days][]" value="wednesday" autocomplete="off"> Wed
                        </label>
                        <label class="btn btn-outline-secondary flex-fill day-label">
                            <input type="checkbox" name="subjects[INDEX][days][]" value="thursday" autocomplete="off"> Thu
                        </label>
                        <label class="btn btn-outline-secondary flex-fill day-label">
                            <input type="checkbox" name="subjects[INDEX][days][]" value="friday" autocomplete="off"> Fri
                        </label>
                        <label class="btn btn-outline-secondary flex-fill day-label">
                            <input type="checkbox" name="subjects[INDEX][days][]" value="saturday" autocomplete="off"> Sat
                        </label>
                        <label class="btn btn-outline-secondary flex-fill day-label">
                            <input type="checkbox" name="subjects[INDEX][days][]" value="sunday" autocomplete="off"> Sun
                        </label>
                    </div>
                </div>
            </div>
        </td>
        <td class="text-center align-middle">
            <button type="button" class="btn btn-danger btn-sm remove-row-btn rounded-circle shadow-sm" style="width: 30px; height: 30px; line-height: 1;">
                <i class="fas fa-times"></i>
            </button>
        </td>
    </tr>
</script>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Init main select2
        $('.select2').select2({ theme: 'bootstrap4', width: '100%' });

        var rowIdx = 0;
        var availableClassrooms = @json($classrooms);
        var availableSubjects = [];

        // --- Shift Logic ---
        var shiftPresets = {};
        @foreach($shifts as $shift)
            shiftPresets['{{ $shift->id }}'] = @json($shift->sessions ?? []);
        @endforeach

        $('#shift_id').change(function() {
            var shiftId = $(this).val();
            updateAllSessionDropdowns(shiftId);
        });

        function updateAllSessionDropdowns(shiftId) {
            $('.session-select').each(function() {
                updateSessionDropdown($(this), shiftId);
            });
        }

        function updateSessionDropdown($select, shiftId) {
            var currentVal = $select.val(); // array for multi-select
            $select.empty();
            
            if (shiftId && shiftPresets[shiftId]) {
                $.each(shiftPresets[shiftId], function(index, session) {
                    var val = index + 1;
                    var name = session.name || 'Session ' + val;
                    var time = session.start + ' - ' + session.end;
                    $select.append('<option value="'+ val +'" data-start="'+ session.start +'" data-end="'+ session.end +'" data-name="'+ name +'">'+ name +' ('+ time +')</option>');
                });
            }
            if(currentVal && currentVal.length > 0) $select.val(currentVal).trigger('change');
        }

        $(document).on('change', '.session-select', function() {
            var $row = $(this).closest('.subject-row');
            var $rangesContainer = $row.find('.session-ranges-hidden');
            var $display = $row.find('.selected-sessions-display');
            var rowIndex = $row.find('.subject-select').attr('name').match(/\d+/)[0];

            $rangesContainer.empty();
            $display.empty();

            $(this).find('option:selected').each(function() {
                var start = $(this).data('start');
                var end = $(this).data('end');
                var name = $(this).data('name');
                var timeRange = start + ' - ' + end;

                // Add hidden input for each session
                $rangesContainer.append('<input type="hidden" name="subjects['+rowIndex+'][time_ranges][]" value="'+timeRange+'">');
                
                // Add badge for display
                $display.append('<span class="badge badge-info mr-1 mb-1 p-2" title="'+name+'">'+timeRange+'</span>');
            });
        });

        $(document).on('change', '.custom-time-toggle', function() {
            var $row = $(this).closest('.schedule-builder');
            if($(this).is(':checked')) {
                $row.find('.custom-time-inputs').slideDown();
            } else {
                $row.find('.custom-time-inputs').slideUp();
                $row.find('.custom-start, .custom-end').val('');
            }
        });

        $(document).on('click', '.day-label', function() {
            $(this).toggleClass('active');
            var chk = $(this).find('input');
            chk.prop('checked', !chk.prop('checked'));
            return false;
        });
        
        $(document).on('change', '.day-label input', function() {
             if($(this).is(':checked')) {
                 $(this).parent().addClass('active btn-primary').removeClass('btn-outline-secondary');
             } else {
                 $(this).parent().removeClass('active btn-primary').addClass('btn-outline-secondary');
             }
        });
        
        // --- Helpers ---
        function parseTimeStr(timeStr) {
             // Convert "8:00 AM" or "08:00" to "08:00"
             if(!timeStr) return '';
             timeStr = timeStr.trim().toUpperCase();
             var parts = timeStr.match(/(\d+):(\d+)(?:\s*(AM|PM))?/);
             if (parts) {
                 var hours = parseInt(parts[1]);
                 var minutes = parts[2];
                 var ampm = parts[3];
                 if (ampm === 'PM' && hours < 12) hours += 12;
                 if (ampm === 'AM' && hours === 12) hours = 0;
                 return (hours < 10 ? '0' : '') + hours + ':' + minutes;
             }
             return timeStr; // Return as is if already HH:mm or unknown
        }

        // --- Functions ---
        function addSubjectRow(data = null) {
            var template = $('#subject-row-template').html();
            var html = template.replace(/INDEX/g, rowIdx++);
            var $row = $(html);

            $('#subjects-container').append($row);
            
            $row.find('.select2').select2({ theme: 'bootstrap4', width: '100%' });
            updateRowClassrooms($row, availableClassrooms);
            updateRowSubjects($row, availableSubjects);
            
            // --- NEW: Populate Session Dropdown ---
            var shiftId = $('#shift_id').val();
            updateSessionDropdown($row.find('.session-select'), shiftId);

            if(data) {
                // Populate Fields
                $row.find('.subject-select').val(data.subject_id).trigger('change');
                // Use timeout to allow select2 to process options if necessary, mostly immediate though
                $row.find('.teacher-select').val(data.teacher_id).trigger('change');
                $row.find('.classroom-select').val(data.classroom_id).trigger('change');
                
                // Parse Schedule
                var sched = data.schedule;
                // Normalize JSON
                if (typeof sched === 'string') {
                    try { sched = JSON.parse(sched); } catch(e) {}
                }

                var days = [];
                var timeRanges = [];

                // Case 1: Object {'monday': ['8:00 AM - 9:00 AM', '10:00 AM - 11:00 AM']}
                if (typeof sched === 'object' && sched !== null) {
                    days = Object.keys(sched);
                    Object.values(sched).forEach(val => {
                        if (Array.isArray(val)) {
                            val.forEach(t => { if(t && !timeRanges.includes(t)) timeRanges.push(t); });
                        } else if (typeof val === 'string') {
                            if(val && !timeRanges.includes(val)) timeRanges.push(val);
                        }
                    });
                } 
                // Case 2: String "Mon 8:00-9:00" (Legacy)
                else if (typeof sched === 'string') {
                    var lower = sched.toLowerCase();
                    ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'].forEach(d => {
                        if(lower.includes(d) || lower.includes(d.substring(0,3))) days.push(d);
                    });
                    
                    var timeMatch = sched.match(/(\d{1,2}:\d{2}(?:\s?[AaPp][Mm])?)\s*-\s*(\d{1,2}:\d{2}(?:\s?[AaPp][Mm])?)/);
                    if(timeMatch) {
                        timeRanges.push(timeMatch[1] + ' - ' + timeMatch[2]);
                    }
                }

                // Set Day Values
                days.forEach(d => {
                    var $chk = $row.find('input[value="'+d.toLowerCase()+'"]');
                    $chk.prop('checked', true);
                    $chk.parent().addClass('active btn-primary').removeClass('btn-outline-secondary');
                });
                
                function normalizeTime(t) {
                    if(!t) return '';
                    var match = t.match(/(\d{1,2}):(\d{2})\s*([AaPp][Mm])?/);
                    if(!match) return t;
                    var h = parseInt(match[1], 10);
                    var m = match[2];
                    var amp = match[3] ? match[3].toUpperCase() : null;
                    if (amp === 'PM' && h < 12) h += 12;
                    if (amp === 'AM' && h === 12) h = 0;
                    return (h < 10 ? '0' + h : h) + ':' + m;
                }

                // Attempt to match sessions
                var selectedSessionIds = [];
                var unmatchedRange = null;

                timeRanges.forEach(range => {
                    var parts = range.split('-');
                    if(parts.length < 2) {
                        unmatchedRange = range;
                        return;
                    }
                    var sVal = parts[0].trim();
                    var eVal = parts[1].trim();
                    var nStart = normalizeTime(sVal);
                    var nEnd = normalizeTime(eVal);

                    var found = false;
                    $row.find('.session-select option').each(function() {
                        var optStart = $(this).data('start');
                        var optEnd = $(this).data('end');
                        if (normalizeTime(optStart) == nStart && normalizeTime(optEnd) == nEnd) {
                            selectedSessionIds.push($(this).val());
                            found = true;
                            return false;
                        }
                    });

                    if(!found) {
                        unmatchedRange = { start: sVal, end: eVal };
                    }
                });

                if(selectedSessionIds.length > 0) {
                    $row.find('.session-select').val(selectedSessionIds).trigger('change');
                }

                if(unmatchedRange) {
                    $row.find('.custom-time-toggle').prop('checked', true).trigger('change');
                    $row.find('.custom-start').val(unmatchedRange.start);
                    $row.find('.custom-end').val(unmatchedRange.end);
                }
            }

            checkEmptyState();
        }

        function removeRow(btn) {
            $(btn).closest('tr').remove();
            checkEmptyState();
        }

        function checkEmptyState() {
            if ($('#subjects-container tr').length === 0) {
                $('#no-subjects-msg').show();
            } else {
                $('#no-subjects-msg').hide();
            }
        }

        function loadCurriculumData(callback = null) {
            var campusId = $('#campus_id').val();
            var programId = $('#program_id').val();
            var semesterId = $('#semester_id').val();

            if (!campusId || !programId || !semesterId) {
                availableSubjects = [];
                updateAllSubjects();
                if(callback) callback();
                return;
            }

            $.ajax({
                url: "{{ route('admin.program-subjects.get-subjects') }}",
                type: "GET",
                data: {
                    campus_id: campusId,
                    program_id: programId,
                    semester_id: semesterId
                },
                success: function(data) {
                    availableSubjects = data;
                    updateAllSubjects();
                    if(callback) callback();
                }
            });
        }

        function loadClassrooms(campusId) {
            if(!campusId) {
                availableClassrooms = [];
                updateAllClassrooms();
                return;
            }

            $.ajax({
                url: "{{ route('admin.classrooms.by-campus', ':id') }}".replace(':id', campusId),
                type: "GET",
                success: function(data) {
                    availableClassrooms = data;
                    updateAllClassrooms();
                }
            });
        }
        
        function loadShifts(campusId) {
            var $shiftSelect = $('#shift_id');
            // Store current selection if we want to try to preserve it (only if same campus, unlikely)
            // But if campus changed, shift is likely invalid.
            $shiftSelect.empty().append('<option value="" disabled selected>Select Shift</option>');
            
            if(!campusId) {
                shiftPresets = {};
                updateAllSessionDropdowns('');
                return;
            }

            $.ajax({
                url: "{{ route('admin.shifts.by-campus', ':id') }}".replace(':id', campusId),
                type: "GET",
                success: function(data) {
                    shiftPresets = {}; 
                    if(data && data.length > 0) {
                        $.each(data, function(key, shift) {
                            $shiftSelect.append('<option value="'+ shift.id +'">'+ shift.name +'</option>');
                            shiftPresets[shift.id] = shift.sessions || [];
                        });
                    } else {
                        $shiftSelect.append('<option value="" disabled>No Shifts Available</option>');
                    }
                    $shiftSelect.trigger('change');
                }
            });
        }

        function updateAllClassrooms() {
            $('#subjects-container .classroom-select').each(function() {
                updateRowClassrooms($(this).closest('tr'), availableClassrooms);
            });
        }

        function updateRowClassrooms($row, classrooms) {
            var $select = $row.find('.classroom-select');
            var currentVal = $select.val();
            $select.empty();
            
            if (classrooms.length === 0) {
                $select.append('<option value="">-- No Classrooms / Select Campus --</option>');
            } else {
                $select.append('<option value="">-- Select Classroom --</option>');
                $.each(classrooms, function(key, room) {
                    var selected = (currentVal == room.id) ? 'selected' : '';
                    $select.append('<option value="'+ room.id +'" '+ selected +'>'+ room.room_number + ' (' + room.type + ')</option>');
                });
            }
        }

        function updateAllSubjects() {
            $('#subjects-container .subject-select').each(function() {
                updateRowSubjects($(this).closest('tr'), availableSubjects);
            });
        }

        function updateRowSubjects($row, subjects) {
            var $select = $row.find('.subject-select');
            var currentVal = $select.val();
            $select.empty();

            if (subjects.length === 0) {
                $select.append('<option value="">-- No Subjects in Curriculum --</option>');
            } else {
                $select.append('<option value="">-- Select Subject --</option>');
                $.each(subjects, function(key, subj) {
                    var selected = (currentVal == subj.id) ? 'selected' : '';
                    $select.append('<option value="'+ subj.id +'" '+ selected +'>['+ subj.code + '] ' + subj.name + '</option>');
                });
            }
        }

        // --- Events ---

        $('#add-subject-btn').click(function() {
            addSubjectRow();
        });

        $(document).on('click', '.remove-row-btn', function() {
            removeRow(this);
        });

        $('.curriculum-trigger').change(function() {
            if ($(this).attr('id') === 'campus_id') {
                loadClassrooms($(this).val());
                loadShifts($(this).val());
            }
            loadCurriculumData();
        });

        // --- Init ---
        // Load initial curriculum data then populate rows
        // Use timeout to wrap async call if needed, but ajax success callback is best
        if($('#campus_id').val()) {
             // In edit mode, we assume classrooms are pre-loaded via controller $classrooms if campus is set
             // But valid to reload to be sure
             // Actually Controller passes $classrooms.
        }

        loadCurriculumData(function() {
            // Populate Rows from Existing Data
            @if($class->classSubjects->count() > 0)
                @foreach($class->classSubjects as $cs)
                    addSubjectRow({
                        subject_id: "{{ $cs->subject_id }}",
                        teacher_id: "{{ $cs->teacher_id }}",
                        classroom_id: "{{ $cs->classroom_id }}",
                        schedule: {!! $cs->schedule_json ? json_encode($cs->schedule_json) : 'null' !!}
                    });
                @endforeach
            @else
                addSubjectRow();
            @endif
        });
    });
</script>
@endpush


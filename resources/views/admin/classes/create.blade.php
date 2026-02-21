@extends('admin.layouts.master')

@section('title', 'Create Class Group')

@push('styles')
<style>
    .select2-container .select2-selection--single { height: 38px !important; }
    .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered { line-height: 38px !important; }
</style>
@endpush

@section('content')
<div class="container-fluid pt-4">
    <form action="{{ route('admin.classes.store') }}" method="POST" id="create-class-form">
        @csrf
        
        <div class="row justify-content-center">
            <div class="col-md-12">
                <!-- Class Group Details -->
                <div class="card card-primary card-outline shadow mb-4">
                    <div class="card-header bg-white">
                        <h3 class="card-title font-weight-bold"><i class="fas fa-layer-group mr-2 text-primary"></i> Class Group Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Campus <span class="text-danger">*</span></label>
                                    <select name="campus_id" id="campus_id" class="form-control select2 curriculum-trigger" required>
                                        <option value="" disabled selected>Select Campus</option>
                                        @foreach($campuses as $campus)
                                            <option value="{{ $campus->id }}" {{ old('campus_id') == $campus->id ? 'selected' : '' }}>
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
                                        <option value="" disabled selected>Select Program</option>
                                        @foreach($programs as $program)
                                            <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>
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
                                        <option value="" disabled selected>Select Semester</option>
                                        @foreach($semesters as $semester)
                                            <option value="{{ $semester->id }}" {{ old('semester_id') == $semester->id ? 'selected' : '' }}>
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
                                            <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : '' }}>
                                                {{ $shift->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Group Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" placeholder="e.g. Year 1 - Group A" value="{{ old('name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Max Students <span class="text-danger">*</span></label>
                                    <input type="number" name="max_students" class="form-control" value="{{ old('max_students', 30) }}" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control" required>
                                        <option value="Scheduled" {{ old('status') == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                                        <option value="Ongoing" {{ old('status') == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                        <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="Cancelled" {{ old('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                                {{-- Rows inserted via JS --}}
                            </tbody>
                        </table>
                        <div class="p-3 text-center text-muted" id="no-subjects-msg" style="display: none;">
                            <i class="fas fa-info-circle mr-1"></i> Click "Add Subject" to assign subjects to this class group.
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light text-right mb-5">
                    <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary px-4 shadow-sm">Cancel</a>
                    <button type="submit" class="btn btn-primary px-5 shadow shadow-sm font-weight-bold ml-2">
                        Create Class Group
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
            <select name="subjects[INDEX][classroom_id]" class="form-control select2 classroom-select" data-placeholder="Select Campus First">
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
                            {{-- Badges via JS --}}
                        </div>
                     </div>
                     
                     <!-- Custom Time Toggle -->
                     <div class="mt-2 text-right">
                        <div class="custom-control custom-checkbox small">
                            <input type="checkbox" class="custom-control-input custom-time-toggle" id="custom-toggle-INDEX">
                            <label class="custom-control-label text-muted" for="custom-toggle-INDEX">Manual / Custom Time</label>
                        </div>
                     </div>

                     <!-- Hidden Inputs by default, shown if Custom Toggle active -->
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
        var availableClassrooms = [];
        var availableSubjects = [];
        
        // --- Shift Logic ---
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
            var currentVal = $select.val();
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
        
        // --- Session Select Handler ---
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

                // Add hidden input
                $rangesContainer.append('<input type="hidden" name="subjects['+rowIndex+'][time_ranges][]" value="'+timeRange+'">');
                
                // Add badge
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




        
        // Fix for radio/checkbox buttons visual state
        // Fix for radio/checkbox buttons visual state
        $(document).on('change', '.day-label input', function() {
             var $lbl = $(this).closest('label');
             if($(this).is(':checked')) {
                 $lbl.addClass('active btn-primary').removeClass('btn-outline-secondary');
             } else {
                 $lbl.removeClass('active btn-primary').addClass('btn-outline-secondary');
             }
        });

        // --- Functions ---

        function addSubjectRow() {
            var template = $('#subject-row-template').html();
            var html = template.replace(/INDEX/g, rowIdx++);
            var $row = $(html);

            $('#subjects-container').append($row);
            
            // Init Select2 on new row
            $row.find('.select2').select2({ theme: 'bootstrap4', width: '100%' });
            
            // Populate classroom and subject options for this row
            updateRowClassrooms($row, availableClassrooms);
            updateRowSubjects($row, availableSubjects);
            
            // --- NEW: Populate Session Dropdown ---
            var shiftId = $('#shift_id').val();
            updateSessionDropdown($row.find('.session-select'), shiftId);

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

        function loadCurriculumData() {
            var campusId = $('#campus_id').val();
            var programId = $('#program_id').val();
            var semesterId = $('#semester_id').val();

            if (!campusId || !programId || !semesterId) {
                availableSubjects = [];
                updateAllSubjects();
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
                    shiftPresets = {}; // Reset presets
                    if(data && data.length > 0) {
                        $.each(data, function(key, shift) {
                            $shiftSelect.append('<option value="'+ shift.id +'">'+ shift.name +'</option>');
                            // Update presets
                            shiftPresets[shift.id] = shift.sessions || [];
                        });
                    } else {
                        $shiftSelect.append('<option value="" disabled>No Shifts Available</option>');
                    }
                    // Trigger change to update row dropdowns (will clear them because no shift selected)
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
        if($('#campus_id').val()) {
            loadClassrooms($('#campus_id').val());
        }
        
        if($('#campus_id').val() && $('#program_id').val() && $('#semester_id').val()) {
            loadCurriculumData();
        }

        // Add initial row
        addSubjectRow();
    });
</script>
@endpush

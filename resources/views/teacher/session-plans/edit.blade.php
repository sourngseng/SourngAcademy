@extends('teacher.layouts.master')

@section('title', 'Edit Lesson Plan')

@push('styles')
<link rel="stylesheet" href="{{ asset('backend/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/summernote/summernote-bs4.min.css') }}">
@endpush

@section('content')
<section class="content-header p-4">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="font-weight-bold"><i class="fas fa-edit text-warning mr-2"></i> Edit Lesson Plan</h1>
            </div>
            <div class="col-sm-6 text-md-right">
                <a href="{{ route('teacher.session-plans.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
</section>

<section class="content px-4">
    <div class="container-fluid">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <form action="{{ route('teacher.session-plans.update', $sessionPlan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="campus_id">Campus <span class="text-danger">*</span></label>
                                <select name="campus_id" id="campus_id" class="form-control select2" required>
                                    @foreach($campuses as $campus)
                                        <option value="{{ $campus->id }}" {{ $sessionPlan->campus_id == $campus->id ? 'selected' : '' }}>{{ $campus->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="program_id">Program <span class="text-danger">*</span></label>
                                <select name="program_id" id="program_id" class="form-control select2" required>
                                    @foreach($programs as $program)
                                        <option value="{{ $program->id }}" {{ $sessionPlan->program_id == $program->id ? 'selected' : '' }}>{{ method_exists($program, 'getTranslatedName') ? $program->getTranslatedName() : $program->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="subject_id">Subject <span class="text-danger">*</span></label>
                                <select name="subject_id" id="subject_id" class="form-control select2" required>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ $sessionPlan->subject_id == $subject->id ? 'selected' : '' }}>{{ method_exists($subject, 'getTranslatedName') ? $subject->getTranslatedName() : $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="academic_year_id">Academic Year</label>
                                <select name="academic_year_id" id="academic_year_id" class="form-control select2">
                                    <option value="">Select Year</option>
                                    @foreach($academicYears as $year)
                                        <option value="{{ $year->id }}" {{ $sessionPlan->academic_year_id == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="semester_id">Semester</label>
                                <select name="semester_id" id="semester_id" class="form-control select2">
                                    @foreach($semesters as $semester)
                                        <option value="{{ $semester->id }}" {{ $sessionPlan->semester_id == $semester->id ? 'selected' : '' }}>{{ $semester->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Duration</label>
                            <div class="input-group">
                                <input type="number" name="duration" class="form-control" value="{{ $sessionPlan->duration }}" min="0">
                                <div class="input-group-append">
                                   <select name="duration_unit" class="form-control" style="width: auto;">
                                       <option value="minutes" {{ $sessionPlan->duration_unit == 'minutes' ? 'selected' : '' }}>Minutes</option>
                                       <option value="hours" {{ $sessionPlan->duration_unit == 'hours' ? 'selected' : '' }}>Hours</option>
                                   </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="parent_id">Parent Session</label>
                                <select name="parent_id" id="parent_id" class="form-control select2">
                                    <option value="">No Parent (Top Level)</option>
                                    @foreach($parents as $parent)
                                        <option value="{{ $parent->id }}" {{ $sessionPlan->parent_id == $parent->id ? 'selected' : '' }}>{{ $parent->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ $sessionPlan->title }}" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Detailed Description</label>
                        <textarea name="description" id="description" class="form-control summernote">{{ $sessionPlan->description }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="attachment">Attachment</label>
                                @if($sessionPlan->attachment)
                                    <div class="mb-2"><a href="{{ asset($sessionPlan->attachment) }}" target="_blank" class="text-primary small font-weight-bold"><i class="fas fa-paperclip"></i> View Current File</a></div>
                                @endif
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="attachment" name="attachment">
                                    <label class="custom-file-label" for="attachment">Replace file</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="video_url">YouTube Video URL</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fab fa-youtube text-danger"></i></span>
                                    </div>
                                    <input type="url" name="video_url" class="form-control" value="{{ $sessionPlan->video_url }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white p-4 text-right">
                    <button type="submit" class="btn btn-warning px-5 btn-lg shadow-sm" style="border-radius: 10px;">Update Lesson Plan</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('backend/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('backend/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script src="{{ asset('backend/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script>
$(function () {
    $('.select2').select2({ theme: 'bootstrap4' });
    bsCustomFileInput.init();
    $('.summernote').summernote({ height: 200 });

    $('#campus_id').change(function() {
        var campusId = $(this).val();
        $('#program_id').html('<option value="">Select Program</option>').prop('disabled', true);
        $('#subject_id').html('<option value="">Select Subject</option>').prop('disabled', true);
        if(campusId) {
            $.get("{{ url('teacher/session-plans/get-programs') }}/" + campusId, function(data){
                $('#program_id').prop('disabled', false);
                $.each(data, function(key, value){ $('#program_id').append('<option value="'+value.id+'">'+value.name+'</option>'); });
            });
        }
    });

    $('#program_id').change(function() {
        var programId = $(this).val();
        $('#subject_id').html('<option value="">Select Subject</option>').prop('disabled', true);
        if(programId) {
            $.get("{{ url('teacher/session-plans/get-subjects') }}/" + programId, function(data){
                $('#subject_id').prop('disabled', false);
                $.each(data, function(key, value){ $('#subject_id').append('<option value="'+value.id+'">'+value.name+'</option>'); });
            });
        }
    });

    $('#subject_id').change(function() {
        var subjectId = $(this).val();
        $('#parent_id').html('<option value="">No Parent (Top Level)</option>').prop('disabled', true);
        if(subjectId) {
             $.get("{{ url('teacher/session-plans/get-parents') }}/" + subjectId, function(data){
                $('#parent_id').prop('disabled', false);
                $.each(data, function(key, value){ $('#parent_id').append('<option value="'+value.id+'">'+value.title+'</option>'); });
            });
        }
    });

    $('#academic_year_id').change(function() {
        var yearId = $(this).val();
        $('#semester_id').html('<option value="">Select Semester</option>').prop('disabled', true);
        if(yearId) {
            $.get("{{ url('teacher/session-plans/get-semesters') }}/" + yearId, function(data){
                $('#semester_id').prop('disabled', false);
                $.each(data, function(key, value){ $('#semester_id').append('<option value="'+value.id+'">'+value.name+'</option>'); });
            });
        }
    });
});
</script>
@endpush

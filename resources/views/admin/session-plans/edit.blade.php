@extends('admin.layouts.master')

@section('title', 'Edit Session Plan')

@push('styles')
<link rel="stylesheet" href="{{ asset('backend/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/summernote/summernote-bs4.min.css') }}">
@endpush

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Session Plan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.session-plans.index') }}">Session Plans</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card card-warning card-outline">
            <form action="{{ route('admin.session-plans.update', $sessionPlan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="campus_id">Campus <span class="text-danger">*</span></label>
                                <select name="campus_id" id="campus_id" class="form-control select2" required>
                                    <option value="">Select Campus</option>
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
                                    <option value="">Select Program</option>
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
                                    <option value="">Select Subject</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ $sessionPlan->subject_id == $subject->id ? 'selected' : '' }}>{{ method_exists($subject, 'getTranslatedName') ? $subject->getTranslatedName() : $subject->name }} ({{ $subject->code }})</option>
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
                                    <option value="">Select Academic Year</option>
                                    @foreach($academicYears as $year)
                                        <option value="{{ $year->id }}" {{ $sessionPlan->academic_year_id == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="semester_id">Semester</label>
                                <select name="semester_id" id="semester_id" class="form-control select2" {{ empty($semesters) ? 'disabled' : '' }}>
                                    <option value="">Select Semester</option>
                                    @if(isset($semesters))
                                        @foreach($semesters as $semester)
                                            <option value="{{ $semester->id }}" {{ $sessionPlan->semester_id == $semester->id ? 'selected' : '' }}>{{ $semester->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Duration (Optional)</label>
                            <div class="input-group">
                                <input type="number" name="duration" class="form-control" placeholder="e.g. 90" min="0" value="{{ $sessionPlan->duration }}">
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
                                <label for="parent_id">Parent Session (Optional)</label>
                                <select name="parent_id" id="parent_id" class="form-control select2">
                                    <option value="">No Parent (Top Level)</option>
                                    @foreach($parents as $parent)
                                        <option value="{{ $parent->id }}" {{ $sessionPlan->parent_id == $parent->id ? 'selected' : '' }}>{{ $parent->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ $sessionPlan->title }}" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control summernote">{{ $sessionPlan->description }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="attachment">Attachment</label>
                                @if($sessionPlan->attachment)
                                    <div class="mb-2">
                                        <a href="{{ asset($sessionPlan->attachment) }}" target="_blank" class="text-primary font-weight-bold"><i class="fas fa-paperclip"></i> Current Attachment</a>
                                    </div>
                                @endif
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="attachment" name="attachment">
                                    <label class="custom-file-label" for="attachment">Change file</label>
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
                                    <input type="url" name="video_url" class="form-control" placeholder="https://www.youtube.com/watch?v=..." value="{{ $sessionPlan->video_url }}">
                                </div>
                                <small class="text-muted">Paste a full YouTube URL here to embed a video in the reader. Example: <code>https://www.youtube.com/watch?v=dQw4w9WgXcQ</code></small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">Update Session Plan</button>
                    <a href="{{ route('admin.session-plans.index') }}" class="btn btn-secondary">Cancel</a>
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
});

// Dependent Dropdowns Logic (Same as Create)
$('#campus_id').change(function() {
    var campusId = $(this).val();
    $('#program_id').html('<option value="">Select Program</option>').prop('disabled', true);
    $('#subject_id').html('<option value="">Select Subject</option>').prop('disabled', true);
    
    if(campusId) {
        $.get("{{ url('admin/session-plans/get-programs') }}/" + campusId, function(data){
            $('#program_id').prop('disabled', false);
            $.each(data, function(key, value){
                $('#program_id').append('<option value="'+value.id+'">'+value.name+'</option>');
            });
        });
    }
});

$('#program_id').change(function() {
    var programId = $(this).val();
    $('#subject_id').html('<option value="">Select Subject</option>').prop('disabled', true);
    $('#parent_id').html('<option value="">No Parent (Top Level)</option>').prop('disabled', true);
    
    if(programId) {
        $.get("{{ url('admin/session-plans/get-subjects') }}/" + programId, function(data){
            $('#subject_id').prop('disabled', false);
            $.each(data, function(key, value){
                $('#subject_id').append('<option value="'+value.id+'">'+value.name+'</option>');
            });
        });
    }
});

$('#subject_id').change(function() {
    var subjectId = $(this).val();
    $('#parent_id').html('<option value="">No Parent (Top Level)</option>').prop('disabled', true);
    
    if(subjectId) {
         $.get("{{ url('admin/session-plans/get-parents') }}/" + subjectId, function(data){
            $('#parent_id').prop('disabled', false);
            $.each(data, function(key, value){
                $('#parent_id').append('<option value="'+value.id+'">'+value.title+'</option>');
            });
        });
    }
});

$('#academic_year_id').change(function() {
    var yearId = $(this).val();
    $('#semester_id').html('<option value="">Select Semester</option>').prop('disabled', true);
    
    if(yearId) {
        $.get("{{ url('admin/session-plans/get-semesters') }}/" + yearId, function(data){
            $('#semester_id').prop('disabled', false);
            $.each(data, function(key, value){
                $('#semester_id').append('<option value="'+value.id+'">'+value.name+'</option>');
            });
        });
    }
});
</script>
@endpush

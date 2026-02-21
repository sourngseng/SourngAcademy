@extends('teacher.layouts.master')

@section('title', 'Create Lesson Plan')

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
                <h1 class="font-weight-bold"><i class="fas fa-plus-circle text-primary mr-2"></i> Create Lesson Plan</h1>
            </div>
            <div class="col-sm-6 text-md-right">
                <a href="{{ route('teacher.session-plans.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Back to List</a>
            </div>
        </div>
    </div>
</section>

<section class="content px-4">
    <div class="container-fluid">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <form action="{{ route('teacher.session-plans.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="campus_id">Campus <span class="text-danger">*</span></label>
                                <select name="campus_id" id="campus_id" class="form-control select2" required>
                                    <option value="">Select Campus</option>
                                    @foreach($campuses as $campus)
                                        <option value="{{ $campus->id }}" {{ request('campus_id') == $campus->id ? 'selected' : '' }}>{{ $campus->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="program_id">Program <span class="text-danger">*</span></label>
                                <select name="program_id" id="program_id" class="form-control select2" required {{ request('program_id') ? '' : 'disabled' }}>
                                    <option value="">Select Program</option>
                                    @if(request('program_id'))
                                        @php $p = \App\Modules\Academic\Models\Program::find(request('program_id')); @endphp
                                        <option value="{{ $p->id }}" selected>{{ $p->getTranslatedName() ?? $p->name }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="subject_id">Subject <span class="text-danger">*</span></label>
                                <select name="subject_id" id="subject_id" class="form-control select2" required {{ request('subject_id') ? '' : 'disabled' }}>
                                    <option value="">Select Subject</option>
                                    @if(request('subject_id'))
                                        @php $s = \App\Modules\Academic\Models\Subject::find(request('subject_id')); @endphp
                                        <option value="{{ $s->id }}" selected>{{ $s->getTranslatedName() ?? $s->name }} ({{ $s->code }})</option>
                                    @endif
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
                                        <option value="{{ $year->id }}">{{ $year->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="semester_id">Semester</label>
                                <select name="semester_id" id="semester_id" class="form-control select2" disabled>
                                    <option value="">Select Semester</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Duration</label>
                            <div class="input-group">
                                <input type="number" name="duration" class="form-control" placeholder="e.g. 90" min="0">
                                <div class="input-group-append">
                                   <select name="duration_unit" class="form-control" style="width: auto;">
                                       <option value="minutes">Minutes</option>
                                       <option value="hours">Hours</option>
                                   </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="parent_id">Parent Session (Optional)</label>
                                <select name="parent_id" id="parent_id" class="form-control select2" disabled>
                                    <option value="">No Parent (Top Level)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="Session Plan Title" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Detailed Description</label>
                        <textarea name="description" id="description" class="form-control summernote"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="attachment">Attachment (PDF, Docx)</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="attachment" name="attachment">
                                    <label class="custom-file-label" for="attachment">Choose file</label>
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
                                    <input type="url" name="video_url" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white p-4 text-right">
                    <button type="submit" class="btn btn-primary px-5 btn-lg shadow-sm" style="border-radius: 10px;">Save Session Plan</button>
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

    // Initial Trigger if IDs exist (e.g. from Dashboard)
    if ($('#campus_id').val() && !$('#program_id').val()) {
        $('#campus_id').trigger('change');
    }
    if ($('#program_id').val() && !$('#subject_id').val()) {
        $('#program_id').trigger('change');
    }
    if ($('#subject_id').val()) {
        $('#subject_id').trigger('change');
    }

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

@extends('admin.layouts.master')

@section('title', 'Edit Semester')

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-info card-outline shadow">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold">Edit Semester: {{ $semester->name }}</h3>
                </div>
                <form action="{{ route('admin.semesters.update', $semester->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group font-weight-500">
                            <label>Academic Year <span class="text-danger">*</span></label>
                            <select name="academic_year_id" class="form-control select2 shadow-sm" required>
                                @foreach($years as $year)
                                    <option value="{{ $year->id }}" {{ $semester->academic_year_id == $year->id ? 'selected' : '' }}>
                                        {{ $year->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group font-weight-500">
                            <label>Semester Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control shadow-sm" value="{{ $semester->name }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group font-weight-500">
                                    <label>Start Date <span class="text-danger">*</span></label>
                                    <div class="input-group date" id="start_date_picker" data-target-input="nearest">
                                        <input type="text" name="start_date" class="form-control datetimepicker-input shadow-sm" data-target="#start_date_picker" value="{{ $semester->start_date ? $semester->start_date->format('Y-m-d') : '' }}" required autocomplete="off"/>
                                        <div class="input-group-append" data-target="#start_date_picker" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group font-weight-500">
                                    <label>End Date <span class="text-danger">*</span></label>
                                    <div class="input-group date" id="end_date_picker" data-target-input="nearest">
                                        <input type="text" name="end_date" class="form-control datetimepicker-input shadow-sm" data-target="#end_date_picker" value="{{ $semester->end_date ? $semester->end_date->format('Y-m-d') : '' }}" required autocomplete="off"/>
                                        <div class="input-group-append" data-target="#end_date_picker" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="custom-control custom-switch mt-3">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" {{ $semester->is_active ? 'checked' : '' }} value="1">
                            <label class="custom-control-label font-weight-bold" for="is_active">Set as Current Active Semester</label>
                        </div>
                    </div>
                    <div class="card-footer bg-light text-right">
                        <a href="{{ route('admin.semesters.index') }}" class="btn btn-secondary px-4 shadow-sm">Cancel</a>
                        <button type="submit" class="btn btn-info px-5 shadow shadow-sm font-weight-bold ml-2">
                             Update Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({ theme: 'bootstrap4' });

        // Initialize Date Pickers
        $('#start_date_picker, #end_date_picker').datetimepicker({
            format: 'YYYY-MM-DD',
            allowInputToggle: true,
            icons: {
                time: 'fas fa-clock',
                date: 'fas fa-calendar',
                up: 'fas fa-chevron-up',
                down: 'fas fa-chevron-down',
                previous: 'fas fa-chevron-left',
                next: 'fas fa-chevron-right',
                today: 'fas fa-calendar-check',
                clear: 'fas fa-trash',
                close: 'fas fa-times'
            }
        });
    });
</script>
@endpush

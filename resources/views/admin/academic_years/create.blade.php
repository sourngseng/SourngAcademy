@extends('admin.layouts.master')

@section('title', 'Add Academic Year')

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-primary card-outline shadow">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold">Create New Academic Year</h3>
                </div>
                <form action="{{ route('admin.academic-years.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group font-weight-500">
                            <label>Campus <span class="text-danger">*</span></label>
                            <select name="campus_id" class="form-control select2 shadow-sm" required>
                                @foreach($campuses as $campus)
                                    <option value="{{ $campus->id }}">{{ $campus->name_en }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group font-weight-500">
                            <label>Academic Year Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control shadow-sm" placeholder="e.g. 2025-2026" required>
                            <small class="text-muted italic mt-1 d-block">System will automatically generate 2 semesters for this year.</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group font-weight-500">
                                    <label>Start Date <span class="text-danger">*</span></label>
                                    <div class="input-group date" id="start_date_picker" data-target-input="nearest">
                                        <input type="text" name="start_date" class="form-control datetimepicker-input shadow-sm" data-target="#start_date_picker" required autocomplete="off"/>
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
                                        <input type="text" name="end_date" class="form-control datetimepicker-input shadow-sm" data-target="#end_date_picker" required autocomplete="off"/>
                                        <div class="input-group-append" data-target="#end_date_picker" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="custom-control custom-switch mt-3">
                            <input type="checkbox" class="custom-control-input" id="status" name="status" checked value="1">
                            <label class="custom-control-label font-weight-bold" for="status">Mark as Active</label>
                        </div>
                    </div>
                    <div class="card-footer bg-light text-right">
                        <a href="{{ route('admin.academic-years.index') }}" class="btn btn-secondary px-4 shadow-sm">Cancel</a>
                        <button type="submit" class="btn btn-primary px-5 shadow shadow-sm font-weight-bold ml-2">
                             Create Year & semesters
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

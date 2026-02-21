@extends('admin.layouts.master')

@section('title', 'Create Shift')

@push('styles')
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <style>
        .custom-switch.custom-switch-md .custom-control-label {
            padding-left: 2rem;
            padding-bottom: 1.5rem;
        }
        .custom-switch.custom-switch-md .custom-control-label::before {
            height: 1.5rem;
            width: calc(2rem + 0.75rem);
            border-radius: 3rem;
        }
        .custom-switch.custom-switch-md .custom-control-label::after {
            width: calc(1.5rem - 4px); 
            height: calc(1.5rem - 4px); 
            border-radius: calc(2rem - (1.5rem / 2));
        }
        .custom-switch.custom-switch-md .custom-control-input:checked ~ .custom-control-label::after {
            transform: translateX(calc(1.5rem - 0.25rem));
        }
    </style>
@endpush

@section('content')
<div class="container-fluid pt-4">
    <div class="row align-items-center mb-4">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Create Shift</h1>
        </div>
        <div class="col-sm-6 text-right">
            <a href="{{ route('admin.shifts.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Shifts
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.shifts.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="campus_id">Campus <span class="text-danger">*</span></label>
                    <select name="campus_id" id="campus_id" class="form-control" required>
                        <option value="">Select Campus</option>
                        @foreach($campuses as $campus)
                            <option value="{{ $campus->id }}" {{ old('campus_id') == $campus->id ? 'selected' : '' }}>{{ $campus->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="card card-primary card-outline card-outline-tabs">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                            @foreach($languages as $code => $lang)
                                <li class="nav-item">
                                    <a class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                       id="custom-tabs-four-{{ $code }}-tab" 
                                       data-toggle="pill" 
                                       href="#custom-tabs-four-{{ $code }}" 
                                       role="tab" 
                                       aria-controls="custom-tabs-four-{{ $code }}" 
                                       aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                       {{ $lang['name'] }} {!! $lang['flag'] ?? '' !!}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-four-tabContent">
                            @foreach($languages as $code => $lang)
                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                     id="custom-tabs-four-{{ $code }}" 
                                     role="tabpanel" 
                                     aria-labelledby="custom-tabs-four-{{ $code }}-tab">
                                    <div class="form-group">
                                        <label for="name_{{ $code }}">Shift Name ({{ strtoupper($code) }}) 
                                            @if($code == config('app.fallback_locale', 'en')) <span class="text-danger">*</span> @endif
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="name_{{ $code }}" 
                                               name="name_{{ $code }}" 
                                               value="{{ old('name_'.$code) }}" 
                                               {{ $code == config('app.fallback_locale', 'en') ? 'required' : '' }}>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <label class="h5">Sessions <span class="text-danger">*</span></label>
                    <div class="card p-3 bg-light">
                        <div class="row font-weight-bold mb-2">
                            <div class="col-md-4">Session Name</div>
                            <div class="col-md-3">Start Time</div>
                            <div class="col-md-3">End Time</div>
                            <div class="col-md-2"></div>
                        </div>
                        <div id="sessions-container">
                            <!-- Initial Sessions -->
                            @if(old('sessions'))
                                @foreach(old('sessions') as $key => $session)
                                    <div class="session-row row align-items-center mb-2">
                                        <div class="col-md-4">
                                            <input type="text" name="sessions[{{ $key }}][name]" class="form-control" placeholder="Session Name" value="{{ $session['name'] }}">
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group date timepicker" id="start_time_{{ $key }}" data-target-input="nearest">
                                                <input type="text" name="sessions[{{ $key }}][start]" class="form-control datetimepicker-input" data-target="#start_time_{{ $key }}" value="{{ $session['start'] }}"/>
                                                <div class="input-group-append" data-target="#start_time_{{ $key }}" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="far fa-clock"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                             <div class="input-group date timepicker" id="end_time_{{ $key }}" data-target-input="nearest">
                                                <input type="text" name="sessions[{{ $key }}][end]" class="form-control datetimepicker-input" data-target="#end_time_{{ $key }}" value="{{ $session['end'] }}"/>
                                                <div class="input-group-append" data-target="#end_time_{{ $key }}" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="far fa-clock"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-session-btn" title="Remove"><i class="fas fa-trash-alt"></i></button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="session-row row align-items-center mb-2">
                                    <div class="col-md-4">
                                        <input type="text" name="sessions[0][name]" class="form-control" placeholder="Session Name (e.g. Morning)" value="Session 1">
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group date timepicker" id="start_time_0" data-target-input="nearest">
                                            <input type="text" name="sessions[0][start]" class="form-control datetimepicker-input" data-target="#start_time_0"/>
                                            <div class="input-group-append" data-target="#start_time_0" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="far fa-clock"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group date timepicker" id="end_time_0" data-target-input="nearest">
                                            <input type="text" name="sessions[0][end]" class="form-control datetimepicker-input" data-target="#end_time_0"/>
                                            <div class="input-group-append" data-target="#end_time_0" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="far fa-clock"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-session-btn" title="Remove"><i class="fas fa-trash-alt"></i></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="mt-2">
                             <button type="button" class="btn btn-outline-primary btn-sm" id="add-session-btn">
                                <i class="fas fa-plus-circle"></i> Add New Session
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-4 mb-4">
                    <label class="h5 d-block">Status</label>
                    <div class="custom-control custom-switch custom-switch-md">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="custom-control-label font-weight-bold" for="is_active">Active</label>
                    </div>
                </div>

                <div class="card-footer bg-white p-0 pt-3">
                     <button type="submit" class="btn btn-success float-right"><i class="fas fa-save"></i> Create Shift</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Init Timepicker
        function initTimePickers() {
             $('.timepicker').each(function() {
                 if (!$(this).data('datetimepicker')) {
                     $(this).datetimepicker({
                        format: 'LT'
                     });
                 }
             });
        }
        initTimePickers();

        let sessionIndex = {{ count(old('sessions') ?? [1]) }};

        $('#add-session-btn').on('click', function() {
            const container = $('#sessions-container');
            const startId = `start_time_${sessionIndex}`;
            const endId = `end_time_${sessionIndex}`;
            
            const rowHtml = `
                <div class="session-row row align-items-center mb-2">
                    <div class="col-md-4">
                        <input type="text" name="sessions[${sessionIndex}][name]" class="form-control" placeholder="Session Name (e.g. Session ${sessionIndex + 1})" value="Session ${sessionIndex + 1}">
                    </div>
                    <div class="col-md-3">
                        <div class="input-group date timepicker" id="${startId}" data-target-input="nearest">
                            <input type="text" name="sessions[${sessionIndex}][start]" class="form-control datetimepicker-input" data-target="#${startId}"/>
                            <div class="input-group-append" data-target="#${startId}" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="far fa-clock"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group date timepicker" id="${endId}" data-target-input="nearest">
                            <input type="text" name="sessions[${sessionIndex}][end]" class="form-control datetimepicker-input" data-target="#${endId}"/>
                            <div class="input-group-append" data-target="#${endId}" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="far fa-clock"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-session-btn" title="Remove"><i class="fas fa-trash-alt"></i></button>
                    </div>
                </div>
            `;
            
            container.append(rowHtml);
            
            // Init new pickers
            $(`#${startId}`).datetimepicker({ format: 'LT' });
            $(`#${endId}`).datetimepicker({ format: 'LT' });
            
            sessionIndex++;
        });

        $('#sessions-container').on('click', '.remove-session-btn', function() {
             // If this is the only row, don't remove, or clear values?
             // Usually it's better to allow removing but warn if 0 sessions.
             // But backend requires sessions array.
             // If user removes all, maybe show a "Add Session" placeholder or validaton error on submit.
             // For now, allow removing.
            $(this).closest('.session-row').remove();
        });
    });
</script>
@endsection

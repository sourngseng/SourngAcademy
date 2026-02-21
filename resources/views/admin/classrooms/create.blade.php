@extends('admin.layouts.master')

@section('title', 'Add New Classroom')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-plus-circle mr-2"></i> Add New Classroom</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.classrooms.index') }}" class="btn btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mx-auto">
            <div class="card card-primary card-outline shadow">
                <div class="card-header bg-white">
                    <h3 class="card-title">Classroom Details</h3>
                </div>
                <form action="{{ route('admin.classrooms.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- Image Section -->
                            <div class="col-md-3 text-center">
                                <label>Classroom Image</label>
                                <div class="position-relative mb-3" style="cursor: pointer;" onclick="document.getElementById('featureImage').click();">
                                    <div id="featurePreviewContainer" class="border rounded shadow-sm bg-light d-flex align-items-center justify-content-center" style="height: 200px; width: 100%;">
                                        <div class="text-center">
                                            <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                            <p class="text-muted mb-0">Browse Image</p>
                                            <small class="text-muted">JPG, PNG, GIF (Max 2MB)</small>
                                        </div>
                                    </div>
                                    <img id="featurePreview" src="" class="img-fluid rounded shadow-sm border w-100" style="height: 200px; object-fit: cover; display: none;">
                                </div>
                                <input type="file" name="image" class="d-none" id="featureImage" accept="image/*">
                                <small class="text-muted mt-2 d-block">Click box to upload image</small>
                            </div>

                            <!-- Basic Info Section -->
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="campus_id">Campus <span class="text-danger">*</span></label>
                                            <select name="campus_id" id="campus_id" class="form-control @error('campus_id') is-invalid @enderror" required>
                                                <option value="">Select Campus</option>
                                                @foreach($campuses as $campus)
                                                    <option value="{{ $campus->id }}" {{ old('campus_id') == $campus->id ? 'selected' : '' }}>{{ $campus->name_en }}</option>
                                                @endforeach
                                            </select>
                                            @error('campus_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="room_number">Room Number <span class="text-danger">*</span></label>
                                            <input type="text" name="room_number" id="room_number" class="form-control @error('room_number') is-invalid @enderror" value="{{ old('room_number') }}" required placeholder="e.g. 101">
                                            @error('room_number') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="type">Room Type <span class="text-danger">*</span></label>
                                            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                                <option value="Classroom" {{ old('type') == 'Classroom' ? 'selected' : '' }}>Classroom</option>
                                                <option value="Lecture Hall" {{ old('type') == 'Lecture Hall' ? 'selected' : '' }}>Lecture Hall</option>
                                                <option value="Lab" {{ old('type') == 'Lab' ? 'selected' : '' }}>Lab</option>
                                                <option value="Online" {{ old('type') == 'Online' ? 'selected' : '' }}>Online</option>
                                            </select>
                                            @error('type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="building">Building</label>
                                            <input type="text" name="building" id="building" class="form-control" value="{{ old('building') }}" placeholder="e.g. Block A">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="floor">Floor</label>
                                            <input type="text" name="floor" id="floor" class="form-control" value="{{ old('floor') }}" placeholder="e.g. 1st Floor">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="capacity">Capacity</label>
                                            <input type="number" name="capacity" id="capacity" class="form-control" value="{{ old('capacity') }}" placeholder="e.g. 30">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                <input type="checkbox" name="status" class="custom-control-input" id="isActiveSwitch" value="1" checked>
                                                <label class="custom-control-label" for="isActiveSwitch">Active</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card card-primary card-outline card-outline-tabs mt-3">
                                    <div class="card-header p-0 border-bottom-0">
                                        <ul class="nav nav-tabs" id="language-tabs" role="tablist">
                                            @foreach(config('app.available_locales') as $locale => $lang)
                                                <li class="nav-item">
                                                    <a class="nav-link @if($loop->first) active @endif" id="tab-{{ $locale }}" data-toggle="pill" href="#content-{{ $locale }}" role="tab" aria-controls="content-{{ $locale }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                                        {{ $lang['flag'] }} {{ $lang['name'] }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content" id="language-tab-content">
                                            @foreach(config('app.available_locales') as $locale => $lang)
                                                <div class="tab-pane fade @if($loop->first) show active @endif" id="content-{{ $locale }}" role="tabpanel" aria-labelledby="tab-{{ $locale }}">
                                                    <div class="form-group">
                                                        <label>Room Name ({{ $lang['name'] }})</label>
                                                        <input type="text" name="translations[{{ $locale }}][name]" class="form-control" placeholder="e.g. Science Lab" value="{{ old('translations.'.$locale.'.name') }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Description ({{ $lang['name'] }})</label>
                                                        <textarea name="translations[{{ $locale }}][description]" class="form-control" rows="3" placeholder="Detailed description...">{{ old('translations.'.$locale.'.description') }}</textarea>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light text-right">
                        <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                            <i class="fas fa-save mr-1"></i> Save Classroom
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
    $(document).ready(function () {
        // Image Preview
        $("#featureImage").change(function () {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#featurePreview').attr('src', e.target.result).css('display', 'block');
                    $('#featurePreviewContainer').removeClass('d-flex').hide();
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
@endpush

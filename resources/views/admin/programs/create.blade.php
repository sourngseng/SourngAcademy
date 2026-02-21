@extends('admin.layouts.master')

@section('title', __('Add New Program'))

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-plus-circle mr-2"></i> {{ __('Add New') }}</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.programs.index') }}" class="btn btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left mr-1"></i> {{ __('Back to List') }}
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
                    <h3 class="card-title">{{ __('Program Details & Media') }}</h3>
                </div>
                <form action="{{ route('admin.programs.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- Feature Image Section -->
                            <div class="col-md-4 text-center">
                                <label>{{ __('Program Thumbnail (Feature Image)') }}</label>
                                <div class="position-relative mb-3" style="cursor: pointer;" onclick="document.getElementById('featureImage').click();">
                                    <div id="featurePreviewContainer" class="border rounded shadow-sm bg-light d-flex align-items-center justify-content-center" style="height: 200px; width: 100%;">
                                        <div class="text-center">
                                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                                            <p class="text-muted mb-0">{{ __('Browse Feature Image') }}</p>
                                            <small class="text-muted">{{ __('JPG, PNG, GIF (Max 2MB)') }}</small>
                                        </div>
                                    </div>
                                    <img id="featurePreview" src="" class="img-fluid rounded shadow-sm border w-100" style="height: 200px; object-fit: cover; display: none;">
                                </div>
                                <input type="file" name="feature_image" class="d-none" id="featureImage" accept="image/*">
                                <small class="text-muted mt-2 d-block">{{ __('Click box to upload image') }}</small>
                            </div>

                            <!-- Basic Info Section -->
                            <div class="col-md-8">
                                <!-- Language Tabs -->
                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                    @foreach(config('app.available_locales', ['en' => 'English', 'km' => 'Khmer']) as $locale => $localeConfig)
                                        @php
                                            $localeName = is_array($localeConfig) ? $localeConfig['name'] : $localeConfig;
                                        @endphp
                                        <li class="nav-item">
                                            <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ $locale }}-tab" data-toggle="pill" href="#tab-{{ $locale }}" role="tab" aria-controls="tab-{{ $locale }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                                <i class="fas fa-globe mr-1"></i> {{ strtoupper($locale) }} - {{ $localeName }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="tab-content border rounded p-3 bg-light mb-3" id="pills-tabContent">
                                    @foreach(config('app.available_locales', ['en' => 'English', 'km' => 'Khmer']) as $locale => $localeConfig)
                                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-{{ $locale }}" role="tabpanel" aria-labelledby="{{ $locale }}-tab">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        @php
                                                            $localeName = is_array($localeConfig) ? $localeConfig['name'] : $localeConfig;
                                                        @endphp
                                                        <label>Program Name ({{ $localeName }}) <span class="text-danger">*</span></label>
                                                        <input type="text" name="translations[{{ $locale }}][name]" class="form-control @error('translations.'.$locale.'.name') is-invalid @enderror" value="{{ old('translations.'.$locale.'.name') }}" placeholder="Enter program name in {{ $localeName }}" {{ $loop->first ? 'required' : '' }}>
                                                        @error('translations.'.$locale.'.name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Program Description ({{ $localeName }})</label>
                                                        <textarea name="translations[{{ $locale }}][description]" class="form-control" rows="4" placeholder="Enter program description in {{ $localeName }}">{{ old('translations.'.$locale.'.description') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="department_id">{{ __('Department') }} <span class="text-danger">*</span></label>
                                            <select name="department_id" id="department_id" class="form-control select2 @error('department_id') is-invalid @enderror" required>
                                                <option value="">{{ __('Select Department') }}</option>
                                                @foreach($departments as $department)
                                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                        {{ $department->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('department_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="code">{{ __('Program Code') }}</label>
                                            <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="e.g. CS-BS">
                                            @error('code') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="degree_level_id">{{ __('Degree Level') }} <span class="text-danger">*</span></label>
                                            <select name="degree_level_id" id="degree_level_id" class="form-control select2 @error('degree_level_id') is-invalid @enderror" required>
                                                <option value="">{{ __('Select Level') }}</option>
                                                @foreach($degreeLevels as $level)
                                                    <option value="{{ $level->id }}" {{ old('degree_level_id') == $level->id ? 'selected' : '' }}>
                                                        {{ $level->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('degree_level_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="duration_years">{{ __('Duration (Years)') }} <span class="text-danger">*</span></label>
                                            <input type="number" name="duration_years" id="duration_years" class="form-control @error('duration_years') is-invalid @enderror" value="{{ old('duration_years', 4) }}" min="1" required>
                                            @error('duration_years') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="total_credits">{{ __('Total Credits') }} <span class="text-danger">*</span></label>
                                            <input type="number" name="total_credits" id="total_credits" class="form-control @error('total_credits') is-invalid @enderror" value="{{ old('total_credits', 120) }}" min="1" required>
                                            @error('total_credits') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <div class="custom-control custom-switch custom-switch-off-light custom-switch-on-success">
                                            <input type="checkbox" name="is_popular" class="custom-control-input" id="isPopular" {{ old('is_popular') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="isPopular">{{ __('Mark as Popular Program (Will be featured on homepage)') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        
                        <!-- Gallery Section -->
                        <div class="form-group">
                            <label><i class="fas fa-images mr-2 text-info"></i> {{ __('Program Gallery Images') }}</label>
                            <div class="custom-file">
                                <input type="file" name="gallery_images[]" class="custom-file-input" id="galleryImages" multiple accept="image/*">
                                <label class="custom-file-label" for="galleryImages">{{ __('Select Multiple Images') }}</label>
                            </div>
                            <div id="galleryPreview" class="row mt-4"></div>
                        </div>
                    </div>
                    <div class="card-footer bg-light text-right">
                        <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                            <i class="fas fa-save mr-1"></i> {{ __('Save Program') }}
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
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });

        // Feature Image Preview
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

        // Gallery Preview
        $("#galleryImages").change(function () {
            $('#galleryPreview').html('');
            let files = this.files;
            for (let i = 0; i < files.length; i++) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#galleryPreview').append(`
                        <div class="col-md-2 mb-3">
                            <div class="position-relative">
                                <img src="${e.target.result}" class="img-fluid rounded border shadow-sm" style="height: 100px; width: 100%; object-fit: cover;">
                            </div>
                        </div>
                    `);
                }
                reader.readAsDataURL(files[i]);
            }
            let fileCount = files.length;
            $(this).next('.custom-file-label').addClass("selected").html(fileCount + " images selected");
        });
    });
</script>
@endpush

@extends('admin.layouts.master')

@section('title', 'Add New Subject')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-plus-circle mr-2"></i> Add New Subject</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary shadow-sm">
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
                    <h3 class="card-title">Subject Details & Media</h3>
                </div>
                <form action="{{ route('admin.subjects.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- Feature Image Section -->
                            <div class="col-md-4 text-center">
                                <label>Subject Thumbnail (Feature Image)</label>
                                <div class="position-relative mb-3" style="cursor: pointer;" onclick="document.getElementById('featureImage').click();">
                                    <div id="featurePreviewContainer" class="border rounded shadow-sm bg-light d-flex align-items-center justify-content-center" style="height: 200px; width: 100%;">
                                        <div class="text-center">
                                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                                            <p class="text-muted mb-0">Browse Feature Image</p>
                                            <small class="text-muted">JPG, PNG, GIF (Max 2MB)</small>
                                        </div>
                                    </div>
                                    <img id="featurePreview" src="" class="img-fluid rounded shadow-sm border w-100" style="height: 200px; object-fit: cover; display: none;">
                                </div>
                                <input type="file" name="feature_image" class="d-none" id="featureImage" accept="image/*">
                                <small class="text-muted mt-2 d-block">Click box to upload image</small>
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
                                        @php
                                            $localeName = is_array($localeConfig) ? $localeConfig['name'] : $localeConfig;
                                        @endphp
                                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-{{ $locale }}" role="tabpanel" aria-labelledby="{{ $locale }}-tab">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Subject Name ({{ $localeName }}) <span class="text-danger">*</span></label>
                                                        <input type="text" name="translations[{{ $locale }}][name]" class="form-control @error('translations.'.$locale.'.name') is-invalid @enderror" value="{{ old('translations.'.$locale.'.name') }}" placeholder="Enter subject name in {{ $localeName }}" {{ $loop->first ? 'required' : '' }}>
                                                        @error('translations.'.$locale.'.name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Subject Description ({{ $localeName }})</label>
                                                        <textarea name="translations[{{ $locale }}][description]" class="form-control" rows="4" placeholder="Enter subject description in {{ $localeName }}">{{ old('translations.'.$locale.'.description') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="program_id">Program <span class="text-danger">*</span></label>
                                            <select name="program_id" id="program_id" class="form-control select2 @error('program_id') is-invalid @enderror" required>
                                                <option value="">Select Program</option>
                                                @foreach($programs as $program)
                                                    <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>
                                                        {{ $program->getTranslatedName() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('program_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="code">Subject Code <span class="text-danger">*</span></label>
                                            <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="e.g. CS201" required>
                                            @error('code') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type">Subject Type <span class="text-danger">*</span></label>
                                            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                                <option value="Core" {{ old('type') == 'Core' ? 'selected' : '' }}>Core</option>
                                                <option value="Elective" {{ old('type') == 'Elective' ? 'selected' : '' }}>Elective</option>
                                                <option value="General" {{ old('type') == 'General' ? 'selected' : '' }}>General</option>
                                            </select>
                                            @error('type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="credit">Credits <span class="text-danger">*</span></label>
                                            <input type="number" name="credit" id="credit" class="form-control @error('credit') is-invalid @enderror" value="{{ old('credit', 3) }}" min="0" step="0.5" required>
                                            @error('credit') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        
                        <!-- Gallery Section -->
                        <div class="form-group">
                            <label><i class="fas fa-images mr-2 text-info"></i> Subject Gallery Images</label>
                            <div class="custom-file">
                                <input type="file" name="gallery_images[]" class="custom-file-input" id="galleryImages" multiple accept="image/*">
                                <label class="custom-file-label" for="galleryImages">Select Multiple Images</label>
                            </div>
                            <div id="galleryPreview" class="row mt-4"></div>
                        </div>
                    </div>
                    <div class="card-footer bg-light text-right">
                        <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                            <i class="fas fa-save mr-1"></i> Save Subject
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

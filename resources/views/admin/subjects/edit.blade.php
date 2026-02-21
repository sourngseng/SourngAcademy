@extends('admin.layouts.master')

@section('title', 'Edit Subject - ' . $subject->name)

@push('styles')
<style>
    .gallery-item {
        position: relative;
        margin-bottom: 20px;
    }
    .gallery-item img {
        height: 120px;
        width: 100%;
        object-fit: cover;
        border-radius: 8px;
        transition: opacity 0.3s;
    }
    .gallery-item.to-delete img {
        opacity: 0.3;
        filter: grayscale(1);
    }
    .delete-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: none;
        color: #dc3545;
        font-weight: bold;
    }
    .gallery-item.to-delete .delete-overlay {
        display: block;
    }
    .btn-delete-img {
        position: absolute;
        top: 5px;
        right: 5px;
        z-index: 10;
    }
</style>
@endpush

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-edit mr-2"></i> Edit Subject</h1>
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
            <div class="card card-warning card-outline shadow">
                <div class="card-header bg-white">
                    <h3 class="card-title">Subject Details & Media: <strong>{{ $subject->name }}</strong></h3>
                </div>
                <form action="{{ route('admin.subjects.update', $subject->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <!-- Feature Image Section -->
                            <div class="col-md-4 text-center">
                                <label>Subject Thumbnail (Feature Image)</label>
                                <div class="position-relative mb-3" style="cursor: pointer;" onclick="document.getElementById('featureImage').click();">
                                    @if($subject->feature_image)
                                        <img id="featurePreview" src="{{ asset($subject->feature_image) }}" class="img-fluid rounded shadow-sm border w-100" style="height: 200px; object-fit: cover;">
                                        <div class="position-absolute change-overlay" style="top: 50%; left: 50%; transform: translate(-50%, -50%); opacity: 0; transition: opacity 0.3s;">
                                            <div class="bg-dark text-white px-3 py-2 rounded">
                                                <i class="fas fa-camera mr-2"></i>Change Image
                                            </div>
                                        </div>
                                    @else
                                        <div id="featurePreviewContainer" class="border rounded shadow-sm bg-light d-flex align-items-center justify-content-center" style="height: 200px; width: 100%;">
                                            <div class="text-center">
                                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                                                <p class="text-muted mb-0">Browse Feature Image</p>
                                                <small class="text-muted">JPG, PNG, GIF (Max 2MB)</small>
                                            </div>
                                        </div>
                                        <img id="featurePreview" src="" class="img-fluid rounded shadow-sm border w-100" style="height: 200px; object-fit: cover; display: none;">
                                    @endif
                                </div>
                                <input type="file" name="feature_image" class="d-none" id="featureImage" accept="image/*">
                                <small class="text-muted mt-2 d-block">Click image to change</small>
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
                                            $trans = $subject->translations[$locale] ?? [];
                                        @endphp
                                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-{{ $locale }}" role="tabpanel" aria-labelledby="{{ $locale }}-tab">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Subject Name ({{ $localeName }}) <span class="text-danger">*</span></label>
                                                        <input type="text" name="translations[{{ $locale }}][name]" class="form-control @error('translations.'.$locale.'.name') is-invalid @enderror" value="{{ old('translations.'.$locale.'.name', $trans['name'] ?? ($loop->first ? $subject->name : '')) }}" placeholder="Enter subject name in {{ $localeName }}" {{ $loop->first ? 'required' : '' }}>
                                                        @error('translations.'.$locale.'.name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Subject Description ({{ $localeName }})</label>
                                                        <textarea name="translations[{{ $locale }}][description]" class="form-control" rows="4" placeholder="Enter subject description in {{ $localeName }}">{{ old('translations.'.$locale.'.description', $trans['description'] ?? ($loop->first ? $subject->description : '')) }}</textarea>
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
                                                    <option value="{{ $program->id }}" {{ (old('program_id', $subject->program_id) == $program->id) ? 'selected' : '' }}>
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
                                            <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $subject->code) }}" required>
                                            @error('code') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type">Subject Type <span class="text-danger">*</span></label>
                                            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                                <option value="Core" {{ (old('type', $subject->type) == 'Core') ? 'selected' : '' }}>Core</option>
                                                <option value="Elective" {{ (old('type', $subject->type) == 'Elective') ? 'selected' : '' }}>Elective</option>
                                                <option value="General" {{ (old('type', $subject->type) == 'General') ? 'selected' : '' }}>General</option>
                                            </select>
                                            @error('type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="credit">Credits <span class="text-danger">*</span></label>
                                            <input type="number" name="credit" id="credit" class="form-control @error('credit') is-invalid @enderror" value="{{ old('credit', $subject->credit) }}" min="0" step="0.5" required>
                                            @error('credit') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        
                        <!-- Existing Gallery -->
                        <div class="form-group">
                            <label><i class="fas fa-th mr-2 text-info"></i> Current Gallery</label>
                            @if($subject->images->count() > 0)
                                <p class="small text-muted mb-3">Click the red <i class="fas fa-times"></i> to mark an image for deletion upon saving.</p>
                                <div class="row">
                                    @foreach($subject->images as $image)
                                        <div class="col-md-2 gallery-item" id="gallery-img-{{ $image->id }}">
                                            <button type="button" class="btn btn-danger btn-xs btn-delete-img" onclick="toggleImageDelete({{ $image->id }})">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <img src="{{ asset($image->image_path) }}" class="shadow-sm border">
                                            <div class="delete-overlay"><i class="fas fa-trash-alt fa-2x"></i></div>
                                            <input type="hidden" name="delete_images[]" id="del-input-{{ $image->id }}" value="" disabled>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted italic">No images in gallery yet.</p>
                            @endif
                        </div>

                        <!-- Add to Gallery -->
                        <div class="form-group">
                            <label><i class="fas fa-plus-circle mr-2 text-success"></i> Add New Images to Gallery</label>
                            <div class="custom-file">
                                <input type="file" name="gallery_images[]" class="custom-file-input" id="galleryImages" multiple accept="image/*">
                                <label class="custom-file-label" for="galleryImages">Select Images</label>
                            </div>
                            <div id="galleryPreview" class="row mt-4"></div>
                        </div>
                    </div>
                    <div class="card-footer bg-light text-right">
                        <button type="submit" class="btn btn-warning shadow-sm text-dark btn-lg px-5">
                            <i class="fas fa-save mr-1"></i> Update Subject
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
    function toggleImageDelete(id) {
        const item = $('#gallery-img-' + id);
        const input = $('#del-input-' + id);
        
        if (item.hasClass('to-delete')) {
            item.removeClass('to-delete');
            input.attr('disabled', true).val('');
        } else {
            item.addClass('to-delete');
            input.attr('disabled', false).val(id);
        }
    }

    $(document).ready(function () {
        // Feature Image Hover
        $('.position-relative').hover(
            function() { $(this).find('.change-overlay').css('opacity', '1'); },
            function() { $(this).find('.change-overlay').css('opacity', '0'); }
        );

        // Feature Image Preview
        $("#featureImage").change(function () {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#featurePreview').attr('src', e.target.result).css('display', 'block');
                    $('#featurePreviewContainer').removeClass('d-flex').hide();
                    $('.change-overlay').hide();
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

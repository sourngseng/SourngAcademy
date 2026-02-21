@extends('admin.layouts.master')

@section('title', 'Edit Slide')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-edit mr-2"></i> {{ __('Edit Slide') }}</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.slides.index') }}" class="btn btn-secondary shadow-sm">
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
                    <h3 class="card-title">{{ __('Slide Details & Media') }} <span class="text-muted small">#{{ $slide->id }}</span></h3>
                    <div class="card-tools">
                        <form action="{{ route('admin.slides.destroy', $slide->id) }}" method="POST" onsubmit="return confirm('Delete this slide?')" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-tool" title="Delete"><i class="fas fa-trash text-danger"></i></button>
                        </form>
                    </div>
                </div>
                <form action="{{ route('admin.slides.update', $slide->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <!-- Image Section -->
                            <div class="col-md-4 text-center">
                                <label>{{ __('Slide Image') }} <span class="text-danger">*</span></label>
                                <div class="position-relative mb-3" style="cursor: pointer;" onclick="document.getElementById('image').click();">
                                    <div id="imagePreviewContainer" class="border rounded shadow-sm bg-light d-flex align-items-center justify-content-center" style="height: 200px; width: 100%; {{ $slide->image_path ? 'display: none !important;' : '' }}">
                                        <div class="text-center">
                                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                                            <p class="text-muted mb-0">{{ __('Browse Image') }}</p>
                                            <small class="text-muted">{{ __('1920x600px recommended') }}</small>
                                        </div>
                                    </div>
                                    <img id="imagePreview" src="{{ $slide->image_path ? asset($slide->image_path) : '' }}" class="img-fluid rounded shadow-sm border w-100" style="height: 200px; object-fit: cover; {{ $slide->image_path ? '' : 'display: none;' }}">
                                </div>
                                <input type="file" name="image" class="d-none" id="image" accept="image/*">
                                <small class="text-muted mt-2 d-block">{{ __('Click box to upload new image') }}</small>

                                <div class="form-group mt-4 text-left">
                                    <label>{{ __('Sort Order') }}</label>
                                    <input type="number" name="order" class="form-control" value="{{ old('order', $slide->order) }}">
                                </div>

                                <div class="form-group text-left">
                                    <div class="custom-control custom-switch custom-switch-off-light custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" {{ old('is_active', $slide->is_active) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">{{ __('Active Status') }}</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Info Section -->
                            <div class="col-md-8">
                                <!-- Language Tabs -->
                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                    @foreach($languages as $locale => $lang)
                                        <li class="nav-item">
                                            <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ $locale }}-tab" data-toggle="pill" href="#tab-{{ $locale }}" role="tab" aria-controls="tab-{{ $locale }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                                <i class="fas fa-globe mr-1"></i> {{ strtoupper($locale) }} - {{ $lang['name'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="tab-content border rounded p-3 bg-light mb-3" id="pills-tabContent">
                                    @foreach($languages as $locale => $lang)
                                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-{{ $locale }}" role="tabpanel" aria-labelledby="{{ $locale }}-tab">
                                            <div class="form-group">
                                                <label>Title ({{ strtoupper($locale) }}) @if($loop->first) <span class="text-danger">*</span> @endif</label>
                                                <input type="text" name="title[{{ $locale }}]" class="form-control" value="{{ old('title.'.$locale, isset($slide->title[$locale]) ? $slide->title[$locale] : '') }}" placeholder="Enter title in {{ $lang['name'] }}" {{ $loop->first ? 'required' : '' }}>
                                            </div>
                                            <div class="form-group">
                                                <label>Subtitle ({{ strtoupper($locale) }})</label>
                                                <input type="text" name="subtitle[{{ $locale }}]" class="form-control" value="{{ old('subtitle.'.$locale, isset($slide->subtitle[$locale]) ? $slide->subtitle[$locale] : '') }}" placeholder="Enter subtitle in {{ $lang['name'] }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Description ({{ strtoupper($locale) }})</label>
                                                <textarea name="description[{{ $locale }}]" class="form-control" rows="3" placeholder="Enter description in {{ $lang['name'] }}">{{ old('description.'.$locale, isset($slide->description[$locale]) ? $slide->description[$locale] : '') }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Button Text ({{ strtoupper($locale) }})</label>
                                                <input type="text" name="button_text[{{ $locale }}]" class="form-control" value="{{ old('button_text.'.$locale, isset($slide->button_text[$locale]) ? $slide->button_text[$locale] : '') }}" placeholder="e.g. {{ $locale == 'km' ? 'អានបន្ថែម' : 'Read More' }}">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ __('Link URL') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-link"></i></span>
                                                </div>
                                                <input type="url" name="link_url" class="form-control" value="{{ old('link_url', $slide->link_url) }}" placeholder="https://example.com/promotion">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="mb-2">{{ __('Display Options') }}</label>
                                        <div class="d-flex flex-wrap">
                                            <div class="custom-control custom-switch mr-4 mb-2">
                                                <input type="checkbox" class="custom-control-input" id="show_title" name="show_title" {{ old('show_title', $slide->show_title) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="show_title">Show Title</label>
                                            </div>
                                            <div class="custom-control custom-switch mr-4 mb-2">
                                                <input type="checkbox" class="custom-control-input" id="show_subtitle" name="show_subtitle" {{ old('show_subtitle', $slide->show_subtitle) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="show_subtitle">Show Subtitle</label>
                                            </div>
                                            <div class="custom-control custom-switch mr-4 mb-2">
                                                <input type="checkbox" class="custom-control-input" id="show_description" name="show_description" {{ old('show_description', $slide->show_description) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="show_description">Show Description</label>
                                            </div>
                                            <div class="custom-control custom-switch mr-4 mb-2">
                                                <input type="checkbox" class="custom-control-input" id="show_button" name="show_button" {{ old('show_button', $slide->show_button) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="show_button">Show Button</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light text-right">
                        <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                            <i class="fas fa-save mr-1"></i> {{ __('Save Changes') }}
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
        $("#image").change(function () {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#imagePreview').attr('src', e.target.result).css('display', 'block');
                    $('#imagePreviewContainer').removeClass('d-flex').hide();
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
@endpush

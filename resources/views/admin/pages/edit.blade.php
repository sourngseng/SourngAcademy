@extends('admin.layouts.master')

@section('title', 'Edit Page')

@push('styles')
<link rel="stylesheet" href="{{ asset('backend/plugins/summernote/summernote-bs4.min.css') }}">
<style>
    .card-primary.card-outline {
        border-top: 3px solid #007bff;
    }
</style>
@endpush

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-edit mr-2"></i> Edit Page: <span class="text-primary">{{ $page->getLocalizedTitleAttribute() }}</span></h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to List
                </a>
                <a href="{{ url('/page/' . $page->slug) }}" target="_blank" class="btn btn-info shadow-sm ml-2">
                    <i class="fas fa-external-link-alt mr-1"></i> View Page
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="container-fluid">
    <form action="{{ route('admin.pages.update', $page->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <!-- Main Content -->
            <div class="col-md-8">
                <!-- Content Card -->
                <div class="card card-primary card-outline shadow">
                    <div class="card-header bg-white">
                        <h3 class="card-title"><i class="fas fa-file-alt mr-2 text-primary"></i> Page Content</h3>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Language Tabs -->
                        <ul class="nav nav-pills mb-3" id="languageTabs" role="tablist">
                            @foreach($languages as $locale => $langData)
                                <li class="nav-item">
                                    <a class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                       id="{{ $locale }}-tab" 
                                       data-toggle="pill" 
                                       href="#lang-{{ $locale }}" 
                                       role="tab">
                                        {{ $langData['flag'] }} {{ $langData['name'] }}
                                        @if($locale === 'en') <span class="text-danger">*</span> @endif
                                        @if(isset($titles[$locale]) && !empty($titles[$locale]))
                                            <i class="fas fa-check-circle text-success ml-1 small"></i>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content" id="languageTabsContent">
                            @foreach($languages as $locale => $langData)
                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                     id="lang-{{ $locale }}" 
                                     role="tabpanel">
                                    <div class="form-group">
                                        <label for="title_{{ $locale }}">
                                            Title ({{ $langData['name'] }})
                                            @if($locale === 'en') <span class="text-danger">*</span> @endif
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('title.'.$locale) is-invalid @enderror" 
                                               id="title_{{ $locale }}" 
                                               name="title[{{ $locale }}]" 
                                               value="{{ old('title.'.$locale, $titles[$locale] ?? '') }}"
                                               placeholder="Enter page title"
                                               {{ $locale === 'en' ? 'required' : '' }}>
                                        @error('title.'.$locale)
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="content_{{ $locale }}">Content ({{ $langData['name'] }})</label>
                                        <textarea class="form-control summernote @error('content.'.$locale) is-invalid @enderror" 
                                                  id="content_{{ $locale }}" 
                                                  name="content[{{ $locale }}]" 
                                                  rows="15">{{ old('content.'.$locale, $contents[$locale] ?? '') }}</textarea>
                                        @error('content.'.$locale)
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- SEO Card -->
                <div class="card card-info card-outline shadow mt-3">
                    <div class="card-header bg-white">
                        <h3 class="card-title"><i class="fas fa-search mr-2 text-info"></i> SEO Configuration</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="meta_title">Meta Title</label>
                            <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" maxlength="255" placeholder="Custom meta title">
                            <small class="text-muted">Leave empty to use page title</small>
                        </div>

                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <textarea class="form-control" id="meta_description" name="meta_description" rows="3" maxlength="500" placeholder="Brief description for search results">{{ old('meta_description', $page->meta_description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Settings -->
            <div class="col-md-4">
                <div class="card card-secondary card-outline shadow">
                    <div class="card-header bg-white">
                        <h3 class="card-title"><i class="fas fa-cogs mr-2 text-secondary"></i> Page Settings</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="slug">URL Slug</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-link"></i></span>
                                </div>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                       id="slug" name="slug" value="{{ old('slug', $page->slug) }}">
                            </div>
                             @error('slug') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="icon">Page Icon</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="icon-preview">
                                        <i class="{{ $page->icon ?: 'fas fa-file-alt' }}"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                       id="icon" name="icon" value="{{ old('icon', $page->icon) }}" 
                                       placeholder="fas fa-file-alt">
                            </div>
                             <small class="text-muted">Font Awesome class (e.g., fas fa-home)</small>
                        </div>

                        <div class="form-group">
                            <label for="sort_order">Sort Order</label>
                             <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-sort-numeric-down"></i></span>
                                </div>
                                <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $page->sort_order) }}" min="0">
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label>Visibility</label>
                            <div class="custom-control custom-switch mt-2">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $page->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Publish Page</label>
                            </div>
                            <div class="custom-control custom-switch mt-2">
                                <input type="checkbox" class="custom-control-input" id="show_in_menu" name="show_in_header" value="1" {{ old('show_in_header', $page->show_in_header) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="show_in_menu">Show in Header Menu</label>
                            </div>
                             <div class="custom-control custom-switch mt-2">
                                <input type="checkbox" class="custom-control-input" id="show_in_footer" name="show_in_footer" value="1" {{ old('show_in_footer', $page->show_in_footer) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="show_in_footer">Show in Footer Links</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card shadow-none bg-transparent">
                     <button type="submit" class="btn btn-primary btn-lg shadow">
                        <i class="fas fa-save mr-1"></i> Update Page
                    </button>
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-default btn-lg mt-2 shadow-sm">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </a>
                </div>

                <!-- Info Card -->
                 <div class="card card-outline card-info shadow-sm mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Information</h3>
                    </div>
                     <div class="card-body p-3">
                        <dl class="mb-0">
                            <dt>Created At</dt>
                            <dd>{{ $page->created_at->format('d M Y, h:i A') }}</dd>
                            <dt>Last Updated</dt>
                            <dd>{{ $page->updated_at->format('d M Y, h:i A') }}</dd>
                        </dl>
                     </div>
                 </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('backend/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script>
$(function() {
    // Summernote
    $('.summernote').summernote({
        height: 300,
        placeholder: 'Write your page content here...',
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    // Icon Preview
    $('#icon').on('input', function() {
        var iconClass = $(this).val();
        $('#icon-preview i').attr('class', iconClass ? iconClass : 'fas fa-file-alt');
    });
});
</script>
@endpush

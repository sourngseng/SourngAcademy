@extends('admin.layouts.master')

@section('title', 'System Settings')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-cogs"></i> System Settings</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Settings</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-3">
            <!-- Sidebar Navigation Links -->
            <div class="card">
                <div class="card-body p-0">
                    <ul class="nav nav-pills flex-column" id="settingsTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="general-tab" data-toggle="pill" href="#general" role="tab">
                                <i class="fas fa-university mr-2"></i> General Information
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="branding-tab" data-toggle="pill" href="#branding" role="tab">
                                <i class="fas fa-palette mr-2"></i> Branding & Appearance
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="content-tab" data-toggle="pill" href="#content" role="tab">
                                <i class="fas fa-file-alt mr-2"></i> Content & Translations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-toggle="pill" href="#contact" role="tab">
                                <i class="fas fa-address-book mr-2"></i> Contact & Socials
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="ads-tab" data-toggle="pill" href="#ads" role="tab">
                                <i class="fas fa-ad mr-2"></i> Ads Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="about-tab" data-toggle="pill" href="#about" role="tab">
                                <i class="fas fa-info-circle mr-2"></i> About Page
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="advanced-tab" data-toggle="pill" href="#advanced" role="tab">
                                <i class="fas fa-tools mr-2"></i> Advanced Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="social-auth-tab" data-toggle="pill" href="#social-auth" role="tab">
                                <i class="fas fa-users-cog mr-2"></i> Social Authentication
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="tab-content" id="settingsTabsContent">
                    <!-- General Settings -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">General Information</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="university_name">University Name</label>
                                            <input type="text" name="university_name" id="university_name" class="form-control" value="{{ $settings['university_name'] ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="university_short_name">Short Name / Abbreviation</label>
                                            <input type="text" name="university_short_name" id="university_short_name" class="form-control" value="{{ $settings['university_short_name'] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="university_address">Official Address</label>
                                            <textarea name="university_address" id="university_address" class="form-control" rows="3">{{ $settings['university_address'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="primary_font">Primary Font (Latin)</label>
                                            <select name="primary_font" class="form-control select2">
                                                <option value="'Source Sans Pro', sans-serif" {{ ($settings['primary_font'] ?? '') == "'Source Sans Pro', sans-serif" ? 'selected' : '' }}>Source Sans Pro</option>
                                                <option value="'Inter', sans-serif" {{ ($settings['primary_font'] ?? '') == "'Inter', sans-serif" ? 'selected' : '' }}>Inter</option>
                                                <option value="'Roboto', sans-serif" {{ ($settings['primary_font'] ?? '') == "'Roboto', sans-serif" ? 'selected' : '' }}>Roboto</option>
                                                <option value="'Outfit', sans-serif" {{ ($settings['primary_font'] ?? '') == "'Outfit', sans-serif" ? 'selected' : '' }}>Outfit (Premium Look)</option>
                                            </select>
                                            <small class="text-muted">Applied to all English text across the system.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="khmer_font">Khmer Font</label>
                                            <select name="khmer_font" class="form-control select2">
                                                <option value="'Kantumruy Pro', sans-serif" {{ ($settings['khmer_font'] ?? '') == "'Kantumruy Pro', sans-serif" ? 'selected' : '' }}>Kantumruy Pro</option>
                                                <option value="'Hanuman', serif" {{ ($settings['khmer_font'] ?? '') == "'Hanuman', serif" ? 'selected' : '' }}>Hanuman</option>
                                                <option value="'Noto Sans Khmer', sans-serif" {{ ($settings['khmer_font'] ?? '') == "'Noto Sans Khmer', sans-serif" ? 'selected' : '' }}>Noto Sans Khmer</option>
                                            </select>
                                            <small class="text-muted">Applied to all Khmer text across the system.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <hr>
                                        <h5 class="text-info"><i class="fas fa-calendar-alt mr-2"></i> Timetable & Certificates</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="director_name">Director Name</label>
                                            <input type="text" name="director_name" id="director_name" class="form-control" value="{{ $settings['director_name'] ?? '' }}" placeholder="e.g. Dr. John Doe">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="timetable_issued_date">Default Issued Date</label>
                                            <input type="text" name="timetable_issued_date" id="timetable_issued_date" class="form-control" value="{{ $settings['timetable_issued_date'] ?? date('d-m-Y') }}">
                                            <small class="text-muted">Default date shown on class timetables.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content & Translations Settings -->
                    <div class="tab-pane fade" id="content" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card card-info card-outline">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fas fa-language"></i> Multi-language Support</h3>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted">Manage system-wide translations, add new languages, or update existing translation keys.</p>
                                        <div class="list-group">
                                            <a href="{{ route('admin.languages.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-list mr-2 text-primary"></i> All Languages</span>
                                                <span class="badge badge-primary badge-pill">{{ count(config('app.available_locales', [])) }}</span>
                                            </a>
                                            <a href="{{ route('admin.languages.create') }}" class="list-group-item list-group-item-action">
                                                <i class="fas fa-plus mr-2 text-success"></i> Add New Language
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-warning card-outline">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fas fa-blog"></i> Content Management (Blog)</h3>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted">Quick access to manage your blog posts, announcements, and news categories.</p>
                                        <div class="list-group">
                                            <a href="{{ route('admin.blog.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-newspaper mr-2 text-warning"></i> Blog Posts</span>
                                                <i class="fas fa-external-link-alt text-muted small"></i>
                                            </a>
                                            <a href="{{ route('admin.blog-categories.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-tags mr-2 text-info"></i> Categories</span>
                                                <i class="fas fa-external-link-alt text-muted small"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Branding Settings -->
                    <div class="tab-pane fade" id="branding" role="tabpanel">
                        <div class="card card-info card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Branding & Appearance</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>University Logo</label>
                                            <div class="mt-2 mb-3">
                                                @if(isset($settings['university_logo']))
                                                    <img src="{{ asset($settings['university_logo']) }}" alt="Logo" class="img-thumbnail" style="max-height: 100px;">
                                                @else
                                                    <p class="text-muted">No logo uploaded.</p>
                                                @endif
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" name="university_logo" class="custom-file-input" id="logoFile">
                                                <label class="custom-file-label" for="logoFile">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Favicon</label>
                                            <div class="mt-2 mb-3">
                                                @if(isset($settings['favicon']))
                                                    <img src="{{ asset($settings['favicon']) }}" alt="Favicon" class="img-thumbnail" style="max-height: 50px;">
                                                @else
                                                    <p class="text-muted">No favicon uploaded.</p>
                                                @endif
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" name="favicon" class="custom-file-input" id="faviconFile">
                                                <label class="custom-file-label" for="faviconFile">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Logo (Dark Variant)</label>
                                            <div class="mt-2 mb-3">
                                                @if(isset($settings['logo_dark']))
                                                    <img src="{{ asset($settings['logo_dark']) }}" alt="Dark Logo" class="img-thumbnail" style="max-height: 100px;">
                                                @else
                                                    <p class="text-muted">No dark variant uploaded.</p>
                                                @endif
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" name="logo_dark" class="custom-file-input" id="logoDarkFile">
                                                <label class="custom-file-label" for="logoDarkFile">Choose file</label>
                                            </div>
                                            <small class="text-muted">Dark-colored logo for use on light backgrounds.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Logo (Bright Variant)</label>
                                            <div class="mt-2 mb-3">
                                                @if(isset($settings['logo_bright']))
                                                    <img src="{{ asset($settings['logo_bright']) }}" alt="Bright Logo" class="img-thumbnail bg-dark" style="max-height: 100px;">
                                                @else
                                                    <p class="text-muted">No bright variant uploaded.</p>
                                                @endif
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" name="logo_bright" class="custom-file-input" id="logoBrightFile">
                                                <label class="custom-file-label" for="logoBrightFile">Choose file</label>
                                            </div>
                                            <small class="text-muted">Light-colored logo for use on dark backgrounds.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="primary_color">Primary Color</label>
                                            <input type="color" name="primary_color" class="form-control" value="{{ $settings['primary_color'] ?? '#007bff' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="secondary_color">Secondary Color</label>
                                            <input type="color" name="secondary_color" class="form-control" value="{{ $settings['secondary_color'] ?? '#6c757d' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact & Socials -->
                    <div class="tab-pane fade" id="contact" role="tabpanel">
                        <div class="card card-success card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Contact & Social Media</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="university_email">Official Email</label>
                                            <input type="email" name="university_email" class="form-control" value="{{ $settings['university_email'] ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="university_phone">Contact Phone</label>
                                            <input type="text" name="university_phone" class="form-control" value="{{ $settings['university_phone'] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="social_facebook">Facebook Page URL</label>
                                            <input type="url" name="social_facebook" class="form-control" value="{{ $settings['social_facebook'] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="social_telegram">Telegram Channel</label>
                                            <input type="text" name="social_telegram" class="form-control" value="{{ $settings['social_telegram'] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ads Management -->
                    <div class="tab-pane fade" id="ads" role="tabpanel">
                        <div class="card card-warning card-outline">
                            <div class="card-header p-0 pt-1 border-bottom-0">
                                <ul class="nav nav-tabs" id="ads-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="ads-code-tab" data-toggle="pill" href="#ads-code" role="tab">
                                            <i class="fas fa-code mr-2"></i> Custom Code
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="ads-image-tab" data-toggle="pill" href="#ads-image" role="tab">
                                            <i class="fas fa-image mr-2"></i> Image Banners
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="ads-tabs-content">
                                    <!-- Custom Code Tab -->
                                    <div class="tab-pane fade show active" id="ads-code" role="tabpanel">
                                        <p class="text-muted">Use this section to insert Google AdSense or other HTML/JS ad codes. Make sure to enable the "Code" option for the specific slot you want to use.</p>
                                        
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i> To show these ads, select <strong>"Code"</strong> as the Ad Type for the respective slot in the "Ad Configuration" below.
                                        </div>

                                        <h5 class="mt-4 mb-3 text-info">Blog Post Detail Page</h5>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="blog_ad_top_code">Top Ad Code</label>
                                                    <textarea name="blog_ad_top_code" id="blog_ad_top_code" class="form-control font-monospace" rows="3" placeholder="<!-- Ad Code -->">{{ $settings['blog_ad_top_code'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="blog_ad_bottom_code">Bottom Ad Code</label>
                                                    <textarea name="blog_ad_bottom_code" id="blog_ad_bottom_code" class="form-control font-monospace" rows="3" placeholder="<!-- Ad Code -->">{{ $settings['blog_ad_bottom_code'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <h5 class="mt-4 mb-3 text-info">Sidebar Ads</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="blog_sidebar_ad_top_code">Sidebar Top Ad Code</label>
                                                    <textarea name="blog_sidebar_ad_top_code" id="blog_sidebar_ad_top_code" class="form-control font-monospace" rows="3" placeholder="<!-- Ad Code -->">{{ $settings['blog_sidebar_ad_top_code'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="blog_sidebar_ad_bottom_code">Sidebar Bottom Ad Code</label>
                                                    <textarea name="blog_sidebar_ad_bottom_code" id="blog_sidebar_ad_bottom_code" class="form-control font-monospace" rows="3" placeholder="<!-- Ad Code -->">{{ $settings['blog_sidebar_ad_bottom_code'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Image Banners Tab -->
                                    <div class="tab-pane fade" id="ads-image" role="tabpanel">
                                        <p class="text-muted">Upload custom banners and set destination URLs. Select <strong>"Image"</strong> as the Ad Type to display these.</p>

                                        <!-- Top Ad Image -->
                                        <div class="card card-outline card-secondary mb-3">
                                            <div class="card-header">
                                                <h3 class="card-title">Top Ad (Above Content)</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Current Image</label>
                                                            <div class="mt-1">
                                                                @if(isset($settings['blog_ad_top_image']))
                                                                    <img src="{{ asset($settings['blog_ad_top_image']) }}" class="img-fluid rounded" style="max-height: 100px;">
                                                                @else
                                                                    <span class="text-muted font-italic">No image uploaded</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Upload Image</label>
                                                            <div class="custom-file">
                                                                <input type="file" name="blog_ad_top_image" class="custom-file-input">
                                                                <label class="custom-file-label">Choose file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Target URL</label>
                                                            <input type="url" name="blog_ad_top_url" class="form-control" value="{{ $settings['blog_ad_top_url'] ?? '' }}" placeholder="https://example.com">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Bottom Ad Image -->
                                        <div class="card card-outline card-secondary mb-3">
                                            <div class="card-header">
                                                <h3 class="card-title">Bottom Ad (Below Content)</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Current Image</label>
                                                            <div class="mt-1">
                                                                @if(isset($settings['blog_ad_bottom_image']))
                                                                    <img src="{{ asset($settings['blog_ad_bottom_image']) }}" class="img-fluid rounded" style="max-height: 100px;">
                                                                @else
                                                                    <span class="text-muted font-italic">No image uploaded</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Upload Image</label>
                                                            <div class="custom-file">
                                                                <input type="file" name="blog_ad_bottom_image" class="custom-file-input">
                                                                <label class="custom-file-label">Choose file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Target URL</label>
                                                            <input type="url" name="blog_ad_bottom_url" class="form-control" value="{{ $settings['blog_ad_bottom_url'] ?? '' }}" placeholder="https://example.com">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Sidebar Top Image -->
                                        <div class="card card-outline card-secondary mb-3">
                                            <div class="card-header">
                                                <h3 class="card-title">Sidebar Top</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Current Image</label>
                                                            <div class="mt-1">
                                                                @if(isset($settings['blog_sidebar_ad_top_image']))
                                                                    <img src="{{ asset($settings['blog_sidebar_ad_top_image']) }}" class="img-fluid rounded" style="max-height: 100px;">
                                                                @else
                                                                    <span class="text-muted font-italic">No image uploaded</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Upload Image</label>
                                                            <div class="custom-file">
                                                                <input type="file" name="blog_sidebar_ad_top_image" class="custom-file-input">
                                                                <label class="custom-file-label">Choose file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Target URL</label>
                                                            <input type="url" name="blog_sidebar_ad_top_url" class="form-control" value="{{ $settings['blog_sidebar_ad_top_url'] ?? '' }}" placeholder="https://example.com">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Sidebar Bottom Image -->
                                        <div class="card card-outline card-secondary mb-3">
                                            <div class="card-header">
                                                <h3 class="card-title">Sidebar Bottom (Sticky)</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Current Image</label>
                                                            <div class="mt-1">
                                                                @if(isset($settings['blog_sidebar_ad_bottom_image']))
                                                                    <img src="{{ asset($settings['blog_sidebar_ad_bottom_image']) }}" class="img-fluid rounded" style="max-height: 100px;">
                                                                @else
                                                                    <span class="text-muted font-italic">No image uploaded</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Upload Image</label>
                                                            <div class="custom-file">
                                                                <input type="file" name="blog_sidebar_ad_bottom_image" class="custom-file-input">
                                                                <label class="custom-file-label">Choose file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Target URL</label>
                                                            <input type="url" name="blog_sidebar_ad_bottom_url" class="form-control" value="{{ $settings['blog_sidebar_ad_bottom_url'] ?? '' }}" placeholder="https://example.com">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <h4 class="text-primary mt-4"><i class="fas fa-toggle-on mr-2"></i> Ad Configuration</h4>
                                <p class="text-muted mb-4">Control which type of ad is displayed for each slot. Choose "None" to disable.</p>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Top Ad</label>
                                            <select name="blog_ad_top_type" class="form-control">
                                                <option value="none" {{ ($settings['blog_ad_top_type'] ?? '') == 'none' ? 'selected' : '' }}>None (Disabled)</option>
                                                <option value="code" {{ ($settings['blog_ad_top_type'] ?? '') == 'code' ? 'selected' : '' }}>Custom Code</option>
                                                <option value="image" {{ ($settings['blog_ad_top_type'] ?? '') == 'image' ? 'selected' : '' }}>Image Banner</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Bottom Ad</label>
                                            <select name="blog_ad_bottom_type" class="form-control">
                                                <option value="none" {{ ($settings['blog_ad_bottom_type'] ?? '') == 'none' ? 'selected' : '' }}>None (Disabled)</option>
                                                <option value="code" {{ ($settings['blog_ad_bottom_type'] ?? '') == 'code' ? 'selected' : '' }}>Custom Code</option>
                                                <option value="image" {{ ($settings['blog_ad_bottom_type'] ?? '') == 'image' ? 'selected' : '' }}>Image Banner</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Sidebar Top</label>
                                            <select name="blog_sidebar_ad_top_type" class="form-control">
                                                <option value="none" {{ ($settings['blog_sidebar_ad_top_type'] ?? '') == 'none' ? 'selected' : '' }}>None (Disabled)</option>
                                                <option value="code" {{ ($settings['blog_sidebar_ad_top_type'] ?? '') == 'code' ? 'selected' : '' }}>Custom Code</option>
                                                <option value="image" {{ ($settings['blog_sidebar_ad_top_type'] ?? '') == 'image' ? 'selected' : '' }}>Image Banner</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Sidebar Bottom</label>
                                            <select name="blog_sidebar_ad_bottom_type" class="form-control">
                                                <option value="none" {{ ($settings['blog_sidebar_ad_bottom_type'] ?? '') == 'none' ? 'selected' : '' }}>None (Disabled)</option>
                                                <option value="code" {{ ($settings['blog_sidebar_ad_bottom_type'] ?? '') == 'code' ? 'selected' : '' }}>Custom Code</option>
                                                <option value="image" {{ ($settings['blog_sidebar_ad_bottom_type'] ?? '') == 'image' ? 'selected' : '' }}>Image Banner</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- About Page Settings -->
                    <div class="tab-pane fade" id="about" role="tabpanel">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">About Page Configuration</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>About Image</label>
                                            <div class="mt-2 mb-3">
                                                @if(isset($settings['about_image']))
                                                    <img src="{{ asset($settings['about_image']) }}" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                                                @else
                                                    <p class="text-muted">No image uploaded.</p>
                                                @endif
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" name="about_image" class="custom-file-input" id="aboutImageFile">
                                                <label class="custom-file-label" for="aboutImageFile">Choose file</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="card card-outline card-info">
                                            <div class="card-header p-0 border-bottom-0">
                                                <ul class="nav nav-tabs" id="about-lang-tab" role="tablist">
                                                    @foreach(config('app.available_locales', ['en' => 'English', 'km' => 'Khmer']) as $locale => $name)
                                                        <li class="nav-item">
                                                            <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="about-{{ $locale }}-tab" data-toggle="pill" href="#about-{{ $locale }}" role="tab" aria-controls="about-{{ $locale }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                                                {{ is_array($name) ? $name['name'] : $name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="card-body">
                                                <div class="tab-content" id="about-lang-tabContent">
                                                    @foreach(config('app.available_locales', ['en' => 'English', 'km' => 'Khmer']) as $locale => $name)
                                                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="about-{{ $locale }}" role="tabpanel" aria-labelledby="about-{{ $locale }}-tab">
                                                            <div class="form-group">
                                                                <label for="about_title_{{ $locale }}">Title ({{ is_array($name) ? $name['name'] : $name }})</label>
                                                                <input type="text" name="about_title_{{ $locale }}" class="form-control" value="{{ $settings['about_title_'.$locale] ?? '' }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="about_text_{{ $locale }}">Description ({{ is_array($name) ? $name['name'] : $name }})</label>
                                                                <textarea name="about_text_{{ $locale }}" class="form-control summernote">{{ $settings['about_text_'.$locale] ?? '' }}</textarea>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="founded_year">Founded Year</label>
                                            <input type="number" name="founded_year" class="form-control" value="{{ $settings['founded_year'] ?? '2016' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="city">City</label>
                                            <input type="text" name="city" class="form-control" value="{{ $settings['city'] ?? 'Phnom Penh' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="years_experience">Years of Experience</label>
                                            <input type="text" name="years_experience" class="form-control" value="{{ $settings['years_experience'] ?? '10' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="total_rooms">Total Rooms/Capacity</label>
                                            <input type="text" name="total_rooms" class="form-control" value="{{ $settings['total_rooms'] ?? '50' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="total_guests">Total Guests/Students</label>
                                            <input type="text" name="total_guests" class="form-control" value="{{ $settings['total_guests'] ?? '15K+' }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="rating">Rating</label>
                                            <input type="text" name="rating" class="form-control" value="{{ $settings['rating'] ?? '4.9' }}">
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">
                                <h4 class="mb-3">Our Values</h4>
                                <div class="row">
                                    @for($i = 1; $i <= 3; $i++)
                                    <div class="col-md-4">
                                        <div class="card card-outline card-secondary">
                                            <div class="card-header">
                                                <h5 class="card-title">Value Item {{ $i }}</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>Icon (SVG Path)</label>
                                                    <div class="input-group">
                                                        <input type="text" name="value_{{$i}}_icon" id="value_{{$i}}_icon" class="form-control" value="{{ $settings['value_'.$i.'_icon'] ?? '' }}" placeholder="Paste SVG path or select icon">
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-outline-secondary" onclick="openIconSelector('value_{{$i}}_icon')">Select Icon</button>
                                                        </div>
                                                    </div>
                                                    <small class="form-text text-muted">Paste an SVG path string here e.g. "M12 2..."</small>
                                                </div>
                                                <ul class="nav nav-pills mb-3" id="value-{{$i}}-tab" role="tablist">
                                                    @foreach(config('app.available_locales') as $locale => $name)
                                                    <li class="nav-item">
                                                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="value-{{$i}}-{{$locale}}-tab" data-toggle="pill" href="#value-{{$i}}-{{$locale}}" role="tab" aria-controls="value-{{$i}}-{{$locale}}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ is_array($name) ? $name['name'] : $name }}</a>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                                <div class="tab-content" id="value-{{$i}}-tabContent">
                                                    @foreach(config('app.available_locales') as $locale => $name)
                                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="value-{{$i}}-{{$locale}}" role="tabpanel" aria-labelledby="value-{{$i}}-{{$locale}}-tab">
                                                        <div class="form-group">
                                                            <label>Title</label>
                                                            <input type="text" name="value_{{$i}}_title_{{$locale}}" class="form-control" value="{{ $settings['value_'.$i.'_title_'.$locale] ?? '' }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Description</label>
                                                            <textarea name="value_{{$i}}_desc_{{$locale}}" class="form-control" rows="3">{{ $settings['value_'.$i.'_desc_'.$locale] ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Advanced Settings -->
                    <div class="tab-pane fade" id="advanced" role="tabpanel">
                        <div class="card card-danger card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Advanced Settings</h3>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> These settings effect core system behavior. Only modify if sure.
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="timezone">System Timezone</label>
                                            <select name="timezone" class="form-control">
                                                <option value="Asia/Phnom_Penh" selected>Asia/Phnom_Penh (GMT+7)</option>
                                                <option value="UTC">UTC (GMT+0)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Authentication Settings -->
                    <div class="tab-pane fade" id="social-auth" role="tabpanel">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-sign-in-alt mr-2"></i> Social Login Configuration</h3>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Configure keys for social login. Redirect URLs must be whitelisted in the respective provider's developer console.
                                </div>

                                <!-- Google Settings -->
                                <div class="card card-outline card-danger">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fab fa-google mr-2"></i> Google Login</h3>
                                        <div class="card-tools">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="google_login_enabled" name="google_login_enabled" {{ ($settings['google_login_enabled'] ?? '') == 'on' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="google_login_enabled">Enable</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Client ID</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="google_client_id" class="form-control" value="{{ $settings['google_client_id'] ?? '' }}" placeholder="Enter Google Client ID">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Client Secret</label>
                                            <div class="col-sm-9">
                                                <input type="password" name="google_client_secret" class="form-control" value="{{ $settings['google_client_secret'] ?? '' }}" placeholder="Enter Google Client Secret">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Redirect URL</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" value="{{ url('auth/google/callback') }}" readonly>
                                                <small class="text-muted">Add this URL to your <a href="https://console.cloud.google.com/" target="_blank">Google Cloud Console</a>.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Facebook Settings -->
                                <div class="card card-outline card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fab fa-facebook mr-2"></i> Facebook Login</h3>
                                        <div class="card-tools">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="facebook_login_enabled" name="facebook_login_enabled" {{ ($settings['facebook_login_enabled'] ?? '') == 'on' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="facebook_login_enabled">Enable</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">App ID</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="facebook_app_id" class="form-control" value="{{ $settings['facebook_app_id'] ?? '' }}" placeholder="Enter Facebook App ID">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">App Secret</label>
                                            <div class="col-sm-9">
                                                <input type="password" name="facebook_app_secret" class="form-control" value="{{ $settings['facebook_app_secret'] ?? '' }}" placeholder="Enter Facebook App Secret">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Redirect URL</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" value="{{ url('auth/facebook/callback') }}" readonly>
                                                <small class="text-muted">Add this URL to your <a href="https://developers.facebook.com/" target="_blank">Meta for Developers Console</a>.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Telegram Settings -->
                                <div class="card card-outline card-info">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fab fa-telegram mr-2"></i> Telegram Login</h3>
                                        <div class="card-tools">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="telegram_login_enabled" name="telegram_login_enabled" {{ ($settings['telegram_login_enabled'] ?? '') == 'on' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="telegram_login_enabled">Enable</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Bot Username</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="telegram_bot_username" class="form-control" value="{{ $settings['telegram_bot_username'] ?? '' }}" placeholder="e.g. MyUniversityBot">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Bot Token</label>
                                            <div class="col-sm-9">
                                                <input type="password" name="telegram_bot_token" class="form-control" value="{{ $settings['telegram_bot_token'] ?? '' }}" placeholder="Enter Telegram Bot Token">
                                                <small class="text-muted">Get this from <a href="https://t.me/BotFather" target="_blank">@BotFather</a>.</small>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Redirect URL</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" value="{{ url('auth/telegram/callback') }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-2">
                    <div class="card-body text-right">
                        <button type="submit" class="btn btn-primary px-5 btn-lg">
                            <i class="fas fa-save mr-2"></i> Save All Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Icon Selector Modal -->
<div class="modal fade" id="iconSelectorModal" tabindex="-1" role="dialog" aria-labelledby="iconSelectorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="iconSelectorModalLabel">Select an Icon</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="iconSearchInput" placeholder="Search icons...">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="iconSearchBtn">Search</button>
                    </div>
                </div>
                <div class="row" id="iconGrid" style="max-height: 500px; overflow-y: auto;">
                    <!-- Icons will be populated here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Custom file input label update
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

        // Icon Data (Heroicons Outline Paths)
        const icons = [
            
            { name: "Academic Cap", path: "M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" },
            { name: "Light Bulb", path: "M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" },
            { name: "User Group", path: "M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" },
            { name: "Globe", path: "M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" },
            { name: "Sparkles", path: "M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" },
            { name: "Lightning Bolt", path: "M13 10V3L4 14h7v7l9-11h-7z" },
            { name: "Scale", path: "M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" },
            { name: "Book Open", path: "M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" },
            { name: "Chart Bar", path: "M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" },
            { name: "Shield Check", path: "M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" },
            { name: "Heart", path: "M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" },
            { name: "Star", path: "M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" },
            { name: "Hand Thumb Up", path: "M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" },
            { name: "Chat Alt", path: "M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" },
            { name: "Clock", path: "M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" },
            { name: "Calendar", path: "M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" },
            { name: "Map", path: "M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" },
            { name: "Location Marker", path: "M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z" },
            { name: "Phone", path: "M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" },
            { name: "Mail", path: "M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" },
            { name: "DeskTop Computer", path: "M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" }
        ];

        let targetInputId = null;

        // Open Modal
        window.openIconSelector = function(inputId) {
            targetInputId = inputId;
            renderIcons(icons);
            $('#iconSelectorModal').modal('show');
        };

        // Render Icon Grid
        function renderIcons(iconList) {
            let html = '';
            iconList.forEach(icon => {
                html += `
                    <div class="col-md-2 col-sm-3 col-4 mb-3">
                        <div class="card h-100 icon-card" onclick="selectIcon('${icon.path}')" style="cursor: pointer;">
                            <div class="card-body text-center p-2">
                                <svg class="w-6 h-6 mx-auto mb-2 text-dark" style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${icon.path}"></path>
                                </svg>
                                <p class="card-text small text-muted text-truncate mb-0" title="${icon.name}">${icon.name}</p>
                            </div>
                        </div>
                    </div>
                `;
            });
            $('#iconGrid').html(html);
        }

        // Search Filter
        $('#iconSearchInput').on('keyup', function() {
            let query = $(this).val().toLowerCase();
            let filteredIcons = icons.filter(icon => icon.name.toLowerCase().includes(query));
            renderIcons(filteredIcons);
        });

        $('#iconSearchBtn').click(function() {
            let query = $('#iconSearchInput').val().toLowerCase();
            let filteredIcons = icons.filter(icon => icon.name.toLowerCase().includes(query));
            renderIcons(filteredIcons);
        });

        // Select Icon
        window.selectIcon = function(path) {
            if (targetInputId) {
                $('#' + targetInputId).val(path);
                $('#iconSelectorModal').modal('hide');
            }
        };

        // Hover effect for icon cards
        $(document).on('mouseenter', '.icon-card', function() {
            $(this).addClass('bg-light border-primary');
        }).on('mouseleave', '.icon-card', function() {
            $(this).removeClass('bg-light border-primary');
        });
    });
</script>
@endpush

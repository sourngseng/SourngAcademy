@extends('admin.layouts.master')

@section('title', 'Hotel Settings')

@push('styles')
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{asset('backend')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- Summernote -->
    <link rel="stylesheet" href="{{asset('backend')}}/plugins/summernote/summernote-bs4.min.css">
@endpush

@section('page-title')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Hotel Settings</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="general-tab" data-toggle="pill" href="#general" role="tab">
                        <i class="fas fa-cog"></i> General Settings
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="branding-tab" data-toggle="pill" href="#branding" role="tab">
                        <i class="fas fa-image"></i> Logo & Favicon
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="payment-tab" data-toggle="pill" href="#payment" role="tab">
                        <i class="fas fa-credit-card"></i> Payment Settings
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="about-tab" data-toggle="pill" href="#about" role="tab">
                        <i class="fas fa-info-circle"></i> About Page
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contact-tab" data-toggle="pill" href="#contact" role="tab">
                        <i class="fas fa-address-book"></i> Contact Page
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="social-tab" data-toggle="pill" href="#social" role="tab">
                        <i class="fas fa-share-alt"></i> Social Media
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="auth-tab" data-toggle="pill" href="#auth" role="tab">
                        <i class="fas fa-users-cog"></i> Social Auth
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="tab-content" id="custom-tabs-four-tabContent">
                    <!-- General Settings -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="app_name">App Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('app_name') is-invalid @enderror" 
                                           id="app_name" name="app_name" 
                                           value="{{ old('app_name', $settings['app_name'] ?? '') }}" required>
                                    <small class="form-text text-muted">This will be displayed in sidebar (e.g., "HotelMIS", "Admin Panel")</small>
                                    @error('app_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hotel_name">Hotel Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('hotel_name') is-invalid @enderror" 
                                           id="hotel_name" name="hotel_name" 
                                           value="{{ old('hotel_name', $settings['hotel_name'] ?? '') }}" required>
                                    @error('hotel_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hotel_email">Hotel Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('hotel_email') is-invalid @enderror" 
                                           id="hotel_email" name="hotel_email" 
                                           value="{{ old('hotel_email', $settings['hotel_email'] ?? '') }}" required>
                                    @error('hotel_email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hotel_phone">Hotel Phone <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('hotel_phone') is-invalid @enderror" 
                                           id="hotel_phone" name="hotel_phone" 
                                           value="{{ old('hotel_phone', $settings['hotel_phone'] ?? '') }}" required>
                                    @error('hotel_phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hotel_address">Hotel Address <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('hotel_address') is-invalid @enderror" 
                                           id="hotel_address" name="hotel_address" 
                                           value="{{ old('hotel_address', $settings['hotel_address'] ?? '') }}" required>
                                    @error('hotel_address')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Font Settings Section -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-font"></i> Font Settings
                                </h5>
                            </div>
                        </div>
                        
                        <!-- Primary Font Selection -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="primary_font">
                                        <i class="fas fa-text-height"></i> Primary Font (English/Default)
                                    </label>
                                    <select class="form-control" id="primary_font" name="primary_font">
                                        <option value="Inter" {{ old('primary_font', $settings['primary_font'] ?? 'Inter') == 'Inter' ? 'selected' : '' }}>
                                            Inter (Modern Sans-serif)
                                        </option>
                                        <option value="Roboto" {{ old('primary_font', $settings['primary_font'] ?? '') == 'Roboto' ? 'selected' : '' }}>
                                            Roboto
                                        </option>
                                        <option value="Open Sans" {{ old('primary_font', $settings['primary_font'] ?? '') == 'Open Sans' ? 'selected' : '' }}>
                                            Open Sans
                                        </option>
                                        <option value="Lato" {{ old('primary_font', $settings['primary_font'] ?? '') == 'Lato' ? 'selected' : '' }}>
                                            Lato
                                        </option>
                                        <option value="Poppins" {{ old('primary_font', $settings['primary_font'] ?? '') == 'Poppins' ? 'selected' : '' }}>
                                            Poppins
                                        </option>
                                        <option value="Montserrat" {{ old('primary_font', $settings['primary_font'] ?? '') == 'Montserrat' ? 'selected' : '' }}>
                                            Montserrat
                                        </option>
                                        <option value="Playfair Display" {{ old('primary_font', $settings['primary_font'] ?? '') == 'Playfair Display' ? 'selected' : '' }}>
                                            Playfair Display (Elegant Serif)
                                        </option>
                                    </select>
                                    <small class="form-text text-muted">
                                        Select the primary font for the frontend when using English or other languages.
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light mt-4">
                                    <div class="card-body p-3">
                                        <p class="mb-2"><strong>Font Preview:</strong></p>
                                        <div id="primary-font-preview" style="font-size: 18px;">
                                            The quick brown fox jumps over the lazy dog
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Khmer Font Selection -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="khmer_font">
                                        <i class="fas fa-language"></i> Khmer Font Selection
                                    </label>
                                    <select class="form-control" id="khmer_font" name="khmer_font">
                                        <option value="">System Default</option>
                                        <option value="hanuman" {{ old('khmer_font', $settings['khmer_font'] ?? '') == 'hanuman' ? 'selected' : '' }}>
                                            Hanuman
                                        </option>
                                        <option value="kantumruy" {{ old('khmer_font', $settings['khmer_font'] ?? '') == 'kantumruy' ? 'selected' : '' }}>
                                            Kantumruy Pro
                                        </option>
                                    </select>
                                    <small class="form-text text-muted">
                                        Select the Khmer font to use when Khmer language is active. 
                                        This will apply to all Khmer text in the admin panel and frontend.
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light mt-4">
                                    <div class="card-body p-3">
                                        <p class="mb-2"><strong>Font Preview:</strong></p>
                                        <div id="font-preview-hanuman" style="font-family: 'Hanuman', sans-serif; font-size: 18px; display: none;">
                                            ជំរាបសួរ - សណ្ឋាគារបណ្តាក់អា រាច (Hanuman)
                                        </div>
                                        <div id="font-preview-kantumruy" style="font-family: 'Kantumruy Pro', sans-serif; font-size: 18px; display: none;">
                                            ជំរាបសួរ - សណ្ឋាគារបណ្តាក់អា រាច (Kantumruy Pro)
                                        </div>
                                        <div id="font-preview-default" style="font-size: 18px;">
                                            System Default Font
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Logo & Favicon -->
                    <div class="tab-pane fade" id="branding" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="logo">Hotel Logo</label>
                                    <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                           id="logo" name="logo" accept="image/*">
                                    @error('logo')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Recommended size: 200x60px (PNG, JPG, SVG)</small>
                                    @if(isset($settings['logo']) && $settings['logo'])
                                        <div class="mt-2">
                                            <p class="text-sm">Current Logo:</p>
                                            <img src="{{ asset($settings['logo']) }}" alt="Logo" class="img-thumbnail" style="max-width: 200px;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="favicon">Favicon</label>
                                    <input type="file" class="form-control @error('favicon') is-invalid @enderror" 
                                           id="favicon" name="favicon" accept="image/x-icon,image/png">
                                    @error('favicon')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Recommended size: 32x32px (ICO or PNG)</small>
                                    @if(isset($settings['favicon']) && $settings['favicon'])
                                        <div class="mt-2">
                                            <p class="text-sm">Current Favicon:</p>
                                            <img src="{{ asset($settings['favicon']) }}" alt="Favicon" class="img-thumbnail" style="max-width: 50px;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Settings -->
                    <div class="tab-pane fade" id="payment" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Configure your payment gateways here. These settings will be used to process payments on the guest portal.
                                </div>
                            </div>
                        </div>

                        <!-- PayPal -->
                        <div class="card card-outline card-primary collapsed-card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fab fa-paypal"></i> PayPal Settings</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="card-body" style="display: none;">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="paypal_enabled" name="paypal_enabled" 
                                               {{ isset($settings['paypal_enabled']) && $settings['paypal_enabled'] == '1' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="paypal_enabled">Enable PayPal Payment</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="paypal_mode">Environment Mode</label>
                                    <select class="form-control" id="paypal_mode" name="paypal_mode">
                                        <option value="sandbox" {{ (isset($settings['paypal_mode']) && $settings['paypal_mode'] == 'sandbox') ? 'selected' : '' }}>Sandbox (Test)</option>
                                        <option value="live" {{ (isset($settings['paypal_mode']) && $settings['paypal_mode'] == 'live') ? 'selected' : '' }}>Live (Production)</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="paypal_client_id">Client ID</label>
                                    <input type="text" class="form-control" id="paypal_client_id" name="paypal_client_id" 
                                           value="{{ $settings['paypal_client_id'] ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="paypal_client_secret">Client Secret</label>
                                    <input type="password" class="form-control" id="paypal_client_secret" name="paypal_client_secret" 
                                           value="{{ $settings['paypal_client_secret'] ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <!-- KHQR (Cambodia) -->
                        <div class="card card-outline card-danger collapsed-card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-qrcode"></i> KHQR (Cambodia Bank API)</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="card-body" style="display: none;">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="khqr_enabled" name="khqr_enabled" 
                                               {{ isset($settings['khqr_enabled']) && $settings['khqr_enabled'] == '1' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="khqr_enabled">Enable KHQR Payment</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="khqr_merchant_name">Merchant Name</label>
                                            <input type="text" class="form-control" id="khqr_merchant_name" name="khqr_merchant_name" 
                                                   value="{{ $settings['khqr_merchant_name'] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="khqr_merchant_city">Merchant City</label>
                                            <input type="text" class="form-control" id="khqr_merchant_city" name="khqr_merchant_city" 
                                                   value="{{ $settings['khqr_merchant_city'] ?? 'Phnom Penh' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="khqr_merchant_id">Merchant ID (Bakong)</label>
                                    <input type="text" class="form-control" id="khqr_merchant_id" name="khqr_merchant_id" 
                                           value="{{ $settings['khqr_merchant_id'] ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="khqr_bakong_account_id">Bakong Account ID</label>
                                    <input type="text" class="form-control" id="khqr_bakong_account_id" name="khqr_bakong_account_id" 
                                           value="{{ $settings['khqr_bakong_account_id'] ?? '' }}">
                                    <small class="text-muted">Required for generating valid KHQR codes.</small>
                                </div>
                            </div>
                        </div>

                        <!-- ACLEDA Bank KHQR -->
                        <div class="card card-outline card-navy collapsed-card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-university"></i> ACLEDA Bank KHQR (API)</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="card-body" style="display: none;">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="acleda_enabled" name="acleda_enabled" 
                                               {{ isset($settings['acleda_enabled']) && $settings['acleda_enabled'] == '1' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="acleda_enabled">Enable ACLEDA Payment</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="acleda_base_url">API Base URL</label>
                                    <input type="text" class="form-control" id="acleda_base_url" name="acleda_base_url" 
                                           value="{{ $settings['acleda_base_url'] ?? '' }}">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="acleda_login_id">Login ID</label>
                                            <input type="text" class="form-control" id="acleda_login_id" name="acleda_login_id" 
                                                   value="{{ $settings['acleda_login_id'] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="acleda_password">Password</label>
                                            <input type="password" class="form-control" id="acleda_password" name="acleda_password" 
                                                   value="{{ $settings['acleda_password'] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="acleda_merchant_id">Merchant ID</label>
                                    <input type="text" class="form-control" id="acleda_merchant_id" name="acleda_merchant_id" 
                                           value="{{ $settings['acleda_merchant_id'] ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="acleda_secret">Secret Key (for Hashing)</label>
                                    <input type="password" class="form-control" id="acleda_secret" name="acleda_secret" 
                                           value="{{ $settings['acleda_secret'] ?? '' }}">
                                    <small class="text-muted">Used to generate signature hash for requests.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Stripe (Universal) -->
                        <div class="card card-outline card-info collapsed-card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fab fa-stripe"></i> Stripe (Credit/Debit Card)</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="card-body" style="display: none;">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="stripe_enabled" name="stripe_enabled" 
                                               {{ isset($settings['stripe_enabled']) && $settings['stripe_enabled'] == '1' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="stripe_enabled">Enable Stripe Payment</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="stripe_key">Publishable Key</label>
                                    <input type="text" class="form-control" id="stripe_key" name="stripe_key" 
                                           value="{{ $settings['stripe_key'] ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="stripe_secret">Secret Key</label>
                                    <input type="password" class="form-control" id="stripe_secret" name="stripe_secret" 
                                           value="{{ $settings['stripe_secret'] ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- About Page -->
                    <div class="tab-pane fade" id="about" role="tabpanel">
                        <div class="form-group">
                            <label for="about_title">About Page Title</label>
                            <input type="text" class="form-control" id="about_title" name="about_title" 
                                   value="{{ old('about_title', $settings['about_title'] ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="about_description">About Page Description</label>
                            <textarea class="form-control summernote" id="about_description" name="about_description" rows="8">{{ old('about_description', $settings['about_description'] ?? '') }}</textarea>
                            <small class="form-text text-muted">Describe your hotel, facilities, and services. You can add images, formatting, and more.</small>
                        </div>
                    </div>

                    <!-- Contact Page -->
                    <div class="tab-pane fade" id="contact" role="tabpanel">
                        <div class="form-group">
                            <label for="contact_details">Contact Page Details</label>
                            <textarea class="form-control summernote" id="contact_details" name="contact_details" rows="8">{{ old('contact_details', $settings['contact_details'] ?? '') }}</textarea>
                            <small class="form-text text-muted">Add additional contact information, office hours, or any other details. You can add images and rich formatting.</small>
                        </div>
                        <div class="form-group">
                            <label for="contact_map_iframe">Google Maps Embed Code</label>
                            <textarea class="form-control" id="contact_map_iframe" name="contact_map_iframe" rows="5">{{ old('contact_map_iframe', $settings['contact_map_iframe'] ?? '') }}</textarea>
                            <small class="form-text text-muted">Paste the complete iframe code from Google Maps</small>
                        </div>
                        <div class="alert alert-info">
                            <h5><i class="icon fas fa-info"></i> How to get Google Maps Embed Code:</h5>
                            <ol class="mb-0">
                                <li>Go to <a href="https://www.google.com/maps" target="_blank">Google Maps</a></li>
                                <li>Search for your hotel location</li>
                                <li>Click on "Share" button</li>
                                <li>Select "Embed a map" tab</li>
                                <li>Copy the HTML code and paste above</li>
                            </ol>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="tab-pane fade" id="social" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="facebook_url"><i class="fab fa-facebook"></i> Facebook URL</label>
                                    <input type="url" class="form-control" id="facebook_url" name="facebook_url" 
                                           value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}" 
                                           placeholder="https://facebook.com/yourpage">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="twitter_url"><i class="fab fa-twitter"></i> Twitter URL</label>
                                    <input type="url" class="form-control" id="twitter_url" name="twitter_url" 
                                           value="{{ old('twitter_url', $settings['twitter_url'] ?? '') }}" 
                                           placeholder="https://twitter.com/yourpage">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="instagram_url"><i class="fab fa-instagram"></i> Instagram URL</label>
                                    <input type="url" class="form-control" id="instagram_url" name="instagram_url" 
                                           value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}" 
                                           placeholder="https://instagram.com/yourpage">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="linkedin_url"><i class="fab fa-linkedin"></i> LinkedIn URL</label>
                                    <input type="url" class="form-control" id="linkedin_url" name="linkedin_url" 
                                           value="{{ old('linkedin_url', $settings['linkedin_url'] ?? '') }}" 
                                           placeholder="https://linkedin.com/company/yourcompany">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="youtube_url"><i class="fab fa-youtube"></i> YouTube URL</label>
                                    <input type="url" class="form-control" id="youtube_url" name="youtube_url" 
                                           value="{{ old('youtube_url', $settings['youtube_url'] ?? '') }}" 
                                           placeholder="https://youtube.com/yourchannel">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Auth -->
                    <div class="tab-pane fade" id="auth" role="tabpanel">
                        <h5 class="mb-3"><i class="fab fa-google"></i> Google OAuth Configuration</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="google_client_id">Google Client ID</label>
                                    <input type="text" class="form-control" id="google_client_id" name="google_client_id" 
                                           value="{{ old('google_client_id', $settings['google_client_id'] ?? '') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="google_client_secret">Google Client Secret</label>
                                    <input type="text" class="form-control" id="google_client_secret" name="google_client_secret" 
                                           value="{{ old('google_client_secret', $settings['google_client_secret'] ?? '') }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="google_redirect">Google Redirect URL</label>
                            <input type="text" class="form-control" id="google_redirect" name="google_redirect" 
                                   value="{{ old('google_redirect', $settings['google_redirect'] ?? '') }}" readonly>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3"><i class="fab fa-facebook"></i> Facebook OAuth Configuration</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="facebook_client_id">Facebook App ID</label>
                                    <input type="text" class="form-control" id="facebook_client_id" name="facebook_client_id" 
                                           value="{{ old('facebook_client_id', $settings['facebook_client_id'] ?? '') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="facebook_client_secret">Facebook App Secret</label>
                                    <input type="text" class="form-control" id="facebook_client_secret" name="facebook_client_secret" 
                                           value="{{ old('facebook_client_secret', $settings['facebook_client_secret'] ?? '') }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="facebook_redirect">Facebook Redirect URL</label>
                            <input type="text" class="form-control" id="facebook_redirect" name="facebook_redirect" 
                                   value="{{ old('facebook_redirect', $settings['facebook_redirect'] ?? '') }}" readonly>
                        </div>

                        <div class="alert alert-warning">
                            <h6><i class="icon fas fa-exclamation-triangle"></i> Important:</h6>
                            <p class="mb-0">To enable social authentication, you need to:</p>
                            <ul class="mb-0">
                                <li>Create OAuth applications in Google/Facebook Developer Console</li>
                                <li>Add the redirect URLs shown above to your app configuration</li>
                                <li>Install Laravel Socialite package if not already installed</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- SweetAlert2 -->
    <script src="{{asset('backend')}}/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Summernote -->
    <script src="{{asset('backend')}}/plugins/summernote/summernote-bs4.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Summernote
            $('.summernote').summernote({
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                callbacks: {
                    onImageUpload: function(files) {
                        uploadImage(files[0], $(this));
                    }
                }
            });
            
            // Khmer Font Preview
            function updateFontPreview() {
                const selectedFont = $('#khmer_font').val();
                
                $('#font-preview-hanuman, #font-preview-kantumruy, #font-preview-default').hide();
                
                if (selectedFont === 'hanuman') {
                    $('#font-preview-hanuman').show();
                } else if (selectedFont === 'kantumruy') {
                    $('#font-preview-kantumruy').show();
                } else {
                    $('#font-preview-default').show();
                }
            }
            
            // Primary Font Preview
            function updatePrimaryFontPreview() {
                const fontMap = {
                    'Inter': "'Inter', sans-serif",
                    'Roboto': "'Roboto', sans-serif",
                    'Open Sans': "'Open Sans', sans-serif",
                    'Lato': "'Lato', sans-serif",
                    'Poppins': "'Poppins', sans-serif",
                    'Montserrat': "'Montserrat', sans-serif",
                    'Playfair Display': "'Playfair Display', serif"
                };
                const selectedFont = $('#primary_font').val();
                const fontFamily = fontMap[selectedFont] || "'Inter', sans-serif";
                $('#primary-font-preview').css('font-family', fontFamily);
            }
            
            // Initialize font previews on page load
            updateFontPreview();
            updatePrimaryFontPreview();
            
            // Update preview when selection changes
            $('#khmer_font').on('change', function() {
                updateFontPreview();
            });
            
            $('#primary_font').on('change', function() {
                updatePrimaryFontPreview();
            });

            // Upload image function
            function uploadImage(file, editor) {
                const data = new FormData();
                data.append("file", file);
                data.append("_token", $('meta[name="csrf-token"]').attr('content'));

                $.ajax({
                    data: data,
                    type: "POST",
                    url: "{{ route('admin.settings.upload-image') }}",
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        editor.summernote('insertImage', response.url);
                    },
                    error: function(data) {
                        console.error('Error uploading image:', data);
                        Swal.fire({
                            icon: 'error',
                            title: 'Upload Failed',
                            text: 'Failed to upload image. Please try again.',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                });
            }

            // Show success message if exists
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            @endif

            // Show error message if exists
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>
@endpush

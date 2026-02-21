@extends('guest.layouts.master')

@section('title', 'My Profile')

@section('page-title')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-user-circle mr-2"></i>My Profile</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('guest.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">My Profile</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')

<div class="row">
    <div class="col-md-8">
        <!-- Personal Information -->
        <div class="card guest-card mb-4">
            <div class="card-header">
                <strong><i class="fas fa-user"></i> Personal Information</strong>
            </div>
            <div class="card-body">
                <form action="{{ route('guest.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                                <small class="text-muted">Email cannot be changed</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone', $profile->phone) }}">
                                @error('phone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date of Birth</label>
                                <div class="input-group date" id="dob_picker" data-target-input="nearest">
                                    <input type="text" name="date_of_birth" class="form-control datetimepicker-input @error('date_of_birth') is-invalid @enderror" 
                                           data-target="#dob_picker"
                                           value="{{ old('date_of_birth', $profile->date_of_birth ? \Carbon\Carbon::parse($profile->date_of_birth)->format('Y-m-d') : '') }}"
                                           placeholder="Select date of birth">
                                    <div class="input-group-append" data-target="#dob_picker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                                    </div>
                                </div>
                                @error('date_of_birth')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Gender</label>
                                <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                                    <option value="">Select gender</option>
                                    <option value="male" {{ old('gender', $profile->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $profile->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $profile->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nationality</label>
                                <input type="text" name="nationality" class="form-control @error('nationality') is-invalid @enderror" 
                                       value="{{ old('nationality', $profile->nationality) }}">
                                @error('nationality')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" 
                               value="{{ old('address', $profile->address) }}">
                        @error('address')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" 
                                       value="{{ old('city', $profile->city) }}">
                                @error('city')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Country</label>
                                <input type="text" name="country" class="form-control @error('country') is-invalid @enderror" 
                                       value="{{ old('country', $profile->country) }}">
                                @error('country')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h5><i class="fas fa-id-card"></i> Identification</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>ID Type</label>
                                <select name="id_type" class="form-control @error('id_type') is-invalid @enderror">
                                    <option value="">Select ID type</option>
                                    <option value="passport" {{ old('id_type', $profile->id_type) == 'passport' ? 'selected' : '' }}>Passport</option>
                                    <option value="national_id" {{ old('id_type', $profile->id_type) == 'national_id' ? 'selected' : '' }}>National ID</option>
                                    <option value="driver_license" {{ old('id_type', $profile->id_type) == 'driver_license' ? 'selected' : '' }}>Driver License</option>
                                </select>
                                @error('id_type')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>ID Number</label>
                                <input type="text" name="id_number" class="form-control @error('id_number') is-invalid @enderror" 
                                       value="{{ old('id_number', $profile->id_number) }}">
                                @error('id_number')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h5><i class="fas fa-phone-alt"></i> Emergency Contact</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contact Name</label>
                                <input type="text" name="emergency_contact_name" class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                                       value="{{ old('emergency_contact_name', $profile->emergency_contact_name) }}">
                                @error('emergency_contact_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contact Phone</label>
                                <input type="text" name="emergency_contact_phone" class="form-control @error('emergency_contact_phone') is-invalid @enderror" 
                                       value="{{ old('emergency_contact_phone', $profile->emergency_contact_phone) }}">
                                @error('emergency_contact_phone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-gradient">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Profile Photo -->
        <div class="card guest-card mb-4">
            <div class="card-header">
                <strong><i class="fas fa-camera"></i> Profile Photo</strong>
            </div>
            <div class="card-body text-center">
                <form action="{{ route('guest.profile.update') }}" method="POST" enctype="multipart/form-data" id="profilePhotoForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="name" value="{{ $user->name }}">
                    
                    <!-- Clickable Profile Image Container -->
                    <div class="profile-image-container position-relative mx-auto mb-3" style="width: 150px; height: 150px; cursor: pointer;" onclick="document.getElementById('profileImageInput').click();">
                        <!-- Current/Preview Image -->
                        <img id="profilePreview" 
                             src="{{ $user->profile_image ? asset($user->profile_image) : asset('backend/dist/img/default-avatar.png') }}" 
                             class="rounded-circle" 
                             width="150" 
                             height="150" 
                             style="object-fit: cover; border: 3px solid #667eea; transition: all 0.3s ease;">
                        
                        <!-- Camera Overlay -->
                        <div class="profile-overlay position-absolute d-flex align-items-center justify-content-center rounded-circle"
                             style="top: 0; left: 0; right: 0; bottom: 0; background: rgba(102, 126, 234, 0.7); opacity: 0; transition: all 0.3s ease;">
                            <i class="fas fa-camera fa-2x text-white"></i>
                        </div>
                    </div>
                    
                    <!-- Hidden File Input -->
                    <input type="file" 
                           name="profile_image" 
                           id="profileImageInput"
                           class="d-none @error('profile_image') is-invalid @enderror" 
                           accept="image/jpeg,image/png,image/jpg,image/gif"
                           onchange="previewProfileImage(this)">
                    
                    @error('profile_image')
                        <div class="text-danger small mb-2">{{ $message }}</div>
                    @enderror
                    
                    <p class="text-muted small mb-2">
                        <i class="fas fa-info-circle"></i> Click on image to change
                    </p>
                    <p class="text-muted small mb-3">JPG, PNG, GIF. Max 2MB</p>
                    
                    <!-- Submit Button (shown after selecting image) -->
                    <div id="uploadActions" style="display: none;">
                        <button type="submit" class="btn btn-gradient btn-sm mb-2">
                            <i class="fas fa-upload"></i> Upload Photo
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="cancelImageSelection()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>


        <!-- Change Password -->
        <div class="card guest-card">
            <div class="card-header">
                <strong><i class="fas fa-lock"></i> Change Password</strong>
            </div>
            <div class="card-body">
                <form action="{{ route('guest.profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn btn-outline-danger btn-block">
                        <i class="fas fa-key"></i> Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .profile-image-container:hover .profile-overlay {
        opacity: 1 !important;
    }
    .profile-image-container:hover #profilePreview {
        transform: scale(1.02);
    }
</style>
@endpush

@push('scripts')
<script>
    // Initialize DateTime Picker for Date of Birth
    $(function () {
        $('#dob_picker').datetimepicker({
            format: 'YYYY-MM-DD',
            maxDate: moment().subtract(18, 'years'), // Must be at least 18 years old
            viewMode: 'years',
            icons: {
                time: 'fas fa-clock',
                date: 'fas fa-calendar',
                up: 'fas fa-arrow-up',
                down: 'fas fa-arrow-down',
                previous: 'fas fa-chevron-left',
                next: 'fas fa-chevron-right',
                today: 'fas fa-calendar-check',
                clear: 'fas fa-trash',
                close: 'fas fa-times'
            }
        });
    });

    // Store original image src for cancel functionality
    var originalImageSrc = '{{ $user->profile_image ? asset($user->profile_image) : asset("backend/dist/img/default-avatar.png") }}';
    
    function previewProfileImage(input) {
        if (input.files && input.files[0]) {
            // Validate file size (2MB max)
            if (input.files[0].size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                input.value = '';
                return;
            }
            
            // Validate file type
            var allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!allowedTypes.includes(input.files[0].type)) {
                alert('Please select a valid image file (JPG, PNG, or GIF)');
                input.value = '';
                return;
            }
            
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profilePreview').src = e.target.result;
                document.getElementById('uploadActions').style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function cancelImageSelection() {
        document.getElementById('profilePreview').src = originalImageSrc;
        document.getElementById('profileImageInput').value = '';
        document.getElementById('uploadActions').style.display = 'none';
    }
</script>
@endpush


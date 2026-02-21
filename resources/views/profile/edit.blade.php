@extends(request()->is('student/*') ? 'student.layouts.master' : (request()->is('teacher/*') ? 'teacher.layouts.master' : (request()->is('staff/*') ? 'staff.layouts.master' : 'admin.layouts.master')))

@section('title', 'Profile Settings')

@push('styles')
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{asset('backend')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
@endpush

@section('page-title')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Profile Settings</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-profile" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="profile-info-tab" data-toggle="pill" href="#profile-info" role="tab">
                        <i class="fas fa-user"></i> Profile Information
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="password-tab" data-toggle="pill" href="#password" role="tab">
                        <i class="fas fa-lock"></i> Update Password
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="two-factor-tab" data-toggle="pill" href="#two-factor" role="tab">
                        <i class="fas fa-shield-alt"></i> Two-Factor Auth
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="social-accounts-tab" data-toggle="pill" href="#social-accounts" role="tab">
                        <i class="fas fa-share-alt"></i> Linked Accounts
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="delete-account-tab" data-toggle="pill" href="#delete-account" role="tab">
                        <i class="fas fa-trash"></i> Delete Account
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-profile-tabContent">
                <!-- Profile Information -->
                <div class="tab-pane fade show active" id="profile-info" role="tabpanel">
                    <form method="POST" action="{{ request()->url() }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        
                        <div class="row">
                            <!-- Left Column: Profile Information -->
                            <div class="col-md-9 order-md-1 order-2">
                                <h5 class="mb-3 text-primary"><i class="fas fa-user-edit mr-2"></i>Personal Information</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Latin Name <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required placeholder="e.g. Sok Sao">
                                            </div>
                                            @error('name') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name_kh">Native Name (Khmer)</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-language"></i></span>
                                                </div>
                                                <input type="text" class="form-control @error('name_kh') is-invalid @enderror" id="name_kh" name="name_kh" value="{{ old('name_kh', $user->name_kh) }}" placeholder="e.g. សុខ សៅ">
                                            </div>
                                            @error('name_kh') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">Gender</label>
                                            <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                                                <option value="">-- Select --</option>
                                                <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                                <option value="Other" {{ old('gender', $user->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('gender') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="dob">Date of Birth</label>
                                            <div class="input-group date" id="dob_picker" data-target-input="nearest">
                                                <div class="input-group-prepend" data-target="#dob_picker" data-toggle="datetimepicker">
                                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="text" class="form-control datetimepicker-input @error('dob') is-invalid @enderror" data-target="#dob_picker" id="dob" name="dob" value="{{ old('dob', $user->dob ? $user->dob->format('Y-m-d') : '') }}" placeholder="Select date">
                                            </div>
                                            @error('dob') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="phone">Phone Number</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                </div>
                                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="e.g. 012 345 678">
                                            </div>
                                            @error('phone') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="email">Email Address <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                </div>
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                            </div>
                                            @error('email') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                                <div class="alert alert-warning mt-2">
                                                    <small>Your email is unverified. <button type="button" form="send-verification" class="btn btn-link p-0 align-baseline" onclick="event.preventDefault(); document.getElementById('send-verification').submit();">Click here to re-send verification.</button></small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" placeholder="Enter your full address...">{{ old('address', $user->address) }}</textarea>
                                            @error('address') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-3">
                                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                        <i class="fas fa-save mr-1"></i> Save Changes
                                    </button>
                                </div>
                            </div>

                            <!-- Right Column: Profile Image -->
                            <div class="col-md-3 order-md-2 order-1 text-center border-left">
                                <h5 class="mb-4 text-muted">Profile Picture</h5>
                                <div class="position-relative d-inline-block" style="cursor: pointer;" onclick="document.getElementById('profile_image').click();">
                                    @if($user->profile_image)
                                        <img id="profilePreview" src="{{ asset($user->profile_image) }}" class="img-fluid rounded-circle shadow-lg border" style="width: 180px; height: 180px; object-fit: cover; border-width: 4px !important; border-color: #fff !important;">
                                    @else
                                        <div id="profilePreviewContainer" class="rounded-circle shadow-lg bg-light d-flex align-items-center justify-content-center mx-auto border" style="width: 180px; height: 180px; border-width: 4px !important; border-color: #fff !important;">
                                            <i class="fas fa-user fa-5x text-secondary"></i>
                                        </div>
                                        <img id="profilePreview" src="" class="img-fluid rounded-circle shadow-lg border" style="width: 180px; height: 180px; object-fit: cover; border-width: 4px !important; border-color: #fff !important; display: none;">
                                    @endif
                                    
                                    <div class="position-absolute" style="bottom: 10px; right: 10px;">
                                        <span class="btn btn-sm btn-primary rounded-circle p-2 shadow">
                                            <i class="fas fa-camera"></i>
                                        </span>
                                    </div>
                                </div>
                                
                                <input type="file" name="profile_image" class="d-none" id="profile_image" accept="image/*">
                                <div class="mt-3">
                                    <p class="text-muted small mb-0">Allowed: JPG, PNG, GIF</p>
                                    <p class="text-muted small">Max size: 2MB</p>
                                </div>
                                @error('profile_image')
                                    <span class="text-danger small d-block mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </form>
                    
                    <!-- Verification Form (Hidden) -->
                    <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
                        @csrf
                    </form>
                </div>

                <!-- Update Password -->
                <div class="tab-pane fade" id="password" role="tabpanel">
                    <h5 class="mb-3">Update Password</h5>
                    <p class="text-muted">Ensure your account is using a long, random password to stay secure.</p>

                    <form method="POST" action="{{ route('password.update') }}" class="mt-4">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="current_password">Current Password <span class="text-danger">*</span></label>
                                    <input type="password" 
                                           class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                                           id="current_password" 
                                           name="current_password" 
                                           autocomplete="current-password">
                                    @error('current_password', 'updatePassword')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">New Password <span class="text-danger">*</span></label>
                                    <input type="password" 
                                           class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           autocomplete="new-password">
                                    @error('password', 'updatePassword')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" 
                                           class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           autocomplete="new-password">
                                    @error('password_confirmation', 'updatePassword')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Two-Factor Auth -->
                <div class="tab-pane fade" id="two-factor" role="tabpanel">
                    <h5 class="mb-3">Two-Factor Authentication</h5>
                    <p class="text-muted">Add additional security to your account using two-factor authentication.</p>

                    <div class="mt-4">
                        @if(!auth()->user()->two_factor_confirmed_at)
                            <div class="alert alert-light border">
                                <h5><i class="icon fas fa-info-circle text-info"></i> You have not enabled two-factor authentication.</h5>
                                <p>When two-factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone's Google Authenticator application.</p>
                                <form action="{{ route('two-factor.enable') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary mt-2">Enable Two-Factor Authentication</button>
                                </form>
                            </div>
                        @else 
                            <div class="alert alert-success">
                                <h5><i class="icon fas fa-check"></i> Two-factor authentication is enabled.</h5>
                                <p>Two-factor authentication is now enabled. Scan the following QR code using your phone's authenticator application.</p>
                            </div>
                            
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <p class="font-weight-bold">Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.</p>
                                    <div class="bg-light p-3 rounded">
                                        @foreach(json_decode(decrypt(auth()->user()->two_factor_recovery_codes)) as $code)
                                            <div class="text-monospace">{{ $code }}</div>
                                        @endforeach
                                    </div>
                                    
                                    <form action="{{ route('two-factor.recovery-codes.store') }}" method="POST" style="display:inline-block">
                                        @csrf
                                        <button type="submit" class="btn btn-secondary mt-3">Regenerate Recovery Codes</button>
                                    </form>
                                </div>
                            </div>

                            <form action="{{ route('two-factor.disable') }}" method="POST" onsubmit="return confirm('Are you sure you want to disable two-factor authentication?');">
                                @csrf
                                @method('DELETE')
                                <div class="form-group">
                                   <label>Current Password</label>
                                   <input type="password" name="password" class="form-control w-50" required>
                                </div>
                                <button type="submit" class="btn btn-danger">Disable Two-Factor Authentication</button>
                            </form>
                        @endif

                        @if (session('status') == 'two-factor-authentication-enabled')
                             <div class="mt-4">
                                <p>Please scan this QR code with your authenticator app:</p>
                                {!! request()->session()->get('qrCode') !!} 
                             </div>
                        @endif
                    </div>
                    
                    <!-- If session has secret, show confirm form -->
                    @if(session('two_factor_secret') && !auth()->user()->two_factor_confirmed_at)
                        <div class="mt-4 border-top pt-4">
                            <h5>Finish Enabling Two-Factor Authentication</h5>
                            <p>To finish enabling two-factor authentication, scan the QR code and enter the generated OTP code.</p>
                            
                            <div class="mb-3" id="qr-code-display">
                                <!-- QR Code will be loaded/displayed here from controller session flash or ajax -->
                                @if(isset($qrCode)) {!! $qrCode !!} @endif
                            </div>

                            <form action="{{ route('two-factor.confirm') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Code</label>
                                    <input type="text" name="code" class="form-control w-25" placeholder="123456" autofocus autocomplete="one-time-code">
                                    @error('code') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">Confirm</button>
                            </form>
                        </div>
                    @endif
                </div>

                <!-- Linked Accounts -->
                <div class="tab-pane fade" id="social-accounts" role="tabpanel">
                    <h5 class="mb-3">Linked Social Accounts</h5>
                    <p class="text-muted">Connect your social accounts to log in faster.</p>

                    <div class="list-group mt-4">
                        <!-- Google -->
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fab fa-google text-danger mr-2 fa-lg"></i> 
                                <strong>Google</strong>
                                @if($user->google_id)
                                    <span class="badge badge-success ml-2">Connected</span>
                                @else
                                    <span class="badge badge-secondary ml-2">Not Connected</span>
                                @endif
                            </div>
                            <div>
                                @if($user->google_id)
                                    <form action="{{ route('social.disconnect', 'google') }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Disconnect</button>
                                    </form>
                                @else
                                    <a href="{{ route('social.redirect', 'google') }}" class="btn btn-sm btn-outline-primary">Connect</a>
                                @endif
                            </div>
                        </div>

                        <!-- Facebook -->
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fab fa-facebook text-primary mr-2 fa-lg"></i> 
                                <strong>Facebook</strong>
                                @if($user->facebook_id)
                                    <span class="badge badge-success ml-2">Connected</span>
                                @else
                                    <span class="badge badge-secondary ml-2">Not Connected</span>
                                @endif
                            </div>
                            <div>
                                @if($user->facebook_id)
                                    <form action="{{ route('social.disconnect', 'facebook') }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Disconnect</button>
                                    </form>
                                @else
                                    <a href="{{ route('social.redirect', 'facebook') }}" class="btn btn-sm btn-outline-primary">Connect</a>
                                @endif
                            </div>
                        </div>

                        <!-- Telegram -->
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fab fa-telegram text-info mr-2 fa-lg"></i> 
                                <strong>Telegram</strong>
                                @if($user->telegram_id)
                                    <span class="badge badge-success ml-2">Connected</span>
                                    <small class="d-block text-muted">ID: {{ $user->telegram_id }}</small>
                                @else
                                    <span class="badge badge-secondary ml-2">Not Connected</span>
                                @endif
                            </div>
                            <div>
                                @if($user->telegram_id)
                                    <form action="{{ route('social.disconnect', 'telegram') }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Disconnect</button>
                                    </form>
                                @else
                                    <a href="{{ route('social.redirect', 'telegram') }}" class="btn btn-sm btn-outline-primary">Connect</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Account -->
                <div class="tab-pane fade" id="delete-account" role="tabpanel">
                    <h5 class="mb-3 text-danger">Delete Account</h5>
                    <p class="text-muted">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>

                    <div class="alert alert-danger">
                        <h6><i class="icon fas fa-ban"></i> Warning!</h6>
                        This action cannot be undone. Please be certain before proceeding.
                    </div>

                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal">
                        <i class="fas fa-trash"></i> Delete Account
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Confirmation Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-exclamation-triangle"></i> Confirm Account Deletion
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ request()->url() }}">
                    @csrf
                    @method('DELETE')
                    
                    <div class="modal-body">
                        <p class="mb-3">Are you sure you want to delete your account?</p>
                        <p class="mb-3">Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>
                        
                        <div class="form-group">
                            <label for="delete_password">Password <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control @error('password', 'userDeletion') is-invalid @enderror" 
                                   id="delete_password" 
                                   name="password" 
                                   placeholder="Enter your password"
                                   required>
                            @error('password', 'userDeletion')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- SweetAlert2 -->
    <script src="{{asset('backend')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Show success message for profile updated
            @if (session('status') === 'profile-updated')
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Profile updated successfully.',
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            @endif

            // Show success message for password updated
            @if (session('status') === 'password-updated')
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Password updated successfully.',
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            @endif

            // Auto-open password tab if there are password errors
            @if ($errors->updatePassword->any())
                $('#password-tab').tab('show');
            @endif

            // Auto-open delete account tab if there are deletion errors
            @if ($errors->userDeletion->any())
                $('#delete-account-tab').tab('show');
                $('#confirmDeleteModal').modal('show');
            @endif

            // Update custom file input label
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });

            // Profile Image Preview (Campus Style)
            $("#profile_image").change(function () {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#profilePreview').attr('src', e.target.result).css('display', 'block');
                        $('#profilePreviewContainer').removeClass('d-flex').hide();
                        
                        // Also hide the change overlay if it exists (for edit mode)
                        $('.position-absolute').hide();
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });

            // Initialize DateTimePicker for DOB
            $('#dob_picker').datetimepicker({
                format: 'YYYY-MM-DD',
                icons: { time: 'far fa-clock' }
            });
        });
    </script>
@endpush

<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('frontend.app');
});

// Role-based redirection helper
function redirectByUserRole($user) {
    if (!$user) return redirect()->route('login');
    
    if ($user->hasRole('super_admin') || $user->hasRole('campus_admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('staff')) {
        return redirect()->route('staff.dashboard');
    } elseif ($user->hasRole('teacher')) {
        return redirect()->route('teacher.dashboard');
    } elseif ($user->hasRole('student')) {
        return redirect()->route('student.dashboard');
    }
    
    return redirect()->route('profile.edit');
}

// Redirect /dashboard based on user role
Route::get('/dashboard', function () {
    return redirectByUserRole(Auth::user());
})->middleware(['auth', 'verified'])->name('dashboard');

// Redirect legacy /guest/dashboard to role-based dashboard
Route::get('/guest/dashboard', function () {
    return redirectByUserRole(Auth::user());
})->middleware(['auth', 'verified']);

// ============================================
// PUBLIC LANGUAGE SWITCHER
// ============================================
Route::get('/language/{locale}', function ($locale) {
    $available_locales = array_keys(config('app.available_locales', ['en' => [], 'km' => []]));
    if (! in_array($locale, $available_locales)) {
        abort(400);
    }
    session(['locale' => $locale]);
    return redirect()->back();
})->name('language.switch');

// ============================================
// ADMIN ROUTES
// ============================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'role:super_admin,campus_admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Language Switcher
    Route::get('/language/{locale}', function ($locale) {
        if (! in_array($locale, array_keys(config('app.available_locales', ['en' => [], 'km' => []])))) {
            abort(400);
        }
        session(['locale' => $locale]);
        return redirect()->back();
    })->name('language.switch');
    
    // Settings
    Route::get('/settings', [App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings/update', [App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
    
    // Users Management
    Route::resource('users', App\Http\Controllers\UserController::class);
    
    // Roles & Permissions
    Route::resource('roles', App\Http\Controllers\RoleController::class)->except(['show', 'destroy']);
    Route::resource('permissions', App\Http\Controllers\PermissionController::class)->except(['edit', 'update', 'show']);

    // University Management
    Route::resource('campuses', App\Http\Controllers\Admin\CampusController::class);
    Route::post('/faculties/{id}/duplicate', [App\Http\Controllers\Admin\FacultyController::class, 'duplicate'])->name('faculties.duplicate');
    Route::resource('faculties', App\Http\Controllers\Admin\FacultyController::class);
    Route::resource('departments', App\Http\Controllers\Admin\DepartmentController::class);
    Route::resource('academic-years', App\Http\Controllers\Admin\AcademicYearController::class);
    Route::resource('degree-levels', App\Http\Controllers\Admin\DegreeLevelController::class);
    Route::resource('semesters', App\Http\Controllers\Admin\SemesterController::class);
    Route::resource('classes', App\Http\Controllers\Admin\ClassController::class);
    Route::get('classes/{id}/qr-code', [App\Http\Controllers\Admin\ClassController::class, 'showQrCode'])->name('classes.qr-code');
    Route::get('classes/{id}/timetable/print', [App\Http\Controllers\Admin\ClassController::class, 'printTimetable'])->name('classes.timetable.print');
    Route::resource('classrooms', App\Http\Controllers\Admin\ClassroomController::class);
    Route::get('classrooms/by-campus/{campusId}', [App\Http\Controllers\Admin\ClassroomController::class, 'getByCampus'])->name('classrooms.by-campus');
    Route::post('/programs/{id}/toggle-popular', [App\Http\Controllers\Admin\ProgramController::class, 'togglePopular'])->name('programs.toggle-popular');
    Route::post('/programs/{id}/duplicate', [App\Http\Controllers\Admin\ProgramController::class, 'duplicate'])->name('programs.duplicate');
    Route::resource('programs', App\Http\Controllers\Admin\ProgramController::class);
    
    // Program Subjects Curriculum mapping
    Route::get('program-subjects', [App\Http\Controllers\Admin\ProgramSubjectController::class, 'index'])->name('program-subjects.index');
    Route::post('program-subjects/assign', [App\Http\Controllers\Admin\ProgramSubjectController::class, 'assign'])->name('program-subjects.assign');
    Route::get('program-subjects/get-subjects', [App\Http\Controllers\Admin\ProgramSubjectController::class, 'getSubjects'])->name('program-subjects.get-subjects');
    Route::delete('program-subjects/{id}', [App\Http\Controllers\Admin\ProgramSubjectController::class, 'destroy'])->name('program-subjects.destroy');

    Route::resource('subjects', App\Http\Controllers\Admin\SubjectController::class);

    // Session Plans
    Route::get('session-plans/get-programs/{campus}', [App\Http\Controllers\Admin\SessionPlanController::class, 'getProgramsByCampus'])->name('session-plans.get-programs');
    Route::get('session-plans/get-academic-years/{campus}', [App\Http\Controllers\Admin\SessionPlanController::class, 'getAcademicYearsByCampus'])->name('session-plans.get-years');
    Route::get('session-plans/get-subjects/{program}', [App\Http\Controllers\Admin\SessionPlanController::class, 'getSubjectsByProgram'])->name('session-plans.get-subjects');
    Route::get('session-plans/get-semesters/{year}', [App\Http\Controllers\Admin\SessionPlanController::class, 'getSemestersByAcademicYear'])->name('session-plans.get-semesters');
    Route::get('session-plans/get-parents/{subject}', [App\Http\Controllers\Admin\SessionPlanController::class, 'getParentsBySubject'])->name('session-plans.get-parents');
    Route::post('session-plans/{id}/duplicate', [App\Http\Controllers\Admin\SessionPlanController::class, 'duplicate'])->name('session-plans.duplicate');
    Route::resource('session-plans', App\Http\Controllers\Admin\SessionPlanController::class)->except(['show']);
    Route::get('session-plans/{session_plan}', [App\Http\Controllers\Common\SessionReaderController::class, 'show'])->name('session-plans.show');
    
    // Content Management
    Route::post('slides/{id}/duplicate', [App\Http\Controllers\Admin\SlideController::class, 'duplicate'])->name('slides.duplicate');
    Route::resource('slides', App\Http\Controllers\Admin\SlideController::class);
    Route::get('shifts/by-campus/{campusId}', [App\Http\Controllers\Admin\ClassController::class, 'getShiftsByCampus'])->name('shifts.by-campus');
    Route::post('shifts/{id}/duplicate', [App\Http\Controllers\Admin\ShiftController::class, 'duplicate'])->name('shifts.duplicate');
    Route::resource('shifts', App\Http\Controllers\Admin\ShiftController::class);
    Route::resource('blog', App\Http\Controllers\Admin\BlogPostController::class)->except(['show']);
    Route::resource('blog-categories', App\Http\Controllers\Admin\BlogCategoryController::class);
    Route::resource('pages', App\Http\Controllers\Admin\PageController::class)->except(['show']);

    // Finance Management
    Route::resource('invoices', App\Http\Controllers\Admin\InvoiceController::class);

    // Invoice Payments (Submit / Verify / Reject)
    Route::post('invoices/{invoice}/payments', [App\Http\Controllers\Admin\InvoicePaymentController::class, 'store'])->name('invoices.payments.store');
    Route::post('invoice-payments/{payment}/verify', [App\Http\Controllers\Admin\InvoicePaymentController::class, 'verify'])->name('invoice-payments.verify');
    Route::post('invoice-payments/{payment}/reject', [App\Http\Controllers\Admin\InvoicePaymentController::class, 'reject'])->name('invoice-payments.reject');

    // Campus Payment Methods Management
    Route::resource('payment-methods', App\Http\Controllers\Admin\CampusPaymentMethodController::class)->except(['show']);
    Route::post('payment-methods/{id}/toggle', [App\Http\Controllers\Admin\CampusPaymentMethodController::class, 'toggle'])->name('payment-methods.toggle');
    Route::post('payment-methods/reorder', [App\Http\Controllers\Admin\CampusPaymentMethodController::class, 'reorder'])->name('payment-methods.reorder');
    Route::get('campus/{campusId}/payment-methods', [App\Http\Controllers\Admin\CampusPaymentMethodController::class, 'forCampus'])->name('campus.payment-methods');

    // Language Management
    Route::prefix('languages')->name('languages.')->middleware(['role:super_admin'])->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\LanguageManagementController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\LanguageManagementController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\LanguageManagementController::class, 'store'])->name('store');
        Route::get('/{locale}/edit', [App\Http\Controllers\Admin\LanguageManagementController::class, 'edit'])->name('edit');
        Route::put('/{locale}', [App\Http\Controllers\Admin\LanguageManagementController::class, 'update'])->name('update');
        Route::delete('/{locale}', [App\Http\Controllers\Admin\LanguageManagementController::class, 'destroy'])->name('destroy');
        Route::post('/add-key', [App\Http\Controllers\Admin\LanguageManagementController::class, 'addKey'])->name('add-key');
        Route::post('/{locale}/set-default', [App\Http\Controllers\Admin\LanguageManagementController::class, 'setDefault'])->name('set-default');
    });
});

// ============================================
// STAFF ROUTES
// ============================================
Route::prefix('staff')->name('staff.')->middleware(['auth', 'verified', 'role:staff'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Staff\DashboardController::class, 'index'])->name('dashboard');
});

// ============================================
// TEACHER ROUTES
// ============================================
Route::prefix('teacher')->name('teacher.')->middleware(['auth', 'verified', 'role:teacher'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/myclasses', [App\Http\Controllers\Teacher\ClassController::class, 'index'])->name('myclasses.index');
    Route::get('/myclasses/{id}', [App\Http\Controllers\Teacher\ClassController::class, 'show'])->name('myclasses.show');
    Route::get('/myclasses/{id}/timetable', [App\Http\Controllers\Teacher\ClassController::class, 'timetable'])->name('myclasses.timetable');
    Route::get('session-plans/get-programs/{campus}', [App\Http\Controllers\Teacher\SessionPlanController::class, 'getProgramsByCampus'])->name('session-plans.get-programs');
    Route::get('session-plans/get-subjects/{program}', [App\Http\Controllers\Teacher\SessionPlanController::class, 'getSubjectsByProgram'])->name('session-plans.get-subjects');
    Route::get('session-plans/get-semesters/{year}', [App\Http\Controllers\Teacher\SessionPlanController::class, 'getSemestersByAcademicYear'])->name('session-plans.get-semesters');
    Route::get('session-plans/get-parents/{subject}', [App\Http\Controllers\Teacher\SessionPlanController::class, 'getParentsBySubject'])->name('session-plans.get-parents');
    Route::resource('session-plans', App\Http\Controllers\Teacher\SessionPlanController::class)->except(['show']);
    Route::get('session-plans/{session_plan}', [App\Http\Controllers\Common\SessionReaderController::class, 'show'])->name('session-plans.show');
});

// ============================================
// STUDENT ROUTES
// ============================================
Route::prefix('student')->name('student.')->middleware(['auth', 'verified', 'role:student'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/session-plans/{id}', [App\Http\Controllers\Common\SessionReaderController::class, 'show'])->name('session-plans.show');
});

// Generic profile routes
use App\Http\Controllers\Auth\TwoFactorController; // Add this import at the top if not present (I'll handle imports carefully)

// ... existing code ...

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Social Auth Disconnect
    Route::delete('social/{provider}', [\App\Http\Controllers\Auth\SocialAuthController::class, 'disconnect'])->name('social.disconnect');

    // Two Factor Auth
    Route::post('two-factor-authentication', [TwoFactorController::class, 'enable'])->name('two-factor.enable');
    Route::post('two-factor-authentication/confirm', [TwoFactorController::class, 'confirm'])->name('two-factor.confirm');
    Route::delete('two-factor-authentication', [TwoFactorController::class, 'disable'])->name('two-factor.disable');
    Route::get('two-factor-authentication/qr-code', [TwoFactorController::class, 'showQrCode'])->name('two-factor.qr-code');
    Route::get('two-factor-authentication/recovery-codes', [TwoFactorController::class, 'recoveryCodes'])->name('two-factor.recovery-codes');
    Route::post('two-factor-authentication/recovery-codes', [TwoFactorController::class, 'regenerateRecoveryCodes'])->name('two-factor.recovery-codes.store');
});

// Student Profile
Route::prefix('student')->name('student.')->middleware(['auth', 'verified', 'role:student'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Teacher Profile
Route::prefix('teacher')->name('teacher.')->middleware(['auth', 'verified', 'role:teacher'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Staff (Staff) Profile
Route::prefix('staff')->name('staff.')->middleware(['auth', 'verified', 'role:staff'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================
// FRONTEND ROUTES - Vue.js SPA Catch-all
// ============================================

// SEO specific route for Blog Posts
Route::get('/blog/{slug}', function ($slug) {
    $post = \App\Models\BlogPost::where('slug', $slug)->where('is_published', true)->first();
    
    if ($post) {
        $meta_title = $post->getTranslatedTitle();
        $meta_description = strip_tags($post->getTranslatedExcerpt() ?: Str::limit($post->getTranslatedContent(), 150));
        $meta_image = $post->image_path ? asset($post->image_path) : asset('uploads/logo/logo.png');
        
        return view('frontend.app', compact('meta_title', 'meta_description', 'meta_image'));
    }
    
    return view('frontend.app');
});

Route::get('/{any}', function () {
    return view('frontend.app');
})->where('any', '.*');

require __DIR__.'/auth.php';

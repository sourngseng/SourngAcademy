<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        $title = 'Login';
        if ($request->is('superadmin/*')) {
            $title = 'Super Admin Login';
        } elseif ($request->is('admin/*')) {
            $title = 'Campus Admin Login';
        } elseif ($request->is('staff/*')) {
            $title = 'Staff Login';
        } elseif ($request->is('teacher/*')) {
            $title = 'Teacher Login';
        } elseif ($request->is('student/*')) {
            $title = 'Student Login';
        }

        return view('auth.login', compact('title'));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirect based on user role
        $user = Auth::user();
        
        if ($user->hasAnyRole(['super_admin', 'campus_admin'])) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        } elseif ($user->hasRole('teacher')) {
            return redirect()->intended(route('teacher.dashboard', absolute: false));
        } elseif ($user->hasRole('student')) {
            return redirect()->intended(route('student.dashboard', absolute: false));
        } elseif ($user->hasRole('staff')) {
            return redirect()->intended(route('staff.dashboard', absolute: false));
        } else {
            return redirect()->intended(route('profile.edit', absolute: false));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

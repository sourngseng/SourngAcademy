<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        // Define Gates for authorization
        
        // Dynamically define gates based on permissions
        try {
            // Check if table exists to avoid errors during migration

            // Check for permissions table (existing code)
            if (\Illuminate\Support\Facades\Schema::hasTable('permissions')) {
                $permissions = \App\Models\Permission::all();
                
                foreach ($permissions as $permission) {
                    Gate::define($permission->name, function (User $user) use ($permission) {
                        // In a real app, cache this query or relation
                        $role = \App\Models\Role::where('name', $user->role)->with('permissions')->first();
                        return $role ? $role->permissions->contains('id', $permission->id) : false;
                    });
                }
            }
            
            // Dynamic Social Auth Configuration
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $settings = \App\Models\Setting::getAll();

                // Google
                if (isset($settings['google_login_enabled']) && $settings['google_login_enabled'] === 'on') {
                    config([
                        'services.google.client_id' => $settings['google_client_id'] ?? null,
                        'services.google.client_secret' => $settings['google_client_secret'] ?? null,
                        'services.google.redirect' => url('auth/google/callback'),
                    ]);
                }

                // Facebook
                if (isset($settings['facebook_login_enabled']) && $settings['facebook_login_enabled'] === 'on') {
                    config([
                        'services.facebook.client_id' => $settings['facebook_app_id'] ?? null,
                        'services.facebook.client_secret' => $settings['facebook_app_secret'] ?? null,
                        'services.facebook.redirect' => url('auth/facebook/callback'),
                    ]);
                }

                // Telegram
                if (isset($settings['telegram_login_enabled']) && $settings['telegram_login_enabled'] === 'on') {
                    config([
                        'services.telegram-login.bot_token' => $settings['telegram_bot_token'] ?? null,
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Fallback
        }
    }
}

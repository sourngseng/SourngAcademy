<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadModuleRoutes();
    }

    protected function loadModuleRoutes()
    {
        $modulePath = app_path('Modules');

        if (File::exists($modulePath)) {
            $modules = File::directories($modulePath);

            foreach ($modules as $module) {
                // Determine module name from path
                $moduleName = basename($module);

                // API Routes
                if (File::exists($module . '/Routes/api.php')) {
                    Route::middleware('api')
                        ->group($module . '/Routes/api.php');
                }

                // Web Routes
                if (File::exists($module . '/Routes/web.php')) {
                    Route::middleware('web')
                        ->group($module . '/Routes/web.php');
                }
            }
        }
    }
}

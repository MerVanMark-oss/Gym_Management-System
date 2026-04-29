<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL; // ✅ added
use App\Models\Admin;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ✅ ADD THIS PART
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        // Define who can access Admin-only features
        Gate::define('access-admin-only', function ($user = null) {
            $admin = $user ?: auth()->guard('admin')->user();

            if (!$admin) {
                return false;
            }

            return in_array($admin->role, ['super_admin', 'admin']);
        });

        // Define who can see the revenue
        Gate::define('view-revenue', function ($user = null) {
            $admin = $user ?: auth()->guard('admin')->user();

            if (!$admin) {
                return false;
            }

            return in_array($admin->role, ['super_admin', 'admin']);
        });
    }
}
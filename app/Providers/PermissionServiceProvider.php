<?php

namespace App\Providers;

use App\Services\Permissions;
use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('permissions', function () {
            return new Permissions();
        });
    }
}

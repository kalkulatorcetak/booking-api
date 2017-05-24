<?php

namespace App\Providers;

use App\Services\Roles;
use Illuminate\Support\ServiceProvider;

class RoleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('roles', function () {
            return new Roles();
        });
    }
}

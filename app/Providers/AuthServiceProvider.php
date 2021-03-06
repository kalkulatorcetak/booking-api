<?php

namespace App\Providers;

use App\Api\V1\Models\Cassa;
use App\Api\V1\Models\User;
use App\Api\V1\Models\Voucher;
use App\Api\V1\Policies\CassaPolicy;
use App\Api\V1\Policies\UserPolicy;
use App\Api\V1\Policies\VoucherPolicy;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Cassa::class => CassaPolicy::class,
        Voucher::class => VoucherPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        $this->app['auth']->viaRequest(
            'api',
            function ($request) {
                return User::where('email', $request->input('email'))->first();
            }
        );
    }

    protected function registerPolicies(): void
    {
        foreach ($this->policies as $modelClass => $policyClass) {
            app(Gate::class)->policy($modelClass, $policyClass);
        }
    }
}

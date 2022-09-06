<?php

namespace App\Providers;

use App\Http\Controllers\WalletController;
use App\Models\SellOrder;
use App\Models\WalletChargeOrder;
use App\Policies\SellOrderPolicy;
use App\Policies\WalletChargeOrderPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use laravel\passport\passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
         'App\Models\Model' => 'App\Policies\ModelPolicy',
         SellOrder::class=> SellOrderPolicy::class,
        WalletChargeOrder::class => WalletChargeOrderPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        if (! $this->app->routesAreCached()) {
            Passport::routes();


        }
    }
}

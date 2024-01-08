<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use App\Policies\ProductPolicy;
use App\Policies\RolePolicy;
use App\Policies\ShopPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Product::class => ProductPolicy::class,
        Shop::class => ShopPolicy::class,
        Role::class => RolePolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        // Gate::define('productIndex', [ProductPolicy::class, 'index']);
    }
}

<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use App\Models\Vcard;
use App\Policies\VcardPolicy;
use App\Models\Category;
use App\Policies\CategoryPolicy;
use App\Models\Transaction;
use App\Policies\TransactionPolicy;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Vcard::class => VcardPolicy::class,
        User::class => UserPolicy::class,
        Category::class => CategoryPolicy::class,
        Transaction::class => TransactionPolicy::class
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}

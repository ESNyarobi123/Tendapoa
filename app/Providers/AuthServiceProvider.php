<?php

namespace App\Providers;

use App\Models\Job;
use App\Policies\JobPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Job::class => JobPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('admin', fn($u)=>$u->role==='admin');
        Gate::define('muhitaji', fn($u)=>$u->role==='muhitaji');
        Gate::define('mfanyakazi', fn($u)=>$u->role==='mfanyakazi');
    }
}

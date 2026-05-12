<?php

namespace App\Providers;
use App\Models\Concept;
use App\Models\Domain;
use App\Policies\DomainPolicy;
use App\Policies\ConceptPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        Gate::policy(Domain::class, DomainPolicy::class);
        Gate::policy(Concept::class, ConceptPolicy::class);
    }
}

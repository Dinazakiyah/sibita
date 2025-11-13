<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\SubmissionFile;
use App\Models\Bimbingan;
use App\Policies\SubmissionFilePolicy;
use App\Policies\BimbinganPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        SubmissionFile::class => SubmissionFilePolicy::class,
        Bimbingan::class => BimbinganPolicy::class,
    ];

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
        $this->registerPolicies();
    }

    /**
     * Register the application's policies.
     */
    protected function registerPolicies(): void
    {
        foreach ($this->policies as $model => $policy) {
            \Illuminate\Support\Facades\Gate::policy($model, $policy);
        }
    }
}

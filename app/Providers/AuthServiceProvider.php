<?php

namespace App\Providers;

use App\Models\Peserta;
use App\Models\training_record;
use App\Policies\PesertaPolicy;
use App\Policies\TrainingRecordPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    protected $policies = [
        Peserta::class => PesertaPolicy::class,
        training_record::class => TrainingRecordPolicy::class,
    ];
    public function register(): void
    {

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}

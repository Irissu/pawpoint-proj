<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Policies\AppointmentPolicy;
use App\Policies\SchedulePolicy;
use App\Policies\SlotPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        UserPolicy::class,
        AppointmentPolicy::class,
        SlotPolicy::class,
        SchedulePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}

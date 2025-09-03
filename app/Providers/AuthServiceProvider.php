<?php

namespace App\Providers;

use App\Models\Facility;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Policies\FacilityPolicy;
use App\Policies\MedicalRecordPolicy;
use App\Policies\PatientPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Patient::class => PatientPolicy::class,
        MedicalRecord::class => MedicalRecordPolicy::class,
        Facility::class => FacilityPolicy::class,

    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}

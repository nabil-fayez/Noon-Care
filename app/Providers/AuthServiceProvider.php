<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Facility;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Permission;
use App\Models\Role;
use App\Policies\AdminPolicy;
use App\Policies\AppointmentPolicy;
use App\Policies\DoctorPolicy;
use App\Policies\FacilityPolicy;
use App\Policies\MedicalRecordPolicy;
use App\Policies\PatientPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Admin::class => \App\Policies\AdminPolicy::class,
        \App\Models\Appointment::class => \App\Policies\AppointmentPolicy::class,
        \App\Models\Doctor::class => \App\Policies\DoctorPolicy::class,
        \App\Models\ErrorLog::class => \App\Policies\ErrorLogPolicy::class,
        \App\Models\Facility::class => \App\Policies\FacilityPolicy::class,
        \App\Models\MedicalRecord::class => \App\Policies\MedicalRecordPolicy::class,
        \App\Models\Patient::class => \App\Policies\PatientPolicy::class,
        \App\Models\Permission::class => \App\Policies\PermissionPolicy::class,
        \App\Models\Role::class => \App\Policies\RolePolicy::class,
        \App\Models\Specialty::class => \App\Policies\SpecialtyPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
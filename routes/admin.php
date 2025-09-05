<?php

use App\Http\Controllers\ErrorLogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SpecialtyController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminIsLoggedInMiddleware;
use App\Http\Middleware\AdminIsLoggedOutMiddleware;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('admin.login');
})->name('home');

Route::middleware('guest')->name('admin.')->group(function () {
    Route::match(['get', 'post'], '/login', [AdminController::class, 'login'])->name('login');
});

Route::middleware('user.type:admin')->name('admin.')->group(function () {
    Route::get('/db', function () {
        return view('admin.db');
    });
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/settings', function () {
        return dd('break point');
    })->name('settings')->middleware('check.permission:settings.view');

    Route::match(['get', 'post'], '/logout', [AdminController::class, 'logout'])->name('logout');

    // Specialties Management
    Route::middleware(['check.permission:specialties.view'])->group(function () {
        Route::get('/specialties', [SpecialtyController::class, 'index'])->name('specialties.index');
        Route::get('/specialty/{specialty}/show', [SpecialtyController::class, 'show'])->name('specialty.show');
    });

    Route::middleware(['check.permission:specialties.create'])->group(function () {
        Route::get('/specialty/create', [SpecialtyController::class, 'create'])->name('specialty.create');
        Route::post('/specialty/store', [SpecialtyController::class, 'store'])->name('specialty.store');
    });

    Route::middleware(['check.permission:specialties.update'])->group(function () {
        Route::get('/specialty/{specialty}/edit', [SpecialtyController::class, 'edit'])->name('specialty.edit');
        Route::put('/specialty/{specialty}/update', [SpecialtyController::class, 'update'])->name('specialty.update');
        Route::post('/specialty/{specialty}/toggle-status', [SpecialtyController::class, 'toggleStatus'])->name('specialty.toggleStatus');
    });

    Route::middleware(['check.permission:specialties.delete'])->group(function () {
        Route::get('/specialty/{specialty}/delete', [SpecialtyController::class, 'delete'])->name('specialty.delete');
        Route::delete('/specialty/{specialty}/destroy', [SpecialtyController::class, 'destroy'])->name('specialty.destroy');
    });
    // End Specialties Management

    // Doctors Management Routes
    Route::middleware(['check.permission:doctors.view'])->group(function () {
        Route::get('/doctors/{per_page?}', [DoctorController::class, 'index'])->name('doctors.index');
        Route::get('/doctors/trashed/{per_page?}', [DoctorController::class, 'trashed'])->name('doctors.trashed');
        Route::get('/doctor/{doctor}/show', [DoctorController::class, 'show'])->name('doctor.show');
    });

    Route::middleware(['check.permission:doctors.create'])->group(function () {
        Route::get('/doctor/create', [DoctorController::class, 'create'])->name('doctor.create');
        Route::post('/doctor/store', [DoctorController::class, 'store'])->name('doctor.store');
    });

    Route::middleware(['check.permission:doctors.update'])->group(function () {
        Route::get('/doctor/{doctor}/edit', [DoctorController::class, 'edit'])->name('doctor.edit');
        Route::put('/doctor/{doctor}/update', [DoctorController::class, 'update'])->name('doctor.update');
        Route::put('/doctors/{doctor}/update/specialties', [DoctorController::class, 'updateSpecialties'])->name('doctor.updateSpecialties');
        Route::post('/doctor/{doctor}/toggle-verification', [DoctorController::class, 'toggleVerification'])->name('doctor.toggleVerification');
    });

    Route::middleware(['check.permission:doctors.delete'])->group(function () {
        Route::get('/doctor/{doctor}/delete', [DoctorController::class, 'delete'])->name('doctor.delete');
        Route::delete('/doctor/{doctor}/destroy', [DoctorController::class, 'destroy'])->name('doctor.destroy');
        Route::delete('/doctor/{id}/force', [DoctorController::class, 'forceDestroy'])->name('doctor.forceDestroy');
    });

    Route::middleware(['check.permission:doctors.restore'])->group(function () {
        Route::post('/doctor/{id}/restore', [DoctorController::class, 'restore'])->name('doctor.restore');
    });
    // End Doctors Management Routes

    // Patients Management
    Route::middleware(['check.permission:patients.view'])->group(function () {
        Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
        Route::get('/patient/{patient}/show', [PatientController::class, 'show'])->name('patient.show');
        Route::get('/patient/{patient}/medical-history', [PatientController::class, 'medicalHistory'])->name('patient.medicalHistory');
    });

    Route::middleware(['check.permission:patients.create'])->group(function () {
        Route::get('/patient/create', [PatientController::class, 'create'])->name('patient.create');
        Route::post('/patient/store', [PatientController::class, 'store'])->name('patient.store');
    });

    Route::middleware(['check.permission:patients.update'])->group(function () {
        Route::get('/patient/{patient}/edit', [PatientController::class, 'edit'])->name('patient.edit');
        Route::put('/patient/{patient}/update', [PatientController::class, 'update'])->name('patient.update');
        Route::post('/patient/{patient}/toggle-status', [PatientController::class, 'toggleStatus'])->name('patient.toggleStatus');
    });

    Route::middleware(['check.permission:patients.delete'])->group(function () {
        Route::get('/patient/{patient}/delete', [PatientController::class, 'delete'])->name('patient.delete');
        Route::delete('/patient/{patient}/destroy', [PatientController::class, 'destroy'])->name('patient.destroy');
    });
    // End Patients Management

    // Facilities Management
    Route::middleware(['check.permission:facilities.view'])->group(function () {
        Route::get('/facilities', [FacilityController::class, 'index'])->name('facilities.index');
        Route::get('/facility/{facility}/show', [FacilityController::class, 'show'])->name('facility.show');
        Route::get('/facility/{facility}/doctors', [FacilityController::class, 'doctors'])->name('facility.doctors');
        Route::get('/facility/{facility}/services', [FacilityController::class, 'services'])->name('facility.services');
        Route::get('/facility/{facility}/appointments', [FacilityController::class, 'appointments'])->name('facility.appointments');
    });

    Route::middleware(['check.permission:facilities.create'])->group(function () {
        Route::get('/facility/create', [FacilityController::class, 'create'])->name('facility.create');
        Route::post('/facility/store', [FacilityController::class, 'store'])->name('facility.store');
    });

    Route::middleware(['check.permission:facilities.update'])->group(function () {
        Route::get('/facility/{facility}/edit', [FacilityController::class, 'edit'])->name('facility.edit');
        Route::put('/facility/{facility}/update', [FacilityController::class, 'update'])->name('facility.update');
        Route::post('/facility/{facility}/toggle-status', [FacilityController::class, 'toggleStatus'])->name('facility.toggleStatus');
    });

    Route::middleware(['check.permission:facilities.delete'])->group(function () {
        Route::get('/facility/{facility}/delete', [FacilityController::class, 'delete'])->name('facility.delete');
        Route::delete('/facility/{facility}/destroy', [FacilityController::class, 'destroy'])->name('facility.destroy');
    });
    // End Facilities Management

    // Medical Records Management
    Route::middleware(['check.permission:medical_records.view'])->group(function () {
        Route::get('/medical-records', [MedicalRecordController::class, 'index'])->name('medical_records.index');
        Route::get('/medical-record/{medicalRecord}/show', [MedicalRecordController::class, 'show'])->name('medical_record.show');
        Route::get('/patient/{patient}/medical-records/print', [MedicalRecordController::class, 'patientRecords'])->name('medical_record.patient');
    });

    Route::middleware(['check.permission:medical_records.create'])->group(function () {
        Route::get('/medical-record/create', [MedicalRecordController::class, 'create'])->name('medical_record.create');
        Route::post('/medical-record/store', [MedicalRecordController::class, 'store'])->name('medical_record.store');
    });

    Route::middleware(['check.permission:medical_records.update'])->group(function () {
        Route::get('/medical-record/{medicalRecord}/edit', [MedicalRecordController::class, 'edit'])->name('medical_record.edit');
        Route::put('/medical-record/{medicalRecord}/update', [MedicalRecordController::class, 'update'])->name('medical_record.update');
    });

    Route::middleware(['check.permission:medical_records.delete'])->group(function () {
        Route::delete('/medical-record/{medicalRecord}/destroy', [MedicalRecordController::class, 'destroy'])->name('medical_record.destroy');
    });
    // End Medical Records Management

    // Error Logs Routes
    Route::middleware(['check.permission:error_logs.view'])->group(function () {
        Route::get('/error-logs', [ErrorLogController::class, 'index'])->name('error-logs.index');
        Route::get('/error-logs/{errorLog}', [ErrorLogController::class, 'show'])->name('error-logs.show');
    });

    Route::middleware(['check.permission:error_logs.delete'])->group(function () {
        Route::delete('/error-logs/{errorLog}', [ErrorLogController::class, 'destroy'])->name('error-logs.destroy');
    });

    Route::middleware(['check.permission:error_logs.clear'])->group(function () {
        Route::post('/error-logs/clear', function () {
            dd('test');
        })->name('error-logs.clear');
    });
    // End Error Logs Routes

    // Appointment Management
    Route::middleware(['check.permission:appointments.view'])->group(function () {
        Route::get('appointments/trashed', [AppointmentController::class, 'trashed'])->name('appointments.trashed');
        Route::resource('appointments', AppointmentController::class)->except(['create', 'store', 'update', 'destroy']);
    });

    Route::middleware(['check.permission:appointments.create'])->group(function () {
        Route::get('appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    });

    Route::middleware(['check.permission:appointments.update'])->group(function () {
        Route::put('appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
        Route::post('appointments/{appointment}/update-status', [AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');
    });

    Route::middleware(['check.permission:appointments.delete'])->group(function () {
        Route::delete('appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
        Route::delete('appointments/{id}/force-delete', [AppointmentController::class, 'forceDelete'])->name('appointments.forceDelete');
    });

    Route::middleware(['check.permission:appointments.restore'])->group(function () {
        Route::post('appointments/{id}/restore', [AppointmentController::class, 'restore'])->name('appointments.restore');
    });
    // End Appointment Management
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index')->middleware('check.permission:roles.view');
    Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create')->middleware('check.permission:roles.create');
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store')->middleware('check.permission:roles.create');
    Route::get('roles/{role}', [RoleController::class, 'show'])->name('roles.show')->middleware('check.permission:roles.view');
    Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit')->middleware('check.permission:roles.update');
    Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update')->middleware('check.permission:roles.update');
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('check.permission:roles.delete');

    // إدارة الصلاحيات
    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index')->middleware('check.permission:permissions.view');
    Route::get('permissions/create', [PermissionController::class, 'create'])->name('permissions.create')->middleware('check.permission:permissions.create');
    Route::post('permissions', [PermissionController::class, 'store'])->name('permissions.store')->middleware('check.permission:permissions.create');
    Route::get('permissions/{permission}', [PermissionController::class, 'show'])->name('permissions.show')->middleware('check.permission:permissions.view');
    Route::get('permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit')->middleware('check.permission:permissions.update');
    Route::put('permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update')->middleware('check.permission:permissions.update');
    Route::delete('permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy')->middleware('check.permission:permissions.delete');

    // Reports
    Route::middleware(['check.permission:reports.view'])->group(function () {
        Route::get('/reports', function () {
            return view('admin.reports.bookings');
        })->name('reports.bookings');
    });

    Route::get('/test-permissions', function () {
        $admin = auth('admin')->user();

        if (!$admin) {
            return "لم يتم تسجيل الدخول";
        }

        $permissionsToCheck = [
            'doctors.view',
            'doctors.create',
            'patients.view',
            'facilities.view'
        ];

        $results = [];
        foreach ($permissionsToCheck as $permission) {
            $results[$permission] = $admin->hasPermission($permission) ? 'نعم' : 'لا';
        }

        return dd([
            'admin' => $admin->name,
            'role' => $admin->role ? $admin->role->role_name : 'لا يوجد دور',
            'permissions' => $results
        ]);
    })->middleware('auth:admin');
});

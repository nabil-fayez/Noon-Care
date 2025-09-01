<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\SpecialtyController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminIsLoggedInMiddleware;
use App\Http\Middleware\AdminIsLoggedOutMiddleware;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('admin.login');
})->name('admin.home');

Route::middleware('guest')->group(function () {
    Route::match(['get', 'post'], '/login', [AdminController::class, 'login'])->name('admin.login');
});

Route::middleware('user.type:admin')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/settings', function () {
        return dd('break point');
    })->name('admin.settings');
    Route::match(['get', 'post'], '/logout', [AdminController::class, 'logout'])->name('admin.logout');

    // Specialties Management
    Route::get('/specialties/{page?}', [SpecialtyController::class, 'index'])->name('admin.specialties.index');
    Route::match(['get', 'post'], '/specialty/create', [SpecialtyController::class, 'create'])->name('admin.specialty.create');
    Route::get('/specialty/show/{id}', [SpecialtyController::class, 'show'])->name('admin.specialty.show');
    Route::match(['get', 'put'], '/specialty/update/{id}', [SpecialtyController::class, 'update'])->name('admin.specialty.update');
    Route::match(['get', 'delete'], '/specialty/delete/{id}', [SpecialtyController::class, 'delete'])->name('admin.specialty.delete');

    // Doctors Management Routes
    Route::get('/doctors/{per_page?}', [DoctorController::class, 'index'])->name('admin.doctors.index');
    Route::get('/doctors/trashed/{per_page?}', [DoctorController::class, 'trashed'])->name('admin.doctors.trashed');
    Route::get('/doctor/create', [DoctorController::class, 'create'])->name('admin.doctor.create');
    Route::post('/doctor/store', [DoctorController::class, 'store'])->name('admin.doctor.store');
    Route::get('/doctor/{doctor}/show', [DoctorController::class, 'show'])->name('admin.doctor.show');
    Route::get('/doctor/{doctor}/edit', [DoctorController::class, 'edit'])->name('admin.doctor.edit');
    Route::put('/doctor/{doctor}/update', [DoctorController::class, 'update'])->name('admin.doctor.update');
    Route::put('/doctors/{doctor}/update/specialties', [DoctorController::class, 'updateSpecialties'])->name('admin.doctor.updateSpecialties');
    Route::get('/doctor/{doctor}/delete', [DoctorController::class, 'delete'])->name('admin.doctor.delete');
    Route::delete('/doctor/{doctor}/destroy', [DoctorController::class, 'destroy'])->name('admin.doctor.destroy');
    Route::post('/doctor/{id}/restore', [DoctorController::class, 'restore'])->name('admin.doctor.restore');
    Route::delete('/doctor/{id}/force', [DoctorController::class, 'forceDestroy'])->name('admin.doctor.forceDestroy');
    Route::post('/doctor/{doctor}/toggle-verification', [DoctorController::class, 'toggleVerification'])->name('admin.doctor.toggleVerification');
    // End Doctors Management Routes

    // Patients Management
    Route::get('/patients/{page?}', [PatientController::class, 'index'])->name('admin.patients.index');
    Route::match(['get', 'post'], '/patient/create', [PatientController::class, 'create'])->name('admin.patient.create');
    Route::get('/patient/show/{id}', [PatientController::class, 'show'])->name('admin.patient.show');
    Route::match(['get', 'put'], '/patient/update/{id}', [PatientController::class, 'update'])->name('admin.patient.update');
    Route::match(['get', 'delete'], '/patient/delete/{id}', [PatientController::class, 'delete'])->name('admin.patient.delete');
    Route::get('/patient/restore/{id}', [PatientController::class, 'restore'])->name('admin.patient.restore');
    Route::get('/patient/destroy/{id}', [PatientController::class, 'destroy'])->name('admin.patient.destroy');

    // Facilities Management
    Route::get('/facilities/{page?}', [FacilityController::class, 'index'])->name('admin.facilities.index');
    Route::match(['get', 'post'], '/facility/create', [FacilityController::class, 'create'])->name('admin.facility.create');
    Route::get('/facility/show/{id}', [FacilityController::class, 'show'])->name('admin.facility.show');
    Route::match(['get', 'put'], '/facility/update/{id}', [FacilityController::class, 'update'])->name('admin.facility.update');
    Route::match(['get', 'delete'], '/facility/delete/{id}', [FacilityController::class, 'delete'])->name('admin.facility.delete');
    Route::get('/facility/restore/{id}', [FacilityController::class, 'restore'])->name('admin.facility.restore');
    Route::get('/facility/destroy/{id}', [FacilityController::class, 'destroy'])->name('admin.facility.destroy');

    //



    Route::get('/appointments', function () {
        return view('admin.appointments.index');
    })->name('admin.appointments.index');
    Route::get('/reports', function () {
        return view('admin.reports.bookings');
    })->name('admin.reports.bookings');
});
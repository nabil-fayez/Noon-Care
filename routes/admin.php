<?php

use App\Http\Controllers\ErrorLogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PatientController;
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

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/settings', function () {
        return dd('break point');
    })->name('settings');
    Route::match(['get', 'post'], '/logout', [AdminController::class, 'logout'])->name('logout');

    // Specialties Management
    Route::get('/specialties', [SpecialtyController::class, 'index'])->name('specialties.index');
    Route::get('/specialty/create', [SpecialtyController::class, 'create'])->name('specialty.create');
    Route::post('/specialty/store', [SpecialtyController::class, 'store'])->name('specialty.store');
    Route::get('/specialty/{specialty}/show', [SpecialtyController::class, 'show'])->name('specialty.show');
    Route::get('/specialty/{specialty}/edit', [SpecialtyController::class, 'edit'])->name('specialty.edit');
    Route::put('/specialty/{specialty}/update', [SpecialtyController::class, 'update'])->name('specialty.update');
    Route::get('/specialty/{specialty}/delete', [SpecialtyController::class, 'delete'])->name('specialty.delete');
    Route::delete('/specialty/{specialty}/destroy', [SpecialtyController::class, 'destroy'])->name('specialty.destroy');
    Route::post('/specialty/{specialty}/toggle-status', [SpecialtyController::class, 'toggleStatus'])->name('specialty.toggleStatus');


    // End Specialties Management
    // Doctors Management Routes
    Route::get('/doctors/{per_page?}', [DoctorController::class, 'index'])->name('doctors.index');
    Route::get('/doctors/trashed/{per_page?}', [DoctorController::class, 'trashed'])->name('doctors.trashed');
    Route::get('/doctor/create', [DoctorController::class, 'create'])->name('doctor.create');
    Route::post('/doctor/store', [DoctorController::class, 'store'])->name('doctor.store');
    Route::get('/doctor/{doctor}/show', [DoctorController::class, 'show'])->name('doctor.show');
    Route::get('/doctor/{doctor}/edit', [DoctorController::class, 'edit'])->name('doctor.edit');
    Route::put('/doctor/{doctor}/update', [DoctorController::class, 'update'])->name('doctor.update');
    Route::put('/doctors/{doctor}/update/specialties', [DoctorController::class, 'updateSpecialties'])->name('doctor.updateSpecialties');
    Route::get('/doctor/{doctor}/delete', [DoctorController::class, 'delete'])->name('doctor.delete');
    Route::delete('/doctor/{doctor}/destroy', [DoctorController::class, 'destroy'])->name('doctor.destroy');
    Route::post('/doctor/{id}/restore', [DoctorController::class, 'restore'])->name('doctor.restore');
    Route::delete('/doctor/{id}/force', [DoctorController::class, 'forceDestroy'])->name('doctor.forceDestroy');
    Route::post('/doctor/{doctor}/toggle-verification', [DoctorController::class, 'toggleVerification'])->name('doctor.toggleVerification');
    // End Doctors Management Routes

    // Patients Management
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/patient/create', [PatientController::class, 'create'])->name('patient.create');
    Route::post('/patient/store', [PatientController::class, 'store'])->name('patient.store');
    Route::get('/patient/{patient}/show', [PatientController::class, 'show'])->name('patient.show');
    Route::get('/patient/{patient}/edit', [PatientController::class, 'edit'])->name('patient.edit');
    Route::put('/patient/{patient}/update', [PatientController::class, 'update'])->name('patient.update');
    Route::get('/patient/{patient}/delete', [PatientController::class, 'delete'])->name('patient.delete');
    Route::delete('/patient/{patient}/destroy', [PatientController::class, 'destroy'])->name('patient.destroy');
    Route::post('/patient/{patient}/toggle-status', [PatientController::class, 'toggleStatus'])->name('patient.toggleStatus');
    Route::get('/patient/{patient}/medical-history', [PatientController::class, 'medicalHistory'])->name('patient.medicalHistory');
    // End Patients Management

    // Facilities Management
    Route::get('/facilities', [FacilityController::class, 'index'])->name('facilities.index');
    Route::get('/facility/create', [FacilityController::class, 'create'])->name('facility.create');
    Route::post('/facility/store', [FacilityController::class, 'store'])->name('facility.store');
    Route::get('/facility/{facility}/show', [FacilityController::class, 'show'])->name('facility.show');
    Route::get('/facility/{facility}/edit', [FacilityController::class, 'edit'])->name('facility.edit');
    Route::put('/facility/{facility}/update', [FacilityController::class, 'update'])->name('facility.update');
    Route::get('/facility/{facility}/delete', [FacilityController::class, 'delete'])->name('facility.delete');
    Route::delete('/facility/{facility}/destroy', [FacilityController::class, 'destroy'])->name('facility.destroy');
    Route::post('/facility/{facility}/toggle-status', [FacilityController::class, 'toggleStatus'])->name('facility.toggleStatus');
    Route::get('/facility/{facility}/doctors', [FacilityController::class, 'doctors'])->name('facility.doctors');
    Route::get('/facility/{facility}/services', [FacilityController::class, 'services'])->name('facility.services');
    Route::get('/facility/{facility}/appointments', [FacilityController::class, 'appointments'])->name('facility.appointments');

    // Medical Records Management
    Route::get('/medical-records', [MedicalRecordController::class, 'index'])->name('medical_records.index');
    Route::get('/medical-record/create', [MedicalRecordController::class, 'create'])->name('medical_record.create');
    Route::post('/medical-record/store', [MedicalRecordController::class, 'store'])->name('medical_record.store');
    Route::get('/medical-record/{medicalRecord}/show', [MedicalRecordController::class, 'show'])->name('medical_record.show');
    Route::get('/medical-record/{medicalRecord}/edit', [MedicalRecordController::class, 'edit'])->name('medical_record.edit');
    Route::put('/medical-record/{medicalRecord}/update', [MedicalRecordController::class, 'update'])->name('medical_record.update');
    Route::delete('/medical-record/{medicalRecord}/destroy', [MedicalRecordController::class, 'destroy'])->name('medical_record.destroy');
    Route::get('/patient/{patient}/medical-records/print', [MedicalRecordController::class, 'patientRecords'])->name('medical_record.patient');
    // End Medical Records Management
    // Error Logs Routes
    Route::get('/error-logs', [ErrorLogController::class, 'index'])->name('error-logs.index');
    Route::get('/error-logs/{errorLog}', [ErrorLogController::class, 'show'])->name('error-logs.show');
    Route::delete('/error-logs/{errorLog}', [ErrorLogController::class, 'destroy'])->name('error-logs.destroy');
    Route::post('/error-logs/clear', [ErrorLogController::class, 'clearOldLogs'])->name('error-logs.clear');
    // End Error Logs Routes



    Route::get('/appointments', function () {
        return view('admin.appointments.index');
    })->name('appointments.index');
    Route::get('/reports', function () {
        return view('admin.reports.bookings');
    })->name('reports.bookings');
});

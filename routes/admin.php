<?php

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
    Route::get('/specialties', [SpecialtyController::class, 'index'])->name('admin.specialties.index');
    Route::get('/specialty/create', [SpecialtyController::class, 'create'])->name('admin.specialty.create');
    Route::post('/specialty/store', [SpecialtyController::class, 'store'])->name('admin.specialty.store');
    Route::get('/specialty/{specialty}/show', [SpecialtyController::class, 'show'])->name('admin.specialty.show');
    Route::get('/specialty/{specialty}/edit', [SpecialtyController::class, 'edit'])->name('admin.specialty.edit');
    Route::put('/specialty/{specialty}/update', [SpecialtyController::class, 'update'])->name('admin.specialty.update');
    Route::get('/specialty/{specialty}/delete', [SpecialtyController::class, 'delete'])->name('admin.specialty.delete');
    Route::delete('/specialty/{specialty}/destroy', [SpecialtyController::class, 'destroy'])->name('admin.specialty.destroy');
    Route::post('/specialty/{specialty}/toggle-status', [SpecialtyController::class, 'toggleStatus'])->name('admin.specialty.toggleStatus');


    // End Specialties Management
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
    Route::get('/patients', [PatientController::class, 'index'])->name('admin.patients.index');
    Route::get('/patient/create', [PatientController::class, 'create'])->name('admin.patient.create');
    Route::post('/patient/store', [PatientController::class, 'store'])->name('admin.patient.store');
    Route::get('/patient/{patient}/show', [PatientController::class, 'show'])->name('admin.patient.show');
    Route::get('/patient/{patient}/edit', [PatientController::class, 'edit'])->name('admin.patient.edit');
    Route::put('/patient/{patient}/update', [PatientController::class, 'update'])->name('admin.patient.update');
    Route::get('/patient/{patient}/delete', [PatientController::class, 'delete'])->name('admin.patient.delete');
    Route::delete('/patient/{patient}/destroy', [PatientController::class, 'destroy'])->name('admin.patient.destroy');
    Route::post('/patient/{patient}/toggle-status', [PatientController::class, 'toggleStatus'])->name('admin.patient.toggleStatus');
    Route::get('/patient/{patient}/medical-history', [PatientController::class, 'medicalHistory'])->name('admin.patient.medicalHistory');
    // End Patients Management

    // Facilities Management
    Route::get('/facilities/{page?}', [FacilityController::class, 'index'])->name('admin.facilities.index');
    Route::match(['get', 'post'], '/facility/create', [FacilityController::class, 'create'])->name('admin.facility.create');
    Route::get('/facility/show/{id}', [FacilityController::class, 'show'])->name('admin.facility.show');
    Route::match(['get', 'put'], '/facility/update/{id}', [FacilityController::class, 'update'])->name('admin.facility.update');
    Route::match(['get', 'delete'], '/facility/delete/{id}', [FacilityController::class, 'delete'])->name('admin.facility.delete');
    Route::get('/facility/restore/{id}', [FacilityController::class, 'restore'])->name('admin.facility.restore');
    Route::get('/facility/destroy/{id}', [FacilityController::class, 'destroy'])->name('admin.facility.destroy');

    // Medical Records Management
    Route::get('/medical-records', [MedicalRecordController::class, 'index'])->name('admin.medical_records.index');
    Route::get('/medical-record/create', [MedicalRecordController::class, 'create'])->name('admin.medical_record.create');
    Route::post('/medical-record/store', [MedicalRecordController::class, 'store'])->name('admin.medical_record.store');
    Route::get('/medical-record/{medicalRecord}/show', [MedicalRecordController::class, 'show'])->name('admin.medical_record.show');
    Route::get('/medical-record/{medicalRecord}/edit', [MedicalRecordController::class, 'edit'])->name('admin.medical_record.edit');
    Route::put('/medical-record/{medicalRecord}/update', [MedicalRecordController::class, 'update'])->name('admin.medical_record.update');
    Route::delete('/medical-record/{medicalRecord}/destroy', [MedicalRecordController::class, 'destroy'])->name('admin.medical_record.destroy');
    Route::get('/patient/{patient}/medical-records/print', [MedicalRecordController::class, 'patientRecords'])->name('admin.medical_record.patient');
    // End Medical Records Management

    Route::get('/appointments', function () {
        return view('admin.appointments.index');
    })->name('admin.appointments.index');
    Route::get('/reports', function () {
        return view('admin.reports.bookings');
    })->name('admin.reports.bookings');
});
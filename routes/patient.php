<?php

use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpecialtyController;

// Route::get('/login/patient', function () {
//     return view('auth.patient.login');
// })->name('patient.login');
Route::middleware('guest')->group(function () {
    Route::match(['get', 'post'], '/login', [PatientController::class, 'login'])->name('patient.login');
    Route::match(['get', 'post'], '/register', [PatientController::class, 'register'])->name('patient.register');
});

Route::middleware(['user.type:patient'])->group(function () {
    Route::match(['get', 'post'], '/logout', [PatientController::class, 'logout'])->name('patient.logout');

    Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('patient.dashboard');
    Route::match(['get', 'post'], 'appointment/create', [PatientController::class, 'appointments'])->name('patient.appointment.create');
    Route::get('/appointment/show/{id}', [PatientController::class, 'appointments'])->name('patient.appointment.show');
    Route::get('/appointment/cancel/{id}', [PatientController::class, 'appointments'])->name('patient.appointment.cancel');
    Route::match(['get', 'post'], '/appointment/review/create', [PatientController::class, 'appointments'])->name('patient.review.create');
    Route::get('/patient/{patient}/show', [PatientController::class, 'show'])->name('patient.show');

    Route::get('/profile/show', [PatientController::class, 'show'])->name('patient.profile');
    Route::get('/profile/edit', [PatientController::class, 'edit'])->name('patient.profile.edit');
    Route::put('/profile/update', [PatientController::class, 'update'])->name('patient.profile.update');
    Route::get('/medical-history', [PatientController::class, 'medicalHistory'])->name('patient.medicalHistory');
    Route::get('/appointments', [PatientController::class, 'appointments'])->name('patient.appointments');
    // Medical Records Management
    Route::get('/medical-record/create', [MedicalRecordController::class, 'create'])->name('medical_record.create');
    Route::post('/medical-record/store', [MedicalRecordController::class, 'store'])->name('medical_record.store');
    Route::get('/medical-record/{medicalRecord}/show', [MedicalRecordController::class, 'show'])->name('medical_record.show');
    Route::get('/medical-record/{medicalRecord}/edit', [MedicalRecordController::class, 'edit'])->name('medical_record.edit');
    Route::put('/medical-record/{medicalRecord}/update', [MedicalRecordController::class, 'update'])->name('medical_record.update');
    Route::delete('/medical-record/{medicalRecord}/destroy', [MedicalRecordController::class, 'destroy'])->name('medical_record.destroy');
    Route::get('/patient/{patient}/medical-records/print', [MedicalRecordController::class, 'patientRecords'])->name('medical_record.patient');
    // End Medical Records Management
});
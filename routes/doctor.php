<?php

use App\Http\Controllers\DoctorController;
use App\Http\Controllers\MedicalRecordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpecialtyController;

Route::middleware(['user.type:doctor'])->prefix('doctor')->group(function () {
    Route::get('/dashboard', [DoctorController::class, 'dashboard'])->name('doctor.dashboard');
    Route::get('/appointments', [DoctorController::class, 'appointments'])->name('doctor.appointments');
    Route::get('/schedule', [DoctorController::class, 'schedule'])->name('doctor.schedule');

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
});
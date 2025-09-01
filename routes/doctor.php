<?php

use App\Http\Controllers\DoctorController;
use Illuminate\Support\Facades\Route;

Route::middleware(['user.type:doctor'])->prefix('doctor')->group(function () {
    Route::get('/dashboard', [DoctorController::class, 'dashboard'])->name('doctor.dashboard');
    Route::get('/appointments', [DoctorController::class, 'appointments'])->name('doctor.appointments');
    Route::get('/schedule', [DoctorController::class, 'schedule'])->name('doctor.schedule');
});
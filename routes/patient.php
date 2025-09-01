<?php

use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;

// Route::get('/login/patient', function () {
//     return view('auth.patient.login');
// })->name('patient.login');

Route::middleware(['user.type:patient'])->group(function () {
    Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('patient.dashboard');
    Route::get('/appointments', [PatientController::class, 'appointments'])->name('patient.appointments');
    Route::match(['get', 'post'], 'appointment/create', [PatientController::class, 'appointments'])->name('patient.appointment.create');
    Route::get('/appointment/show/{id}', [PatientController::class, 'appointments'])->name('patient.appointment.show');
    Route::get('/appointment/cancel/{id}', [PatientController::class, 'appointments'])->name('patient.appointment.cancel');
    Route::match(['get', 'post'], '/appointment/review/create', [PatientController::class, 'appointments'])->name('patient.review.create');

    Route::get('/profile', [PatientController::class, 'profile'])->name('patient.profile');
});
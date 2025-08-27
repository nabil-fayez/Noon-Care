<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\SpecialtyController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminIsLoggedInMiddleware;
use App\Http\Middleware\AdminIsLoggedOutMiddleware;

Route::get('/', function () {
    return redirect()->route('admin.login');
})->name('admin.home');

Route::middleware(AdminIsLoggedOutMiddleware::class)->group(function () {
    Route::match(['get', 'post'], '/login', [AdminController::class, 'login'])->name('admin.login');
});

Route::middleware(AdminIsLoggedInMiddleware::class)->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::match(['get', 'post'], '/logout', [AdminController::class, 'logout'])->name('admin.logout');

    // Specialties Management
    Route::get('/specialties/{page?}', [SpecialtyController::class, 'index'])->name('admin.specialties.index');
    Route::match(['get', 'post'], '/specialty/create', [SpecialtyController::class, 'create'])->name('admin.specialty.create');
    Route::get('/specialty/show/{id}', [SpecialtyController::class, 'show'])->name('admin.specialty.show');
    Route::match(['get', 'post'], '/specialty/update/{id}', [SpecialtyController::class, 'update'])->name('admin.specialty.update');
    Route::match(['get', 'post'], '/specialty/delete/{id}', [SpecialtyController::class, 'delete'])->name('admin.specialty.delete');

    // Doctors Management

    Route::get('/doctors/{page?}', [DoctorController::class, 'index'])->name('admin.doctors.index');
    Route::match(['get', 'post'], '/doctors/create', [DoctorController::class, 'create'])->name('admin.doctors.create');


    Route::get('/patients', function () {
        return view('admin.patient.index');
    })->name('admin.patients.index');
    Route::get('/facilities', function () {
        return view('admin.facility.index');
    })->name('admin.facilities.index');
    Route::get('/appointments', function () {
        return view('admin.appointment.index');
    })->name('admin.appointments.index');
    Route::get('/reports', function () {
        return view('admin.reports.bookings');
    })->name('admin.reports.bookings');
});
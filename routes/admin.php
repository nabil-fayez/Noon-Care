<?php
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminIsLoggedInMiddleware;
use App\Http\Middleware\AdminIsLoggedOutMiddleware;

Route::get('/', function () {

    return redirect()->route('admin.login');
})->name('admin.home');

    Route::middleware(AdminIsLoggedOutMiddleware::class)->group(function () {
        Route::get('/login', function () {
            return view('auth.admin.login');
        })->name('admin.login');
    });

    Route::middleware(AdminIsLoggedInMiddleware::class)->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
        Route::get('/doctors', function()
        {
            return view('admin.doctor.index');
        })->name('admin.doctors.index');

        Route::get('/patients', function()
        {
            return view('admin.patient.index');
        })->name('admin.patients.index');
   Route::get('/facilities', function()
        {
            return view('admin.facility.index');
        })->name('admin.facilities.index');
           Route::get('/appointments', function()
        {
            return view('admin.appointment.index');
        })->name('admin.appointments.index');
                   Route::get('/reports', function()
        {
            return view('admin.reports.bookings');
        })->name('admin.reports.bookings');
    });
    



<?php
use Illuminate\Support\Facades\Route;

Route::domain('admin.nooncare.test')->group(function () {
    Route::get('/', function () {
        dd('admin./');
        // return redirect()->route('admin.dashboard');
    });


    Route::middleware(AdminIsLoggedInMiddleware::class)->group(function () {
        Route::get('/dashboard', function () {
            dd('admin.dashboard');
            // return view('admin.dashboard');
        })->name('admin.dashboard');

    });
    
    Route::middleware(AdminIsLoggedOutMiddleware::class)->group(function () {
        Route::get('/login', function () {
            dd('admin.login');
        // return view('auth.admin.login');
        })->name('admin.login');
    });
});

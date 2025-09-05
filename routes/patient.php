<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpecialtyController;

Route::get('/', function () {
    return redirect()->route('patient.login');
})->name('patient.home');
Route::middleware('guest')->name('patient.')->group(function () {
    Route::get('/login', [PatientController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [PatientController::class, 'login']);
    Route::get('/register', [PatientController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [PatientController::class, 'register']);
    Route::get('/forgot-password', [PatientController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [PatientController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PatientController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [PatientController::class, 'resetPassword'])->name('password.update');
});

Route::middleware(['user.type:patient'])->name('patient.')->group(function () {
    Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('patient.dashboard');
    Route::post('/logout', [PatientController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');

    // Profile
    Route::get('/profile', [PatientController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [PatientController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [PatientController::class, 'updateProfile'])->name('profile.update');
    // Appointments
    Route::get('/appointments', [AppointmentController::class, 'patientIndex'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments/store', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{appointment}/show', [AppointmentController::class, 'patientShow'])->name('appointments.show');
    Route::delete('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    // Medical Records
    Route::get('/medical-history', [PatientController::class, 'medicalHistory'])->name('medicalHistory');
    Route::get('/medical-records/{medicalRecord}', [MedicalRecordController::class, 'patientShow'])->name('medicalRecords.show');
    // Invoices
    // Route::get('/invoices', [InvoiceController::class, 'patientIndex'])->name('invoices.index');
    // Route::get('/invoices/{invoice}', [InvoiceController::class, 'patientShow'])->name('invoices.show');
    // Route::post('/invoices/{invoice}/pay', [InvoiceController::class, 'pay'])->name('invoices.pay');

    // Doctors
    Route::get('/doctors', [DoctorController::class, 'patientIndex'])->name('doctors.index');
    Route::get('/doctors/{doctor}', [DoctorController::class, 'patientShow'])->name('doctors.show');
    Route::get('/doctors/search', [DoctorController::class, 'search'])->name('doctors.search');

    Route::get('/appointments', [AppointmentController::class, 'patientIndex'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'patientCreate'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{appointment}', [AppointmentController::class, 'patientShow'])->name('appointments.show');
    Route::post('/appointments/{appointment}/cancel', [AppointmentController::class, 'patientCancel'])->name('appointments.cancel');

    // فواتير المريض
    Route::get('/invoices', [InvoiceController::class, 'patientIndex'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'patientShow'])->name('invoices.show');
    Route::post('/invoices/{invoice}/pay', [InvoiceController::class, 'patientPay'])->name('invoices.pay');

    // Notifications
    // Route::get('/notifications', [NotificationController::class, 'patientIndex'])->name('notifications.index');
    // Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    // Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    //
    // Route::match(['get', 'post'], 'appointment/create', [PatientController::class, 'appointments'])->name('patient.appointment.create');
    // Route::get('/appointment/show/{id}', [PatientController::class, 'appointments'])->name('patient.appointment.show');
    // Route::get('/appointment/cancel/{id}', [PatientController::class, 'appointments'])->name('patient.appointment.cancel');
    // Route::match(['get', 'post'], '/appointment/review/create', [PatientController::class, 'appointments'])->name('patient.review.create');
    // Route::get('/patient/{patient}/show', [PatientController::class, 'show'])->name('patient.show');

    // Route::get('/profile/show', [PatientController::class, 'show'])->name('patient.profile');
    // Route::get('/profile/edit', [PatientController::class, 'edit'])->name('patient.profile.edit');
    // Route::put('/profile/update', [PatientController::class, 'update'])->name('patient.profile.update');
    // Route::get('/medical-history', [PatientController::class, 'medicalHistory'])->name('patient.medicalHistory');
    // Route::get('/appointments', [PatientController::class, 'appointments'])->name('patient.appointments');
    // // Medical Records Management
    // Route::get('/medical-record/create', [MedicalRecordController::class, 'create'])->name('medical_record.create');
    // Route::post('/medical-record/store', [MedicalRecordController::class, 'store'])->name('medical_record.store');
    // Route::get('/medical-record/{medicalRecord}/show', [MedicalRecordController::class, 'show'])->name('medical_record.show');
    // Route::get('/medical-record/{medicalRecord}/edit', [MedicalRecordController::class, 'edit'])->name('medical_record.edit');
    // Route::put('/medical-record/{medicalRecord}/update', [MedicalRecordController::class, 'update'])->name('medical_record.update');
    // Route::delete('/medical-record/{medicalRecord}/destroy', [MedicalRecordController::class, 'destroy'])->name('medical_record.destroy');
    // Route::get('/patient/{patient}/medical-records/print', [MedicalRecordController::class, 'patientRecords'])->name('medical_record.patient');
    // End Medical Records Management
});
// Patient Routes
Route::name('patient.')->group(function () {
    // Authentication Routes

    Route::middleware('auth:patient')->group(function () {});
});

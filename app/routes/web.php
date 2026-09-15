<?php

use App\Modules\AdminAnalytics\Http\Controllers\AdminDashboardController;
use App\Modules\AdminAnalytics\Http\Controllers\ClinicVerificationController;
use App\Modules\Appointment\Http\Controllers\AppointmentController;
use App\Modules\Clinic\Http\Controllers\ClinicDashboardController;
use App\Modules\Clinic\Http\Controllers\ClinicDirectoryController;
use App\Modules\Clinic\Http\Controllers\ClinicOnboardingController;
use App\Modules\Clinic\Http\Controllers\ClinicPatientController;
use App\Modules\Identity\Http\Controllers\Auth\AdminLoginController;
use App\Modules\Identity\Http\Controllers\Auth\LoginController;
use App\Modules\Identity\Http\Controllers\Auth\PasswordResetController;
use App\Modules\Identity\Http\Controllers\Auth\RegisterController;
use App\Modules\Identity\Http\Controllers\Public\PublicPageController;
use App\Modules\Patient\Http\Controllers\ClinicEnrollmentController;
use App\Modules\Patient\Http\Controllers\PatientDashboardController;
use App\Modules\Supplier\Http\Controllers\SupplierDashboardController;
use App\Modules\Supplier\Http\Controllers\SupplierOnboardingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public site — PRD §8/§9/§121. No marketplace navigation lives here.
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicPageController::class, 'home'])->name('home');
Route::get('/for-patients', [PublicPageController::class, 'forPatients'])->name('for-patients');
Route::get('/for-clinics', [PublicPageController::class, 'forClinics'])->name('for-clinics');
Route::get('/for-suppliers', [PublicPageController::class, 'forSuppliers'])->name('for-suppliers');
Route::get('/about', [PublicPageController::class, 'about'])->name('about');
Route::get('/contact', [PublicPageController::class, 'contact'])->name('contact');

Route::get('/clinics', [ClinicDirectoryController::class, 'index'])->name('clinics.index');
Route::get('/clinics/{clinic}', [ClinicDirectoryController::class, 'show'])->name('clinics.show');

/*
|--------------------------------------------------------------------------
| Auth — patient/clinic/supplier registration + login (PRD §10/§11)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('register.store');

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('login.store');

    Route::get('/forgot-password', [PasswordResetController::class, 'requestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])
        ->middleware('throttle:5,1')
        ->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

    // Separate, unadvertised admin login (PRD §10) — never linked from primary nav.
    Route::get('/platform/admin-login', [AdminLoginController::class, 'create'])->name('admin.login');
    Route::post('/platform/admin-login', [AdminLoginController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('admin.login.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Patient — /app/* — mobile-app-like, clinic-scoped, NO marketplace routes
|--------------------------------------------------------------------------
*/
Route::prefix('app')->as('patient.')->middleware(['auth', 'role:patient'])->group(function () {
    Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('dashboard');
    Route::get('/appointments', [AppointmentController::class, 'patientIndex'])->name('appointments.index');
    Route::get('/appointments/{appointment}', [AppointmentController::class, 'patientShow'])->name('appointments.show');
    Route::post('/clinics/{clinic}/enroll', [ClinicEnrollmentController::class, 'store'])->name('clinics.enroll');
    Route::post('/clinics/{clinic}/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
});

/*
|--------------------------------------------------------------------------
| Clinic — /clinic/*
|--------------------------------------------------------------------------
*/
Route::prefix('clinic')->as('clinic.')->middleware(['auth', 'role:clinic_owner|clinic_admin|clinic_staff'])->group(function () {
    Route::get('/onboarding', [ClinicOnboardingController::class, 'create'])->name('onboarding');
    Route::post('/onboarding', [ClinicOnboardingController::class, 'store'])->name('onboarding.store');
    Route::get('/dashboard', [ClinicDashboardController::class, 'index'])->name('dashboard');
    Route::get('/patients', [ClinicPatientController::class, 'index'])->name('patients.index');
    Route::get('/patients/{clinicPatient}', [ClinicPatientController::class, 'show'])->name('patients.show');
    Route::get('/appointments', [AppointmentController::class, 'clinicIndex'])->name('appointments.index');
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status');
});

/*
|--------------------------------------------------------------------------
| Supplier — /supplier/*
|--------------------------------------------------------------------------
*/
Route::prefix('supplier')->as('supplier.')->middleware(['auth', 'role:supplier_owner|supplier_admin|supplier_staff'])->group(function () {
    Route::get('/onboarding', [SupplierOnboardingController::class, 'create'])->name('onboarding');
    Route::post('/onboarding', [SupplierOnboardingController::class, 'store'])->name('onboarding.store');
    Route::get('/dashboard', [SupplierDashboardController::class, 'index'])->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Marketplace — clinics/suppliers/admin only, enforced server-side.
| No /patient/marketplace routes exist (PRD §125, non-negotiable).
|--------------------------------------------------------------------------
*/
Route::prefix('marketplace')->as('marketplace.')->middleware(['auth', 'marketplace.access'])->group(function () {
    Route::get('/', function () {
        return view('marketplace.home');
    })->name('home');
});

/*
|--------------------------------------------------------------------------
| Admin — /admin/*
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->as('admin.')->middleware(['auth', 'role:admin|super_admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/clinics/verification', [ClinicVerificationController::class, 'index'])->name('clinics.verification.index');
    Route::patch('/clinics/{clinic}/verification', [ClinicVerificationController::class, 'update'])
        ->middleware('permission:clinic.verify')
        ->name('clinics.verification.update');
});

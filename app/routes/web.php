<?php

use App\Modules\AdminAnalytics\Http\Controllers\AdminDashboardController;
use App\Modules\AdminAnalytics\Http\Controllers\ClinicVerificationController;
use App\Modules\AdminAnalytics\Http\Controllers\ProductModerationController;
use App\Modules\Appointment\Http\Controllers\AppointmentController;
use App\Modules\Clinic\Http\Controllers\ClinicAvailabilityController;
use App\Modules\Clinic\Http\Controllers\ClinicDashboardController;
use App\Modules\Clinic\Http\Controllers\ClinicDirectoryController;
use App\Modules\Clinic\Http\Controllers\ClinicOnboardingController;
use App\Modules\Clinic\Http\Controllers\ClinicPatientController;
use App\Modules\Clinic\Http\Controllers\ClinicServiceController;
use App\Modules\Clinic\Http\Controllers\ClinicStaffController;
use App\Modules\Clinic\Http\Controllers\DentistController;
use App\Modules\Identity\Http\Controllers\Auth\AdminLoginController;
use App\Modules\Identity\Http\Controllers\Auth\LoginController;
use App\Modules\Identity\Http\Controllers\Auth\PasswordResetController;
use App\Modules\Identity\Http\Controllers\Auth\RegisterController;
use App\Modules\Identity\Http\Controllers\Public\NewsletterController;
use App\Modules\Identity\Http\Controllers\Public\PublicPageController;
use App\Modules\Marketplace\Http\Controllers\MarketplaceController;
use App\Modules\Marketplace\Http\Controllers\RfqController;
use App\Modules\Notification\Http\Controllers\PatientNotificationController;
use App\Modules\Patient\Http\Controllers\ClinicEnrollmentController;
use App\Modules\Patient\Http\Controllers\PatientDashboardController;
use App\Modules\Patient\Http\Controllers\PatientProfileController;
use App\Modules\Supplier\Http\Controllers\SupplierDashboardController;
use App\Modules\Supplier\Http\Controllers\SupplierOnboardingController;
use App\Modules\Supplier\Http\Controllers\SupplierProductController;
use App\Modules\TrustSupport\Http\Controllers\ReviewController;
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
Route::post('/newsletter', [NewsletterController::class, 'store'])->middleware('throttle:5,1')->name('newsletter.store');

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
    Route::livewire('/dashboard', 'patient.dashboard')->name('dashboard');
    Route::get('/switch-clinic', [PatientDashboardController::class, 'switchClinic'])->name('clinics.switch');
    Route::post('/switch-clinic', [PatientDashboardController::class, 'selectClinic'])->name('clinics.switch.store');

    Route::get('/appointments', [AppointmentController::class, 'patientIndex'])->name('appointments.index');
    Route::get('/appointments/{appointment}', [AppointmentController::class, 'patientShow'])->name('appointments.show');
    Route::get('/appointments/{appointment}/confirmation', [AppointmentController::class, 'confirmation'])->name('appointments.confirmation');
    Route::get('/appointments/{appointment}/reschedule', [AppointmentController::class, 'rescheduleForm'])->name('appointments.reschedule');
    Route::patch('/appointments/{appointment}/reschedule', [AppointmentController::class, 'reschedule'])->name('appointments.reschedule.update');
    Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');

    Route::get('/clinics/{clinic}/enroll', [ClinicEnrollmentController::class, 'create'])->name('clinics.enroll.form');
    Route::post('/clinics/{clinic}/enroll', [ClinicEnrollmentController::class, 'store'])->name('clinics.enroll');
    Route::get('/clinics/{clinic}/book', [AppointmentController::class, 'bookForm'])->name('appointments.book');
    Route::post('/clinics/{clinic}/appointments', [AppointmentController::class, 'store'])->name('appointments.store');

    Route::get('/notifications', [PatientNotificationController::class, 'index'])->name('notifications.index');
    Route::get('/profile', [PatientProfileController::class, 'index'])->name('profile.index');

    Route::get('/appointments/{appointment}/review', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/appointments/{appointment}/review', [ReviewController::class, 'store'])->name('reviews.store');
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
    Route::get('/rfqs', [RfqController::class, 'clinicIndex'])->name('rfqs.index');

    Route::get('/dentists', [DentistController::class, 'index'])->name('dentists.index');
    Route::get('/dentists/create', [DentistController::class, 'create'])->name('dentists.create');
    Route::post('/dentists', [DentistController::class, 'store'])->name('dentists.store');
    Route::get('/dentists/{dentist}/edit', [DentistController::class, 'edit'])->name('dentists.edit');
    Route::put('/dentists/{dentist}', [DentistController::class, 'update'])->name('dentists.update');

    Route::get('/services', [ClinicServiceController::class, 'index'])->name('services.index');
    Route::put('/services', [ClinicServiceController::class, 'update'])->name('services.update');

    Route::get('/availability', [ClinicAvailabilityController::class, 'index'])->name('availability.index');
    Route::put('/availability', [ClinicAvailabilityController::class, 'update'])->name('availability.update');
    Route::post('/availability/blackout-dates', [ClinicAvailabilityController::class, 'storeBlackout'])->name('availability.blackout.store');
    Route::delete('/availability/blackout-dates/{blackoutDate}', [ClinicAvailabilityController::class, 'destroyBlackout'])->name('availability.blackout.destroy');

    Route::get('/staff', [ClinicStaffController::class, 'index'])->name('staff.index');
    Route::post('/staff', [ClinicStaffController::class, 'store'])->name('staff.store');
    Route::delete('/staff/{clinicStaff}', [ClinicStaffController::class, 'destroy'])->name('staff.destroy');
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
    Route::get('/products', [SupplierProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [SupplierProductController::class, 'create'])->name('products.create');
    Route::post('/products', [SupplierProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [SupplierProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [SupplierProductController::class, 'update'])->name('products.update');
    Route::patch('/products/{product}/availability', [SupplierProductController::class, 'toggleAvailability'])->name('products.availability');
    Route::get('/rfqs', [RfqController::class, 'supplierIndex'])->name('rfqs.index');
});

/*
|--------------------------------------------------------------------------
| Marketplace — clinics/suppliers/admin only, enforced server-side.
| No /patient/marketplace routes exist (PRD §125, non-negotiable).
|--------------------------------------------------------------------------
*/
Route::prefix('marketplace')->as('marketplace.')->middleware(['auth', 'marketplace.access'])->group(function () {
    Route::get('/', [MarketplaceController::class, 'index'])->name('home');
    Route::get('/products/{product}', [MarketplaceController::class, 'show'])->name('products.show');
    Route::post('/products/{product}/rfqs', [RfqController::class, 'store'])
        ->middleware(['role:clinic_owner|clinic_admin|clinic_staff', 'throttle:10,1'])
        ->name('rfqs.store');
    Route::get('/rfqs/{rfq}', [RfqController::class, 'show'])->name('rfqs.show');
    Route::post('/rfqs/{rfq}/respond', [RfqController::class, 'respond'])->name('rfqs.respond');
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
    Route::get('/products/moderation', [ProductModerationController::class, 'index'])->name('products.moderation.index');
    Route::patch('/products/{product}/moderation', [ProductModerationController::class, 'update'])
        ->middleware('permission:products.moderate')
        ->name('products.moderation.update');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;

Route::get('/{any?}', function () {
    return view('welcome');
})->where('any', '.*');

// Patient Portal Routes
Route::middleware(['auth', 'patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');

    // Appointments
    Route::get('/appointments', [PatientController::class, 'appointments'])->name('appointments');
    Route::get('/appointments/create', [PatientController::class, 'createAppointment'])->name('appointments.create');
    Route::post('/appointments', [PatientController::class, 'storeAppointment'])->name('appointments.store');
    Route::get('/appointments/{appointment}', [PatientController::class, 'showAppointment'])->name('appointments.show');

    // Medical Records
    Route::get('/medical-records', [PatientController::class, 'medicalRecords'])->name('medical-records');
    Route::get('/medical-records/{record}', [PatientController::class, 'showMedicalRecord'])->name('medical-records.show');

    // Prescriptions
    Route::get('/prescriptions', [PatientController::class, 'prescriptions'])->name('prescriptions');
    Route::get('/prescriptions/{prescription}', [PatientController::class, 'showPrescription'])->name('prescriptions.show');

    // Billing
    Route::get('/billing', [PatientController::class, 'billing'])->name('billing');
    Route::get('/billing/{bill}', [PatientController::class, 'showBill'])->name('billing.show');

    // Profile
    Route::get('/profile', [PatientController::class, 'profile'])->name('profile');
    Route::put('/profile', [PatientController::class, 'updateProfile'])->name('profile.update');
});

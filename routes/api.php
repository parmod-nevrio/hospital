<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    // Admin routes
    Route::middleware('role:admin,super-admin')->group(function () {
        Route::post('/users', [AdminController::class, 'createUser']);
        Route::put('/users/{user}', [AdminController::class, 'updateUser']);
        Route::get('/users', [AdminController::class, 'listUsers']);
        Route::post('/departments', [AdminController::class, 'createDepartment']);
        Route::put('/departments/{department}', [AdminController::class, 'updateDepartment']);
        Route::get('/departments', [AdminController::class, 'listDepartments']);
        Route::get('/admin/dashboard', [AdminController::class, 'getDashboardStats']);
    });

    // Doctor routes
    Route::middleware('role:doctor')->group(function () {
        Route::get('/doctor/appointments', [DoctorController::class, 'getAppointments']);
        Route::put('/doctor/appointments/{appointment}', [DoctorController::class, 'updateAppointment']);
        Route::post('/medical-records', [DoctorController::class, 'createMedicalRecord']);
        Route::get('/patients/{patient}/history', [DoctorController::class, 'getPatientHistory']);
        Route::post('/prescriptions', [DoctorController::class, 'createPrescription']);
        Route::get('/doctor/dashboard', [DoctorController::class, 'getDashboardStats']);
    });

    // Patient routes
    Route::middleware('role:patient')->group(function () {
        Route::post('/appointments', [PatientController::class, 'bookAppointment']);
        Route::get('/appointments', [PatientController::class, 'getAppointments']);
        Route::get('/medical-records', [PatientController::class, 'getMedicalRecords']);
        Route::get('/prescriptions', [PatientController::class, 'getPrescriptions']);
        Route::get('/lab-tests', [PatientController::class, 'getLabTests']);
        Route::get('/billings', [PatientController::class, 'getBillings']);
        Route::get('/doctors', [PatientController::class, 'getDoctors']);
        Route::get('/patient/dashboard', [PatientController::class, 'getDashboardStats']);
    });

    // User Management routes
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);

    // Roles
    Route::get('/roles', [RoleController::class, 'index']);
    Route::post('/roles', [RoleController::class, 'store']);
    Route::get('/roles/{role}', [RoleController::class, 'show']);
    Route::put('/roles/{role}', [RoleController::class, 'update']);
    Route::delete('/roles/{role}', [RoleController::class, 'destroy']);

    // Permissions
    Route::get('/permissions', [PermissionController::class, 'index']);
    Route::get('/permissions/{permission}', [PermissionController::class, 'show']);
    Route::put('/permissions/{permission}/roles', [PermissionController::class, 'updateRoles']);
});

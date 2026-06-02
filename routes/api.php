<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmployeeApiController;
use App\Http\Controllers\Api\PresensiApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('mobile.api.key')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile', [EmployeeApiController::class, 'updateProfile']);
        Route::post('/profile', [EmployeeApiController::class, 'updateProfile']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/dashboard', [EmployeeApiController::class, 'dashboard']);
        Route::get('/shifts', [EmployeeApiController::class, 'shifts']);

        Route::get('/presensi/today', [PresensiApiController::class, 'today']);
        Route::get('/presensi/history', [PresensiApiController::class, 'history']);
        Route::post('/presensi/check-in', [PresensiApiController::class, 'checkIn']);
        Route::post('/presensi/check-out', [PresensiApiController::class, 'checkOut']);

        Route::get('/izin', [EmployeeApiController::class, 'izinIndex']);
        Route::post('/izin', [EmployeeApiController::class, 'izinStore']);
        Route::get('/izin/{id}', [EmployeeApiController::class, 'izinShow']);

        Route::get('/overtime', [EmployeeApiController::class, 'overtimeIndex']);
        Route::post('/overtime', [EmployeeApiController::class, 'overtimeStore']);
        Route::get('/overtime/{id}', [EmployeeApiController::class, 'overtimeShow']);

        Route::get('/payroll', [EmployeeApiController::class, 'payrollIndex']);
        Route::get('/payroll/{id}', [EmployeeApiController::class, 'payrollShow']);

        Route::get('/project-payroll', [EmployeeApiController::class, 'projectPayrollIndex']);
        Route::get('/project-payroll/{id}', [EmployeeApiController::class, 'projectPayrollShow']);

        Route::get('/reimbursements', [EmployeeApiController::class, 'reimbursementIndex']);
        Route::post('/reimbursements', [EmployeeApiController::class, 'reimbursementStore']);
        Route::get('/reimbursements/{id}', [EmployeeApiController::class, 'reimbursementShow']);

        Route::get('/offboarding', [EmployeeApiController::class, 'offboardingIndex']);
        Route::post('/offboarding', [EmployeeApiController::class, 'offboardingStore']);
        Route::get('/offboarding/{id}', [EmployeeApiController::class, 'offboardingShow']);

        Route::get('/perjalanan-dinas', [EmployeeApiController::class, 'tripIndex']);
        Route::post('/perjalanan-dinas', [EmployeeApiController::class, 'tripStore']);
        Route::get('/perjalanan-dinas/{id}', [EmployeeApiController::class, 'tripShow']);

        Route::get('/performance', [EmployeeApiController::class, 'performanceIndex']);
        Route::get('/performance/{id}', [EmployeeApiController::class, 'performanceShow']);

        Route::get('/announcements', [EmployeeApiController::class, 'announcementIndex']);
        Route::get('/announcements/{id}', [EmployeeApiController::class, 'announcementShow']);

        Route::get('/mutations', [EmployeeApiController::class, 'mutationIndex']);
        Route::get('/mutations/{id}', [EmployeeApiController::class, 'mutationShow']);

        Route::get('/sanctions', [EmployeeApiController::class, 'sanctionIndex']);
        Route::get('/sanctions/{id}', [EmployeeApiController::class, 'sanctionShow']);

        Route::get('/events', [EmployeeApiController::class, 'eventIndex']);
        Route::get('/events/{id}', [EmployeeApiController::class, 'eventShow']);

        Route::get('/insurance', [EmployeeApiController::class, 'insurance']);

        Route::get('/notifications', [EmployeeApiController::class, 'notifications']);
        Route::post('/notifications/read-all', [EmployeeApiController::class, 'markAllNotificationsAsRead']);
        Route::post('/notifications/{id}/read', [EmployeeApiController::class, 'markNotificationAsRead']);
    });
});

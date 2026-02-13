<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PeranController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('employee.login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Shared Notifications
Route::middleware(['auth:web,employee'])->group(function () {
    Route::get('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
});

// Employee Auth
Route::get('/login-pegawai', [App\Http\Controllers\EmployeeAuthController::class, 'showLoginForm'])->name('employee.login');
Route::post('/login-pegawai', [App\Http\Controllers\EmployeeAuthController::class, 'login']);

Route::middleware(['auth:employee'])->group(function () {
    Route::get('/dashboard-pegawai', [App\Http\Controllers\EmployeeAuthController::class, 'dashboard'])->name('employee.dashboard');
    Route::get('/attendance', [App\Http\Controllers\AttendanceController::class, 'index'])->name('employee.attendance.index');
    Route::get('/attendance-history', [App\Http\Controllers\AttendanceController::class, 'history'])->name('employee.attendance.history');
    Route::post('/attendance/clock-in', [App\Http\Controllers\AttendanceController::class, 'clockIn'])->name('employee.attendance.clock-in');
    Route::post('/attendance/clock-out', [App\Http\Controllers\AttendanceController::class, 'clockOut'])->name('employee.attendance.clock-out');
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('employee.profile');
    Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('employee.profile.update');
    Route::get('/id-card', [App\Http\Controllers\EmployeeAuthController::class, 'idCard'])->name('employee.id-card');
    Route::get('/id-card-back', [App\Http\Controllers\EmployeeAuthController::class, 'idCardBack'])->name('employee.id-card-back');
    Route::post('/logout-pegawai', [App\Http\Controllers\EmployeeAuthController::class, 'logout'])->name('employee.logout');

    // Izin / Sakit
    Route::get('/izin', [App\Http\Controllers\IzinController::class, 'index'])->name('employee.izin.index');
    Route::get('/izin/create', [App\Http\Controllers\IzinController::class, 'create'])->name('employee.izin.create');
    Route::post('/izin', [App\Http\Controllers\IzinController::class, 'store'])->name('employee.izin.store');
    Route::get('/izin/{izin}', [App\Http\Controllers\IzinController::class, 'show'])->name('employee.izin.show');

    // Lembur
    Route::get('/overtime', [App\Http\Controllers\OvertimeController::class, 'index'])->name('employee.overtime.index');
    Route::get('/overtime/create', [App\Http\Controllers\OvertimeController::class, 'create'])->name('employee.overtime.create');
    Route::post('/overtime', [App\Http\Controllers\OvertimeController::class, 'store'])->name('employee.overtime.store');

    // Payroll
    Route::get('/payroll', [App\Http\Controllers\EmployeePayrollController::class, 'index'])->name('employee.payroll.index');
    Route::get('/payroll/{payroll}', [App\Http\Controllers\EmployeePayrollController::class, 'show'])->name('employee.payroll.show');

    // Perjalanan Dinas
    Route::get('/perjalanan-dinas', [App\Http\Controllers\PerjalananDinasController::class, 'index'])->name('employee.perjalanan_dinas.index');
    Route::get('/perjalanan-dinas/create', [App\Http\Controllers\PerjalananDinasController::class, 'create'])->name('employee.perjalanan_dinas.create');
    Route::post('/perjalanan-dinas', [App\Http\Controllers\PerjalananDinasController::class, 'store'])->name('employee.perjalanan_dinas.store');
    Route::get('/perjalanan-dinas/{id}', [App\Http\Controllers\PerjalananDinasController::class, 'show'])->name('employee.perjalanan_dinas.show');

    // Performance (KPI)
    Route::get('/performance', [App\Http\Controllers\PerformanceAppraisalController::class, 'index'])->name('employee.performance.index');
    Route::get('/performance/{id}', [App\Http\Controllers\PerformanceAppraisalController::class, 'show'])->name('employee.performance.show');
    Route::get('/performance/{id}/print', [App\Http\Controllers\PerformanceAppraisalController::class, 'print'])->name('employee.performance.print');

    // Announcements
    Route::get('/announcements', [App\Http\Controllers\AnnouncementController::class, 'index'])->name('employee.announcements.index');
    Route::get('/announcements/{id}', [App\Http\Controllers\AnnouncementController::class, 'show'])->name('employee.announcements.show');

    Route::get('/mutations', [App\Http\Controllers\EmployeeMutasiController::class, 'index'])->name('employee.mutations.index');
    Route::get('/mutations/{id}', [App\Http\Controllers\EmployeeMutasiController::class, 'show'])->name('employee.mutations.show');

    // Sanksi (Disciplinary Action)
    Route::get('/sanctions', [App\Http\Controllers\SanctionController::class, 'index'])->name('employee.sanctions.index');
    Route::get('/sanctions/{id}', [App\Http\Controllers\SanctionController::class, 'show'])->name('employee.sanctions.show');
    Route::get('/sanctions/{id}/print', [App\Http\Controllers\SanctionController::class, 'print'])->name('employee.sanctions.print');
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    Route::get('/master-data', [App\Http\Controllers\DataMasterController::class, 'index'])->name('master.index');
    Route::get('/master-office', [App\Http\Controllers\DataMasterController::class, 'masterOffice'])->name('master.office');
    Route::get('/master-employee', [App\Http\Controllers\DataMasterController::class, 'masterEmployee'])->name('master.employee');

    // Role Management
    Route::middleware('permission:manage-roles')->group(function () {
        Route::resource('role', PeranController::class);
        Route::post('role/{id}/toggle-status', [PeranController::class, 'toggleStatus'])->name('role.toggleStatus');
        Route::get('role/{id}/permissions', [PeranController::class, 'permissions'])->name('role.permissions');
        Route::put('role/{id}/permissions', [PeranController::class, 'updatePermissions'])->name('role.update-permissions');
    });

    Route::resource('users', UserController::class)->middleware('permission:manage-users');

    Route::resource('cities', App\Http\Controllers\KotaController::class)->middleware('permission:manage-cities');
    Route::post('cities/sync', [App\Http\Controllers\KotaController::class, 'sync'])->name('cities.sync')->middleware('permission:manage-cities');

    Route::get('offices/get-next-code', [App\Http\Controllers\KantorController::class, 'getNextCode'])->name('offices.get-next-code')->middleware('permission:manage-offices');
    Route::resource('offices', App\Http\Controllers\KantorController::class)->middleware('permission:manage-offices');

    Route::resource('directorates', App\Http\Controllers\DirectorateController::class)->middleware('permission:manage-divisions');
    Route::resource('divisions', App\Http\Controllers\DivisiController::class)->middleware('permission:manage-divisions');
    Route::resource('positions', App\Http\Controllers\JabatanController::class)->middleware('permission:manage-positions');

    Route::resource('assets', App\Http\Controllers\AsetController::class)->middleware('permission:manage-assets');
    Route::resource('employment-statuses', App\Http\Controllers\StatusPegawaiController::class)->middleware('permission:manage-employee-statuses');
    Route::resource('employees', App\Http\Controllers\PegawaiController::class)->middleware('permission:manage-employees');
    Route::get('employees/{employee}/id-card', [App\Http\Controllers\PegawaiController::class, 'idCard'])->name('employees.id-card')->middleware('permission:manage-employees');
    Route::get('employees/{employee}/id-card-back', [App\Http\Controllers\PegawaiController::class, 'idCardBack'])->name('employees.id-card-back')->middleware('permission:manage-employees');

    Route::resource('mutations', App\Http\Controllers\MutasiPegawaiController::class)->except(['edit', 'update', 'destroy'])->middleware('permission:manage-mutations');
    Route::get('mutations/{mutation}/print', [App\Http\Controllers\MutasiPegawaiController::class, 'print'])->name('mutations.print')->middleware('permission:manage-mutations');
    Route::resource('employee-shifts', App\Http\Controllers\ShiftPegawaiController::class)->only(['index', 'store', 'destroy'])->middleware('permission:manage-shifts');
    Route::resource('shifts', App\Http\Controllers\ShiftController::class)->middleware('permission:manage-shifts');
    Route::post('holidays/sync-api', [App\Http\Controllers\HariLiburController::class, 'syncFromApi'])->name('holidays.sync-api')->middleware('permission:manage-holidays');
    Route::resource('holidays', App\Http\Controllers\HariLiburController::class)->only(['index', 'store', 'destroy'])->middleware('permission:manage-holidays');

    // Monitoring Presensi
    Route::get('/presensi', [App\Http\Controllers\Admin\PresensiController::class, 'index'])->name('admin.presensi.index');
    Route::get('/presensi/{id}', [App\Http\Controllers\Admin\PresensiController::class, 'show'])->name('admin.presensi.show');
    Route::put('/presensi/{id}', [App\Http\Controllers\Admin\PresensiController::class, 'update'])->name('admin.presensi.update');

    // Pengajuan Izin / Sakit
    Route::get('/izin', [App\Http\Controllers\Admin\IzinPegawaiController::class, 'index'])->name('admin.izin.index');
    Route::post('/izin', [App\Http\Controllers\Admin\IzinPegawaiController::class, 'store'])->name('admin.izin.store');
    Route::patch('/izin/{izin}/status', [App\Http\Controllers\Admin\IzinPegawaiController::class, 'updateStatus'])->name('admin.izin.update-status');
    Route::delete('/izin/{izin}', [App\Http\Controllers\Admin\IzinPegawaiController::class, 'destroy'])->name('admin.izin.destroy');

    // Manajemen Lembur
    Route::get('/overtime', [App\Http\Controllers\Admin\OvertimeController::class, 'index'])->name('admin.overtime.index');
    Route::post('/overtime', [App\Http\Controllers\Admin\OvertimeController::class, 'store'])->name('admin.overtime.store');
    Route::patch('/overtime/{overtime}/status', [App\Http\Controllers\Admin\OvertimeController::class, 'updateStatus'])->name('admin.overtime.update-status');
    Route::delete('/overtime/{overtime}', [App\Http\Controllers\Admin\OvertimeController::class, 'destroy'])->name('admin.overtime.destroy');

    // Manajemen Payroll
    Route::get('/payroll', [App\Http\Controllers\Admin\PayrollController::class, 'index'])->name('admin.payroll.index');
    Route::get('/payroll/generate', [App\Http\Controllers\Admin\PayrollController::class, 'generate'])->name('admin.payroll.generate');
    Route::post('/payroll/generate', [App\Http\Controllers\Admin\PayrollController::class, 'processGenerate'])->name('admin.payroll.process-generate');
    // Route::get('/payroll/{payroll}', [App\Http\Controllers\Admin\PayrollController::class, 'show'])->name('admin.payroll.show');
    Route::patch('/payroll/{payroll}/status', [App\Http\Controllers\Admin\PayrollController::class, 'updateStatus'])->name('admin.payroll.update-status');
    Route::delete('/payroll/{payroll}', [App\Http\Controllers\Admin\PayrollController::class, 'destroy'])->name('admin.payroll.destroy');

    // Perjalanan Dinas
    Route::get('/perjalanan-dinas', [App\Http\Controllers\Admin\PerjalananDinasController::class, 'index'])->name('admin.perjalanan_dinas.index');
    Route::get('/perjalanan-dinas/create', [App\Http\Controllers\Admin\PerjalananDinasController::class, 'create'])->name('admin.perjalanan_dinas.create');
    Route::post('/perjalanan-dinas', [App\Http\Controllers\Admin\PerjalananDinasController::class, 'store'])->name('admin.perjalanan_dinas.store');
    Route::get('/perjalanan-dinas/{id}', [App\Http\Controllers\Admin\PerjalananDinasController::class, 'show'])->name('admin.perjalanan_dinas.show');
    Route::patch('/perjalanan-dinas/{id}/status', [App\Http\Controllers\Admin\PerjalananDinasController::class, 'updateStatus'])->name('admin.perjalanan_dinas.update-status');
    Route::delete('/perjalanan-dinas/{id}', [App\Http\Controllers\Admin\PerjalananDinasController::class, 'destroy'])->name('admin.perjalanan_dinas.destroy');

    // Performance Appraisal (KPI)
    Route::get('/performance', [App\Http\Controllers\Admin\PerformanceAppraisalController::class, 'index'])->name('admin.performance.index');
    Route::get('/performance/create', [App\Http\Controllers\Admin\PerformanceAppraisalController::class, 'create'])->name('admin.performance.create');
    Route::post('/performance', [App\Http\Controllers\Admin\PerformanceAppraisalController::class, 'store'])->name('admin.performance.store');
    Route::get('/performance/{id}', [App\Http\Controllers\Admin\PerformanceAppraisalController::class, 'show'])->name('admin.performance.show');
    Route::get('/performance/{id}/edit', [App\Http\Controllers\Admin\PerformanceAppraisalController::class, 'edit'])->name('admin.performance.edit');
    Route::patch('/performance/{id}', [App\Http\Controllers\Admin\PerformanceAppraisalController::class, 'update'])->name('admin.performance.update');
    Route::get('/performance/{id}/print', [App\Http\Controllers\Admin\PerformanceAppraisalController::class, 'print'])->name('admin.performance.print');
    Route::delete('/performance/{id}', [App\Http\Controllers\Admin\PerformanceAppraisalController::class, 'destroy'])->name('admin.performance.destroy');

    // Disciplinary Sanctions
    Route::resource('sanctions', App\Http\Controllers\Admin\DisciplinarySanctionController::class)->names('admin.sanctions')->only(['index', 'create', 'store', 'show', 'destroy']);
    Route::get('sanctions/{sanction}/print', [App\Http\Controllers\Admin\DisciplinarySanctionController::class, 'print'])->name('admin.sanctions.print');

    // Announcements
    Route::resource('announcements', App\Http\Controllers\Admin\AnnouncementController::class)->names('admin.announcements');
});

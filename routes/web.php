<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PeranController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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
    Route::post('/logout-pegawai', [App\Http\Controllers\EmployeeAuthController::class, 'logout'])->name('employee.logout');

    // Izin / Sakit
    Route::get('/izin', [App\Http\Controllers\IzinController::class, 'index'])->name('employee.izin.index');
    Route::get('/izin/create', [App\Http\Controllers\IzinController::class, 'create'])->name('employee.izin.create');
    Route::post('/izin', [App\Http\Controllers\IzinController::class, 'store'])->name('employee.izin.store');
    Route::get('/izin/{izin}', [App\Http\Controllers\IzinController::class, 'show'])->name('employee.izin.show');
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    Route::get('/master-data', [App\Http\Controllers\DataMasterController::class, 'index'])->name('master.index');
    Route::get('/master-office', [App\Http\Controllers\DataMasterController::class, 'masterOffice'])->name('master.office');
    Route::get('/master-employee', [App\Http\Controllers\DataMasterController::class, 'masterEmployee'])->name('master.employee');

    Route::resource('role', PeranController::class);
    Route::post('role/{id}/toggle-status', [PeranController::class, 'toggleStatus'])->name('role.toggleStatus');

    Route::resource('users', UserController::class);

    Route::resource('cities', App\Http\Controllers\KotaController::class);
    Route::post('cities/sync', [App\Http\Controllers\KotaController::class, 'sync'])->name('cities.sync');

    Route::get('offices/get-next-code', [App\Http\Controllers\KantorController::class, 'getNextCode'])->name('offices.get-next-code');
    Route::resource('offices', App\Http\Controllers\KantorController::class);

    Route::resource('divisions', App\Http\Controllers\DivisiController::class);
    Route::resource('positions', App\Http\Controllers\JabatanController::class);

    Route::resource('assets', App\Http\Controllers\AsetController::class);
    Route::resource('employment-statuses', App\Http\Controllers\StatusPegawaiController::class);
    Route::resource('employees', App\Http\Controllers\PegawaiController::class);

    Route::resource('mutations', App\Http\Controllers\MutasiPegawaiController::class)->except(['edit', 'update', 'destroy']); // Usually mutations are final records
    Route::resource('employee-shifts', App\Http\Controllers\ShiftPegawaiController::class)->only(['index', 'store', 'destroy']);
    Route::resource('shifts', App\Http\Controllers\ShiftController::class);
    Route::post('holidays/sync-api', [App\Http\Controllers\HariLiburController::class, 'syncFromApi'])->name('holidays.sync-api');
    Route::resource('holidays', App\Http\Controllers\HariLiburController::class)->only(['index', 'store', 'destroy']);

    // Monitoring Presensi
    Route::get('/presensi', [App\Http\Controllers\Admin\PresensiController::class, 'index'])->name('admin.presensi.index');
    Route::get('/presensi/{id}', [App\Http\Controllers\Admin\PresensiController::class, 'show'])->name('admin.presensi.show');
    Route::put('/presensi/{id}', [App\Http\Controllers\Admin\PresensiController::class, 'update'])->name('admin.presensi.update');

    // Pengajuan Izin / Sakit
    Route::get('/izin', [App\Http\Controllers\Admin\IzinPegawaiController::class, 'index'])->name('admin.izin.index');
    Route::post('/izin', [App\Http\Controllers\Admin\IzinPegawaiController::class, 'store'])->name('admin.izin.store');
    Route::patch('/izin/{izin}/status', [App\Http\Controllers\Admin\IzinPegawaiController::class, 'updateStatus'])->name('admin.izin.update-status');
    Route::delete('/izin/{izin}', [App\Http\Controllers\Admin\IzinPegawaiController::class, 'destroy'])->name('admin.izin.destroy');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', function () {
    return view('login.login');
});

Route::get('/index', function () {
    return view('employee');
});

Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/create', [AdminController::class, 'create'])->name('admin.create');
});

Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/superadmin/dashboard/', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');
    Route::get('/superadmin/create', [SuperAdminController::class, 'create'])->name('superadmin.create');
    Route::post('/superadmin/create', [SuperAdminController::class, 'store'])->name('superadmin.store');
    Route::get('/superadmin/employee', [SuperAdminController::class, 'employee'])->name('superadmin.employee');
    Route::get('/superadmin/employee/{id}', [SuperAdminController::class, 'show'])->name('superadmin.show');
    Route::get('/superadmin/summary', [SuperAdminController::class, 'summary'])->name('superadmin.summary');

    Route::get('/participants/{badge_no}', [PesertaController::class, 'getParticipantByBadgeNo']);
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
});

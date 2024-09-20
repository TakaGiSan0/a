<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Routing\Middleware\ThrottleRequests;


Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', function () {
    return view('login.login');
});

Route::get('/index', function () {
    return view('employee');
});

Route::get('/memory', function() {
    echo 'Penggunaan Memori: ' . memory_get_usage() . ' bytes';
});


// Login Route
Route::post('login', [AuthenticatedSessionController::class, 'store'])
->middleware('throttle:10,1')->name('login');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
Route::get('/test-pdf', [SuperAdminController::class, 'generatePdf']);

// Super Admin Route
Route::middleware(['auth', 'role:super admin'])->group(function () {


    // Dashboard Form
    Route::get('/superadmin/dashboard/', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');
    Route::get('/superadmin/dashboard/create', [SuperAdminController::class, 'create'])->name('superadmin.create');
    Route::post('/superadmin/dashboard/create', [SuperAdminController::class, 'store'])->name('superadmin.store');
    Route::get('/superadmin/dashboard/edit/{id}', [SuperAdminController::class, 'edit'])->name('superadmin.edit');
    Route::delete('/superadmin/dashboard/{id}', [SuperAdminController::class, 'destroy'])->name('superadmin.destroy');
    Route::put('/superadmin/dashboard/update/{id}', [SuperAdminController::class, 'update'])->name('superadmin.update');


    // Dashboard Employee Training Record
    Route::get('/superadmin/employee', [EmployeeController::class, 'index'])->name('superadmin.employee');
    Route::get('/superadmin/employee/{id}', [EmployeeController::class, 'show'])->name('superadmin.employee.show');

    // Dashboard Summary Training Record
    Route::get('/superadmin/summary', [SummaryController::class, 'index'])->name('superadmin.summary');
    Route::get('/superadmin/summary/{id}', [SummaryController::class, 'show'])->name('superadmin.summary.show');
    Route::post('/api/trainings/search', [SummaryController::class, 'search']);
    Route::get('/generator/{id}', [SummaryController::class, 'downloadSummaryPdf'])->name('download');

    // Dashboard User
    Route::get('/superadmin/user', [UserController::class, 'index'])->name('superadmin.user.index');
    Route::get('/superadmin/user/create', [UserController::class, 'create'])->name('superadmin.user.create');
    Route::post('/superadmin/user/create', [UserController::class, 'store'])->name('superadmin.user.store');
    Route::delete('/superadmin/user/{id}', [UserController::class, 'delete'])->name('superadmin.user.destroy');
    Route::get('/superadmin/user/edit/{user}', [UserController::class, 'edit'])->name('superadmin.user.edit');
    Route::put('/superadmin/user/update/{user}', [UserController::class, 'update'])->name('superadmin.user.update');



    // Dashboard Menambah Peserta
    Route::get('/superadmin/peserta/', [PesertaController::class, 'index'])->name('superadmin.peserta');
    Route::get('/superadmin/peserta/create/', [PesertaController::class, 'create'])->name('superadmin.peserta.create');
    Route::post('/superadmin/peserta/create/', [PesertaController::class, 'store'])->name('superadmin.peserta.store');
    Route::delete('/superadmin/peserta/{id}', [PesertaController::class, 'destroy'])->name('superadmin.peserta.destroy');
    Route::get('/superadmin/peserta/edit/{peserta}', [PesertaController::class, 'edit'])->name('superadmin.peserta.edit');
    Route::put('/superadmin/peserta/update/{peserta}', [PesertaController::class, 'update'])->name('superadmin.peserta.update');
    Route::post('/import', [ExcelController::class, 'import'])->name('import');
    Route::get('/users/export', [ExcelController::class, 'export'])->name('export');
    Route::get('/participants/{badgeNo}', [PesertaController::class, 'getParticipantByBadgeNo']);
});


// Admin Route
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/summary', [SummaryController::class, 'index'])->name('admin.summary');
    Route::get('/admin/employee', [EmployeeController::class, 'index'])->name('admin.employee');
    Route::get('/admin/peserta', [PesertaController::class, 'index'])->name('admin.peserta');
    Route::get('/admin/user', [UserController::class, 'index'])->name('admin.user');



    Route::get('/admin/dashboard/create', [SuperAdminController::class, 'create'])->name('admin.create');
});


// User Route
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/employee', [EmployeeController::class, 'index'])->name('user.employee');
    Route::get('/user/summary', [SummaryController::class, 'index'])->name('user.summary');

});

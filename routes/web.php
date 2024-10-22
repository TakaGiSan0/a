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
use App\Http\Controllers\FormController;
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

// Route AllRole
Route::middleware(['auth:web'])->group(function () {
    // Dashboard Employee Training Record
    Route::get('/EmployeeTrainingRecord_list', [EmployeeController::class, 'index'])->name('dashboard.employee');
    Route::get('employee/{id}', [EmployeeController::class, 'show'])->name('employee.show');
    
    // Dashboard Summary Training Record
    Route::get('/SummaryTrainingRecord_list', [SummaryController::class, 'index'])->name('dashboard.summary');
    Route::get('summary/{id}', [SummaryController::class, 'show'])->name('summary.show');

    // Search SummaryTraining Record
    Route::post('/api/trainings/search', [SummaryController::class, 'search']);
    
    // API download pdf summary
    Route::get('/summary/download/{id}', [SummaryController::class, 'downloadSummaryPdf'])->name('download.summary');
    Route::get('/employee/download/{id}', [EmployeeController::class, 'downloadPdf'])->name('download.employee');

});

// Super Admin Route
Route::middleware(['auth', 'role:Super Admin,Admin'])->group(function () {
    
    Route::get('/index', [FormController::class, 'index'])->name('dashboard.index');
    
    // Crud Form
    Route::get('/index/create', [FormController::class, 'create'])->name('dashboard.create');
    Route::post('/index/create/store', [FormController::class, 'store'])->name('dashboard.store');
    Route::get('/index/edit/{id}', [FormController::class, 'edit'])->name('dashboard.edit');
    Route::delete('/index/{id}', [FormController::class, 'destroy'])->name('dashboard.destroy');
    Route::put('/index/update/{id}', [FormController::class, 'update'])->name('dashboard.update');
    
    
    Route::get('/peserta/dashboard', [PesertaController::class, 'index'])->name('dashboard.peserta');
    Route::get('/peserta/create/', [PesertaController::class, 'create'])->name('peserta.create');
    Route::post('/peserta/create/store', [PesertaController::class, 'store'])->name('peserta.store');
    Route::get('/peserta/edit/{peserta}', [PesertaController::class, 'edit'])->name('peserta.edit');
    Route::put('/peserta/update/{peserta}', [PesertaController::class, 'update'])->name('peserta.update');
    Route::delete('/peserta/delete/{id}', [PesertaController::class, 'destroy'])->name('superadmin.peserta.destroy');






    // Route User
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
  
  
  
  
  
    Route::post('/superadmin/user/create', [UserController::class, 'store'])->name('superadmin.user.store');
    Route::get('/superadmin/user', [UserController::class, 'index'])->name('superadmin.user.index');
    Route::delete('/superadmin/user/{id}', [UserController::class, 'destroy'])->name('superadmin.user.destroy');
    Route::get('/superadmin/user/edit/{user}', [UserController::class, 'edit'])->name('superadmin.user.edit');
    Route::put('/superadmin/user/update/{user}', [UserController::class, 'update'])->name('superadmin.user.update');
    

    // API Search Peserta Form
    Route::get('/participants/{badgeNo}', [PesertaController::class, 'getParticipantByBadgeNo']);
});



// Super Admin Route
Route::middleware(['auth', 'role:Super Admin'])->group(function () {
    
    // Dashboard Menambah Peserta
    Route::get('/superadmin/peserta/edit/{peserta}', [PesertaController::class, 'edit'])->name('superadmin.peserta.edit');

    Route::post('/users/import', [ExcelController::class, 'import_peserta'])->name('import.peserta');
    Route::get('/users/expor', [ExcelController::class, 'export_peserta'])->name('export.peserta');
    Route::post('/training/import', [ExcelController::class, 'import_training'])->name('import.training');
    Route::get('/training/export', [ExcelController::class, 'export_training'])->name('export.training');


});


// Admin Route
Route::middleware(['auth', 'role:Admin'])->group(function () {


    Route::get('/admin/peserta', [PesertaController::class, 'index'])->name('admin.peserta');

    // Dashboard User
    Route::get('/admin/user', [UserController::class, 'index'])->name('admin.user.index');
    Route::get('/admin/user/create', [UserController::class, 'create'])->name('admin.user.create');
    Route::post('/admin/user/create', [UserController::class, 'store'])->name('admin.user.store');
    Route::delete('/admin/user/{id}', [UserController::class, 'delete'])->name('admin.user.destroy');
    Route::get('/admin/user/edit/{user}', [UserController::class, 'edit'])->name('admin.user.edit');
    Route::put('/admin/user/update/{user}', [UserController::class, 'update'])->name('admin.user.update');

    Route::get('/admin/dashboard/create', [SuperAdminController::class, 'create'])->name('admin.create');
});


// User Route
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/employee', [EmployeeController::class, 'index'])->name('user.employee');
    
    Route::get('/user/summary', [SummaryController::class, 'index'])->name('user.summary');

});




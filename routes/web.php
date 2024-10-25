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
    Route::put('/index/update/{id}', [FormController::class, 'update'])->name('dashboard.update');
    
    
    Route::get('/Employee/dashboard', [PesertaController::class, 'index'])->name('dashboard.peserta');
    Route::get('/Employee/New_Employee/', [PesertaController::class, 'create'])->name('peserta.create');
    Route::post('/Employee/New_Employee/store', [PesertaController::class, 'store'])->name('peserta.store');
    Route::get('/Employee/edit/{peserta}', [PesertaController::class, 'edit'])->name('peserta.edit');
    Route::put('/Employee/update/{peserta}', [PesertaController::class, 'update'])->name('peserta.update');
    Route::delete('/Employee/delete/{id}', [PesertaController::class, 'destroy'])->name('peserta.destroy');


    // Route User
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/User/New_User', [UserController::class, 'store'])->name('user.store');
    Route::get('/User/dashboard', [UserController::class, 'index'])->name('user.index');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::get('/user/edit/{user}', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/update/{user}', [UserController::class, 'update'])->name('user.update');
    

    // API Search Peserta Form
    Route::get('/participants/{badgeNo}', [PesertaController::class, 'getParticipantByBadgeNo']);
});



// Super Admin Route
Route::middleware(['auth', 'role:Super Admin'])->group(function () {
    
    Route::post('/users/import', [ExcelController::class, 'import_peserta'])->name('import.peserta');
    Route::get('/users/expor', [ExcelController::class, 'export_peserta'])->name('export.peserta');
    Route::post('/training/import', [ExcelController::class, 'import_training'])->name('import.training');
    Route::get('/training/export', [ExcelController::class, 'export_training'])->name('export.training');
    
    Route::delete('/index/{id}', [FormController::class, 'destroy'])->name('dashboard.destroy');

});






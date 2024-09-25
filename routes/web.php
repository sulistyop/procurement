<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\AssignRoleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
	
    Route::post('/roles', [RoleController::class, 'createRole']);
    Route::post('/user/{userId}/assign-role-permission', [UserController::class, 'assignRole']);

	// dashboard
	Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
	
	
    Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
    
    Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');
    Route::get('/pengajuan/{pengajuan}', [PengajuanController::class, 'show'])->name('pengajuan.show');
    
    Route::get('/pengajuan/{pengajuan}/edit', [PengajuanController::class, 'edit'])->name('pengajuan.edit');
    Route::put('/pengajuan/{pengajuan}', [PengajuanController::class, 'update'])->name('pengajuan.update');
    Route::delete('/pengajuan/{pengajuan}', [PengajuanController::class, 'destroy'])->name('pengajuan.destroy');
    
    // Approval routes
    Route::get('/pengajuan/{pengajuan}/approve', [PengajuanController::class, 'approve'])->name('pengajuan.approve');
    Route::post('/pengajuan/{pengajuan}/approve', [PengajuanController::class, 'storeApproval'])->name('pengajuan.storeApproval');
	
	
	// Rekap Pengajuan
	Route::get('/rekap-pengajuan', [App\Http\Controllers\RekapPengajuanController::class, 'index'])->name('rekap-pengajuan.index');
	
	Route::get('pengajuan/import', [PengajuanController::class, 'importForm'])->name('pengajuan.importForm');
	Route::post('pengajuan/import', [PengajuanController::class, 'import'])->name('pengajuan.import');

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
	
});

Route::middleware(['auth', 'admin'])->group(function () {
	Route::resource('user',  UserController::class);
	
	Route::get('/roles-permissions/edit', [RolePermissionController::class, 'edit'])->name('roles-permissions.edit');
	Route::post('/roles-permissions/update', [RolePermissionController::class, 'update'])->name('roles-permissions.update');

});
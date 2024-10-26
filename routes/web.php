<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\AssignRoleController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\ApproveKeuanganController;

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



Route::middleware('auth')->group(function () {
	
	Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
	Route::get('/show/{pengajuan}', [\App\Http\Controllers\HomeController::class, 'show'])->name('home-show');
	
    Route::post('/roles', [RoleController::class, 'createRole']);
    Route::post('/user/{userId}/assign-role-permission', [UserController::class, 'assignRole']);

	// dashboard
	Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
	
	// route group pengajuan dengan prefix pengajuan
	Route::group(['prefix' => 'pengajuan'], function () {
		Route::get('/', [PengajuanController::class, 'index'])->name('pengajuan.index');
		Route::get('/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
		
		Route::post('/', [PengajuanController::class, 'store'])->name('pengajuan.store');
		Route::get('/{pengajuan}', [PengajuanController::class, 'show'])->name('pengajuan.show');
		
		Route::get('/{pengajuan}/edit', [PengajuanController::class, 'edit'])->name('pengajuan.edit');
		Route::put('/{pengajuan}', [PengajuanController::class, 'update'])->name('pengajuan.update');
		Route::delete('/{pengajuan}', [PengajuanController::class, 'destroy'])->name('pengajuan.destroy');
		
		// Approval routes
		Route::get('/{pengajuan}/approve', [PengajuanController::class, 'approve'])->name('pengajuan.approve');
		Route::post('/{pengajuan}/approve', [PengajuanController::class, 'storeApproval'])->name('pengajuan.storeApproval');
	});
	
	Route::group(['prefix' => 'approve-keuangan'], function () {
	    // Route::resource('approve-keuangan', ApproveKeuanganController::class);
	    Route::get('/', [ApproveKeuanganController::class, 'index'])->name('approve-keuangan.index');
	    Route::get('/create', [ApproveKeuanganController::class, 'create'])->name('approve-keuangan.create');
	    Route::post('/', [ApproveKeuanganController::class, 'store'])->name('approve-keuangan.store');
	    Route::get('/{approveKeuangan}', [ApproveKeuanganController::class, 'show'])->name('approve-keuangan.show');
	    Route::get('/{approveKeuangan}/edit', [ApproveKeuanganController::class, 'edit'])->name('approve-keuangan.edit');
	    Route::put('/{approveKeuangan}', [ApproveKeuanganController::class, 'update'])->name('approve-keuangan.update');
	    Route::delete('/{approveKeuangan}', [ApproveKeuanganController::class, 'destroy'])->name('approve-keuangan.destroy');
	});
	
    // Rekap Pengajuan
	Route::get('/rekap-pengajuan', [App\Http\Controllers\RekapPengajuanController::class, 'index'])->name('rekap-pengajuan.index');

	Route::get('pengajuan/import', [PengajuanController::class, 'importForm'])->name('pengajuan.importForm');
	Route::post('pengajuan/import', [PengajuanController::class, 'import'])->name('pengajuan.import');

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
	
});

Route::middleware(['auth', 'admin'])->group(function () {
	Route::resource('user',  UserController::class);
	Route::get('/activity-logs', [App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-logs.index');
	Route::get('/roles-permissions/edit', [RolePermissionController::class, 'edit'])->name('roles-permissions.edit');
	Route::put('/roles-permissions/{role}', [RolePermissionController::class, 'update'])->name('roles-permissions.update');
});
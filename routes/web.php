<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::middleware('auth')->group(function (){
    Route::get('/pengajuan', [App\Http\Controllers\PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/create', [App\Http\Controllers\PengajuanController::class, 'create'])->name('pengajuan.create');

    Route::post('/pengajuan', [App\Http\Controllers\PengajuanController::class, 'store'])->name('pengajuan.store');
    Route::get('/pengajuan/{pengajuan}', [App\Http\Controllers\PengajuanController::class, 'show'])->name('pengajuan.show');

    Route::get('/pengajuan/{pengajuan}/edit', [App\Http\Controllers\PengajuanController::class, 'edit'])->name('pengajuan.edit');
    Route::put('/pengajuan/{pengajuan}', [App\Http\Controllers\PengajuanController::class, 'update'])->name('pengajuan.update');
    Route::delete('/pengajuan/{pengajuan}', [App\Http\Controllers\PengajuanController::class, 'destroy'])->name('pengajuan.destroy');
});

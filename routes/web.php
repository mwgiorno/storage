<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\OnlyOffice\DocumentController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\EnsureFileIsConvertible;
use App\Http\Middleware\EnsureFileIsPDFConvertible;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/files');

Route::post('/onlyoffice/save', [DocumentController::class, 'save'])->name('onlyoffice.save');

Route::middleware('auth')->group(function () {
    Route::get('/files/download/{file}', [FileController::class, 'download'])->name('files.download');
    Route::get('/files', [FileController::class, 'index'])->name('files');
    Route::get('/files/create', [FileController::class, 'create'])->name('files.create');
    Route::post('/files', [FileController::class, 'store'])->name('files.store');
    Route::get('/files/{file}/show', [FileController::class, 'show'])->name('files.show');
    Route::get('/files/{file}', [FileController::class, 'edit'])->name('files.edit');
    Route::patch('/files/{file}', [FileController::class, 'update'])->name('files.update');
    Route::delete('/files/{file}', [FileController::class, 'destroy'])->name('files.destroy');

    Route::get('/onlyoffice/download', [DocumentController::class, 'download'])
        ->name('onlyoffice.download')
        ->middleware('convertible:pdf');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

<?php

use Illuminate\Support\Facades\Route;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use App\Http\Controllers\KonateliaController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthController;


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






Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->middleware('auth')->name('password.change.form');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->middleware('auth')->name('password.change');


    Route::get('/generate-company-pdfs', [PdfController::class, 'generateCompanyPdfs']);
    Route::get('/download-pdf-zip', [PdfController::class, 'downloadZip']);
    Route::get('/download-redirect', [PdfController::class, 'downloadZipAndRedirect']);

    Route::get('/', [Controller::class, 'show']);
});



<?php

use Illuminate\Support\Facades\Route;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use App\Http\Controllers\KonateliaController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\Controller;


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



Route::get('/generate-company-pdfs', [PdfController::class, 'generateCompanyPdfs']);
Route::get('/download-pdf-zip', [PdfController::class, 'downloadZip']);
Route::get('/download-redirect', [PdfController::class, 'downloadZipAndRedirect']);

Route::get('/', [Controller::class, 'show']);


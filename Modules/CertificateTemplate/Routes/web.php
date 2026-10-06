<?php

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

use Illuminate\Support\Facades\Route;
use Modules\CertificateTemplate\Http\Controllers\CertificateTemplateController;

Route::group([
    'prefix' => 'admin/certificate/template'
], function () {
    Route::get('/', [CertificateTemplateController::class, 'index'])->name('admin.certificate.template.index');
    Route::get('create', [CertificateTemplateController::class, 'create'])->name('admin.certificate.template.create');
    Route::get('edit/{id}', [CertificateTemplateController::class, 'edit'])->name('admin.certificate.template.edit');
    Route::post('update/{id}', [CertificateTemplateController::class, 'update'])->name('admin.certificate.template.update');
    Route::get('/upload/{id}', [CertificateTemplateController::class, 'loadHTML'])->name('admin.certificate.template.upload');
    Route::get('show/{id}', [CertificateTemplateController::class, 'show'])->name('admin.certificate.template.show');
    Route::post('/convert', [CertificateTemplateController::class, 'convertDoc'])->name('admin.certificate.template.convert');
    Route::post('/save-html', [CertificateTemplateController::class, 'saveHtml'])->name('admin.certificate.template.saveHtml');
});

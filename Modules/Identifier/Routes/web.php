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
use Modules\Identifier\Http\Controllers\CountryIdentifierController;
use Modules\Identifier\Http\Controllers\IdentifierController;
use Modules\Identifier\Http\Controllers\IdentifierTypeController;

Route::group([
    'prefix' => 'admin/identifier'
], function () {
    Route::get('/', [IdentifierController::class, 'index'])->name('admin.identifier.index');
    Route::get('create', [IdentifierController::class, 'create'])->name('admin.identifier.create');
    Route::post('store', [IdentifierController::class, 'store'])->name('admin.identifier.store');
    Route::get('edit/{id}', [IdentifierController::class, 'edit'])->name('admin.identifier.edit');
    Route::post('update/{id}', [IdentifierController::class, 'update'])->name('admin.identifier.update');
    Route::get('destroy/{id}', [IdentifierController::class, 'destroy'])->name('admin.identifier.delete');
});
Route::group([
    'prefix' => 'admin/identifier-type'
], function () {
    Route::get('/', [IdentifierTypeController::class, 'index'])->name('admin.identifier.type.index');
    Route::get('create', [IdentifierTypeController::class, 'create'])->name('admin.identifier.type.create');
    Route::post('store', [IdentifierTypeController::class, 'store'])->name('admin.identifier.type.store');
    Route::get('edit/{id}', [IdentifierTypeController::class, 'edit'])->name('admin.identifier.type.edit');
    Route::post('update/{id}', [IdentifierTypeController::class, 'update'])->name('admin.identifier.type.update');
    Route::get('destroy/{id}', [IdentifierTypeController::class, 'destroy'])->name('admin.identifier.type.delete');
});
Route::group([
    'prefix' => 'admin/identifier-country'
], function () {
    Route::get('/', [CountryIdentifierController::class, 'index'])->name('admin.identifier.country.index');
    Route::post('updateBulk', [CountryIdentifierController::class, 'updateBulk'])->name('admin.identifier.country.update.bulk');
});

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
use Modules\Classroom\Http\Controllers\ClassroomController;

Route::group([
    'prefix' => 'admin/classroom'
], function () {
    Route::get('/', [ClassroomController::class, 'index'])->name('admin.classroom.index');
    Route::get('create', [ClassroomController::class, 'create'])->name('admin.classroom.create');
    Route::post('store', [ClassroomController::class, 'store'])->name('admin.classroom.store');
    Route::get('edit/{id}', [ClassroomController::class, 'edit'])->name('admin.classroom.edit');
    Route::post('update/{id}', [ClassroomController::class, 'update'])->name('admin.classroom.update');
});

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
use Modules\Course\Http\Controllers\AssignmentController;
use Modules\Course\Http\Controllers\AssignmentFileController;
use Modules\Course\Http\Controllers\AssignmentMCQController;
use Modules\Course\Http\Controllers\AssignmentQuestionController;
use Modules\Course\Http\Controllers\CourseController;
use Modules\Course\Http\Controllers\CourseFeeController;
use Modules\Course\Http\Controllers\MissingUnitController;
use Modules\Course\Http\Controllers\ResourceController;
use Modules\Course\Http\Controllers\UnitController;

Route::group([
    'prefix' => 'admin/course'
], function () {
    Route::get('/', [CourseController::class, 'index'])->name('admin.course.index');
    Route::get('create', [CourseController::class, 'create'])->name('admin.course.create');
    Route::post('store', [CourseController::class, 'store'])->name('admin.course.store');
    Route::get('edit/{id}', [CourseController::class, 'edit'])->name('admin.course.edit');
    Route::post('update/{id}', [CourseController::class, 'update'])->name('admin.course.update');
    Route::get('destroy/{id}', [CourseController::class, 'destroy'])->name('admin.course.delete');
    Route::get('menu', [CourseController::class, 'menu'])->name('admin.course.menu');
    Route::group([
        'prefix' => 'unit'
    ], function () {
        Route::get('/{id}', [UnitController::class, 'index'])->name('admin.course.unit.index');
        Route::get('create/{id}', [UnitController::class, 'create'])->name('admin.course.unit.create');
        Route::post('store/{id}', [UnitController::class, 'store'])->name('admin.course.unit.store');
        Route::get('edit/{id}', [UnitController::class, 'edit'])->name('admin.course.unit.edit');
        Route::post('update/{id}', [UnitController::class, 'update'])->name('admin.course.unit.update');
        Route::get('destroy/{id}', [UnitController::class, 'destroy'])->name('admin.course.unit.delete');
        Route::group([
            'prefix' => 'resource'
        ], function () {
            Route::get('/{id}', [ResourceController::class, 'index'])->name('admin.course.unit.resource.index');
            Route::get('create/{id}', [ResourceController::class, 'create'])->name('admin.course.unit.resource.create');
            Route::post('store/{id}', [ResourceController::class, 'store'])->name('admin.course.unit.resource.store');
            Route::get('edit/{id}', [ResourceController::class, 'edit'])->name('admin.course.unit.resource.edit');
            Route::post('update/{id}', [ResourceController::class, 'update'])->name('admin.course.unit.resource.update');
            Route::get('destroy/{id}', [ResourceController::class, 'destroy'])->name('admin.course.unit.resource.delete');
        });
        Route::group([
            'prefix' => 'assignment'
        ], function () {
            Route::get('/{id}', [AssignmentController::class, 'index'])->name('admin.course.unit.assignment.index');
            Route::get('create/{id}', [AssignmentController::class, 'create'])->name('admin.course.unit.assignment.create');
            Route::post('store/{id}', [AssignmentController::class, 'store'])->name('admin.course.unit.assignment.store');
            Route::get('edit/{id}', [AssignmentController::class, 'edit'])->name('admin.course.unit.assignment.edit');
            Route::post('update/{id}', [AssignmentController::class, 'update'])->name('admin.course.unit.assignment.update');
            Route::get('destroy/{id}', [AssignmentController::class, 'destroy'])->name('admin.course.unit.assignment.delete');
            Route::group([
                'prefix' => 'file'
            ], function () {
                Route::get('create/{id}', [AssignmentFileController::class, 'create'])->name('admin.course.unit.assignment.file.create');
                Route::post('store/{id}', [AssignmentFileController::class, 'store'])->name('admin.course.unit.assignment.file.store');
            });
            Route::group([
                'prefix' => 'question'
            ], function () {
                Route::get('create/{id}', [AssignmentQuestionController::class, 'create'])->name('admin.course.unit.assignment.question.create');
                Route::post('store/{id}', [AssignmentQuestionController::class, 'store'])->name('admin.course.unit.assignment.question.store');
                Route::get('show/{id}', [AssignmentQuestionController::class, 'show'])->name('admin.course.unit.assignment.question.show');
                Route::post('update/{id}', [AssignmentQuestionController::class, 'update'])->name('admin.course.unit.assignment.question.update');
            });
            Route::group([
                'prefix' => 'mcq'
            ], function () {
                Route::get('create/{id}', [AssignmentMCQController::class, 'create'])->name('admin.course.unit.assignment.mcq.create');
                Route::post('store/{id}', [AssignmentMCQController::class, 'store'])->name('admin.course.unit.assignment.mcq.store');
                Route::post('finish/{id}', [AssignmentMCQController::class, 'finish'])->name('admin.course.unit.assignment.mcq.finish');
                Route::get('show/{id}', [AssignmentMCQController::class, 'show'])->name('admin.course.unit.assignment.mcq.show');
                Route::get('edit/{id}', [AssignmentMCQController::class, 'edit'])->name('admin.course.unit.assignment.mcq.edit');
                Route::post('update/{id}', [AssignmentMCQController::class, 'update'])->name('admin.course.unit.assignment.mcq.update');
            });
        });
        Route::group([
            'prefix' => 'missing'
        ], function () {
            Route::get('/{id}', [MissingUnitController::class, 'index'])->name('admin.course.unit.missing.index');
            Route::post('update/{id}', [MissingUnitController::class, 'update'])->name('admin.course.unit.missing.update');
        });
    });
    Route::group([
        'prefix' => 'fee'
    ], function () {
        Route::get('{id}', [CourseFeeController::class, 'index'])->name('admin.course.fee.index');
        Route::get('create/{id}', [CourseFeeController::class, 'create'])->name('admin.course.fee.create');
        Route::post('store/{id}', [CourseFeeController::class, 'store'])->name('admin.course.fee.store');
        Route::get('edit/{id}', [CourseFeeController::class, 'edit'])->name('admin.course.fee.edit');
        Route::post('update/{id}', [CourseFeeController::class, 'update'])->name('admin.course.fee.update');
        Route::get('destroy/{id}', [CourseFeeController::class, 'destroy'])->name('admin.course.fee.delete');
    });
});

Route::group([
    'prefix' => 'admin/unregistered'
], function () {
    Route::get('/', [CourseController::class, 'index'])->name('admin.unregistered.index');
    Route::get('create', [CourseController::class, 'create'])->name('admin.unregistered.create');
    Route::post('store', [CourseController::class, 'store'])->name('admin.unregistered.store');
    Route::get('edit/{id}', [CourseController::class, 'edit'])->name('admin.unregistered.edit');
    Route::post('update/{id}', [CourseController::class, 'update'])->name('admin.unregistered.update');
    Route::group([
        'prefix' => 'unit'
    ], function () {
        Route::get('/{id}', [UnitController::class, 'index'])->name('admin.unregistered.unit.index');
        Route::get('create/{id}', [UnitController::class, 'create'])->name('admin.unregistered.unit.create');
        Route::post('store/{id}', [UnitController::class, 'store'])->name('admin.unregistered.unit.store');
        Route::get('edit/{id}', [UnitController::class, 'edit'])->name('admin.unregistered.unit.edit');
        Route::post('update/{id}', [UnitController::class, 'update'])->name('admin.unregistered.unit.update');
        Route::group([
            'prefix' => 'resource'
        ], function () {
            Route::get('/{id}', [ResourceController::class, 'index'])->name('admin.unregistered.unit.resource.index');
            Route::get('create/{id}', [ResourceController::class, 'create'])->name('admin.unregistered.unit.resource.create');
            Route::post('store/{id}', [ResourceController::class, 'store'])->name('admin.unregistered.unit.resource.store');
            Route::get('edit/{id}', [ResourceController::class, 'edit'])->name('admin.unregistered.unit.resource.edit');
            Route::post('update/{id}', [ResourceController::class, 'update'])->name('admin.unregistered.unit.resource.update');
        });
        Route::group([
            'prefix' => 'assignment'
        ], function () {
            Route::get('/{id}', [AssignmentController::class, 'index'])->name('admin.unregistered.unit.assignment.index');
            Route::get('create/{id}', [AssignmentController::class, 'create'])->name('admin.unregistered.unit.assignment.create');
            Route::post('store/{id}', [AssignmentController::class, 'store'])->name('admin.unregistered.unit.assignment.store');
            Route::get('edit/{id}', [AssignmentController::class, 'edit'])->name('admin.unregistered.unit.assignment.edit');
            Route::post('update/{id}', [AssignmentController::class, 'update'])->name('admin.unregistered.unit.assignment.update');
            Route::group([
                'prefix' => 'file'
            ], function () {
                Route::get('create/{id}', [AssignmentFileController::class, 'create'])->name('admin.unregistered.unit.assignment.file.create');
                Route::post('store/{id}', [AssignmentFileController::class, 'store'])->name('admin.unregistered.unit.assignment.file.store');
            });
            Route::group([
                'prefix' => 'question'
            ], function () {
                Route::get('create/{id}', [AssignmentQuestionController::class, 'create'])->name('admin.unregistered.unit.assignment.question.create');
                Route::post('store/{id}', [AssignmentQuestionController::class, 'store'])->name('admin.unregistered.unit.assignment.question.store');
                Route::get('show/{id}', [AssignmentQuestionController::class, 'show'])->name('admin.unregistered.unit.assignment.question.show');
                Route::post('update/{id}', [AssignmentQuestionController::class, 'update'])->name('admin.unregistered.unit.assignment.question.update');
            });
            Route::group([
                'prefix' => 'mcq'
            ], function () {
                Route::get('create/{id}', [AssignmentMCQController::class, 'create'])->name('admin.unregistered.unit.assignment.mcq.create');
                Route::post('store/{id}', [AssignmentMCQController::class, 'store'])->name('admin.unregistered.unit.assignment.mcq.store');
                Route::post('finish/{id}', [AssignmentMCQController::class, 'finish'])->name('admin.unregistered.unit.assignment.mcq.finish');
                Route::get('show/{id}', [AssignmentMCQController::class, 'show'])->name('admin.unregistered.unit.assignment.mcq.show');
                Route::get('edit/{id}', [AssignmentMCQController::class, 'edit'])->name('admin.unregistered.unit.assignment.mcq.edit');
                Route::post('update/{id}', [AssignmentMCQController::class, 'update'])->name('admin.unregistered.unit.assignment.mcq.update');
            });
        });
        Route::group([
            'prefix' => 'missing'
        ], function () {
            Route::get('/{id}', [MissingUnitController::class, 'index'])->name('admin.unregistered.unit.missing.index');
            Route::post('update/{id}', [MissingUnitController::class, 'create'])->name('admin.unregistered.unit.missing.update');
        });
    });
    Route::group([
        'prefix' => 'fee'
    ], function () {
        Route::get('{id}', [CourseFeeController::class, 'index'])->name('admin.unregistered.fee.index');
        Route::get('create/{id}', [CourseFeeController::class, 'create'])->name('admin.unregistered.fee.create');
        Route::post('store/{id}', [CourseFeeController::class, 'store'])->name('admin.unregistered.fee.store');
        Route::get('edit/{id}', [CourseFeeController::class, 'edit'])->name('admin.unregistered.fee.edit');
        Route::post('update/{id}', [CourseFeeController::class, 'update'])->name('admin.unregistered.fee.update');
    });
});

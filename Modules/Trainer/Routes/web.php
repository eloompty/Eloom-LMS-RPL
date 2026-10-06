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
use Modules\Trainer\Http\Controllers\Trainer\AssignmentController;
use Modules\Trainer\Http\Controllers\Trainer\AssignmentFileController;
use Modules\Trainer\Http\Controllers\Trainer\AssignmentMCQController;
use Modules\Trainer\Http\Controllers\Trainer\AssignmentQuestionController;
use Modules\Trainer\Http\Controllers\Trainer\AttendanceController;
use Modules\Trainer\Http\Controllers\Trainer\CalendarController;
use Modules\Trainer\Http\Controllers\Trainer\CourseController;
use Modules\Trainer\Http\Controllers\Trainer\IntakeUnitChatController;
use Modules\Trainer\Http\Controllers\Trainer\LoginController;
use Modules\Trainer\Http\Controllers\Trainer\OnlineClassController;
use Modules\Trainer\Http\Controllers\Trainer\OnlineClassGroupClassController;
use Modules\Trainer\Http\Controllers\Trainer\OnlineClassGroupController;
use Modules\Trainer\Http\Controllers\Trainer\OnlineClassGroupTeamController;
use Modules\Trainer\Http\Controllers\Trainer\ResourceController;
use Modules\Trainer\Http\Controllers\Trainer\ResubmissionController;
use Modules\Trainer\Http\Controllers\Trainer\Student\ChatController;
use Modules\Trainer\Http\Controllers\Trainer\Student\StudentController as StudentStudentController;
use Modules\Trainer\Http\Controllers\Trainer\StudentController;
use Modules\Trainer\Http\Controllers\Trainer\SubmissionController;
use Modules\Trainer\Http\Controllers\Trainer\TeamController;
use Modules\Trainer\Http\Controllers\Trainer\TrainerAssignmentController;
use Modules\Trainer\Http\Controllers\Trainer\TrainerController as TrainerTrainerController;
use Modules\Trainer\Http\Controllers\Trainer\TrainerSubmissionController;
use Modules\Trainer\Http\Controllers\TrainerController;
use Modules\Trainer\Http\Controllers\TrainerDeviceController;
use Modules\Trainer\Http\Controllers\TrainerIntakeController;
use Modules\Trainer\Http\Controllers\TrainerLogController;
use Modules\Trainer\Http\Controllers\TrainerProfessionalDevelopmentController;
use Modules\Trainer\Http\Controllers\TrainerQualificationController;
use Modules\Trainer\Http\Controllers\TrainerWorkPlacementController;

Route::group([
    'prefix' => 'admin/trainer'
], function () {
    Route::get('/', [TrainerController::class, 'index'])->name('admin.trainer.index');
    Route::get('create', [TrainerController::class, 'create'])->name('admin.trainer.create');
    Route::post('store', [TrainerController::class, 'store'])->name('admin.trainer.store');
    Route::get('edit/{id}', [TrainerController::class, 'edit'])->name('admin.trainer.edit');
    Route::post('update/{id}', [TrainerController::class, 'update'])->name('admin.trainer.update');
    Route::get('destroy/{id}', [TrainerController::class, 'destroy'])->name('admin.trainer.delete');
    Route::get('dashboard/{id}', [TrainerController::class, 'dashboard'])->name('admin.trainer.dashboard');
    Route::get('university', [TrainerQualificationController::class, 'university']);
    Route::group([
        'prefix' => 'qualification'
    ], function () {
        Route::get('/{id}', [TrainerQualificationController::class, 'index'])->name('admin.trainer.qualification.index');
        Route::get('create/{id}', [TrainerQualificationController::class, 'create'])->name('admin.trainer.qualification.create');
        Route::post('store/{id}', [TrainerQualificationController::class, 'store'])->name('admin.trainer.qualification.store');
        Route::get('edit/{id}', [TrainerQualificationController::class, 'edit'])->name('admin.trainer.qualification.edit');
        Route::post('update/{id}', [TrainerQualificationController::class, 'update'])->name('admin.trainer.qualification.update');
        Route::get('destroy/{id}', [TrainerQualificationController::class, 'destroy'])->name('admin.trainer.qualification.delete');
    });
    Route::group([
        'prefix' => 'profession'
    ], function () {
        Route::get('/{id}', [TrainerProfessionalDevelopmentController::class, 'index'])->name('admin.trainer.profession.index');
        Route::get('create/{id}', [TrainerProfessionalDevelopmentController::class, 'create'])->name('admin.trainer.profession.create');
        Route::post('store/{id}', [TrainerProfessionalDevelopmentController::class, 'store'])->name('admin.trainer.profession.store');
        Route::get('edit/{id}', [TrainerProfessionalDevelopmentController::class, 'edit'])->name('admin.trainer.profession.edit');
        Route::post('update/{id}', [TrainerProfessionalDevelopmentController::class, 'update'])->name('admin.trainer.profession.update');
        Route::get('destroy/{id}', [TrainerProfessionalDevelopmentController::class, 'destroy'])->name('admin.trainer.profession.delete');
    });
    Route::group([
        'prefix' => 'workplacement'
    ], function () {
        Route::get('/{id}', [TrainerWorkPlacementController::class, 'index'])->name('admin.trainer.workplacement.index');
        Route::get('create/{id}', [TrainerWorkPlacementController::class, 'create'])->name('admin.trainer.workplacement.create');
        Route::post('store/{id}', [TrainerWorkPlacementController::class, 'store'])->name('admin.trainer.workplacement.store');
        Route::get('edit/{id}', [TrainerWorkPlacementController::class, 'edit'])->name('admin.trainer.workplacement.edit');
        Route::post('update/{id}', [TrainerWorkPlacementController::class, 'update'])->name('admin.trainer.workplacement.update');
        Route::get('destroy/{id}', [TrainerWorkPlacementController::class, 'destroy'])->name('admin.trainer.workplacement.delete');
    });
    Route::group([
        'prefix' => 'intake'
    ], function () {
        Route::get('/{id}', [TrainerIntakeController::class, 'index'])->name('admin.trainer.intake.index');
        Route::get('create/{id}', [TrainerIntakeController::class, 'create'])->name('admin.trainer.intake.create');
        Route::post('store/{id}', [TrainerIntakeController::class, 'store'])->name('admin.trainer.intake.store');
        Route::get('edit/{id}', [TrainerIntakeController::class, 'edit'])->name('admin.trainer.intake.edit');
        Route::post('update/{id}', [TrainerIntakeController::class, 'update'])->name('admin.trainer.intake.update');
        Route::get('destroy/{id}', [TrainerIntakeController::class, 'destroy'])->name('admin.trainer.intake.delete');
    });
    Route::get('log/{id}', [TrainerLogController::class, 'index'])->name('admin.trainer.log.index');
    Route::get('device/{id}', [TrainerDeviceController::class, 'index'])->name('admin.trainer.device.index');
});

Route::group([
    'prefix' => 'trainer'
], function () {
    Route::get('/', [LoginController::class, 'home'])->name('trainer.login');
    Route::get('login', [LoginController::class, 'home'])->name('trainer.login');
    Route::post('login', [LoginController::class, 'login'])->name('trainer.login');
    Route::post('save/device', [LoginController::class, 'saveToken'])->name('trainer.device.save');
    Route::get('logout', [LoginController::class, 'logout'])->name('trainer.logout')->middleware('auth:trainer');;
    Route::group([
        'prefix' => 'password'
    ], function () {
        Route::get('forgot', [LoginController::class, 'forgotPassword'])->name('trainer.password.forgot');
        Route::post('reset', [LoginController::class, 'resetPassword'])->name('trainer.password.reset');
        Route::get('reset/{email}/{code}', [LoginController::class, 'resettingPassword']);
        Route::post('update', [LoginController::class, 'updatePassword'])->name('trainer.password.update');
    });
    Route::get('dashboard', [TrainerTrainerController::class, 'dashboard'])->name('trainer.dashboard');
    Route::get('profile', [TrainerTrainerController::class, 'profile'])->name('trainer.profile');
    Route::get('change/password', [TrainerTrainerController::class, 'changePassword'])->name('trainer.change.password');
    Route::post('fill/password', [TrainerTrainerController::class, 'fillPassword'])->name('trainer.fill.password');
    Route::group([
        'prefix' => 'course'
    ], function () {
        Route::get('/', [CourseController::class, 'index'])->name('trainer.course.index');
        Route::get('unit/{id}', [CourseController::class, 'unit'])->name('trainer.unit.index');
        Route::group([
            'prefix' => 'unit/chat'
        ], function () {
            Route::get('/{id}', [IntakeUnitChatController::class, 'index'])->name('trainer.unit.chat.index');
            Route::get('create/{id}/{trainer_intake_id}', [IntakeUnitChatController::class, 'create'])->name('trainer.unit.chat.create');
            Route::post('store/{id}/{trainer_intake_id}', [IntakeUnitChatController::class, 'store'])->name('trainer.unit.chat.store');
            Route::get('show/{id}', [IntakeUnitChatController::class, 'show'])->name('trainer.unit.chat.show');
            Route::get('edit/{id}', [IntakeUnitChatController::class, 'edit'])->name('trainer.unit.chat.edit');
            Route::post('update/{id}', [IntakeUnitChatController::class, 'update'])->name('trainer.unit.chat.update');
            Route::post('sendMessage/{id}', [IntakeUnitChatController::class, 'sendMessage'])->name('trainer.unit.chat.send.message');
            Route::post('createMessage', [IntakeUnitChatController::class, 'createMessage']);
            Route::get('load/message', [IntakeUnitChatController::class, 'loadMessages']);
        });
        Route::group([
            'prefix' => 'resource'
        ], function () {
            Route::get('/{id}', [ResourceController::class, 'index'])->name('trainer.resource.index');
            Route::get('create/{id}/{trainer_intake_id}', [ResourceController::class, 'create'])->name('trainer.resource.create');
            Route::post('store/{id}/{trainer_intake_id}', [ResourceController::class, 'store'])->name('trainer.resource.store');
            Route::get('edit/{id}/{trainer_intake_id}', [ResourceController::class, 'edit'])->name('trainer.resource.edit');
            Route::post('update/{id}', [ResourceController::class, 'update'])->name('trainer.resource.update');
        });
        Route::group([
            'prefix' => 'assignment'
        ], function () {
            Route::get('/{id}', [AssignmentController::class, 'index'])->name('trainer.assignment.index');
            Route::get('create/{id}', [AssignmentController::class, 'create'])->name('trainer.assignment.create');
            Route::post('store/{id}', [AssignmentController::class, 'store'])->name('trainer.assignment.store');
            Route::get('edit/{id}/{trainer_intake_id}', [AssignmentController::class, 'edit'])->name('trainer.assignment.edit');
            Route::post('update/{id}/{trainer_intake_id}', [AssignmentController::class, 'update'])->name('trainer.assignment.update');
            Route::group([
                'prefix' => 'file'
            ], function () {
                Route::get('create/{id}', [AssignmentFileController::class, 'create'])->name('trainer.assignment.file.create');
                Route::post('store/{id}', [AssignmentFileController::class, 'store'])->name('trainer.assignment.file.store');
            });
            Route::group([
                'prefix' => 'question'
            ], function () {
                Route::get('create/{id}', [AssignmentQuestionController::class, 'create'])->name('trainer.assignment.question.create');
                Route::post('store/{id}', [AssignmentQuestionController::class, 'store'])->name('trainer.assignment.question.store');
                Route::get('show/{id}', [AssignmentQuestionController::class, 'show'])->name('trainer.assignment.question.show');
                Route::post('update/{id}', [AssignmentQuestionController::class, 'update'])->name('trainer.assignment.question.update');
            });
            Route::group([
                'prefix' => 'mcq'
            ], function () {
                Route::get('create/{id}', [AssignmentMCQController::class, 'create'])->name('trainer.assignment.mcq.create');
                Route::post('store/{id}', [AssignmentMCQController::class, 'store'])->name('trainer.assignment.mcq.store');
                Route::post('finish/{id}', [AssignmentMCQController::class, 'finish'])->name('trainer.assignment.mcq.finish');
                Route::get('show/{id}', [AssignmentMCQController::class, 'show'])->name('trainer.assignment.mcq.show');
                Route::get('edit/{id}', [AssignmentMCQController::class, 'edit'])->name('trainer.assignment.mcq.edit');
                Route::post('update/{id}', [AssignmentMCQController::class, 'update'])->name('trainer.assignment.mcq.update');
            });
            Route::group([
                'prefix' => 'submission'
            ], function () {
                Route::get('/{id}/{trainer_intake_id}', [SubmissionController::class, 'index'])->name('trainer.submission.index');
                Route::get('edit/{id}/{trainer_intake_id}', [SubmissionController::class, 'edit'])->name('trainer.submission.edit');
                Route::post('update/{id}/{trainer_intake_id}', [SubmissionController::class, 'update'])->name('trainer.submission.update');
                Route::get('question/{id}/{trainer_intake_id}', [SubmissionController::class, 'question'])->name('trainer.submission.question.index');
                Route::post('question/remarks/{id}', [SubmissionController::class, 'questionRemarks'])->name('trainer.submission.question.remarks');
                Route::get('mcq/{id}/{trainer_intake_id}', [SubmissionController::class, 'mcq'])->name('trainer.submission.mcq.index');
                Route::post('mcq/{id}', [SubmissionController::class, 'mcqRemarks'])->name('trainer.submission.mcq.remarks');
                Route::get('pdf/{id}/{type}', [SubmissionController::class, 'pdf'])->name('trainer.submission.pdf');
                Route::post('pdfshow/{id}/{type}/save', [SubmissionController::class, 'pdfsave'])->name('trainer.submission.pdfsave');
            });
            Route::group([
                'prefix' => 'resubmission'
            ], function () {
                Route::get('/{id}/{trainer_intake_id}', [ResubmissionController::class, 'index'])->name('trainer.resubmission.index');
                Route::get('edit/{id}/{trainer_intake_id}', [ResubmissionController::class, 'edit'])->name('trainer.resubmission.edit');
                Route::post('update/{id}/{trainer_intake_id}', [ResubmissionController::class, 'update'])->name('trainer.resubmission.update');
            });
        });
        Route::group([
            'prefix' => 'onlineclass'
        ], function () {
            Route::get('/{id}', [OnlineClassController::class, 'index'])->name('trainer.onlineclass.index');
            Route::get('create/{id}', [OnlineClassController::class, 'create'])->name('trainer.onlineclass.create');
            Route::post('store/{id}', [OnlineClassController::class, 'store'])->name('trainer.onlineclass.store');
        });
        Route::group([
            'prefix' => 'team'
        ], function () {
            Route::get('/{id}', [TeamController::class, 'index'])->name('trainer.team.index');
            Route::post('store', [TeamController::class, 'store'])->name('trainer.team.store');
        });
        Route::group([
            'prefix' => 'student'
        ], function () {
            Route::get('/{id}', [StudentController::class, 'index'])->name('trainer.student.index');
            Route::get('/unit/{id}/{intake_course_id}', [StudentController::class, 'unit'])->name('trainer.student.unit.index');
            Route::get('assignment/{id}/{intake_unit_id}', [StudentController::class, 'assignment'])->name('trainer.student.assignment.index');
            Route::get('unit/complete/{id}/{intake_unit_id}', [StudentController::class, 'completeUnit'])->name('trainer.student.unit.complete');
        });
        Route::group([
            'prefix' => 'attendance'
        ], function () {
            Route::get('/{id}/{year}/{month}', [AttendanceController::class, 'index'])->name('trainer.attendance.index');
            Route::get('create/{id}', [AttendanceController::class, 'create'])->name('trainer.attendance.create');
            Route::post('store/{id}', [AttendanceController::class, 'store'])->name('trainer.attendance.store');
            Route::post('multiStore/{id}', [AttendanceController::class, 'multiStore'])->name('admin.intake.unit.attendance.multiStore');
            Route::get('year', [AttendanceController::class, 'getMonthByYear']);
        });
    });
    Route::group([
        'prefix' => 'students'
    ], function () {
        Route::get('/', [StudentStudentController::class, 'index'])->name('trainer.students.index');
        Route::get('/unit/{id}/{intake_course_id}', [StudentStudentController::class, 'unit'])->name('trainer.students.unit.index');
        Route::get('assignment/{id}/{intake_unit_id}', [StudentStudentController::class, 'assignment'])->name('trainer.students.assignment.index');
        Route::get('unit/complete/{id}/{intake_unit_id}', [StudentStudentController::class, 'completeUnit'])->name('trainer.students.unit.complete');
        Route::group([
            'prefix' => 'chat'
        ], function () {
            Route::get('{id}', [ChatController::class, 'index'])->name('trainer.students.chat.index');
            Route::post('sendMessage/{id}', [ChatController::class, 'sendMessage'])->name('trainer.students.chat.send.message');
        });
    });
    Route::group([
        'prefix' => 'onlineclass/group'
    ], function () {
        Route::get('/', [OnlineClassGroupController::class, 'index'])->name('trainer.onlineclass.group.index');
        Route::get('create', [OnlineClassGroupController::class, 'create'])->name('trainer.onlineclass.group.create');
        Route::post('store', [OnlineClassGroupController::class, 'store'])->name('trainer.onlineclass.group.store');
        Route::get('edit/{id}', [OnlineClassGroupController::class, 'edit'])->name('trainer.onlineclass.group.edit');
        Route::post('update/{id}', [OnlineClassGroupController::class, 'update'])->name('trainer.onlineclass.group.update');
        Route::group([
            'prefix' => 'class'
        ], function () {
            Route::get('/{id}', [OnlineClassGroupClassController::class, 'index'])->name('trainer.onlineclass.group.class.index');
            Route::get('create/{id}', [OnlineClassGroupClassController::class, 'create'])->name('trainer.onlineclass.group.class.create');
            Route::post('store/{id}', [OnlineClassGroupClassController::class, 'store'])->name('trainer.onlineclass.group.class.store');
        });
        Route::group([
            'prefix' => 'teams'
        ], function () {
            Route::get('/{id}', [OnlineClassGroupTeamController::class, 'index'])->name('trainer.onlineclass.group.teams.index');
            Route::post('store', [OnlineClassGroupTeamController::class, 'store'])->name('trainer.onlineclass.group.teams.store');
        });
    });
    Route::get('calendar', [CalendarController::class, 'index'])->name('trainer.calendar.index');
    Route::get('calendar/time', [CalendarController::class, 'getTrainerTimeTable']);
    Route::get('assignments', [TrainerAssignmentController::class, 'index'])->name('trainer.assignments.index');
    Route::get('submissions', [TrainerSubmissionController::class, 'index'])->name('trainer.submissions.index');
});

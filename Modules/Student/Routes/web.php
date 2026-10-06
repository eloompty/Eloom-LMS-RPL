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
use Modules\Student\Http\Controllers\Student\CalendarController;
use Modules\Student\Http\Controllers\Student\ChatController;
use Modules\Student\Http\Controllers\Student\CourseController;
use Modules\Student\Http\Controllers\Student\FeeController;
use Modules\Student\Http\Controllers\Student\LoginController;
use Modules\Student\Http\Controllers\Student\NotificationController;
use Modules\Student\Http\Controllers\Student\OnlineClassGroupController;
use Modules\Student\Http\Controllers\Student\StudentController as StudentStudentController;
use Modules\Student\Http\Controllers\Student\TicketController;
use Modules\Student\Http\Controllers\Student\UnitChatController;
use Modules\Student\Http\Controllers\StudentController;
use Modules\Student\Http\Controllers\StudentDeviceController;
use Modules\Student\Http\Controllers\StudentDocumentController;
use Modules\Student\Http\Controllers\StudentEmailController;
use Modules\Student\Http\Controllers\StudentIntakeCourseCompetenceCertificateController;
use Modules\Student\Http\Controllers\StudentIntakeCourseCompetenceController;
use Modules\Student\Http\Controllers\StudentIntakeCourseController;
use Modules\Student\Http\Controllers\StudentIntakeCourseFeeController;
use Modules\Student\Http\Controllers\StudentIntakeCourseFeeInstallmentController;
use Modules\Student\Http\Controllers\StudentIntakeUnitController;
use Modules\Student\Http\Controllers\StudentLogController;
use Modules\Student\Http\Controllers\StudentNoteController;
use Modules\Student\Http\Controllers\StudentOfferController;
use Modules\Student\Http\Controllers\StudentOfferLetterController;
use Modules\Student\Http\Controllers\StudentPaymentController;
use Modules\Student\Http\Controllers\StudentSocialController;
use Modules\Student\Http\Controllers\StudentSubmissionController;
use Modules\Student\Http\Controllers\StudentTemplateController;

Route::group([
    'prefix' => 'admin/student'
], function () {
    Route::get('/', [StudentController::class, 'index'])->name('admin.student.index');
    Route::get('create', [StudentController::class, 'create'])->name('admin.student.create');
    Route::post('store', [StudentController::class, 'store'])->name('admin.student.store');
    Route::get('show/{id}', [StudentController::class, 'show'])->name('admin.student.show');
    Route::get('edit/{id}', [StudentController::class, 'edit'])->name('admin.student.edit');
    Route::post('update/{id}', [StudentController::class, 'update'])->name('admin.student.update');
    Route::get('destroy/{id}', [StudentController::class, 'destroy'])->name('admin.student.delete');
    Route::get('dashboard/{id}', [StudentController::class, 'dashboard'])->name('admin.student.dashboard');
    Route::get('branch', [StudentController::class, 'getBranches']);
    Route::get('branch/user', [StudentController::class, 'getBranchUser']);
    Route::group([
        'prefix' => 'intake'
    ], function () {
        Route::group([
            'prefix' => 'course'
        ], function () {
            Route::get('/{id}', [StudentIntakeCourseController::class, 'index'])->name('admin.student.intake.course.index');
            Route::get('create/{id}', [StudentIntakeCourseController::class, 'create'])->name('admin.student.intake.course.create');
            Route::post('store/{id}', [StudentIntakeCourseController::class, 'store'])->name('admin.student.intake.course.store');
            Route::get('edit/{id}', [StudentIntakeCourseController::class, 'edit'])->name('admin.student.intake.course.edit');
            Route::post('update/{id}', [StudentIntakeCourseController::class, 'update'])->name('admin.student.intake.course.update');
            Route::get('destroy/{id}', [StudentIntakeCourseController::class, 'destroy'])->name('admin.student.intake.course.delete');
        });
        Route::group([
            'prefix' => 'unit'
        ], function () {
            Route::get('/{id}', [StudentIntakeUnitController::class, 'index'])->name('admin.student.intake.unit.index');
            Route::get('create/{id}', [StudentIntakeUnitController::class, 'create'])->name('admin.student.intake.unit.create');
            Route::post('store/{id}', [StudentIntakeUnitController::class, 'store'])->name('admin.student.intake.unit.store');
            Route::get('edit/{id}', [StudentIntakeUnitController::class, 'edit'])->name('admin.student.intake.unit.edit');
            Route::post('update/{id}', [StudentIntakeUnitController::class, 'update'])->name('admin.student.intake.unit.update');
            Route::post('bulkupdate/{id}', [StudentIntakeUnitController::class, 'bulkupdate'])->name('admin.student.intake.unit.bulkupdate');
            Route::get('destroy/{id}', [StudentIntakeUnitController::class, 'destroy'])->name('admin.student.intake.unit.delete');
            Route::group([
                'prefix' => 'submission'
            ], function () {
                Route::get('/{id}', [StudentSubmissionController::class, 'index'])->name('admin.student.intake.unit.submission.index');
                Route::get('edit/{id}', [StudentSubmissionController::class, 'edit'])->name('admin.student.intake.unit.submission.edit');
                Route::post('update/{id}', [StudentSubmissionController::class, 'update'])->name('admin.student.intake.unit.submission.update');
                Route::get('mcq/{id}', [StudentSubmissionController::class, 'mcq'])->name('admin.student.intake.unit.submission.mcq');
                Route::get('question/{id}', [StudentSubmissionController::class, 'question'])->name('admin.student.intake.unit.submission.question');
            });
        });
        Route::group([
            'prefix' => 'fee'
        ], function () {
            Route::get('/{id}', [StudentIntakeCourseFeeController::class, 'index'])->name('admin.student.intake.course.fee.index');
            Route::get('create/{id}', [StudentIntakeCourseFeeController::class, 'create'])->name('admin.student.intake.course.fee.create');
            Route::post('store/{id}', [StudentIntakeCourseFeeController::class, 'store'])->name('admin.student.intake.course.fee.store');
            Route::get('edit/{id}', [StudentIntakeCourseFeeController::class, 'edit'])->name('admin.student.intake.course.fee.edit');
            Route::post('update/{id}', [StudentIntakeCourseFeeController::class, 'update'])->name('admin.student.intake.course.fee.update');
            Route::group([
                'prefix' => 'installment'
            ], function () {
                Route::get('/{id}', [StudentIntakeCourseFeeInstallmentController::class, 'index'])->name('admin.student.intake.course.fee.installment.index');
                Route::get('edit/{id}', [StudentIntakeCourseFeeInstallmentController::class, 'edit'])->name('admin.student.intake.course.fee.installment.edit');
                Route::post('update/{id}', [StudentIntakeCourseFeeInstallmentController::class, 'update'])->name('admin.student.intake.course.fee.installment.update');
                Route::post('payment/{id}', [StudentIntakeCourseFeeInstallmentController::class, 'intallmentPayment'])->name('admin.student.intake.course.fee.installment.payment');
            });
        });
        Route::group([
            'prefix' => 'competence'
        ], function () {
            Route::get('/{id}', [StudentIntakeCourseCompetenceController::class, 'index'])->name('admin.student.intake.competence.index');
            Route::post('store/{id}', [StudentIntakeCourseCompetenceController::class, 'store'])->name('admin.student.intake.competence.store');
            Route::post('update/{id}', [StudentIntakeCourseCompetenceController::class, 'update'])->name('admin.student.intake.competence.update');
            Route::post('show/{id}', [StudentIntakeCourseCompetenceController::class, 'generateCertificate'])->name('admin.student.intake.competence.show');
            Route::group([
                'prefix' => 'certificate'
            ], function () {
                Route::get('/{id}', [StudentIntakeCourseCompetenceCertificateController::class, 'index'])->name('admin.student.intake.course.competence.certificate.index');
                Route::get('create/{id}', [StudentIntakeCourseCompetenceCertificateController::class, 'create'])->name('admin.student.intake.course.competence.certificate.create');
                Route::post('store/{id}', [StudentIntakeCourseCompetenceCertificateController::class, 'store'])->name('admin.student.intake.course.competence.certificate.store');
                Route::get('print/{id}', [StudentIntakeCourseCompetenceCertificateController::class, 'print'])->name('admin.student.intake.course.competence.certificate.print');
                Route::get('edit/{id}', [StudentIntakeCourseCompetenceCertificateController::class, 'edit'])->name('admin.student.intake.course.competence.certificate.edit');
                Route::post('update/{id}', [StudentIntakeCourseCompetenceCertificateController::class, 'update'])->name('admin.student.intake.course.competence.certificate.update');
                Route::get('delete/{id}', [StudentIntakeCourseCompetenceCertificateController::class, 'destroy'])->name('admin.student.intake.course.competence.certificate.delete');
            });
        });
    });
    Route::group([
        'prefix' => 'fee'
    ], function () {
        Route::get('/{id}', [StudentIntakeCourseFeeController::class, 'index'])->name('admin.student.fee.index');
        Route::get('create/{id}', [StudentIntakeCourseFeeController::class, 'create'])->name('admin.student.fee.create');
        Route::post('store/{id}', [StudentIntakeCourseFeeController::class, 'store'])->name('admin.student.fee.store');
        Route::get('edit/{id}', [StudentIntakeCourseFeeController::class, 'edit'])->name('admin.student.fee.edit');
        Route::post('update/{id}', [StudentIntakeCourseFeeController::class, 'update'])->name('admin.student.fee.update');
        Route::get('pay/{id}', [StudentIntakeCourseFeeController::class, 'pay'])->name('admin.student.fee.installment.pay');
        Route::get('pay/edit/{id}', [StudentIntakeCourseFeeController::class, 'payEdit'])->name('admin.student.fee.installment.pay.edit');
        Route::get('receipt/{id}', [StudentIntakeCourseFeeController::class, 'receipt'])->name('admin.student.fee.installment.receipt');
        Route::get('receipt/print/{id}', [StudentIntakeCourseFeeController::class, 'receiptPrint'])->name('admin.student.fee.installment.receipt.print');
        Route::get('statement/{id}', [StudentIntakeCourseFeeController::class, 'statement'])->name('admin.student.fee.statement');
        Route::get('statement/print/{id}', [StudentIntakeCourseFeeController::class, 'statementPrint'])->name('admin.student.fee.statement.print');
        Route::group([
            'prefix' => 'installment'
        ], function () {
            Route::get('/{id}', [StudentIntakeCourseFeeInstallmentController::class, 'index'])->name('admin.student.fee.installment.index');
            Route::get('edit/{id}', [StudentIntakeCourseFeeInstallmentController::class, 'edit'])->name('admin.student.fee.installment.edit');
            Route::post('update/{id}', [StudentIntakeCourseFeeInstallmentController::class, 'update'])->name('admin.student.fee.installment.update');
            Route::group([
                'prefix' => 'payment'
            ], function () {
                Route::post('{id}', [StudentIntakeCourseFeeInstallmentController::class, 'intallmentPayment'])->name('admin.student.fee.installment.payment');
                Route::post('update/{id}', [StudentIntakeCourseFeeInstallmentController::class, 'installmentUpdate'])->name('admin.student.fee.installment.payment.update');
                Route::post('refund/{id}', [StudentIntakeCourseFeeInstallmentController::class, 'refundPayment'])->name('admin.student.fee.installment.payment.refund');
            });
        });
    });
    Route::get('unit', [StudentIntakeCourseController::class, 'getUnit']);
    Route::get('intake_course', [StudentIntakeCourseController::class, 'getCourseByIntake']);
    Route::get('intake_course_fee', [StudentIntakeCourseFeeController::class, 'getIntakeCourseFee']);
    Route::group([
        'prefix' => 'social'
    ], function () {
        Route::get('/{id}', [StudentSocialController::class, 'index'])->name('admin.student.social.index');
        Route::get('create/{id}', [StudentSocialController::class, 'create'])->name('admin.student.social.create');
        Route::post('store/{id}', [StudentSocialController::class, 'store'])->name('admin.student.social.store');
        Route::get('edit/{id}', [StudentSocialController::class, 'edit'])->name('admin.student.social.edit');
        Route::post('update/{id}', [StudentSocialController::class, 'update'])->name('admin.student.social.update');
        Route::get('destroy/{id}', [StudentSocialController::class, 'destroy'])->name('admin.student.social.delete');
    });
    Route::get('log/{id}', [StudentLogController::class, 'index'])->name('admin.student.log.index');
    Route::get('device/{id}', [StudentDeviceController::class, 'index'])->name('admin.student.device.index');
    Route::group([
        'prefix' => 'note'
    ], function () {
        Route::get('create/{id}', [StudentNoteController::class, 'create'])->name('admin.student.note.create');
        Route::post('store/{id}', [StudentNoteController::class, 'store'])->name('admin.student.note.store');
        Route::get('edit/{id}', [StudentNoteController::class, 'edit'])->name('admin.student.note.edit');
        Route::post('update/{id}', [StudentNoteController::class, 'update'])->name('admin.student.note.update');
        Route::get('destroy/{id}', [StudentNoteController::class, 'destroy'])->name('admin.student.note.delete');
    });
    Route::group([
        'prefix' => 'payment'
    ], function () {
        Route::get('/{id}', [StudentPaymentController::class, 'index'])->name('admin.student.payment.index');
        Route::get('create/{id}', [StudentPaymentController::class, 'create'])->name('admin.student.payment.create');
        Route::post('store/{id}', [StudentPaymentController::class, 'store'])->name('admin.student.payment.store');
        Route::get('show/{id}', [StudentPaymentController::class, 'show'])->name('admin.student.payment.show');
        Route::get('edit/{id}', [StudentPaymentController::class, 'edit'])->name('admin.student.payment.edit');
        Route::post('update/{id}', [StudentPaymentController::class, 'update'])->name('admin.student.payment.update');
        Route::get('destroy/{id}', [StudentPaymentController::class, 'destroy'])->name('admin.student.payment.delete');
        Route::get('remaining/amount', [StudentPaymentController::class, 'remainingAmount'])->name('admin.student.payment.remaining');
        Route::get('pay/{id}', [StudentPaymentController::class, 'payCommission'])->name('admin.student.payment.commission.pay');
        Route::post('paid/{id}', [StudentPaymentController::class, 'commisionPaid'])->name('admin.student.payment.commission.paid');
        Route::get('receipt/{id}', [StudentPaymentController::class, 'receipt'])->name('admin.student.payment.commission.receipt');
        Route::get('print/{id}', [StudentPaymentController::class, 'print'])->name('admin.student.payment.print');
    });
    Route::group([
        'prefix' => 'email'
    ], function () {
        Route::get('/{id}', [StudentEmailController::class, 'index'])->name('admin.student.email.index');
        Route::get('create/{id}', [StudentEmailController::class, 'create'])->name('admin.student.email.create');
        Route::post('store/{id}', [StudentEmailController::class, 'store'])->name('admin.student.email.store');
        Route::get('template/selected', [StudentEmailController::class, 'getEmailTemplates']);
    });
    Route::group([
        'prefix' => 'template'
    ], function () {
        Route::get('/{id}', [StudentTemplateController::class, 'index'])->name('admin.student.template.index');
        Route::get('create/{type}/{id}', [StudentTemplateController::class, 'create'])->name('admin.student.template.create');
        Route::post('store/{id}', [StudentTemplateController::class, 'store'])->name('admin.student.template.store');
        Route::get('show/{id}', [StudentTemplateController::class, 'show'])->name('admin.student.template.show');
        Route::get('edit/{id}', [StudentTemplateController::class, 'edit'])->name('admin.student.template.edit');
        Route::post('update/{id}', [StudentTemplateController::class, 'update'])->name('admin.student.template.update');
        Route::get('destroy/{id}', [StudentTemplateController::class, 'destroy'])->name('admin.student.template.delete');
        Route::get('print/{id}', [StudentTemplateController::class, 'print'])->name('admin.student.template.print');
    });
    Route::group([
        'prefix' => 'document'
    ], function () {
        Route::get('/{id}', [StudentDocumentController::class, 'index'])->name('admin.student.document.index');
        Route::get('create/{id}', [StudentDocumentController::class, 'create'])->name('admin.student.document.create');
        Route::post('store/{id}', [StudentDocumentController::class, 'store'])->name('admin.student.document.store');
        Route::get('edit/{id}', [StudentDocumentController::class, 'edit'])->name('admin.student.document.edit');
        Route::post('update/{id}', [StudentDocumentController::class, 'update'])->name('admin.student.document.update');
        Route::get('destroy/{id}', [StudentDocumentController::class, 'destroy'])->name('admin.student.document.delete');
    });
});
Route::group([
    'prefix' => 'admin/offer'
], function () {
    Route::get('/', [StudentOfferController::class, 'index'])->name('admin.student.offer.index');
    Route::get('create', [StudentOfferController::class, 'create'])->name('admin.student.offer.create');
    Route::post('enroll/{id}', [StudentOfferController::class, 'enroll'])->name('admin.student.offer.enroll');
    Route::group([
        'prefix' => 'letter'
    ], function () {
        Route::get('/{id}', [StudentOfferLetterController::class, 'index'])->name('admin.student.offer.letter.index');
        Route::get('create/{id}', [StudentOfferLetterController::class, 'create'])->name('admin.student.offer.letter.create');
        Route::post('store/{id}', [StudentOfferLetterController::class, 'store'])->name('admin.student.offer.letter.store');
        Route::get('show/{id}', [StudentOfferLetterController::class, 'show'])->name('admin.student.offer.letter.show');
        Route::get('edit/{id}', [StudentOfferLetterController::class, 'edit'])->name('admin.student.offer.letter.edit');
        Route::post('update/{id}', [StudentOfferLetterController::class, 'update'])->name('admin.student.offer.letter.update');
        Route::get('destroy/{id}', [StudentOfferLetterController::class, 'destroy'])->name('admin.student.offer.letter.delete');
        Route::get('export/{id}', [StudentOfferLetterController::class, 'exportWord'])->name('admin.student.offer.letter.export');
        Route::get('print/{id}', [StudentOfferLetterController::class, 'printPDF'])->name('admin.student.offer.letter.print');
    });
});

Route::group([
    'prefix' => 'student'
], function () {
    Route::get('/', [LoginController::class, 'home'])->name('student.login');
    Route::get('login', [LoginController::class, 'home'])->name('student.login');
    Route::post('login', [LoginController::class, 'login'])->name('student.login');
    Route::post('save/device', [LoginController::class, 'saveToken'])->name('student.device.save');
    Route::get('logout', [LoginController::class, 'logout'])->name('student.logout')->middleware('auth:student');
    Route::group([
        'prefix' => 'password'
    ], function () {
        Route::get('forgot', [LoginController::class, 'forgotPassword'])->name('student.password.forgot');
        Route::post('reset', [LoginController::class, 'resetPassword'])->name('student.password.reset');
        Route::get('reset/{email}/{code}', [LoginController::class, 'resettingPassword']);
        Route::post('update', [LoginController::class, 'updatePassword'])->name('student.password.update');
    });
    Route::get('dashboard', [StudentStudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('profile', [StudentStudentController::class, 'profile'])->name('student.profile');
    Route::get('change/password', [StudentStudentController::class, 'changePassword'])->name('student.change.password');
    Route::post('fill/password', [StudentStudentController::class, 'fillPassword'])->name('student.fill.password');
    Route::group([
        'prefix' => 'course'
    ], function () {
        Route::get('/', [CourseController::class, 'index'])->name('student.course.index');
        Route::get('unit/{id}', [CourseController::class, 'unit'])->name('student.unit.index');
        Route::get('resource/{id}', [CourseController::class, 'resource'])->name('student.resource.index');
        Route::get('onlineclass/{id}', [CourseController::class, 'onlineclass'])->name('student.onlineclass.index');
        Route::get('teams/{id}', [CourseController::class, 'onlineClassTeam'])->name('student.team.index');
        Route::group([
            'prefix' => 'assignment'
        ], function () {
            Route::get('/{id}', [CourseController::class, 'assigment'])->name('student.assignment.index');
            Route::get('question/{id}/{student_intake_id}', [CourseController::class, 'assigmentQuestion'])->name('student.assignment.question.index');
            Route::post('question/submit/{id}/{student_intake_id}', [CourseController::class, 'submitAssignmentQuestion'])->name('student.assignment.question.submit.index');
            Route::get('mcq/{id}/{student_intake_id}', [CourseController::class, 'assigmentMCQ'])->name('student.assignment.mcq.index');
            Route::post('mcq/submit/{id}/{student_intake_id}', [CourseController::class, 'submitAssignmentMCQ'])->name('student.assignment.mcq.submit.index');
            Route::group([
                'prefix' => 'submission'
            ], function () {
                Route::get('/{id}/{student_intake_id}', [CourseController::class, 'submission'])->name('student.submission.index');
                Route::get('create/{id}/{student_intake_id}', [CourseController::class, 'createSubmission'])->name('student.submission.create');
                Route::post('store/{id}/{student_intake_id}', [CourseController::class, 'storeSubmission'])->name('student.submission.store');
                Route::get('question/{id}/{student_intake_id}', [CourseController::class, 'questionSubmission'])->name('student.submission.question.index');
                Route::get('mcq/{id}/{student_intake_id}', [CourseController::class, 'mcqSubmission'])->name('student.submission.mcq.index');
                Route::post('resubmission/{id}', [CourseController::class, 'requestResubmission'])->name('student.submission.resubmission.index');
            });
        });
        Route::group([
            'prefix' => 'unit/chat'
        ], function () {
            Route::get('/{id}', [UnitChatController::class, 'index'])->name('student.unit.chat.index');
            Route::get('show/{id}', [UnitChatController::class, 'show'])->name('student.unit.chat.show');
            Route::post('sendMessage/{id}', [UnitChatController::class, 'sendMessage'])->name('student.unit.chat.message.send');
            Route::post('createMessage', [UnitChatController::class, 'createMessage']);
            Route::get('load/message', [UnitChatController::class, 'loadMessages']);
        });
        Route::get('attendance/{id}', [CourseController::class, 'attendance'])->name('student.attendance.index');
    });
    Route::get('notification', [NotificationController::class, 'index'])->name('student.notification.index');
    Route::group([
        'prefix' => 'onlineclass/group'
    ], function () {
        Route::get('/', [OnlineClassGroupController::class, 'index'])->name('student.onlineclass.group.index');
        Route::get('onlineclass/{id}', [OnlineClassGroupController::class, 'onlineClass'])->name('student.onlineclass.group.onlineClass');
        Route::get('teams/{id}', [OnlineClassGroupController::class, 'onlineClassTeams'])->name('student.onlineclass.group.teams');
    });
    Route::get('calendar', [CalendarController::class, 'index'])->name('student.calendar.index');
    Route::get('calendar/time', [CalendarController::class, 'getTimeTable']);
    Route::get('fee', [FeeController::class, 'index'])->name('student.fee.index');
    Route::group([
        'prefix' => 'ticket'
    ], function () {
        Route::get('/', [TicketController::class, 'index'])->name('student.ticket.index');
        Route::get('create', [TicketController::class, 'create'])->name('student.ticket.create');
        Route::post('store', [TicketController::class, 'store'])->name('student.ticket.store');
        Route::get('show/{id}', [TicketController::class, 'show'])->name('student.ticket.show');
        Route::post('reply/{id}', [TicketController::class, 'reply'])->name('student.ticket.reply');
    });
    Route::group([
        'prefix' => 'chat'
    ], function () {
        Route::get('trainer', [ChatController::class, 'index'])->name('student.chat.trainer.index');
        Route::get('trainer/messages/{id}', [ChatController::class, 'show'])->name('student.chat.trainer.message');
    });
});

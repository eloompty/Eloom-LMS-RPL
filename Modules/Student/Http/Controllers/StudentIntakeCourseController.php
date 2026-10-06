<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Course\Entities\Unit;
use Modules\Intake\Entities\Intake;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Intake\Entities\IntakeCourseFee;
use Modules\Intake\Entities\IntakeCourseFeeInstallment;
use Modules\Intake\Entities\IntakeUnit;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentIntakeCourseFee;
use Modules\Student\Entities\StudentIntakeCourseFeeInstallment;
use Modules\Student\Entities\StudentIntakeUnit;

class StudentIntakeCourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the student intake course.
     * @return Renderable
     */
    public function index($id)
    {
        $student = Student::find($id);
        if (checkRole('student_intake_course', 'view') == true && $student) {
            $intakes = StudentIntakeCourse::where('student_id', $id)->get();
            activityLog('Admin', 'Opened ' . userName('Student', $student->id) . ' Intake Course Menu');
            return view('student::intake.course.index', compact('student', 'intakes'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new student intake course.
     * @return Renderable
     */
    public function create($id)
    {
        $student = Student::find($id);
        if (checkRole('student_intake_course', 'add') == true && $student) {
            $intakes = Intake::where('status', 1)->pluck('name', 'id');
            activityLog('Admin', 'Opened ' . userName('Student', $student->id) . ' Intake Assign Page');
            return view('student::intake.course.create', compact('student', 'intakes'));
        } else {
            return redirect()->route('admin.student.intake.course.index', $id)->with('failure', 'This user does not have permission to add student intake');
        }
    }

    /**
     * Store a newly created student intake course in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $intakeCourse = IntakeCourse::find($request->intake_course_id);
        $student_unit_course = StudentIntakeCourse::create([
            'student_id' => $id,
            'intake_course_id' => $request->intake_course_id,
            'duration' => $intakeCourse->duration,
            'starting_date' => $intakeCourse->starting_date,
            'ending_date' => $intakeCourse->ending_date,
            'status' => $request->status
        ]);
        $intakeUnits = IntakeUnit::where('intake_course_id', $request->intake_course_id)->get();
        foreach ($intakeUnits as $key => $value) {
            $unit = Unit::find($value->unit_id);
            $student = Student::find($id);
            StudentIntakeUnit::create([
                'student_intake_course_id' => $student_unit_course->id,
                'intake_unit_id' => $value->id,
                'funding_source_national' => $student->funding_source_national,
                'funding_source_state_training_authority' => $student->funding_source_state_training_authority,
                'delivery_mode' => $intakeCourse->course->delivery_mode,
                'internal' => $intakeCourse->course->internal,
                'predominant_delivery_mode' => $intakeCourse->course->predominant_delivery_mode,
                'duration' => $unit->duration,
                'starting_date' => $value->starting_date,
                'ending_date' => $value->ending_date,
                'due_date' => $value->due_date,
                'sequence' => $value->sequence,
                'status' => $value->status,
            ]);
        }
        /* $intake_course_fee = IntakeCourseFee::find($request->fee_id);
        $student_intake_course_fee = StudentIntakeCourseFee::create([
            'student_id' => $id,
            'intake_course_id' => $request->intake_course_id,
            'name' => $intake_course_fee->name,
            'initial_fee' => $intake_course_fee->initial_fee,
            'enrollment_fee' => $intake_course_fee->enrollment_fee,
            'enrollment_fee_wavier' => $intake_course_fee->enrollment_fee_wavier,
            'material_fee' => $intake_course_fee->material_fee,
            'material_fee_wavier' => $intake_course_fee->material_fee_wavier,
            'fee' => $intake_course_fee->fee,
            'type' => $intake_course_fee->type,
            'due_date' => $intake_course_fee->due_date,
            'status' => $intake_course_fee->status
        ]);
        $intake_course_fee_installments = IntakeCourseFeeInstallment::where('intake_course_fee_id', $intake_course_fee->id)->get();
        foreach ($intake_course_fee_installments as $key => $value) {
            StudentIntakeCourseFeeInstallment::create([
                'student_intake_course_fee_id' => $student_intake_course_fee->id,
                'name' => $value->name,
                'enrollment_fee' => $value->enrollment_fee,
                'material_fee' => $value->material_fee,
                'amount' => $value->amount,
                'due_date' => $value->due_date,
                'status' => $value->status
            ]);
        } */
        activityLog('Admin', 'Intake for ' . userName('Student', $id) . ' has been assigned');
        return redirect()->route('admin.student.intake.course.index', $id)->with('success', 'Student Intake added successfully');
    }

    /**
     * Show the form for editing the specified student intake course.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $studentIntakeCourse = StudentIntakeCourse::findorfail($id);
        if (checkRole('student_intake_course', 'edit') == true) {
            activityLog('Admin', 'Opened ' . userName('Student', $studentIntakeCourse->student_id) . ' Intake Assign Edit Page Opened');
            $student = Student::find($studentIntakeCourse->student_id);
            $intakes = Intake::where('status', 1)->pluck('name', 'id');
            $courses = IntakeCourse::join('courses', 'courses.id', '=', 'intake_courses.course_id')
                ->where('intake_courses.intake_id', $studentIntakeCourse->intakeCourse->intake_id)->where('intake_courses.status', 1)
                ->select('intake_courses.id', DB::raw("CONCAT(courses.course_name, ' (', DATE_FORMAT(intake_courses.starting_date,'%d/%m/%Y'), ' - ',  DATE_FORMAT(intake_courses.ending_date,'%d/%m/%Y'),')') as course"))
                ->pluck('course', 'intake_courses.id');
            $student_fee = StudentIntakeCourseFee::where('student_id', $studentIntakeCourse->student_id)->where('intake_course_id',  $studentIntakeCourse->intake_course_id)->first();
            $fees = IntakeCourseFee::where('intake_course_id', $studentIntakeCourse->intake_course_id)->where('status', 1)->pluck('name', 'id');
            return view('student::intake.course.edit', compact('studentIntakeCourse', 'student', 'intakes', 'courses', 'student_fee', 'fees'));
        } else {
            return redirect()->route('admin.student.intake.course.index', $studentIntakeCourse->student_id)->with('failure', 'This user does not have permission to edit student intake');
        }
    }

    /**
     * Update the specified student intake course in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $studentIntakeCourse = StudentIntakeCourse::find($id);
        if ($studentIntakeCourse->intake_course_id == $data['intake_course_id']) {
            unset($data['_token'], $data['fee_id']);
            /* $fee = StudentIntakeCourseFee::where('student_id', $studentIntakeCourse->student_id)->where('intake_course_id',  $studentIntakeCourse->intake_course_id)->first();
            if ($fee) {
                $installments = $fee->installments;
                foreach ($installments as $installment) {
                    $payment = $installment->studentIntakeCourseFeePayment;
                    if ($payment) {
                        return redirect()->back()->with('failure', 'Payment for this intake course has already been done, thus this intake course cannot be modified');
                    } else {
                        $installment->delete();
                    }
                }
                $fee->delete();
            } */
            StudentIntakeCourse::where('id', $id)->update($data);
        } else {
            /* $fee = StudentIntakeCourseFee::where('student_id', $studentIntakeCourse->student_id)->where('intake_course_id',  $studentIntakeCourse->intake_course_id)->first();
            if ($fee) {
                $installments = $fee->installments;
                foreach ($installments as $installment) {
                    $payment = $installment->studentIntakeCourseFeePayment;
                    if ($payment) {
                        return redirect()->back()->with('failure', 'Payment for this intake course has already been done, thus this intake course cannot be modified');
                    } else {
                        $installment->delete();
                    }
                }
                $fee->delete();
            } */
            StudentIntakeUnit::where('student_intake_course_id', $studentIntakeCourse->id)->delete();
            $studentIntakeCourse->update($data);
            $intakeUnits = IntakeUnit::where('intake_course_id', $request->intake_course_id)->get();
            foreach ($intakeUnits as $key => $value) {
                $unit = Unit::find($value->unit_id);
                $student = Student::find($studentIntakeCourse->student_id);
                StudentIntakeUnit::create([
                    'student_intake_course_id' => $id,
                    'intake_unit_id' => $value->id,
                    'funding_source_national' => $student->funding_source_national,
                    'funding_source_state_training_authority' => $student->funding_source_state_training_authority,
                    'delivery_mode' => $studentIntakeCourse->intakeCourse->course->delivery_mode,
                    'internal' => $studentIntakeCourse->intakeCourse->course->internal,
                    'predominant_delivery_mode' => $studentIntakeCourse->intakeCourse->course->predominant_delivery_mode,
                    'duration' => $unit->duration,
                    'starting_date' => $value->starting_date,
                    'ending_date' => $value->ending_date,
                    'due_date' => $value->due_date,
                    'status' => $value->status,
                ]);
            }
        }
        /* $student_fee = StudentIntakeCourseFee::where('student_id', $studentIntakeCourse->student_id)->where('intake_course_id',  $studentIntakeCourse->intake_course_id)->first();
        if ($student_fee == NULL) {
            $intake_course_fee = IntakeCourseFee::find($request->fee_id);
            $student_intake_course_fee = StudentIntakeCourseFee::create([
                'student_id' => $studentIntakeCourse->student_id,
                'intake_course_id' => $request->intake_course_id,
                'name' => $intake_course_fee->name,
                'initial_fee' => $intake_course_fee->initial_fee,
                'enrollment_fee' => $intake_course_fee->enrollment_fee,
                'enrollment_fee_wavier' => $intake_course_fee->enrollment_fee_wavier,
                'material_fee' => $intake_course_fee->material_fee,
                'material_fee_wavier' => $intake_course_fee->material_fee_wavier,
                'fee' => $intake_course_fee->fee,
                'type' => $intake_course_fee->type,
                'due_date' => $intake_course_fee->due_date,
                'status' => $intake_course_fee->status
            ]);
            $intake_course_fee_installments = IntakeCourseFeeInstallment::where('intake_course_fee_id', $intake_course_fee->id)->get();
            foreach ($intake_course_fee_installments as $key => $value) {
                StudentIntakeCourseFeeInstallment::create([
                    'student_intake_course_fee_id' => $student_intake_course_fee->id,
                    'name' => $value->name,
                    'enrollment_fee' => $value->enrollment_fee,
                    'material_fee' => $value->material_fee,
                    'amount' => $value->amount,
                    'due_date' => $value->due_date,
                    'status' => $value->status
                ]);
            }
        } */
        activityLog('Admin', userName('Student', $studentIntakeCourse->student_id) . ' Intake Assign Updated');
        return redirect()->route('admin.student.intake.course.index', $studentIntakeCourse->student_id)->with('success', 'Student Intake updated successfully');
    }

    /**
     * Remove the specified student intake course from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('student_intake_course', 'delete') == true) {
            StudentIntakeCourse::where('id', $id)->update(['status' => 2]);
            return redirect()->back()->with('success', 'Student Intake Course has been deleted successfully');
        } else {
            $studentIntakeCourse = StudentIntakeCourse::find($id);
            return redirect()->route('admin.student.intake.course.index', $studentIntakeCourse->student_id)->with('failure', 'This user does not have permission to delete student intake');
        }
    }

    /* Get Unit By Course Id */
    function getUnit(Request $request)
    {
        $units = Unit::where('course_id', $request->course_id)->where('status', 1)->pluck('name', 'id');
        return response()->json($units);
    }

    /* Get Course By Intake Id */
    public function getCourseByIntake(Request $request)
    {
        $courses = IntakeCourse::join('courses', 'courses.id', '=', 'intake_courses.course_id')
            ->where('intake_courses.intake_id', $request->intake_id)->where('intake_courses.status', 1)
            ->select('intake_courses.id', DB::raw("CONCAT(courses.course_name, ' (', DATE_FORMAT(intake_courses.starting_date,'%d/%m/%Y'), ' - ',  DATE_FORMAT(intake_courses.ending_date,'%d/%m/%Y'),')') as course"))
            ->pluck('course', 'intake_courses.id');
        return response()->json($courses);
    }
}

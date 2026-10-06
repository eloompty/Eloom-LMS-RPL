<?php

namespace Modules\Intake\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Assignment\Entities\Assignment;
use Modules\Assignment\Entities\AssignmentChoice;
use Modules\Assignment\Entities\AssignmentQuestion;
use Modules\Course\Entities\Course;
use Modules\Course\Entities\CourseFee;
use Modules\Course\Entities\Unit;
use Modules\Course\Entities\UnitAssignment;
use Modules\Course\Entities\UnitAssignmentChoice;
use Modules\Course\Entities\UnitAssignmentQuestion;
use Modules\Intake\Entities\Intake;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Intake\Entities\IntakeCourseFee;
use Modules\Intake\Entities\IntakeCourseFeeInstallment;
use Modules\Intake\Entities\IntakeUnit;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Trainer\Entities\Trainer;
use Modules\Trainer\Entities\TrainerIntake;

class IntakeCourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the assigned intake courses.
     * @return Renderable
     */
    public function index(Request $request, $id)
    {
        $intake = Intake::find($id);
        if (checkRole('intake_course', 'view') == true && $intake) {
            $ids = getDeliverySiteIds();
            $status = $request->status;
            if ($status == NULL) $status_code = [0, 1];
            else $status_code = [2];
            $courses = IntakeCourse::join('courses', 'courses.id', '=', 'intake_courses.course_id')
                ->leftJoin('course_delivery_sites', 'course_delivery_sites.course_id', '=', 'courses.id')
                ->where(function ($query) use ($ids) {
                    $query->whereNull('course_delivery_sites.course_id')
                        ->orWhereIn('course_delivery_sites.company_delivery_site_id', $ids);
                })
                ->select('intake_courses.*')
                ->where('courses.status', 1)
                ->where('intake_courses.intake_id', $id)->whereIn('intake_courses.status', $status_code)->orderBy('intake_courses.id', 'desc')->get();
            foreach ($courses as $key => $value) {
                $courses[$key]['enrolled_student'] = StudentIntakeCourse::join('students', 'students.id', 'student_intake_courses.student_id')
                ->where('intake_course_id', $value->id)
                ->whereIn('students.status', [0,1])->where('students.is_enrolled', 1)->count();
            }
            activityLog('Admin', 'Course list of ' . $intake->name . ' Intake');
            return view('intake::course.index', compact('intake', 'courses', 'status'))->with('no', 1);
        } else {
            return redirect()->route('admin.intake.index')->with('failure', 'This user does not have permission to view intake course');
        }
    }

    /**
     * Show the form for assign course and trainer to intake.
     * @return Renderable
     */
    public function create(Request $request, $id)
    {
        $intake = Intake::find($id);
        if (checkRole('intake_course', 'add') == true && $intake) {
            $ids = getDeliverySiteIds();
            $courses = Course::leftJoin('course_delivery_sites', 'course_delivery_sites.course_id', '=', 'courses.id')
                ->where(function ($query) use ($ids) {
                    $query->whereNull('course_delivery_sites.course_id')
                        ->orWhereIn('course_delivery_sites.company_delivery_site_id', $ids);
                })
                ->select('courses.*')
                ->where('courses.status', 1)->pluck('course_name', 'id');
            $units = Unit::where('course_id', $request->course_id)->where('status', 1)->get();
            $trainers = Trainer::where('status', 1)->get();
            activityLog('Admin', 'Course list of ' . $intake->name . ' Intake');
            return view('intake::course.create', compact('intake', 'courses', 'units', 'trainers'));
        } else {
            return redirect()->route('admin.intake.course.index', $id)->with('failure', 'This user does not have permission to add intake course');
        }
    }

    /**
     * Assign trainer and trainer to intake
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $intake = Intake::findorfail($id);
        $course = Course::findorfail($request->course_id);
        // Check if course contains unit
        if ($course->unit->count() == 0) {
            return redirect()->route('admin.intake.course.index', $id)->with('failure', 'Course with no units cannot be added to intake');
        } else {
            $unit_id = $data['unit_id'];
            $data['intake_id'] = $id;
            if ($course->duration == NULL) {
                $course->duration = 0;
            }
            $data['duration'] = $course->duration;
            // Create Intakecourse
            $intakeCourse = IntakeCourse::create($data);
            $unit_id = $data['unit_id'];
            $starting_date = $data['unit_starting_date'];
            $ending_date = $data['unit_ending_date'];
            $ending_date = $data['unit_ending_date'];
            $due_date = $data['due_date'];
            $unit_status = $data['unit_status'];
            $sequence = $data['sequence'];
            // Make array of unit
            foreach ($unit_id as $i => $val) {
                $unit[] = array($val, $starting_date[$i], $ending_date[$i], $due_date[$i], $unit_status[$i], $sequence[$i]);
            }
            // Insert all the units
            foreach ($unit as $key => $value) {
                if ($value[1] == NULL) $value[1] = $data['starting_date'];
                if ($value[2] == NULL) {
                    $value[2] = $data['ending_date'];
                    $twoweeks = strtotime($data['ending_date']);
                    $value[3] = date('Y-m-d', strtotime("+ 2 weeks", $twoweeks));
                }
                IntakeUnit::create([
                    'intake_course_id' => $intakeCourse->id,
                    'unit_id' => $value[0],
                    'starting_date' => $value[1],
                    'ending_date' => $value[2],
                    'due_date' => $value[3],
                    'status' => $value[4],
                    'sequence' => $value[5],
                ]);
            }
            $intakeUnits = IntakeUnit::where('intake_course_id', $intakeCourse->id)->get();
            // Assign trainer to units of the course
            if ($request->trainer_id != NULL) {
                foreach ($intakeUnits as $key => $value) {
                    $unit = Unit::find($value->unit_id);
                    TrainerIntake::create([
                        'trainer_id' => $request->trainer_id,
                        'intake_course_id' => $value->intake_course_id,
                        'intake_unit_id' => $value->id,
                        'duration' => $unit->duration,
                        'starting_date' => $value->starting_date,
                        'status' => $value->status,
                    ]);
                }
                $trainer_name = userName('Trainer', $request->trainer_id);
                $log = $course->course_name . ' and ' . $trainer_name . ' assigned to ' . $intake->name . ' Intake';
            } else {
                $log = $course->course_name . ' assigned to ' . $intake->name . ' Intake';
            }
            // Add Assignment to intake unit
            foreach ($intakeUnits as $key => $value) {
                $unit_assignments = UnitAssignment::where('unit_id', $value->unit_id)->where('status', 1)->get();
                foreach ($unit_assignments as $key => $unit_assignment) {
                    $trainerIntake = TrainerIntake::where('intake_unit_id', $value->id)->first();
                    if ($trainerIntake) {
                        $data['trainer_id'] = $trainerIntake->trainer_id;
                    }
                    $data['name'] = $unit_assignment->name;
                    $data['type'] = $unit_assignment->type;
                    $data['path'] = $unit_assignment->path;
                    $data['due_date'] = $unit_assignment->due_date;
                    $data['unit_assignment_id'] = $unit_assignment->id;
                    $data['intake_unit_id'] = $value->id;
                    $data['uploaded_by'] = 'Admin';
                    $data['uploaded_user_id'] = $unit_assignment->user_id;
                    $data['status'] = $unit_assignment->status;
                    $assignment = Assignment::create($data);
                    $unit_assignment_questions = UnitAssignmentQuestion::where('unit_assignment_id', $unit_assignment->id)->get();
                    if (count($unit_assignment_questions) > 0) {
                        foreach ($unit_assignment_questions as $question) {
                            $assignment_question = AssignmentQuestion::create([
                                'assignment_id' => $assignment->id,
                                'question' => $question->question,
                                'status' => $question->status,
                            ]);

                            $unit_assignment_choices = UnitAssignmentChoice::where('unit_assignment_question_id', $question->id)->get();
                            if (count($unit_assignment_choices) > 0) {
                                foreach ($unit_assignment_choices as $choice) {
                                    AssignmentChoice::create([
                                        'assignment_question_id' => $assignment_question->id,
                                        'choice' => $choice->choice,
                                        'is_correct' => $choice->is_correct,
                                        'status' => $choice->status,
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
            // Add Intake Course Fee
            $course_fees = CourseFee::where('course_id', $request->course_id)->where('status', 1)->get();
            if (count($course_fees) > 0) {
                foreach ($course_fees as $key => $value) {
                    $intake_course_fee = IntakeCourseFee::create([
                        'intake_course_id' => $intakeCourse->id,
                        'name' => $value->name,
                        'initial_fee' => $value->initial_fee,
                        'enrollment_fee' => $value->enrollment_fee,
                        'enrollment_fee_wavier' => $value->enrollment_fee_wavier,
                        'material_fee' => $value->material_fee,
                        'material_fee_wavier' => $value->material_fee_wavier,
                        'fee' => $value->fee,
                        'installment' => $value->installment,
                        'type' => $value->type,
                        'due_date' => $intakeCourse->ending_date,
                        'status' => $value->status
                    ]);
                    if ($intake_course_fee->installment == 0) {
                        $total_amount = $value->enrollment_fee + $value->material_fee + $value->fee;
                        IntakeCourseFeeInstallment::create([
                            'intake_course_fee_id' => $intake_course_fee->id,
                            'name' => 'First Installment',
                            'enrollment_fee' => $course->enrollment_fee,
                            'material_fee' => $course->material_fee,
                            'amount' => $total_amount,
                            'due_date' => $intakeCourse->starting_date,
                        ]);
                    } else {
                        $initial_amount = $intake_course_fee->enrollment_fee + $intake_course_fee->material_fee + $intake_course_fee->initial_fee;
                        IntakeCourseFeeInstallment::create([
                            'intake_course_fee_id' => $intake_course_fee->id,
                            'name' => 'First Installment',
                            'enrollment_fee' => $intake_course_fee->enrollment_fee,
                            'material_fee' => $intake_course_fee->material_fee,
                            'amount' => $initial_amount,
                            'due_date' => $intakeCourse->starting_date,
                        ]);
                        $installment = $intake_course_fee->installment;

                        $total_fee = $intake_course_fee->fee;
                        $duration = $course->duration;
                        $per_week_payment = $total_fee / $duration;
                        $initial_payment = $intake_course_fee->initial_fee;
                        $initial_payment_duration = $initial_payment / $per_week_payment;
                        $first_due_date = $value->starting_date;
                        $second_due_date = date('Y-m-d', strtotime($first_due_date . '+' . $initial_payment_duration . 'weeks'));
                        $monday_second_due_date = date('Y-m-d', strtotime("next monday", strtotime($second_due_date)));
                        $remaining_amount = $total_fee - $initial_payment;
                        $installment_amount = $remaining_amount / $installment;
                        IntakeCourseFeeInstallment::create([
                            'intake_course_fee_id' => $intake_course_fee->id,
                            'name' => 'Second Installment',
                            'enrollment_fee' => 0,
                            'material_fee' => 0,
                            'amount' => $installment_amount,
                            'due_date' => $second_due_date,
                        ]);
                        if ($installment > 1) {
                            $remaining_weeks = $duration - $initial_payment_duration;
                            $remaining_weeks_interval = $remaining_weeks / $installment;
                            $remaining_weeks_interval = (int)$remaining_weeks_interval;

                            for ($i = 1; $i < $installment; $i++) {
                                $name = $i + 2;
                                $rmi = $remaining_weeks_interval * $i;
                                $next_due_date = date('Y-m-d', strtotime($monday_second_due_date . '+' . $rmi  . 'weeks'));
                                IntakeCourseFeeInstallment::create([
                                    'intake_course_fee_id' => $intake_course_fee->id,
                                    'name' => $name . ' Installment',
                                    'enrollment_fee' => 0,
                                    'material_fee' => 0,
                                    'amount' => $installment_amount,
                                    'due_date' => $next_due_date,
                                ]);
                            }
                        }
                    }
                }
            }
            activityLog('Admin', $log);
            return redirect()->route('admin.intake.course.index', $id)->with('success', 'Intake Course has been added successfully');
        }
    }

    /**
     * Show the form for editing the specified intake course.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $intakeCourse = IntakeCourse::findorfail($id);
        if (checkRole('intake_course', 'edit') == true) {
            // List of assigned unit and trainer
            $check = checkCourseDeliverySite($intakeCourse->course_id);
            if ($check == true) {
                $intakeUnits = IntakeUnit::where('intake_course_id', $id)->orderByRaw('ISNULL(sequence), sequence ASC')->orderBy('id', 'asc')->get();
                foreach ($intakeUnits as $key => $value) {
                    $intakeTrainer = TrainerIntake::where('intake_unit_id', $value->id)->first();
                    if ($intakeTrainer) {
                        $intakeUnits[$key]['trainer_id'] = $intakeTrainer->trainer_id;
                    } else {
                        $intakeUnits[$key]['trainer_id'] = NULL;
                    }
                }
                $trainers = Trainer::where('status', 1)->get();
                activityLog('Admin', $intakeCourse->reference_name . ' edit page opened');
                return view('intake::course.edit', compact('intakeCourse', 'intakeUnits', 'trainers'))->with('no', 1);
            } else {
                return abort(404);
            }
        } else {
            return redirect()->route('admin.intake.course.index', $intakeCourse->intake_id)->with('failure', 'This user does not have permission to edit intake course');
        }
    }

    /**
     * Update the specified intake course in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        IntakeCourse::where('id', $id)->update($data);
        StudentIntakeCourse::where('intake_course_id', $id)->update([
            'starting_date' => $data['starting_date'],
            'ending_date' => $data['ending_date'],
        ]);
        $intakeCourse = IntakeCourse::find($id);
        activityLog('Admin', $intakeCourse->reference_name . ' Updated');
        return redirect()->route('admin.intake.course.index', $intakeCourse->intake_id)->with('success', 'Intake Course has been updated successfully');
    }

    /**
     * Update status of intake course to deleted.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('intake_course', 'delete') == true) {
            IntakeCourse::where('id', $id)->update(['status' => 2]);
            $intakeCourse = IntakeCourse::find($id);
            activityLog('Admin', $intakeCourse->reference_name . ' Updated status to deleted');
            return redirect()->back()->with('success', 'Intake Course deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to add intake course');
        }
    }

    /* get unit list by course id */
    public function getUnit(Request $request)
    {
        $units = Unit::where('course_id', $request->course_id)->where('status', 1)->get();
        foreach ($units as $key => $value) {
            $data[] = [
                'id' => $value->id,
                'code' => $value->code,
                'name' => $value->name,
                'status' => 3,
            ];
        }
        return response()->json($data);
    }


    /* API to add missing assignment from course to the unit */
    public function addMissingAssignmentToIntake()
    {
        $intake_units = IntakeUnit::get();
        foreach ($intake_units as $key => $intake_unit) {
            $unit_assignments = UnitAssignment::where('unit_id', $intake_unit->unit_id)->where('status', 1)->get();
            foreach ($unit_assignments as $unit_assignment) {
                $assignment =  Assignment::where('unit_assignment_id', $unit_assignment->id)->where('intake_unit_id', $intake_unit->id)->first();
                if ($assignment == NULL) {
                    $trainerIntake = TrainerIntake::where('intake_unit_id', $intake_unit->id)->first();
                    if ($trainerIntake) {
                        $data['trainer_id'] = $trainerIntake->trainer_id;
                    }
                    $data['name'] = $unit_assignment->name;
                    $data['type'] = $unit_assignment->type;
                    $data['path'] = $unit_assignment->path;
                    $data['due_date'] = $unit_assignment->due_date;
                    $data['unit_assignment_id'] = $unit_assignment->id;
                    $data['intake_unit_id'] = $intake_unit->id;
                    $data['uploaded_by'] = 'Admin';
                    $data['uploaded_user_id'] = $unit_assignment->user_id;
                    $data['status'] = $unit_assignment->status;
                    $assignment = Assignment::create($data);
                    $unit_assignment_questions = UnitAssignmentQuestion::where('unit_assignment_id', $unit_assignment->id)->get();
                    if (count($unit_assignment_questions) > 0) {
                        foreach ($unit_assignment_questions as $question) {
                            $assignment_question = AssignmentQuestion::create([
                                'assignment_id' => $assignment->id,
                                'question' => $question->question,
                                'status' => $question->status,
                            ]);

                            $unit_assignment_choices = UnitAssignmentChoice::where('unit_assignment_question_id', $question->id)->get();
                            if (count($unit_assignment_choices) > 0) {
                                foreach ($unit_assignment_choices as $choice) {
                                    AssignmentChoice::create([
                                        'assignment_question_id' => $assignment_question->id,
                                        'choice' => $choice->choice,
                                        'is_correct' => $choice->is_correct,
                                        'status' => $choice->status,
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
        return response()->json(['message' => 'All the assignments added']);
    }
}

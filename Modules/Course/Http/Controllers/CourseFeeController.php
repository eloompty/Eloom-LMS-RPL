<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Course\Entities\Course;
use Modules\Course\Entities\CourseFee;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Intake\Entities\IntakeCourseFee;
use Modules\Intake\Entities\IntakeCourseFeeInstallment;

class CourseFeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the course fee.
     * @return Renderable
     */
    public function index($id)
    {
        $course = Course::find($id);
        if (checkRole('course_fee', 'view') == true && $course) {
            // Check for general course fee
            $fees_count = CourseFee::where('course_id', $id)->count();
            if ($fees_count == 0) {
                $offshore['course_id'] = $onshore['course_id'] = $id;
                $offshore['name'] = 'General Offshore';
                $onshore['name'] = 'General Onshore';
                $offshore['enrollment_fee'] = $onshore['enrollment_fee'] = $course->enrollment_fee;
                $offshore['material_fee'] = $onshore['material_fee'] = $course->material_fee;
                $offshore['fee'] = $course->fee;
                $onshore['fee'] = $course->onshore_fee;
                $offshore['type'] = 'Offshore';
                $onshore['type'] = 'Onshore';
                $offshore['initial_fee'] = $course->fee_initial;
                $offshore['installment'] = $course->fee_installment;
                $onshore['initial_fee'] = $course->onshore_initial;
                $onshore['installment'] = $course->onshore_installment;
                // Create Onshore and Offshore Course Fee
                CourseFee::create($offshore);
                CourseFee::create($onshore);
                $intake_courses = IntakeCourse::where('course_id', $id)->get();
                if (count($intake_courses) > 0) {
                    foreach ($intake_courses as $key => $value) {
                        $offshore['intake_course_id'] = $onshore['intake_course_id'] = $value->id;
                        $offshore['due_date'] = $onshore['due_date'] =  $value->ending_date;
                        // Create Onshore and Offshore Intake Course Fee
                        $fee_offshore = IntakeCourseFee::create($offshore);
                        $offshore_amount = $course->enrollment_fee + $course->material_fee + $course->fee;
                        IntakeCourseFeeInstallment::create([
                            'intake_course_fee_id' => $fee_offshore->id,
                            'name' => 'First Installment',
                            'enrollment_fee' => $course->enrollment_fee,
                            'material_fee' => $course->material_fee,
                            'amount' => $offshore_amount,
                            'due_date' => $value->starting_date,
                        ]);

                        $fee_onshore = IntakeCourseFee::create($onshore);
                        $onshore_amount = $course->enrollment_fee + $course->material_fee + $course->onshore_fee;
                        IntakeCourseFeeInstallment::create([
                            'intake_course_fee_id' => $fee_onshore->id,
                            'name' => 'First Installment',
                            'enrollment_fee' => $course->enrollment_fee,
                            'material_fee' => $course->material_fee,
                            'amount' => $onshore_amount,
                            'due_date' => $value->starting_date,
                        ]);
                    }
                }
            }
            $fees = CourseFee::where('course_id', $id)->get();
            activityLog('Admin', 'Opened Course Fee List Page');
            return view('course::fee.index', compact('course', 'fees'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new course fee.
     * @return Renderable
     */
    public function create($id)
    {
        $course = Course::find($id);
        if (checkRole('course_fee', 'add') == true && $course) {
            activityLog('Admin', 'Opened Create Fee Page of ' . $course->course_name);
            return view('course::fee.create', compact('course'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created course fee in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $count = CourseFee::where('course_id', $id)->where('name', $data['name'])->count();
        if ($count == 0) {
            $data['course_id'] = $id;
            $fee = CourseFee::create($data);
            $intake_courses = IntakeCourse::where('course_id', $id)->get();
            if (count($intake_courses) > 0) {
                foreach ($intake_courses as $key => $value) {
                    $data['intake_course_id'] = $value->id;
                    $data['due_date'] = $value->ending_date;
                    $intake_course_fee = IntakeCourseFee::create($data);
                    if (isset($data['enrollment_fee_wavier'])) $enrollment_fee = 0;
                    else $enrollment_fee = $data['enrollment_fee'];
                    if (isset($data['material_fee_wavier'])) $material_fee = 0;
                    else $material_fee = $data['material_fee'];

                    if ($intake_course_fee->installment == 0) {
                        $total_amount = $enrollment_fee + $material_fee + $data['fee'];
                        IntakeCourseFeeInstallment::create([
                            'intake_course_fee_id' => $intake_course_fee->id,
                            'name' => 'First Installment',
                            'enrollment_fee' => $enrollment_fee,
                            'material_fee' => $enrollment_fee,
                            'amount' => $total_amount,
                            'due_date' => $value->starting_date,
                        ]);
                    } else {
                        $initial_amount = $enrollment_fee + $material_fee + $intake_course_fee->initial_fee;
                        IntakeCourseFeeInstallment::create([
                            'intake_course_fee_id' => $intake_course_fee->id,
                            'name' => 'First Installment',
                            'enrollment_fee' => $enrollment_fee,
                            'material_fee' => $material_fee,
                            'amount' => $initial_amount,
                            'due_date' => $value->starting_date,
                        ]);
                        $installment = $intake_course_fee->installment;

                        $total_fee = $intake_course_fee->fee;
                        $duration = $value->course->duration;
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
            activityLog('Admin', $fee->name . ' fee of ' . $fee->course->course_name . ' created',);
            if (request()->is('admin/course*')) $route = 'admin.course.fee.index';
            else $route = 'admin.unregistered.fee.index';
            return redirect()->route($route, $id)->with('success', 'Course Fee added successfully');
        } else {
            return redirect()->back()->with('failure', 'Fee Name already exsists, please enter another name');
        }
    }

    /**
     * Show the form for editing the specified course fee.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $fee = CourseFee::findorfail($id);
        if (checkRole('course_fee', 'edit') == true) {
            activityLog('Admin', $fee->name . ' edit page opened',);
            return view('course::fee.edit', compact('fee'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified course fee in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $fee = CourseFee::where('id', $id)->first();
        $count_fee = Coursefee::where('id', '!=', $id)->where('course_id', $id)->where('name', $request->name)->count();
        if ($count_fee == 0) {
            $fee->update($data);
            activityLog('Admin', $fee->name . ' fee updated',);
            if ($fee->course->registered == 1) $route = 'admin.course.fee.index';
            else $route = 'admin.unregistered.fee.index';
            return redirect()->route($route, $fee->course_id)->with('success', 'Course fee has been updated successfully');
        } else {
            return redirect()->back()->with('failure', 'Fee Name already exsists, please enter another name');
        }
    }

    /**
     * Remove the specified course fee from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('course_fee', 'delete') == true) {
            $fee = CourseFee::where('id', $id)->first();
            $fee->update(['status' => 2]);
            activityLog('Admin', $fee->name . ' of ' . $fee->course->course_name . ' has been deleted');
            return redirect()->back()->with('success', 'Course Fee has been deleted successfully');
        } else {
            return abort(404);
        }
    }
}

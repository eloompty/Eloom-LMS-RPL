<?php

namespace Modules\Intake\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Intake\Entities\IntakeCourseFee;

class IntakeCourseFeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the intake course fee.
     * @return Renderable
     */
    public function index($id)
    {
        $intakeCourse = IntakeCourse::findorfail($id);
        $check = checkCourseDeliverySite($intakeCourse->course_id);
        if (checkRole('intake_course_fee', 'view') == true && $intakeCourse && $check == true) {
            $fees = IntakeCourseFee::where('intake_course_id', $id)->orderBy('id', 'desc')->get();
            activityLog('Admin', $intakeCourse->reference_name . ' fee lists opened');
            return view('intake::course.fee.index', compact('intakeCourse', 'fees'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified intake course fee.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $intakeCourseFee = IntakeCourseFee::findorfail($id);
        $check = checkCourseDeliverySite($intakeCourseFee->intakeCourse->course_id);
        if (checkRole('intake_course_fee', 'edit') == true && $intakeCourseFee && $check == true) {
            activityLog('Admin', $intakeCourseFee->name . ' intake course fee edit page opened');
            return view('intake::course.fee.edit', compact('intakeCourseFee'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified intake course fee in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $intakeCourseFee = IntakeCourseFee::where('id', $id)->first();
        $intakeCourseFee->update($data);
        activityLog('Admin', $intakeCourseFee->name . ' intake course fee updated');
        return redirect()->route('admin.intake.course.fee.index', $intakeCourseFee->intake_course_id)->with('success', 'Intake Course Fee updated successfully');
    }

    /**
     * Remove the specified intake course fee from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}

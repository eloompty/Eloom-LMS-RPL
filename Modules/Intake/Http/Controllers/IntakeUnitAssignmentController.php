<?php

namespace Modules\Intake\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Assignment\Entities\Assignment;
use Modules\Document\Entities\DocumentType;
use Modules\Intake\Entities\IntakeUnit;
use Modules\Notification\Entities\Notification;
use Modules\Student\Entities\StudentDevice;
use Modules\Student\Entities\StudentIntakeUnit;
use Modules\Trainer\Entities\TrainerIntake;

class IntakeUnitAssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the intake unit assignment.
     * @return Renderable
     */
    public function index($id)
    {
        $intakeUnit = IntakeUnit::findorfail($id);
        $check = checkCourseDeliverySite($intakeUnit->intakeCourse->course_id);
        if (checkRole('intake_course', 'view') == true && $intakeUnit && $check == true) {
            $assignments = Assignment::where('intake_unit_id', $id)->orderBy('id', 'desc')->get();
            activityLog('Admin', $intakeUnit->unit->name . ' assignments opened');
            return view('intake::course.unit.assignment.index', compact('intakeUnit', 'assignments'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new intake unit assignment.
     * @return Renderable
     */
    public function create($id)
    {
        $intakeUnit = IntakeUnit::findorfail($id);
        $check = checkCourseDeliverySite($intakeUnit->intakeCourse->course_id);
        if (checkRole('intake_course', 'add') == true && $intakeUnit && $check == true) {
            $document_types = DocumentType::where('status', 1)->get();
            activityLog('Admin', $intakeUnit->unit->name . ' assignments create page opened');
            return view('intake::course.unit.assignment.create', compact('intakeUnit', 'document_types'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created intake unit assignment in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $user_id = Auth::guard('user')->user()->id;
        $trainerIntake = TrainerIntake::where('intake_unit_id', $id)->first();
        if ($trainerIntake) {
            $data = $request->all();
            $data['intake_unit_id'] = $trainerIntake->intake_unit_id;
            $data['trainer_id'] = $trainerIntake->trainer_id;
            $data['uploaded_by'] = 'Admin';
            $data['uploaded_user_id'] = $user_id;
            $data['type'] = 'file';
            if ($data['document_type'] == 'Video Link') {
                $data['path'] = $data['video_link'];
            } else {
                $imageName = time() . '.' . request()->path->getClientOriginalExtension();
                request()->path->move(public_path('/images/assignments'), $imageName);
                $data['path'] = 'images/assignments/' . $imageName;
            }
            $assignment = Assignment::create($data);
            $studentIntakeUnits = StudentIntakeUnit::where('intake_unit_id', $assignment->intake_unit_id)->where('status', 1)->get();
            foreach ($studentIntakeUnits as $key => $value) {
                $students = StudentDevice::where('student_id', $value->studentIntakeCourse->student_id)->where('status', 1)->get();
                $title = 'New Assignment';
                $body = 'New Assignment of ' . $value->intakeUnit->unit->name;
                if ($students->count() > 0) {
                    foreach ($students as $key => $student) {
                        $fcmData = [
                            'registration_ids' => [$student->device_token],
                            "notification" => [
                                "title" => $title,
                                "body" => $body,
                                "click_action" => route('student.assignment.index',  $assignment->intake_unit_id),
                            ],
                            "data" => [
                                "is_admin" => false,
                            ],
                        ];
                    }
                    fcm($fcmData);
                }
                Notification::create([
                    'user_type' => 'Student',
                    'user_id' => $value->studentIntakeCourse->student_id,
                    'sender_type' => 'Admin',
                    'sender_id' => $user_id,
                    'title' => $title,
                    'body' => $body,
                    'type' => 'Assignment',
                    'link' => $assignment->intake_unit_id,
                ]);
            }
            activityLog('Admin', $assignment->name . ' assignment file created');
            return redirect()->route('admin.intake.unit.assignment.index',  $assignment->intake_unit_id)->with('success', 'Unit assignment created successfully');
            /* if ($data['type'] == 'file') {
                return redirect()->route('admin.intake.unit.assignment.file.create', [$assignment->id])->with('assignment', $assignment);
            } elseif ($data['type'] == 'question') {
                return redirect()->route('admin.intake.unit.assignment.question.create', [$assignment->id])->with('assignment', $assignment);
            } else {
                return redirect()->route('admin.intake.unit.assignment.mcq.create', [$assignment->id])->with('assignment', $assignment);
            } */
        } else {
            return redirect()->back()->with('failure', 'Trainer has not been assigned to this unit');
        }
    }

    /**
     * Show the form for editing the specified intake unit assignment.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $assignment = Assignment::findorfail($id);
        $check = checkCourseDeliverySite($assignment->intakeUnit->intakeCourse->course_id);
        if (checkRole('intake_course', 'view') == true && $assignment && $check == true) {
            $document_types = DocumentType::where('status', 1)->get();
            activityLog('Admin', $assignment->name . ' assignment opened');
            return view('intake::course.unit.assignment.edit', compact('assignment', 'document_types'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified intake unit assignment in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        if ($request->hasfile('path')) {
            $imageName = time() . '.' . request()->path->getClientOriginalExtension();
            request()->path->move(public_path('/images/assignments'), $imageName);
            $data['path'] = 'images/assignments/' . $imageName;
        }
        unset($data['_token']);
        $assignment = Assignment::where('id', $id)->first();
        $assignment->update($data);
        activityLog('Admin', $data['name'] . ' assignment updated');
        return redirect()->route('admin.intake.unit.assignment.index', $assignment->intake_unit_id)->with('success', 'Unit assignment updated successfully');
    }

    /**
     * Remove the specified intake unit assignment from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('intake_course', 'delete') == true) {
            $assignment = Assignment::where('id', $id)->first();
            $assignment->update(['status' => 2]);
            activityLog('Admin', $assignment->name . ' Updated status to deleted');
            return redirect()->back()->with('success', 'Intake Course deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete intake unit assignment');
        }
    }
}

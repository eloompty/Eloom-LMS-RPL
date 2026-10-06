<?php

namespace Modules\Trainer\Http\Controllers\Trainer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Assignment\Entities\Assignment;
use Modules\Document\Entities\DocumentType;
use Modules\Notification\Entities\Notification;
use Modules\Student\Entities\StudentDevice;
use Modules\Student\Entities\StudentIntakeUnit;
use Modules\Trainer\Entities\TrainerIntake;

class AssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    /**
     * Display a listing of the assignment.
     * @return Renderable
     */
    public function index($id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('id', $id)->where('trainer_id', $trainer_id)->first();
        if ($trainerIntake) {
            $assignments = Assignment::where('intake_unit_id', $trainerIntake->intake_unit_id)->where('trainer_id', $trainerIntake->trainer_id)->where('status', 1)->get();
            activityLog('Trainer', 'Opened assignments of ' . $trainerIntake->intakeUnit->unit->name . ' from web');
            return view('trainer::trainer.unit.assignment.index', compact('trainerIntake', 'assignments'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new assignment.
     * @return Renderable
     */
    public function create($id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('id', $id)->where('trainer_id', $trainer_id)->first();
        if ($trainerIntake) {
            $document_types = DocumentType::where('status', 1)->get();
            activityLog('Trainer', 'Opened assignments of ' . $trainerIntake->intakeUnit->unit->name . ' from web');
            return view('trainer::trainer.unit.assignment.create', compact('trainerIntake', 'document_types'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created assignment in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $trainerIntake = TrainerIntake::find($id);
        $data = $request->all();
        $data['intake_unit_id'] = $trainerIntake->intake_unit_id;
        $data['trainer_id'] = $trainerIntake->trainer_id;
        $data['uploaded_by'] = 'Trainer';
        $data['uploaded_user_id'] = $trainerIntake->trainer_id;
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
            $body = 'New Assignment of ' . $assignment->intakeUnit->unit->name;
            if ($students->count() > 0) {
                foreach ($students as $key => $student) {
                    $fcmData = [
                        'registration_ids' => [$student->device_token],
                        "notification" => [
                            "title" => $title,
                            "body" => $body,
                            "click_action" => route('student.assignment.index', $assignment->intake_unit_id),
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
                'sender_type' => 'Trainer',
                'sender_id' => $trainerIntake->trainer_id,
                'title' => $title,
                'body'=> $body,
                'type' => 'Assignment',
                'link' => $assignment->intake_unit_id,
            ]);
        }
        activityLog('Trainer', $assignment->name . ' assignment created');
        $trainerIntake = TrainerIntake::where('intake_unit_id', $assignment->intake_unit_id)->first();
        return redirect()->route('trainer.assignment.index', $trainerIntake->id)->with('success', 'Unit assignment created successfully');
        /* if ($data['type'] == 'file') {
            return redirect()->route('trainer.assignment.file.create', [$assignment->id])->with('assignment', $assignment);
        } elseif ($data['type'] == 'question') {
            return redirect()->route('trainer.assignment.question.create', [$assignment->id])->with('assignment', $assignment);
        } else {
            return redirect()->route('trainer.assignment.mcq.create', [$assignment->id])->with('assignment', $assignment);
        } */
    }

    /**
     * Show the form for editing the specified assignment.
     * @param int $id
     * @return Renderable
     */
    public function edit($id, $trainer_intake_id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('id', $trainer_intake_id)->where('trainer_id', $trainer_id)->first();
        if ($trainerIntake) {
            $assignment = Assignment::find($id);
            $document_types = DocumentType::where('status', 1)->get();
            activityLog('Trainer', $assignment->name . ' assignment edit page opened');
            return view('trainer::trainer.unit.assignment.edit', compact('assignment', 'trainer_intake_id', 'trainerIntake', 'document_types'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified assignment in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id, $trainer_intake_id)
    {
        $data = $request->all();
        if ($request->hasfile('path')) {
            $imageName = time() . '.' . request()->path->getClientOriginalExtension();
            request()->path->move(public_path('/images/assignments'), $imageName);
            $data['path'] = 'images/assignments/' . $imageName;
        }
        unset($data['_token']);
        Assignment::where('id', $id)->update($data);
        activityLog('Trainer', $data['name'] . ' assignment updated');
        return redirect()->route('trainer.assignment.index', $trainer_intake_id)->with('success', 'Unit assignment uodated successfully');
    }
}

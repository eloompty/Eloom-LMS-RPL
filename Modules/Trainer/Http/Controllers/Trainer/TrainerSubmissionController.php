<?php

namespace Modules\Trainer\Http\Controllers\Trainer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Assignment\Entities\AssignmentSubmission;
use Modules\Trainer\Entities\TrainerIntake;

class TrainerSubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    /**
     * Display a listing of the submissions.
     * @return Renderable
     */
    public function index()
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $submissions = AssignmentSubmission::join('assignments', 'assignments.id', '=', 'assignment_submissions.assignment_id')
            ->join('trainer_intakes', 'trainer_intakes.intake_unit_id', '=', 'assignments.intake_unit_id')
            ->where('assignments.trainer_id', $trainer_id)
            ->select('assignment_submissions.*', 'assignments.intake_unit_id')->orderBy('assignment_submissions.id', 'desc')->get();
        foreach ($submissions as $key => $value) {
            $trainerIntake = TrainerIntake::where('intake_unit_id', $value->intake_unit_id)->first();
            $submissions[$key]['trainer_intake_id'] = $trainerIntake->id;
            $submissions[$key]['trainer_course_id'] = $trainerIntake->intake_course_id;
        }
        activityLog('Trainer', 'Opened submissions menu from web');
        return view('trainer::trainer.submission.index', compact('submissions'))->with('no', 1);
    }
}

<?php

namespace Modules\Trainer\Http\Controllers\Trainer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Assignment\Entities\Assignment;
use Modules\Assignment\Entities\AssignmentSubmission;
use Modules\Course\Entities\WorkPlacement;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Trainer\Entities\Trainer;
use Modules\Trainer\Entities\TrainerIntake;
use Modules\Trainer\Entities\TrainerProfessionalDevelopment;
use Modules\Trainer\Entities\TrainerQualification;

class TrainerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    /* Trainer Dashboard */
    public function dashboard()
    {
        activityLog('Trainer', 'Opened Trainer Dashboard from web');
        $id = Auth::guard('trainer')->user()->id;
        $courses = TrainerIntake::where('trainer_id', $id)->whereIn('status', [1, 3])->distinct()->get(['intake_course_id']);
        $total_courses = $courses->count();
        $total_units = TrainerIntake::where('trainer_id', $id)->where('intake_course_id', $id)->whereIn('status', [1, 3])->count();
        $students = [];
        foreach ($courses as $key => $value) {
            $students[] = StudentIntakeCourse::where('intake_course_id', $value->intake_course_id)->whereIn('status', [1, 3])->count();
        }
        $total_students = array_sum($students);
        $total_assignments = Assignment::where('trainer_id', $id)->where('status', 1)->count();
        $submissions = AssignmentSubmission::join('assignments', 'assignment_submissions.assignment_id', '=', 'assignments.id')
        ->select('assignment_submissions.*')->where('assignments.trainer_id', $id)
        ->orderBy('assignment_submissions.id', 'desc')->paginate(5);
        foreach ($submissions as $key => $value) {
            $trainerIntake = TrainerIntake::where('intake_unit_id', $value->assignment->intake_unit_id)->first();
            $submissions[$key]['trainer_intake_id'] = $trainerIntake->id;
        }
        return view('trainer::trainer.dashboard.dashboard', compact('total_courses', 'total_units', 'total_students', 'total_assignments', 'submissions'));
    }

    /* Trainer Profile */
    public function profile()
    {
        activityLog('Trainer', 'Opened Trainer Profile from web');
        $id = Auth::guard('trainer')->user()->id;
        $qualifications = TrainerQualification::where('trainer_id', $id)->get();
        $professions = TrainerProfessionalDevelopment::where('trainer_id', $id)->get();
        $works = WorkPlacement::where('type', 'Trainer')->where('type_id', $id)->get();
        return view('trainer::trainer.profile', compact('qualifications', 'professions', 'works'));
    }

    /* Change Password */
    public function changePassword()
    {
        activityLog('Trainer', 'Opened Change Password from web');
        return view('trainer::trainer.password.change');
    }

    /* Update Password */
    public function fillPassword(Request $request)
    {
        $trainer = Auth::guard('trainer')->user();
        // Check current password matches from database
        $current = Hash::check($request->current_password, $trainer->password);
        if ($current == true) {
            if ($request->new_password == $request->confirm_password) {
                // Check new password matches from database
                $new = Hash::check($request->new_password, $trainer->password);
                if ($new == true) {
                    activityLog('Trainer', 'Update Password Failed');
                    return redirect()->back()->with('failure', 'New password cannot be as current password');
                } else {
                    $change = Trainer::find($trainer->id);
                    $change->password = Hash::make($request->new_password);
                    $change->save();
                    activityLog('Trainer', 'Updated Password from Web');
                    return redirect()->back()->with('success', 'Password has been updated');
                }
            } else {
                return redirect()->back()->with('failure', 'New Password and Current Password must be same');
            }
        } else {
            activityLog('Student', 'Update Password Failed');
            return redirect()->back()->with('failure', 'Wrong Password');
        }
    }
}

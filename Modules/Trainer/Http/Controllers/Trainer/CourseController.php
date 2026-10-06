<?php

namespace Modules\Trainer\Http\Controllers\Trainer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Intake\Entities\IntakeCourseTime;
use Modules\Intake\Entities\IntakeUnitTime;
use Modules\Resource\Entities\Resource;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Trainer\Entities\TrainerIntake;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    /**
     * Display a listing of the course.
     * @return Renderable
     */
    public function index()
    {
        activityLog('Trainer', 'Opened Course menu from web');
        $id = Auth::guard('trainer')->user()->id;
        $courses = TrainerIntake::where('trainer_id', $id)->whereIn('status', [1, 3])->distinct()->get(['intake_course_id']);
        foreach ($courses as $key => $value) {
            $courses[$key]['intakeCourseTime'] = IntakeCourseTime::where('intake_course_id', $value->intake_course_id)->where('status', 1)->get();
            $courses[$key]['enrolled_student'] = StudentIntakeCourse::join('students', 'students.id', 'student_intake_courses.student_id')
            ->select('student_intake_courses.*')->where('students.status', 1)
            ->where('intake_course_id', $value->intake_course_id)->whereIn('student_intake_courses.status', [1, 3])
            ->orderBy('student_intake_courses.id', 'desc')->count();
        }
        return view('trainer::trainer.course.index', compact('courses'))->with('no', 1);
    }

    /* Trainer Unit List */
    public function unit($id)
    {
        activityLog('Trainer', 'Opened units list from web');
        $tainer_id = Auth::guard('trainer')->user()->id;
        $units = TrainerIntake::where('trainer_id', $tainer_id)->where('intake_course_id', $id)->whereIn('status', [1, 3])->orderByRaw('ISNULL(sequence), sequence ASC')->orderBy('id', 'asc')->get();
        foreach ($units as $key => $value) {
            $units[$key]['intakeUnitTime'] = IntakeUnitTime::where('intake_unit_id', $value->intake_unit_id)->where('status', 1)->get();
        }
        return view('trainer::trainer.unit.index', compact('units'))->with('no', 1);
    }
}

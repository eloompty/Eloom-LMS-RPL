<?php

namespace Modules\Student\Http\Controllers\Student;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Intake\Entities\IntakeCourseTime;
use Modules\Student\Entities\StudentIntakeCourse;

class CalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:student');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        activityLog('Student', 'Opened Calendar menu from web');
        return view('student::student.calendar.index');
    }

    /* Get Time Table of assigned Intake Course */
    public function getTimeTable()
    {
        $id = Auth::guard('student')->user()->id;
        $courses = StudentIntakeCourse::where('student_id', $id)->whereIn('status', [1, 3])->get();
        $data = [];
        foreach ($courses as $key => $value) {
            $intakeCourseTime = IntakeCourseTime::where('intake_course_id', $value->intake_course_id)->where('status', 1)->get();
            foreach ($intakeCourseTime as $time) {
                $endDate = $time->intakeCourse->ending_date;
                $startDate = $time->intakeCourse->starting_date;
                $day = $time->day;
                $endDate = strtotime($endDate);
                for ($i = strtotime($day, strtotime($startDate)); $i <= $endDate; $i = strtotime('+1 week', $i))
                    $data[] = [
                        'title' => $time->intakeCourse->course->course_name,
                        'start' => date('Y-m-d', $i) . ' ' . $time->from,
                        'end' => date('Y-m-d', $i) . ' ' . $time->to,
                        'allDay' => false,
                        'backgroundColor' => '#0073b7', //Blue
                        'borderColor' => '#0073b7' //Blue
                    ];
            }
        }
        return response()->json($data);
    }
}

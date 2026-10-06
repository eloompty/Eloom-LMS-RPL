<?php

namespace Modules\Intake\Http\Controllers;

use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Attendance\Entities\Attendance;
use Modules\Intake\Entities\IntakeUnit;
use Modules\Student\Entities\StudentIntakeUnit;

class IntakeUnitAttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the attendance.
     * @return Renderable
     */
    public function index($id, $year, $month)
    {
        $intakeUnit = IntakeUnit::findorfail($id);
        $check = checkCourseDeliverySite($intakeUnit->intakeCourse->course_id);
        if (checkRole('intake_course', 'view') == true && $intakeUnit && $check == true) {
            $intakeCourseStartingDate = $intakeUnit->intakeCourse->starting_date;
            $start    = new DateTime($intakeCourseStartingDate);
            $end      = new DateTime(date('Y-m-d'));
            $interval = DateInterval::createFromDateString('1 year');
            $period   = new DatePeriod($start, $interval, $end);
            $intakeYears = [];
            foreach ($period as $dt) {
                $intakeYears[] =  $dt->format("Y");
            }
            $date_now = date("Y-m");
            $given_date = date("Y-m", strtotime($year . '-' . $month));
            if ($given_date <= $date_now) {
                $students = StudentIntakeUnit::where('intake_unit_id', $id)->where('status', 1)->orderBy('id', 'desc')->get();
                $attendances = Attendance::select(['student_id', 'date'])->where('intake_unit_id', $id)
                    ->get()
                    ->groupBy('student_id')
                    ->map(function ($items) {
                        return $items->pluck('student_id', 'date');
                    });
                $daysInMonth = daysInMonth($year, $month);
                activityLog('Admin', $intakeUnit->unit->name . ' student attendance opened');
                return view('intake::course.unit.attendance.index', compact('intakeUnit', 'students', 'attendances', 'daysInMonth', 'year', 'month', 'intakeYears'));
            } else {
                return redirect()->route('admin.intake.unit.index', $intakeUnit->intake_course_id)->with('failure', 'Cannot take attendance of future month');
            }
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created attendance in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $attendances = $request->all();
        $year = $attendances['year'];
        $month = $attendances['month'];
        $ids = [];
        unset($attendances['_token'], $attendances['year'], $attendances['month']);
        foreach ($attendances as $key => $dates) {
            $ids[] = $key;
            $studentAttendances = Attendance::where('student_id', $key)->where('intake_unit_id', $id)
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->get();
            $student_dates = [];
            foreach ($studentAttendances as $studentAttendance) {
                $student_dates[] = $studentAttendance->date;
            }
            $deleteAttendance = array_diff($student_dates, $dates);
            foreach ($deleteAttendance as $deletedate) {
                Attendance::where('student_id', $key)->where('date', $deletedate)->whereYear('date', $year)->whereMonth('date', $month)->delete();
            }
            foreach ($dates as $date) {
                $attendance = Attendance::where('student_id', $key)->where('date', $date)->where('intake_unit_id', $id)->first();
                if (!$attendance) {
                    Attendance::create([
                        'student_id' => $key,
                        'date' => $date,
                        'intake_unit_id' => $id,
                        'user_id' => Auth::guard('user')->user()->id,
                        'user_type' => 'Admin',
                    ]);
                }
            }
        }
        $all = Attendance::where('intake_unit_id', $id)->whereYear('date', $year)->whereMonth('date', $month)->get();
        foreach ($all as $student) {
            $student_ids[] = $student->student_id;
        }
        $unique_student = array_unique($student_ids);
        $studentIds = array_values($unique_student);
        $deleteStudent = array_diff($studentIds, $ids);
        foreach ($deleteStudent as $studentDelete) {
            Attendance::where('student_id', $studentDelete)->whereYear('date', $year)->whereMonth('date', $month)->delete();
        }
        return redirect()->back()->with('success', 'Attendace has been added');
    }

    /* Get lists of month by year */
    public function getMonthByYear(Request $request)
    {
        $year = $request->year;
        $intakeId = $request->intakeid;
        $intakeUnit = IntakeUnit::find($intakeId);
        $intakeCourseStartingDate = $intakeUnit->intakeCourse->starting_date;
        $startYear = date("Y", strtotime($intakeCourseStartingDate));
        if ($startYear == $year && date('Y') == $year) {
            $start = new DateTime($intakeCourseStartingDate);
            $end = new DateTime(date('Y-m-d'));
        } elseif ($startYear == $year && date('Y') != $year) {
            $start = new DateTime($intakeCourseStartingDate);
            $enddate = date($year . '-' . '12-31');
            $end = new DateTime($enddate);
        } elseif ($startYear != $year && date('Y') == $year) {
            $startDate  = date($year . '-' . '01-01');
            $start = new DateTime($startDate);
            $end = new DateTime(date('Y-m-d'));
        } elseif ($startYear != $year && date('Y') != $year) {
            $startDate  = date($year . '-' . '01-01');
            $start = new DateTime($startDate);
            $enddate = date($year . '-' . '12-31');
            $end = new DateTime($enddate);
        }
        $interval = DateInterval::createFromDateString('1 month');
        $period   = new DatePeriod($start, $interval, $end);
        foreach ($period as $dt) {
            $intakeMonths[$dt->format("m")] =  $dt->format("F");
        }
        return response()->json($intakeMonths);
    }
}

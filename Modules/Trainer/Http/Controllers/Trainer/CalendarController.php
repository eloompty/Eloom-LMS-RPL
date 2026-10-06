<?php

namespace Modules\Trainer\Http\Controllers\Trainer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Intake\Entities\IntakeUnitTime;
use Modules\Trainer\Entities\TrainerIntake;

class CalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        activityLog('Trainer', 'Opened Calendar menu from web');
        return view('trainer::trainer.calendar.index');
    }

    /* Get Time Table of assigned Intake Unit */
    public function getTrainerTimeTable()
    {
        $id = Auth::guard('trainer')->user()->id;
        $courses = TrainerIntake::where('trainer_id', $id)->whereIn('status', [1, 3])->get();
        $data = [];
        foreach ($courses as $key => $value) {
            $intakeCourseTime = IntakeUnitTime::where('intake_unit_id', $value->intake_unit_id)->where('status', 1)->get();
            foreach ($intakeCourseTime as $time) {
                $endDate = $time->intakeUnit->ending_date;
                $startDate = $time->intakeUnit->starting_date;
                $day = $time->day;
                $endDate = strtotime($endDate);
                for ($i = strtotime($day, strtotime($startDate)); $i <= $endDate; $i = strtotime('+1 week', $i))
                    $data[] = [
                        'title' => $time->intakeUnit->unit->name,
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

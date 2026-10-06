<?php

namespace Modules\User\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Agent\Entities\Agent;
use Modules\Assignment\Entities\AssignmentSubmission;
use Modules\Country\Entities\Country;
use Modules\Course\Entities\Course;
use Modules\Intake\Entities\Intake;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentIntakeCourseFeeInstallment;
use Modules\Trainer\Entities\Trainer;
use Modules\User\Entities\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /* Dashboard */
    public function dashboard()
    {
        activityLog('Admin', 'Opened Dashboard');
        $ids = getDeliverySiteIds();
        $total_courses = Course::whereIn('status', [0, 1])->count();
        $total_students = Student::where('is_enrolled', 1)->whereIn('status', [0, 1])->count();
        $trainers = Trainer::whereIn('status', [0, 1])->get();
        $total_trainers = $trainers->count();
        $all_intakes = Intake::whereIn('status', [0,1])->get();
        $total_intakes = $all_intakes->count();
        $submissions = AssignmentSubmission::join('assignments', 'assignments.id', '=', 'assignment_submissions.assignment_id')
            ->join('students', 'students.id', '=', 'assignment_submissions.student_id')
            ->join('intake_units', 'intake_units.id', '=', 'assignments.intake_unit_id')
            ->join('intake_courses', 'intake_courses.id', '=', 'intake_units.intake_course_id')
            ->join('courses', 'courses.id', '=', 'intake_courses.course_id')
            ->leftJoin('course_delivery_sites', 'course_delivery_sites.course_id', '=', 'courses.id')
            ->where(function ($query) use ($ids) {
                $query->whereNull('course_delivery_sites.course_id')
                    ->orWhereIn('course_delivery_sites.company_delivery_site_id', $ids);
            })
            ->where('students.status', 1)
            ->select('assignment_submissions.*')
            ->orderBy('id', 'desc')->paginate(5);
        $today = Carbon::now();
        $next_week = Carbon::now()->addDay(7);
        $payment_dues = StudentIntakeCourseFeeInstallment::whereBetween('due_date', [$today, $next_week])->where('status', 1)->paginate(5);
        if (count($all_intakes) > 0) {
            foreach ($all_intakes as $key => $value) {
                $intakeCourses = IntakeCourse::where('intake_id', $value->id)->get();
                if (count($intakeCourses) > 0)
                    foreach ($intakeCourses as $intakeCourse) {
                        $count = StudentIntakeCourse::where('intake_course_id', $intakeCourse->id)->count();
                    }
                else {
                    $count = 0;
                }
                $chart_intakes[] = $value->name;
                $chart_students[] = $count;
            }
        } else {
            $chart_intakes = [];
            $chart_students = [];
        }
        $all_countries = Student::select(DB::raw('count(*) as count, overseas_country_id'))->groupBy('overseas_country_id')->orderBy('count', 'desc')->get();
        if (count($all_countries) > 0) {
            $top_countries = $all_countries->take(5);
            foreach ($top_countries as $key => $value) {
                $top_country[] = $value->overseasCountry->name;
                $top_count[] = $value->count;
            }
            foreach ($all_countries as $key => $value) {
                $countries[] = $value->overseasCountry->name;
                $student_counts[] = $value->count;
            }
            $colors = randomHexColor(count($all_countries));
        } else {
            $top_country = [];
            $top_count = [];
            $countries = [];
            $student_counts = [];
            $colors = [];
        }
        $top_agents = Student::join('student_agents', 'student_agents.student_id', '=', 'students.id')
            ->select(DB::raw('count(*) as count, agent_id'))->groupBy('agent_id')->orderBy('count', 'desc')->take(5)->get();
        if (count($top_agents)>0) {
            foreach ($top_agents as $key => $value) {
                $agent = Agent::find($value->agent_id);
                $top_agent[] = acronym($agent->company_name);
                $top_agent_count[] = $value->count;
            }
        } else {
            $top_agent = [];
            $top_agent_count = [];
        }
        return view('user::dashboard.dashboard', compact(
            'total_courses',
            'total_students',
            'total_trainers',
            'total_intakes',
            'submissions',
            'payment_dues',
            'chart_intakes',
            'chart_students',
            'trainers',
            'top_country',
            'top_count',
            'countries',
            'student_counts',
            'colors',
            'top_agent',
            'top_agent_count'
        ));
    }

    /* Toggle from dark to light and vice versa */
    public function toggleTheme(Request $request)
    {
        $id = Auth::guard('user')->user()->id;
        $user = User::where('id', $id)->update(['theme' => $request->theme]);
        activityLog('Admin', 'Theme Updated to ' . $request->theme);
        $data = [
            'success' => true,
            'theme' => $request->theme,
            'message' => 'Theme toggled' . $request->theme
        ];
        return response()->json($data);
    }

    /**
     * Display User/Admin Profile.
     * @return Renderable
     */
    public function index()
    {
        activityLog('Admin', 'Opened Profile');
        $countries = Country::pluck('name', 'id');
        return view('user::index', compact('countries'));
    }

    /**
     * Update User/Admin Profile.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        if ($data['password'] == NULL) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }
        if ($request->has('image')) {
            $imageName = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('/images/users'), $imageName);
            $data['image'] = 'images/users/' . $imageName;
        }
        unset($data['_token']);
        User::where('id', $id)->update($data);
        activityLog('Admin', 'Profile Updated');
        return redirect()->back()->with('success', 'Profile updated successfully');
    }
}

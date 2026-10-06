<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Address\Entities\Address;
use Modules\Agent\Entities\Agent;
use Modules\Agent\Entities\AgentBranch;
use Modules\AgentBranchUser\Entities\AgentBranchUser;
use Modules\Country\Entities\Country;
use Modules\Log\Entities\Log;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentAgent;
use Modules\Student\Entities\StudentDeliverySite;
use Modules\Student\Entities\StudentIntakeUnit;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the students.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if (checkRole('student', 'view') == true) {
            activityLog('Admin', 'Opened Student Menu');
            $status = $request->status;
            $ids = getDeliverySiteIds();
            if ($status == NULL) $status_code = [0, 1];
            else $status_code = [2];
            $all_students = Student::leftJoin('student_delivery_sites', 'student_delivery_sites.student_id', '=', 'students.id')
                ->where(function ($query) use ($ids) {
                    $query->whereNull('student_delivery_sites.student_id')
                        ->orWhereIn('student_delivery_sites.company_delivery_site_id', $ids)
                        ->where('student_delivery_sites.status', 1);
                })
                ->select('students.*')
                ->whereIn('students.status', $status_code)->where('students.is_enrolled', 1);
            $overseas_country = $request->overseas_country;
            if ($overseas_country != NULL) {
                $country = Country::where('name', $overseas_country)->first();
                $students = $all_students->where('overseas_country_id', $country->id)->orderBy('students.id', 'desc')->get();
            } else {
                $students = $all_students->orderBy('students.id', 'desc')->get();
            }
            foreach ($students as $key => $value) {
                $site = $value->deliverySite->where('status', 1)->first();
                if ($site) {
                    $students[$key]['site'] = $site->companyDeliverySite->site_name;
                } else {
                    $students[$key]['site'] = "Not Assigned";
                }
            }
            $table = 'students';
            $students_count = Student::count();
            $students_overloaded = false;
            $student_env_value = env('STUDENT_COUNT') ?? 100;
            if ($students_count >= $student_env_value) {
                $students_overloaded = true;
            }

            return view('student::index', compact('students', 'status', 'table', 'students_overloaded', 'student_env_value'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new student.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('student', 'add') == true) {
            $students_count = Student::count();
            $students_overloaded = false;
            $student_env_value = env('STUDENT_COUNT') ?? 100;
            if ($students_count >= $student_env_value) {
                 return abort(404);
            }

            activityLog('Admin', 'Opened Create Student Page');
            $countries = Country::where('status', 1)->pluck('name', 'id');
            $delivery_sites = getDeliverySites();
            $agents = Agent::where('status', 1)->orderby('company_name', 'asc')->pluck('company_name', 'id');
            $table = 'students';
            return view('student::create', compact('countries', 'delivery_sites', 'agents', 'table'));
        } else {
            return redirect()->route('admin.student.index')->with('failure', 'This user does not have permission to add student');
        }
    }

    /**
     * Store a newly created student in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $imageName = time() . '.' . request()->image->getClientOriginalExtension();
        request()->image->move(public_path('/images/students'), $imageName);
        $data['image'] = 'images/students/' . $imageName;
        $data['password'] = Hash::make($data['password']);
        if ($data['table'] == 'students') {
            $data['is_enrolled'] = 1;
        }
        if ($data['student_id_apprenticeships'] == NULL) {
            unset($data['student_id_apprenticeships']);
        }
        if ($data['language_identifier'] == NULL) {
            unset($data['language_identifier']);
        }
        if ($data['indigenous_status_identifier'] == NULL) {
            unset($data['indigenous_status_identifier']);
        }
        if ($data['high_school_level_completed_identifier'] == NULL) {
            unset($data['high_school_level_completed_identifier']);
        }
        if ($data['labour_force_status_identifier'] == NULL) {
            unset($data['labour_force_status_identifier']);
        }
        if ($data['funding_source_national'] == NULL) {
            unset($data['funding_source_national']);
        }
        if ($data['statistical_area_level_1_identifier'] == NULL) {
            unset($data['statistical_area_level_1_identifier']);
        }
        if ($data['statistical_area_level_2_identifier'] == NULL) {
            unset($data['statistical_area_level_2_identifier']);
        }
        if ($data['survey_contact_status'] == NULL) {
            unset($data['survey_contact_status']);
        }
        if ($data['anzsic'] == NULL) {
            unset($data['anzsic']);
        }
        if (!isset($data['country_id']) || $data['country_id'] == NULL) {
            $data['country_id'] = $data['citizenship_country'];
        }
        if (!isset($data['phone']) || $data['phone'] == NULL) {
            $data['phone'] = $data['mobile'];
        }
        $student = Student::create($data);
        $data['type_id'] = $student->id;
        $data['type'] = 'student';
        if (isset($data['street_no']) || $data['street_no'] != NULL) {
            Address::create($data);
        }
        $data['student_id'] = $student->id;
        StudentDeliverySite::create($data);
        if (isset($data['agent_id']) != NULL) {
            $data['student_id'] = $student->id;
            StudentAgent::create($data);
        }
        activityLog('Admin', userName('Student', $student->id) . ' created');
        return redirect()->route('admin.student.index')->with('success', 'Student has been added successfully');
    }

    /**
     * Show the specified student.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        if (checkRole('student', 'view') == true) {
            $student = Student::findorfail($id);
            $delivery_site = $student->deliverySite->first();
            if ($delivery_site) {
                $ids = getDeliverySiteIds();
                $sites = StudentDeliverySite::whereIn('company_delivery_site_id', $ids)->where('student_id', $id)->get();
                if (count($sites) == 0) $show = false;
                else $show = true;
            } else {
                $show = true;
            }
            if ($show == true) {
                $site = $student->deliverySite->where('status', 1)->first();
                if ($site) {
                    $student->delivery_site = $site->companyDeliverySite->site_name;
                } else {
                    $student->delivery_site = 'Not Assigned';
                }
                $student_agent = StudentAgent::where('student_id', $id)->first();
                if ($student_agent) {
                    $student->agent = $student_agent->agent->company_name;
                } else {
                    $student->agent = 'No Agent';
                }
                $fees = $student->fees;
                foreach ($fees as $key => $value) {
                    $fees[$key]['installments'] = $value->installments;
                    if ($value->enrollment_fee_wavier == 1) $fees[$key]['enrollment_fee'] = 0;
                    else $fees[$key]['enrollment_fee'] = $value->enrollment_fee;
                    if ($value->material_fee_wavier == 1) $fees[$key]['material_fee'] = 0;
                    else $fees[$key]['material_fee'] = $value->material_fee;
                    $fees[$key]['total_fee'] = $fees[$key]['enrollment_fee'] + $fees[$key]['material_fee'] + $value->amount;
                    $fees[$key]['paid'] = $value->installments->where('status', 2)->sum('amount');
                    $fees[$key]['remaining'] = $value->installments->where('status', 1)->sum('amount');
                }
                activityLog('Admin', userName('Student', $student->id) . ' detail page opened');
                return view('student::show', compact('student', 'fees'));
            } else {
                return abort(404);
            }
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified student.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('student', 'edit') == true) {
            $student = Student::findorfail($id);
            $delivery_site = $student->deliverySite->first();
            if ($delivery_site) {
                $ids = getDeliverySiteIds();
                $sites = StudentDeliverySite::whereIn('company_delivery_site_id', $ids)->where('student_id', $id)->get();
                if (count($sites) == 0) $edit = false;
                else $edit = true;
            } else {
                $edit = true;
            }
            if ($edit == true) {
                $countries = Country::where('status', 1)->pluck('name', 'id');
                $delivery_sites = getDeliverySites();
                $site = $student->deliverySite->where('status', 1)->first();
                if ($site) {
                    $site_id = $site->company_delivery_site_id;
                } else {
                    $site_id = 0;
                }
                $student_agent = StudentAgent::where('student_id', $id)->first();
                $agents = Agent::where('status', 1)->orderby('company_name', 'asc')->pluck('company_name', 'id');
                if ($student_agent) {
                    $branches = AgentBranch::where('agent_id', $student_agent->agent_id)->pluck('name', 'id');
                    $users = AgentBranchUser::where('branch_id', $student_agent->branch_id)->pluck('name', 'id');
                    $agent_id = $student_agent->agent_id;
                    $branch_id = $student_agent->branch_id;
                    $user_id = $student_agent->user_id;
                } else {
                    $branches = [];
                    $users = [];
                    $agent_id = 0;
                    $branch_id = 0;
                    $user_id = 0;
                }
                activityLog('Admin', userName('Student', $student->id) . ' edit page opened');
                return view('student::edit', compact('student', 'countries', 'delivery_sites', 'site_id', 'student_agent', 'agents', 'branches', 'users', 'agent_id', 'branch_id', 'user_id'));
            } else {
                return abort(404);
            }
        } else {
            return redirect()->route('admin.student.index')->with('failure', 'This user does not have permission to edit student');
        }
    }

    /**
     * Update the specified student in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        if ($request->has('image')) {
            $imageName = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('/images/students'), $imageName);
            $data['image'] = 'images/students/' . $imageName;
        }
        if ($data['password'] == NULL) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }
        $student = Student::find($id);
        if ($data['student_id_apprenticeships'] == NULL) unset($data['student_id_apprenticeships']);
        if ($data['high_school_level_completed_identifier'] == NULL) $data['high_school_level_completed_identifier'] = '@@';
        if ($data['indigenous_status_identifier'] == NULL) $data['indigenous_status_identifier'] = '@';
        if ($data['labour_force_status_identifier'] == NULL) $data['labour_force_status_identifier'] = '@@';
        if ($data['statistical_area_level_1_identifier'] == NULL) $data['statistical_area_level_1_identifier'] = '@@@@@@@@@@@';
        if ($data['statistical_area_level_2_identifier'] == NULL) $data['statistical_area_level_2_identifier'] = '@@@@@@@@@';
        if ($data['anzsic'] == NULL) $data['anzsic'] = '@@@@';
        if ($data['language_identifier'] == NULL) $data['language_identifier'] = '@@@@';
        if (!isset($data['country_id']) || $data['country_id'] == NULL) {
            $data['country_id'] = $data['citizenship_country'];
        }
        if (!isset($data['phone']) || $data['phone'] == NULL) {
            $data['phone'] = $data['mobile'];
        }
        $student->fill($data)->save();

        if (isset($data['street_no']) || $data['street_no'] != NULL) {
            $address = Address::where('type', 'student')->where('type_id', $id)->first();
            if ($address) {
                $address->fill($data)->save();
            } else {
                $data['type_id'] = $student->id;
                $data['type'] = 'student';
                Address::create($data);
            }
        }
        $site = $student->deliverySite->where('status', 1)->first();
        if ($site) {
            unset($data['status']);
            $site->fill($data)->save();
        } else {
            $data['student_id'] = $id;
            StudentDeliverySite::create($data);
        }
        if (isset($data['agent_id']) != NULL) {
            $student_agent = StudentAgent::where('student_id', $id)->first();
            if ($student_agent) {
                unset($data['status']);
                $student_agent->fill($data)->save();
            } else {
                $data['student_id'] = $id;
                StudentAgent::create($data);
            }
        }
        if ($student->intake->first() != NULL) {
            foreach ($student->intake as $intake) {
                foreach ($intake->studentIntakeUnit as $unit) {
                    $intake_unit = StudentIntakeUnit::find($unit->id);
                    // if ($intake_unit->funding_source_national == NULL) {
                    $intake_unit->funding_source_national = $student->funding_source_national;
                    $intake_unit->funding_source_state_training_authority = $student->funding_source_state_training_authority;
                    $intake_unit->save();
                    // };
                }
            }
        }
        activityLog('Admin', userName('Student', $student->id) . ' updated');
        if (strpos($data['url'], 'show')) {
            return redirect()->route('admin.student.show', $id)->with('success', 'Student has been updated successfully');
        } elseif (strpos($data['url'], 'offer')) {
            return redirect()->route('admin.student.offer.index')->with('success', 'Student has been updated successfully');
        } else {
            return redirect()->route('admin.student.index')->with('success', 'Student has been updated successfully');
        }
    }

    /**
     * Remove the specified student from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('student', 'delete') == true) {
            Student::where('id', $id)->update(['status' => 2]);
            return redirect()->back()->with('success', 'Student has been deleted successfully');
        } else {
            return redirect()->route('admin.student.index')->with('failure', 'This user does not have permission to delete student');
        }
    }

    /* Get branch by agent */
    public function getBranches(Request $request)
    {
        $branches = AgentBranch::where('agent_id', $request->agent_id)->where('status', 1)->pluck('name', 'id');
        return response()->json($branches);
    }

    /* Get branch by agent */
    public function getBranchUser(Request $request)
    {
        $users = AgentBranchUser::where('branch_id', $request->branch_id)->where('status', 1)->pluck('name', 'id');
        return response()->json($users);
    }

    /* Login in Student Dashboard */
    public function dashboard(Request $request, $id)
    {
        if (checkRole('student_dashboard', 'view') == true) {
            activityLog('Admin', 'Opened Student Dashboard');
            Log::create([
                'user_type' => 'Student',
                'user_id' => $id,
                'action' => 'Logged In by Admin'
            ]);
            Log::create([
                'user_type' => 'Student',
                'user_id' => $id,
                'action' => 'Logged In from web'
            ]);
            $student = Student::findorfail($id);
            if (Auth::guard('student')->loginUsingId($student->id)) {
                return redirect()->intended(route('student.dashboard'));
            }
        } else {
            return abort(404);
        }
    }
}

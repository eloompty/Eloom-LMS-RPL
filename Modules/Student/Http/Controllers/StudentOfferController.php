<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Agent\Entities\Agent;
use Modules\Company\Entities\CompanyDeliverySite;
use Modules\Country\Entities\Country;
use Modules\Student\Entities\Student;

class StudentOfferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }
    
    /**
     * Display a listing of the student offers.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if (checkRole('student', 'view') == true) {
            activityLog('Admin', 'Opened Student Offer Menu');
            $status = $request->status;
            $ids = getDeliverySiteIds();
            if ($status == NULL) $status_code = [0, 1];
            else $status_code = [2];
            $students = Student::leftJoin('student_delivery_sites', 'student_delivery_sites.student_id', '=', 'students.id')
                ->where(function ($query) use ($ids) {
                    $query->whereNull('student_delivery_sites.student_id')
                        ->orWhereIn('student_delivery_sites.company_delivery_site_id', $ids);
                })
                ->select('students.*')
                ->whereIn('students.status', $status_code)->where('students.is_enrolled', 0)->orderBy('students.id', 'desc')->get();
            foreach ($students as $key => $value) {
                $site = $value->deliverySite->where('status', 1)->first();
                if ($site) {
                    $students[$key]['site'] = $site->companyDeliverySite->site_name;
                } else {
                    $students[$key]['site'] = "Not Assigned";
                }
            }
            $table = 'offer';
            return view('student::index', compact('students', 'status', 'table'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new student offers.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('student', 'add') == true) {
            activityLog('Admin', 'Opened Create Student Offer Page');
            $countries = Country::where('status', 1)->pluck('name', 'id');
            $delivery_sites = getDeliverySites();
            $agents = Agent::where('status', 1)->orderby('company_name', 'asc')->pluck('company_name', 'id');
            $table = 'offer';
            return view('student::create', compact('countries', 'delivery_sites', 'agents', 'table'));
        } else {
            return redirect()->route('admin.student.offer.index')->with('failure', 'This user does not have permission to add student');
        }
    }

    public function enroll(Request $request, $id)
    {
        $student = Student::findorfail($id);
        $student->is_enrolled = 1;
        $student->remarks = $request->remarks;
        if ($request->has('file')) {
            $imageName = time() . '.' . request()->file->getClientOriginalExtension();
            request()->file->move(public_path('/images/students/files'), $imageName);
            $student->file = 'images/students/files/' . $imageName;
        }
        $student->save();
        return redirect()->route('admin.student.index')->with('success', 'Offer student moved to enrolled list');;
    }

    /**
     * Store a newly created student offers in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified student offers.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('student::show');
    }

    /**
     * Show the form for editing the specified student offers.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('student::edit');
    }

    /**
     * Update the specified student offers in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified student offers from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}

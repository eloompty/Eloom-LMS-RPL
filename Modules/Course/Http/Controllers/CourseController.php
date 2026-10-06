<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Company\Entities\CompanyDeliverySite;
use Modules\Course\Entities\Course;
use Modules\Course\Entities\CourseDeliverySite;
use Modules\Course\Entities\CourseFee;
use Modules\Course\Entities\WorkPlacement;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the courses.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('course', 'view') == true) {
            activityLog('Admin', 'Opened Courses Menu');
            if (request()->is('admin/course*')) $registered = 1;
            else $registered = 0;
            $ids = getDeliverySiteIds();
            $courses = Course::leftJoin('course_delivery_sites', 'course_delivery_sites.course_id', '=', 'courses.id')
            ->where(function ($query) use ($ids) {
                $query->whereNull('course_delivery_sites.course_id')
                    ->orWhereIn('course_delivery_sites.company_delivery_site_id', $ids);
            })
            ->select('courses.*')
            ->where('courses.registered', $registered)->orderBy('courses.id', 'desc')->get();
            return view('course::index', compact('courses', 'registered'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new course.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('course', 'add') == true) {
            activityLog('Admin', 'Opened Course Create Menu');
            if (request()->is('admin/course*')) $registered = 1;
            else $registered = 0;
            $deliveryMode = getDefaultIdentifier('DELIVERY MODE IDENTIFIER');
            $predominantDeliveryMode = getDefaultIdentifier('PREDOMINANT DELIVERY MODE');
            $delivery_sites = getDeliverySites();
            return view('course::create', compact('delivery_sites', 'registered', 'deliveryMode', 'predominantDeliveryMode'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created course in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        if (request()->is('admin/course*')) {
            $data['registered'] = 1;
            $route = 'admin.course.index';
        } else {
            $data['registered'] = 0;
            $route = 'admin.unregistered.index';
        }
        if ($data['delivery_mode'] == 'YNN') {
            $data['internal'] = 'Yes';
        }
        // if ($data['fee_initial'] == NULL) $data['fee_initial'] = 0;
        // if ($data['fee_installment'] == NULL) $data['fee_installment'] = 0;
        // if ($data['onshore_initial'] == NULL) $data['onshore_initial'] = 0;
        // if ($data['onshore_installment'] == NULL) $data['onshore_installment'] = 0;
        $course = Course::create($data);
        $course_id = $course->id;
        if ($request->placement_company_name != NULL) {
            $data['type'] = 'Course';
            $data['type_id'] = $course_id;
            WorkPlacement::create($data);
        }
        // if ($request->delivery_mode == 'Physical') {
        $data['course_id'] = $course_id;
        $delivery_site = CompanyDeliverySite::first();
        $data['company_delivery_site_id'] = $delivery_site->id;
        CourseDeliverySite::create($data);
        // }
        /* $offshore['course_id'] = $onshore['course_id'] = $course_id;
        $offshore['name'] = 'General Offshore';
        $onshore['name'] = 'General Onshore';
        $offshore['enrollment_fee'] = $onshore['enrollment_fee'] = $course->enrollment_fee;
        $offshore['material_fee'] = $onshore['material_fee'] = $course->material_fee;
        $offshore['fee'] = $course->fee;
        $onshore['fee'] = $course->onshore_fee;
        $offshore['type'] = 'Offshore';
        $onshore['type'] = 'Onshore';
        $offshore['enrollment_fee'] = $course->fee_initial;
        $offshore['installment'] = $course->fee_installment;
        $onshore['initial_fee'] = $course->onshore_initial;
        $onshore['installment'] = $course->onshore_installment; */
        // Create Onshore and Offshore Course Fee
        /* CourseFee::create($offshore);
        CourseFee::create($onshore); */
        activityLog('Admin', $course->course_name . ' created');
        return redirect()->route($route)->with('success', 'Course has been added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $course = Course::find($id);
        if (checkRole('course', 'edit') == true && $course) {
            if (request()->is('admin/course*')) $registered = 1;
            else $registered = 0;
            $work_placement = WorkPlacement::where('type', 'Course')->where('type_id', $id)->get();
            $course_delivery_site = CourseDeliverySite::where('course_id', $id)->first();
            $delivery_sites = getDeliverySites();
            activityLog('Admin', $course->course_name . ' edit page opened');
            return view('course::edit', compact('course', 'registered', 'work_placement', 'course_delivery_site', 'delivery_sites'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified course in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        // dd($data);
        unset($data['_token']);
        $course = Course::find($id);
        /* if ($request->delivery_mode == 'Physical') {
            if ($course->delivery_mode == 'Online') {
                $data['course_id'] = $id;
                CourseDeliverySite::create($data);
            } else {
                $course_delivery_site = CourseDeliverySite::where('course_id', $id)->first();
                if ($course_delivery_site->company_delivery_site_id != $request->company_delivery_site_id) {
                    CourseDeliverySite::where('course_id', $id)->delete();
                    $data['course_id'] = $id;
                    CourseDeliverySite::create($data);
                }
            }
        } else {
            CourseDeliverySite::where('course_id', $id)->delete();
        } */
        if ($data['delivery_mode'] == 'YNN') {
            $data['internal'] = 'Yes';
        }
        /* $course_delivery_site = CourseDeliverySite::where('course_id', $id)->first();
        if ($course_delivery_site == NULL || $course_delivery_site->company_delivery_site_id != $request->company_delivery_site_id) {
            CourseDeliverySite::where('course_id', $id)->delete();
            $data['course_id'] = $id;
            CourseDeliverySite::create($data);
        }
        if ((!isset($data['initial_fee']))) {
            $data['fee_initial'] = 0;
            $data['fee_installment'] = 0;
        }
        if (!isset($data['initial_onshore_fee'])) {
            $data['onshore_initial'] = 0;
            $data['onshore_installment'] = 0;
        } */
        $course->fill($data)->save();
        /* CourseFee::where('course_id', $id)->where('name', 'General Offshore')->update([
            'initial_fee' => $data['fee_initial'],
            'enrollment_fee' => $data['enrollment_fee'],
            'material_fee' => $data['material_fee'],
            'fee' => $data['fee'],
            'installment' => $data['fee_installment'],
        ]);
        CourseFee::where('course_id', $id)->where('name', 'General Onshore')->update([
            'initial_fee' => $data['onshore_initial'],
            'enrollment_fee' => $data['enrollment_fee'],
            'material_fee' => $data['material_fee'],
            'fee' => $data['onshore_fee'],
            'installment' => $data['onshore_installment'],
        ]); */
        activityLog('Admin', $course->course_name . ' updated');
        if (request()->is('admin/course*')) {
            $route = 'admin.course.index';
        } else {
            $route = 'admin.unregistered.index';
        }
        return redirect()->route($route)->with('success', 'Course has been updated successfully');
    }

    /**
     * Update the specified course status to deleted
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('course', 'delete') == true) {
            Course::where('id', $id)->update(['status' => 2]);
            $course = Course::find($id);
            activityLog('Admin',  $course->course_name . ' updated status to deleted');
            return redirect()->back()->with('success', 'Course has been deleted successfully');
        } else {
            return abort(404);
        }
    }
    
    public function menu()
    {
        if (checkRole('course', 'view') == true) {
            activityLog('Admin',  'Opened Courses Menu Page');
            return view('course::menu');
        } else {
            return abort(404);
        }
    }
}

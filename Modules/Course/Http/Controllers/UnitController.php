<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Course\Entities\Course;
use Modules\Course\Entities\Unit;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the units.
     * @return Renderable
     */
    public function index($id)
    {
        $course = Course::find($id);
        if (checkRole('unit', 'view') == true && $course) {
            $units = Unit::where('course_id', $id)->orderBy('id', 'asc')->get();
            activityLog('Admin', 'Opened Course Unit Page');
            return view('course::unit.index', compact('course', 'units'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new unit.
     * @return Renderable
     */
    public function create($id)
    {
        $course = Course::find($id);
        if (checkRole('unit', 'add') == true && $course) {
            activityLog('Admin', 'Opened Create Unit Page of ' . $course->course_name);
            return view('course::unit.create', compact('course'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created unit in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $data['course_id'] = $id;
        if ($data['hours'] == NULL) $data['hours'] = 0;
        if ($data['duration'] == NULL) $data['duration'] = 0;
        if ($data['due_date'] == NULL) $data['due_date'] = 0;
        $unit = Unit::create($data);
        activityLog('Admin', $unit->name . ' of ' . $unit->course->course_name . ' created',);
        if (request()->is('admin/course*')) $route = 'admin.course.unit.index';
        else $route = 'admin.unregistered.unit.index';
        return redirect()->route($route, $id)->with('success', 'Unit has been added successfully');
    }

    /**
     * Show the form for editing the specified unit.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $unit = Unit::findorfail($id);
        if (checkRole('unit', 'edit') == true) {
            activityLog('Admin', $unit->name . ' edit page opened',);
            return view('course::unit.edit', compact('unit'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified unit in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        if ($data['hours'] == NULL) $data['hours'] = 0;
        if ($data['duration'] == NULL) $data['duration'] = 0;
        if ($data['due_date'] == NULL) $data['due_date'] = 0;
        $unit = Unit::find($id);
        $unit->update($data);
        activityLog('Admin', $unit->name . ' updated',);
        if ($unit->course->regsitered == 1) $route = 'admin.course.unit.index';
        else $route = 'admin.unregistered.unit.index';
        return redirect()->route($route, $unit->course_id)->with('success', 'Unit has been updated successfully');
    }

    /**
     * Update the status of specified unit to deleted.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('unit', 'delete') == true) {
            Unit::where('id', $id)->update(['status' => 2]);
            $unit = Unit::find($id);
            activityLog('Admin', $unit->name . ' updated to status deleted',);
            return redirect()->back()->with('success', 'Unit has been deleted successfully');
        } else {
            return abort(404);
        }
    }
}

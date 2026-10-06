<?php

namespace Modules\Resource\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Course\Entities\Course;
use Modules\Course\Entities\Unit;
use Modules\Document\Entities\DocumentType;
use Modules\Resource\Entities\Resource;
use Modules\Resource\Entities\ResourceCategory;

class ResourceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('resource', 'view') == true) {
            activityLog('Admin', 'Opened Resource Menu');
            $ids = getDeliverySiteIds();
            $resources = Resource::join('units', 'units.id', '=', 'resources.unit_id')
                ->join('courses', 'courses.id', '=', 'units.course_id')
                ->leftJoin('course_delivery_sites', 'course_delivery_sites.course_id', '=', 'courses.id')
                ->where(function ($query) use ($ids) {
                    $query->whereNull('course_delivery_sites.course_id')
                        ->orWhereIn('course_delivery_sites.company_delivery_site_id', $ids);
                })
                ->select('resources.*')
                ->orderBy('resources.id', 'desc')->get();
            return view('resource::index', compact('resources'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('resource', 'add') == true) {
            activityLog('Admin', 'Opened Create Resource Page');
            $categories = ResourceCategory::where('status', 1)->pluck('name', 'id');
            $document_types = DocumentType::where('status', 1)->get();
            $ids = getDeliverySiteIds();
            $courses = Course::leftJoin('course_delivery_sites', 'course_delivery_sites.course_id', '=', 'courses.id')
                ->where(function ($query) use ($ids) {
                    $query->whereNull('course_delivery_sites.course_id')
                        ->orWhereIn('course_delivery_sites.company_delivery_site_id', $ids);
                })
                ->select('courses.*')
                ->where('courses.status', 1)->pluck('course_name', 'id');
            return view('resource::create', compact('categories', 'courses', 'document_types'));
        } else {
            return redirect()->route('admin.resource.index')->with('failure', 'This user does not have permission to add resource');
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        if ($request->hasfile('file')) {
            $files =  $request->file('file');
            foreach ($files as $file) {
                $name = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path() . '/images/resources', $name);
                $data['path'] = 'images/resources/' . $name;
                $category = ResourceCategory::find($request->resource_category_id);
                $data['user_type'] = $category->user_type;
                $data['uploaded_by'] = 'Admin';
                $data['uploaded_user_id'] = Auth::guard('user')->user()->id;
                Resource::create($data);
            }
        } else {
            $data['path'] = $data['file'];
            $category = ResourceCategory::find($request->resource_category_id);
            $data['user_type'] = $category->user_type;
            $data['uploaded_by'] = 'Admin';
            $data['uploaded_user_id'] = Auth::guard('user')->user()->id;
            Resource::create($data);
        }
        activityLog('Admin', 'Rescources created');
        return redirect()->route('admin.resource.index')->with('success', 'Resource has been added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $resource = Resource::findorfail($id);
        $check = checkCourseDeliverySite($resource->unit->course_id);
        if (checkRole('resource', 'edit') == true && $check == true) {
            $categories = ResourceCategory::where('status', 1)->pluck('name', 'id');
            $document_types = DocumentType::where('status', 1)->get();
            $ids = getDeliverySiteIds();
            $courses = Course::leftJoin('course_delivery_sites', 'course_delivery_sites.course_id', '=', 'courses.id')
                ->where(function ($query) use ($ids) {
                    $query->whereNull('course_delivery_sites.course_id')
                        ->orWhereIn('course_delivery_sites.company_delivery_site_id', $ids);
                })
                ->select('courses.*')
                ->where('courses.status', 1)->pluck('course_name', 'id');
            $units = Unit::where('course_id', $resource->unit->course_id)->where('status', 1)->pluck('name', 'id');
            activityLog('Admin', $resource->name . ' edit page opened');
            return view('resource::edit', compact('resource', 'categories', 'courses', 'units', 'document_types'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        if ($request->hasfile('file')) {
            $imageName = time() . '.' . request()->file->getClientOriginalExtension();
            request()->file->move(public_path('/images/resources'), $imageName);
            $data['path'] = 'images/resources/' . $imageName;
        }
        $category = ResourceCategory::find($request->resource_category_id);
        $data['user_type'] = $category->user_type;
        unset($data['_token']);
        $resource = Resource::find($id);
        if ($resource->resource_type == 'Video Link') {
            $data['path'] = $data['file'];
        }
        unset($data['file']);
        $resource->update($data);
        activityLog('Admin', $resource->name . ' updated');
        return redirect()->route('admin.resource.index')->with('success', 'Resource has been updated successfully');
    }

    /**
     * Update status of specified resource to deleted.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('resource', 'delete') == true) {
            Resource::where('id', $id)->update(['status' => 2]);
            $resource = Resource::find($id);
            activityLog('Admin', 'Status of' . $resource->name . ' updated to deleted');
            return redirect()->back()->with('success', 'Resource deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete resource');
        }
    }

    public function menu()
    {
        if (checkRole('resource', 'view') == true) {
            activityLog('Admin',  'Opened Resources Menu Page');
            return view('resource::menu');
        } else {
            return abort(404);
        }
    }
}

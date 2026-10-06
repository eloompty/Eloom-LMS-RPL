<?php

namespace Modules\Identifier\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Identifier\Entities\IdentifierType;

class IdentifierTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the identifier type.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('setting', 'view') == true) {
            activityLog('Admin', 'Opened Identifier Type Setting Menu');
            $types = IdentifierType::get();
            return view('identifier::type.index', compact('types'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new identifier type.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('setting', 'view') == true) {
            activityLog('Admin', 'Opened Add Identifier Type Page');
            return view('identifier::type.create');
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created identifier type in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $type = IdentifierType::create($data);
        activityLog('Admin', $type->title . ' identifier type created');
        return redirect()->route('admin.identifier.type.index')->with('success', 'Identifier type has been added successfully');
    }

    /**
     * Show the form for editing the specified identifier type.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('setting', 'edit') == true) {
            $type = IdentifierType::find($id);
            activityLog('Admin', $type->title .  ' opened');
            return view('identifier::type.edit', compact('type'));
        } else {
            return redirect()->route('admin.identifier.type.index')->with('failure', 'This user does not have permission to edit type');
        }
    }

    /**
     * Update the specified identifier type in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $type = IdentifierType::where('id', $id)->first();
        $type->update($data);
        activityLog('Admin', $type->title . ' updated');
        return redirect()->route('admin.identifier.type.index')->with('success', 'Identifier Type has been updated successfully');
    }

    /**
     * Remove the specified identifier type from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (Auth::guard('user')->user()->user_type == 'super_admin') {
            $type = IdentifierType::where('id', $id)->first();
            $type->update(['status' => 2]);
            activityLog('Admin', 'Status of ' . $type->title . ' updated to deleted');
            return redirect()->back()->with('success', 'Identifier deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete identifier type');
        }
    }
}

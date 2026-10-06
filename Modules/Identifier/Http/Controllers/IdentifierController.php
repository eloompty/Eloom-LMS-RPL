<?php

namespace Modules\Identifier\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Identifier\Entities\Identifier;
use Modules\Identifier\Entities\IdentifierType;

class IdentifierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the identifier.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('setting', 'view') == true) {
            activityLog('Admin', 'Opened Identifier Setting Menu');
            $identifiers = Identifier::get();
            return view('identifier::index', compact('identifiers'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new identifier.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('setting', 'view') == true) {
            $types = IdentifierType::where('status', 1)->get();
            activityLog('Admin', 'Opened Add Identifier Page');
            return view('identifier::create', compact('types'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created identifier in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $identifier = Identifier::create($data);
        activityLog('Admin', $identifier->title . ' identifier created');
        return redirect()->route('admin.identifier.index')->with('success', 'Identifier has been added successfully');
    }

    /**
     * Show the form for editing the specified identifier.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('setting', 'edit') == true) {
            $identifier = Identifier::find($id);
            $types = IdentifierType::where('status', 1)->get();
            activityLog('Admin', $identifier->title .  ' opened');
            return view('identifier::edit', compact('identifier', 'types'));
        } else {
            return redirect()->route('admin.identifier.index')->with('failure', 'This user does not have permission to edit identifier');
        }
    }

    /**
     * Update the specified identifier in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $identifier = Identifier::where('id', $id)->first();
        $identifier->update($data);
        activityLog('Admin', $identifier->title . ' updated');
        return redirect()->route('admin.identifier.index')->with('success', 'Identifier has been updated successfully');
    }

    /**
     * Remove the specified identifier from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (Auth::guard('user')->user()->user_type == 'super_admin') {
            $identifier = Identifier::where('id', $id)->first();
            $identifier->update(['status' => 2]);
            activityLog('Admin', 'Status of ' . $identifier->title . ' updated to deleted');
            return redirect()->back()->with('success', 'Identifier deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete identifier');
        }
    }
}

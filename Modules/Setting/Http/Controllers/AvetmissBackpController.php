<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\AvetmissBackup;

class AvetmissBackpController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the Avetmiss Backup.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('avetmiss_backup', 'view') == true) {
            activityLog('Admin', 'Opened Avetmiss Backup List');
            $avetmiss_backups = AvetmissBackup::orderby('id', 'desc')->get();
            return view('setting::avetmiss.index', compact('avetmiss_backups'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new Avetmiss Backup.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('avetmiss_backup', 'add') == true) {
            activityLog('Admin', 'Opened create Avetmiss Backup Page');
            return view('setting::avetmiss.create');
        } else {
            return redirect()->route('admin.setting.avetmiss.index')->with('failure', 'This user does not have permission to add Avetmiss Backup');
        }
    }

    /**
     * Store a newly created Avetmiss Backup in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $imageName = time() . '.' . request()->image->getClientOriginalExtension();
        request()->image->move(public_path('/images/avetmiss_backup'), $imageName);
        $data['path'] = 'images/avetmiss_backup/' . $imageName;
        $avetmiss_backup = AvetmissBackup::create($data);
        activityLog('Admin', $avetmiss_backup->title . ' Avetmiss Backup created');
        return redirect()->route('admin.setting.avetmiss.index')->with('success', 'Avetmiss Backup has been added successfully');
    }

    /**
     * Show the form for editing the specified Avetmiss Backup.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('avetmiss_backup', 'edit') == true) {
            $avetmiss_backup = AvetmissBackup::findorfail($id);
            activityLog('Admin', $avetmiss_backup->name .  'edit page opened');
            return view('setting::avetmiss.edit', compact('avetmiss_backup'));
        } else {
            return redirect()->route('admin.setting.avetmiss.index')->with('failure', 'This user does not have permission to edit Avetmiss Backup');
        }
    }

    /**
     * Update the specified Avetmiss Backup in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        if ($request->has('image')) {
            $imageName = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('/images/avetmiss_backup'), $imageName);
            $data['path'] = 'images/avetmiss_backup/' . $imageName;
        }
        $avetmiss_backup = AvetmissBackup::where('id', $id)->first();
        $avetmiss_backup->update($data);
        activityLog('Admin', $avetmiss_backup->title . ' updated');
        return redirect()->route('admin.setting.avetmiss.index')->with('success', 'Avetmiss Backup has been updated successfully');
    }

    /**
     * Remove the specified Avetmiss Backup from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('avetmiss_backup', 'delete') == true) {
            $avetmiss_backup = AvetmissBackup::where('id', $id)->first();
            $avetmiss_backup->update(['status' => 2]);
            activityLog('Admin', 'Status of ' . $avetmiss_backup->title . ' updated to deleted');
            return redirect()->back()->with('success', 'Avetmiss Backup deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete Avetmiss Backup');
        }
    }
}

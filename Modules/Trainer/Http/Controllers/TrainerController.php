<?php

namespace Modules\Trainer\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Address\Entities\Address;
use Modules\Country\Entities\Country;
use Modules\Log\Entities\Log;
use Modules\Trainer\Entities\Trainer;

class TrainerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the trainers.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('trainer', 'view') == true) {
            activityLog('Admin', 'Opened Trainer Menu');
            $trainers = Trainer::orderBy('id', 'desc')->get();
            return view('trainer::index', compact('trainers'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new trainer.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('trainer', 'add') == true) {
            activityLog('Admin', 'Opened Create Trainer Page');
            $countries = Country::where('status', 1)->pluck('name', 'id');
            return view('trainer::create', compact('countries'));
        } else {
            return redirect()->route('admin.trainer.index')->with('failure', 'This user does not have permission to add trainer');
        }
    }

    /**
     * Store a newly created trainer in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $imageName = time() . '.' . request()->image->getClientOriginalExtension();
        request()->image->move(public_path('/images/trainers'), $imageName);
        $data['image'] = 'images/trainers/' . $imageName;
        $data['password'] = Hash::make($data['password']);
        $trainer = Trainer::create($data);
        $data['type_id'] = $trainer->id;
        $data['type'] = 'trainer';
        Address::create($data);
        $name = userName('Trainer', $trainer->id);
        activityLog('Admin', $name . ' trainer created');
        return redirect()->route('admin.trainer.index')->with('success', 'Trainer has been added successfully');
    }

    /**
     * Show the specified trainer.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('trainer::show');
    }

    /**
     * Show the form for editing the specified trainer.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('trainer', 'edit') == true) {
            $trainer = Trainer::find($id);
            $countries = Country::where('status', 1)->pluck('name', 'id');
            activityLog('Admin', userName('Trainer', $trainer->id) . ' edit page opened');
            return view('trainer::edit', compact('trainer', 'countries'));
        } else {
            return redirect()->route('admin.trainer.index')->with('failure', 'This user does not have permission to add trainer');
        }
    }

    /**
     * Update the specified trainer in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        if ($request->has('image')) {
            $imageName = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('/images/trainers'), $imageName);
            $data['image'] = 'images/trainers/' . $imageName;
        }
        if ($data['password'] == NULL) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }
        $trainer = Trainer::find($id);
        $trainer->fill($data)->save();

        $address = Address::where('type', 'trainer')->where('type_id', $id)->first();
        $address->fill($data)->save();
        activityLog('Admin', userName('Trainer', $trainer->id) . ' updated');
        return redirect()->route('admin.trainer.index')->with('success', 'Trainer has been updated successfully');
    }

    /**
     * Remove the specified trainer from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('trainer', 'delete') == true) {
            Trainer::where('id', $id)->update(['status' => 2]);
            return redirect()->back()->with('success', 'Trainer has been deleted successfully');
        } else {
            return redirect()->route('admin.trauber.index')->with('failure', 'This user does not have permission to delete student');
        }
    }

    public function dashboard($id)
    {
        if (checkRole('trainer_dashboard', 'view') == true) {
            activityLog('Admin', 'Opened Trainer Dashboard');
            Log::create([
                'user_type' => 'Trainer',
                'user_id' => $id,
                'action' => 'Logged In by Admin'
            ]);
            $trainer = Trainer::findorfail($id);
            if (Auth::guard('trainer')->loginUsingId($trainer->id)) {
                return redirect()->intended(route('trainer.dashboard'));
            }
        } else {
            return abort(404);
        }
    }
}

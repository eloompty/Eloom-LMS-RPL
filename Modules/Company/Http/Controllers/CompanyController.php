<?php

namespace Modules\Company\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Address\Entities\Address;
use Modules\Company\Entities\Company;
use Modules\Country\Entities\Country;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Company Profile.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('company', 'view') == true) {
            activityLog('Admin', 'Opened Company Menu');
            $company = Company::first();
            $countries = Country::where('status', 1)->pluck('name', 'id');
            return view('company::index', compact('company', 'countries'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the company profile.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        if (checkRole('company', 'edit') == true) {
            if ($request->has('image')) {
                $imageName = time() . '.' . request()->image->getClientOriginalExtension();
                request()->image->move(public_path('/images/company'), $imageName);
                $logo = 'images/company/' . $imageName;
                Company::where('id', $id)->update(['logo' => $logo]);
            }
            Company::where('id', $id)->update([
                'company_name' => $request->company_name,
                'trading_name' => $request->trading_name,
                'company_ceo' => $request->company_ceo,
                'rto_no' => $request->rto_no,
                'cricos_no' => $request->cricos_no,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
            Address::where('type_id', $id)->where('type', 'Company')->update([
                'building_number' =>  $request->building_number,
                'flat_unit' =>  $request->flat_unit,
                'street_no' =>  $request->street_no,
                'street_address' =>  $request->street_address,
                'p_o_box' =>  $request->p_o_box,
                'suburb' =>  $request->suburb,
                'state' =>  $request->state,
                'zip_code' =>  $request->zip_code,
                'country_id' =>  $request->country_id,
            ]);
            activityLog('Admin', 'Updated Company Details');
            return redirect()->back()->with('success', 'Company updated successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to update company');
        }
    }

    public function menu()
    {
        if (checkRole('company', 'view') == true) {
            activityLog('Admin',  'Opened Company Menu Page');
            return view('company::menu');
        } else {
            return abort(404);
        }
    }
}

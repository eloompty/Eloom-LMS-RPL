<?php

namespace Modules\Company\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Address\Entities\Address;
use Modules\Company\Entities\Company;
use Modules\Company\Entities\CompanyDeliverySite;
use Modules\Country\Entities\Country;

class CompanyDeliverySiteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the company delivery sites.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('company_delivery_site', 'view') == true) {
            activityLog('Admin', 'Opened Company Delivery Sites List');
            $delivery_sites = getDeliverySites();
            return view('company::deliverysite.index', compact('delivery_sites'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new company delivery site.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('company_delivery_site', 'add') == true) {
            activityLog('Admin', 'Opened Company Delivery Site Create Page');
            $countries = Country::where('status', 1)->pluck('name', 'id');
            return view('company::deliverysite.create', compact('countries'));
        } else {
            return redirect()->route('admin.company.delivery.index')->with('failure', 'This user does not have permission to add company delivery site');
        }
    }

    /**
     * Store a newly created company delivery sites in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $company = Company::first();
        $data['company_id'] = $company->id;
        $delivery_site = CompanyDeliverySite::create($data);
        $data['type'] = 'company_delivery_site';
        $data['type_id'] = $delivery_site->id;
        $address = Address::create($data);
        activityLog('Admin', $delivery_site->site_name . ' created');
        return redirect()->route('admin.company.delivery.index')->with('success', 'Company Delivery Site has been added successfully');
    }

    /**
     * Show the form for editing the specified company delivery site.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $delivery_site = CompanyDeliverySite::find($id);
        if (checkRole('company_delivery_site', 'edit') == true  && $delivery_site) {
            $countries = Country::where('status', 1)->pluck('name', 'id');
            activityLog('Admin', $delivery_site->site_name .  $delivery_site->site_name . ' edit page opened');
            return view('company::deliverysite.edit', compact('delivery_site', 'countries'));
        } else {
            return redirect()->route('admin.company.delivery.index')->with('failure', 'This user does not have permission to edit company delivery site');
        }
    }

    /**
     * Update the specified company delivery site in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        CompanyDeliverySite::where('id', $id)->update([
            'site_name' => $data['site_name'],
            'phone' => $data['phone'],
            'status' => $data['status'],
        ]);
        $site_name = $data['site_name'];
        unset($data['_token'], $data['site_name'], $data['phone'], $data['status']);
        Address::where('type_id', $id)->where('type', 'company_delivery_site')->update($data);
        activityLog('Admin', $site_name . ' updated');
        return redirect()->route('admin.company.delivery.index')->with('success', 'Company Delivery Site updated successfully');
    }

    /**
     * Remove the specified company delivery site from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('company_delivery_site', 'delete') == true) {
            CompanyDeliverySite::where('id', $id)->update(['status' => 2]);
            $delivery_site = CompanyDeliverySite::find($id);
            activityLog('Admin', $delivery_site->site_name . $delivery_site->site_name . ' changed status to delete');
            return redirect()->back()->with('success', 'Company Delivery Site deleted successfully');
        } else {
            return redirect()->route('admin.company.delivery.index')->with('failure', 'This user does not have permission to edit company delivery site');
        }
    }
}

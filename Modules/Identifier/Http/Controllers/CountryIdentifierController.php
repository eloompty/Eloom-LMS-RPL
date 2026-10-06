<?php

namespace Modules\Identifier\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Identifier\Entities\Identifier;
use Modules\Identifier\Entities\IdentifierType;

class CountryIdentifierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the country identifier.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('setting', 'view') == true) {
            activityLog('Admin', 'Opened Countries Identifier Setting Menu');
            $identifier_type = IdentifierType::where('title', 'COUNTRY IDENTIFIER')->first();
            $countries = Identifier::where('identifier_type_id', $identifier_type->id)->orderBy('status', 'desc')->orderBy('value', 'asc')->get();
            return view('identifier::country.index', compact('countries'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Update the bulk country identifier in storage.
     * @param Request $request
     * @return Renderable
     */
    public function updateBulk(Request $request)
    {
        if (checkRole('setting', 'edit') == true) {
            activityLog('Admin', 'Bulk Country Identifier Status updated');
            $data = $request->all();
            if (isset($data['id'])) {
                Identifier::whereIn('id', $data['id'])->update(['status' => $request->status]);
                return redirect()->back()->with('success', 'All selected countries have been updated');
            } else {
                return redirect()->back()->with('failure', 'Choose atleast one row to update');
            }
        } else {
            return abort(404);
        }
    }
}

<?php

namespace App\Http\Controllers\HeadOffice;

use App\Http\Controllers\Controller;
use App\Models\LocationPharmacyType;
use App\Models\LocationRegulatoryBody;
use App\Models\LocationType;
use Illuminate\Http\Request;

class LocationsController extends Controller
{
    public function create(Request $request) {
        $location_types = LocationType::all();
        $location_pharmacy_types = LocationPharmacyType::all();
        $location_regulatory_bodies = LocationRegulatoryBody::all();
        $location_name = $request->query("loc");
        return view('head_office.my_organisation.location_create',compact('location_types','location_pharmacy_types','location_regulatory_bodies','location_name'));
    }
}

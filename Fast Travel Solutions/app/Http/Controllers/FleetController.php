<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Fleet;
use App\Models\FleetClass;
use App\Models\FleetManufacturer;
use App\Models\FleetModel;
use App\Models\FleetType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\FileUploadTrait;





class FleetController extends Controller
{
    use FileUploadTrait;

    public function create_fleet(Request $request)
    {
        $auth = Auth::user();

        if ($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) {

            if ($auth->id == $request->company_id || $auth->fixed_role_id == 1) {

                $rules = [
                    'company_id' => 'required',
                    'vehicle_id' => 'required|string|max:255',
                    'vehicle_type' => 'required|exists:fleet_types,id',
                    'vehicle_class' => 'required|exists:fleet_classes,id',
                    'vehicle_num' => 'required|string|max:255',
                    'vehicle_features' => 'required|string|max:255',
                    'color' => 'required|string|max:255',
                    'manufacturer' => 'required',
                    'model' => 'required',
                    'max_passengers' => 'required',
                    'max_luggage' => 'required',
                    'year_manufacturer' => 'required',
                    // 'engine_size' => 'required',
                    // 'miles_per_gallon' => 'required',
                    // 'fuel_type' => 'required|string|max:255',
                    // 'wheel_plan' => 'required|string|max:255',
                    // 'emission_class' => 'required',
                    // 'milage' => 'required',
                    'mot' => 'required|string|max:255',
                    // 'school_contract' => 'required|string|max:255',

                ];


                if (is_null($request->id)) {

                    $rules['vehicle_pco_license_pic'] = 'required|image|mimes:jpeg,png,jpg,gif';
                    $rules['vehicle_isurance_pic'] = 'required|image|mimes:jpeg,png,jpg,gif';
                    $rules['logbook_pic'] = 'required|image|mimes:jpeg,png,jpg,gif';
                    $rules['vehicle_mot_pic'] = 'required|image|mimes:jpeg,png,jpg,gif';
                }

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    $errors = $validator->errors()->first(); // Get the first error message

                    $response = [
                        'success' => false,
                        'message' => $errors,
                    ];

                    return response()->json($response); // Return JSON response with HTTP 
                }

                if (is_null($request->id)) {
                    $fleet = new Fleet();
                } else {
                    $fleet = Fleet::find($request->id);
                    if (!$fleet) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Fleet not found',
                        ]);
                    }
                    $fleet->active_status = 0;
                }

                if ($auth->fixed_role_id == 1) {

                    $company = Company::find($request->company_id);
                } else {

                    $user = User::find($request->company_id);
                    $company = Company::find($user->company_id);
                }


                // $company = Company::select('id')->where('user_id', $request->company_id)->first();

                $fleet->company_id = $company->id;
                $fleet->vehicle_id = $request->vehicle_id;
                $fleet->vehicle_type = $request->vehicle_type;
                $fleet->vehicle_class = $request->vehicle_class;
                $fleet->vehicle_features = $request->vehicle_features;
                $fleet->vehicle_num = $request->vehicle_num;
                $fleet->color = $request->color;
                $fleet->manufacturer = $request->manufacturer;
                $fleet->model = $request->model;
                $fleet->max_passengers = $request->max_passengers;
                $fleet->max_luggage = $request->max_luggage;
                $fleet->year_manufacturer = $request->year_manufacturer;
                // $fleet->engine_size = $request->engine_size;
                // $fleet->miles_per_gallon = $request->miles_per_gallon;
                // $fleet->fuel_type = $request->fuel_type;
                // $fleet->wheel_plan = $request->wheel_plan;
                // $fleet->emission_class = $request->emission_class;
                // $fleet->milage = $request->milage;
                $fleet->mot = $request->mot;
                // $fleet->school_contract = $request->school_contract;

                if ($request->hasFile('vehicle_pco_license_pic')) {

                    $fleet->vehicle_pco_license_pic = $this->handleFileUpload($request->file('vehicle_pco_license_pic'));

                    // $fleet->vehicle_pco_license_pic = $request->file('vehicle_pco_license_pic')->store('images', 'public');
                }

                if ($request->hasFile('vehicle_isurance_pic')) {

                    $fleet->vehicle_isurance_pic = $this->handleFileUpload($request->file('vehicle_isurance_pic'));

                    // $fleet->vehicle_isurance_pic = $request->file('vehicle_isurance_pic')->store('images', 'public');
                }

                if ($request->hasFile('logbook_pic')) {

                    $fleet->logbook_pic = $this->handleFileUpload($request->file('logbook_pic'));

                    // $fleet->logbook_pic = $request->file('logbook_pic')->store('images', 'public');
                }

                if ($request->hasFile('vehicle_mot_pic')) {

                    $fleet->vehicle_mot_pic = $this->handleFileUpload($request->file('vehicle_mot_pic'));

                    // $fleet->vehicle_mot_pic = $request->file('vehicle_mot_pic')->store('images', 'public');
                }

                // if($auth->fixed_role_id == 1){

                //     $fleet->active_status = 1;
                // }

                $fleet->save();
                return response()->json([
                    'status' => true,
                    'message' => is_null($request->id) ? 'Fleet created successfully' : 'Fleet updated successfully',
                    'fleet' => $fleet,
                ]);
            } else {

                return response()->json([
                    'status' => false,
                    'message' => 'Please enter valid company ID',
                ]);
            }
        } else {

            return response()->json([
                'status' => false,
                'message' => 'You have not permission to create and update the fleet',
            ]);
        }
    }

    // public function fleet_list(Request $request)
    // {
    //     $auth = Auth::user();

    //     if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) && ($auth->id == $request->company_id || $auth->fixed_role_id == 1)) {


    //         $validator = Validator::make($request->all(), [
    //             'company_id' => 'required|exists:users,id',

    //         ]);

    //         if ($validator->fails()) {
    //             $errors = $validator->errors()->first();

    //             $response = [
    //                 'success' => false,
    //                 'message' => $errors,
    //             ];

    //             return response()->json($response);
    //         }

    //         $company = Company::select('id')->where('user_id', $request->company_id)->first();


    //         if ($company || $auth->fixed_role_id == 1) {

    //             if ($company) {

    //                 $fleets = Fleet::where('company_id', $company->id)->where('active_status', 1)->where('is_deleted', 0)->get();

    //                 return $fleets;

    //             } else {

    //                 if ($auth->fixed_role_id == 1) {
    //                     $fleets = Fleet::where('active_status', 1)->where('is_deleted', 0)->get();
    //                 } else {
    //                     return response()->json([
    //                         'status' => false,
    //                         'message' => 'You have not permission to see fleet list!',
    //                     ]);
    //                 }
    //             }


    //             foreach ($fleets as $fleet) {

    //                 $fleet_type = FleetType::find($fleet->vehicle_type);

    //                 $fleet->fleet_type = $fleet_type;

    //                 $fleet_class = FleetClass::find($fleet->vehicle_class);
    //                 $fleet->fleet_class = $fleet_class;

    //                 $fleet_model = FleetModel::find($fleet->model);
    //                 $fleet->fleet_model = $fleet_model;

    //                 $fleet_manufacturer = FleetManufacturer::find($fleet->manufacturer);
    //                 $fleet->fleet_manufacturer = $fleet_manufacturer;

    //                 if ($auth->fixed_role_id == 1) {
    //                     $company = Company::find($fleet->company_id);
    //                     $fleet->company_name = $company->company_name;
    //                 }
    //             }


    //             return response()->json([
    //                 'status' => true,
    //                 'message' => 'Fleet list',
    //                 'response' => $fleets
    //             ]);

    //         } else {

    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Company does not exist',
    //             ]);
    //         }
    //     } else {

    //         return response()->json([
    //             'status' => false,
    //             'message' => 'You have not permission to see fleet list!',
    //         ]);
    //     }
    // }

    public function fleet_list(Request $request) // Single page 
    {
        // $user = auth()->user(); // Replace with the admin user if not authenticated
        // $permissions = $user->getAllPermissions();
        // dd($permissions);



        $auth = Auth::user();

        // Check if user has permission based on role and company ID
        if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) && ($auth->id == $request->company_id || $auth->fixed_role_id == 1)) {

            if ($auth->fixed_role_id == 2) {
                // Validate the request
                $validator = Validator::make($request->all(), [
                    'company_id' => 'required|exists:users,id',
                ]);

                // If validation fails, return error response
                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->first(),
                    ]);
                }
            }

            if ($request->company_id) {

                $user = User::find($request->company_id);
                $company = Company::find($user->company_id);
                // Retrieve company by user ID
                // $company = Company::select('id')->where('user_id', $request->company_id)->first();
            } else {
                $company = [];
            }

            // Check if the company exists or if the user is an admin (role_id == 1)
            if ($company || $auth->fixed_role_id == 1) {

                // Get fleets for the company or all fleets if admin
                $fleets = $company ?
                    Fleet::where('company_id', $company->id)->where('is_deleted', 0)->orderByRaw('active_status ASC')->orderBy('created_at', 'desc')->paginate(6) :
                    Fleet::where('is_deleted', 0)->orderByRaw('active_status ASC')->orderBy('created_at', 'desc')->paginate(6);

                // If no fleets found, return a response indicating no permission
                if ($fleets->isEmpty()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Fleets does not exist',
                    ]);
                }

                // Enrich each fleet with additional data
                foreach ($fleets as $fleet) {
                    $fleet->fleet_type = FleetType::find($fleet->vehicle_type);
                    $fleet->fleet_class = FleetClass::find($fleet->vehicle_class);
                    // $fleet->fleet_model = FleetModel::find($fleet->model);
                    // $fleet->fleet_manufacturer = FleetManufacturer::find($fleet->manufacturer);

                    if ($auth->fixed_role_id == 1) {
                        $fleet->company_name = Company::find($fleet->company_id)->company_name;
                    }
                }

                // Return the fleet list
                return response()->json([
                    'status' => true,
                    'message' => 'Fleet list retrieved successfully.',
                    'response' => $fleets,
                ]);
            }

            // Return error if the company does not exist
            return response()->json([
                'status' => false,
                'message' => 'Company does not exist.',
            ]);
        } else {
            // Return error if user does not have permission
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to see the fleet list!',
            ]);
        }
    }

    // public function fleet_list(Request $request) // multiple pages like approved, pending and rejected
    // {
    //     $auth = Auth::user();

    //     // Check if user has permission based on role and company ID
    //     if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) && ($auth->id == $request->company_id || $auth->fixed_role_id == 1)) {

    //         if ($auth->fixed_role_id == 2) {
    //             // Validate the request
    //             $validator = Validator::make($request->all(), [
    //                 'company_id' => 'required|exists:users,id',
    //             ]);

    //             // If validation fails, return error response
    //             if ($validator->fails()) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => $validator->errors()->first(),
    //                 ]);
    //             }
    //         }

    //         if ($request->company_id) {

    //             $user = User::find($request->company_id);
    //             $company = Company::find($user->company_id);
    //             // Retrieve company by user ID
    //             // $company = Company::select('id')->where('user_id', $request->company_id)->first();
    //         } else {
    //             $company = [];
    //         }

    //         // Check if the company exists or if the user is an admin (role_id == 1)
    //         if ($company || $auth->fixed_role_id == 1) {


    //             if ($request->status == 'approved') {


    //                 // Get fleets for the company or all fleets if admin
    //                 $fleets = $company ?
    //                     Fleet::where('company_id', $company->id)->where('is_deleted', 0)->where('active_status', 1)->orderBy('created_at', 'desc')->paginate(6) :
    //                     Fleet::where('is_deleted', 0)->where('active_status', 1)->orderBy('created_at', 'desc')->paginate(6);

    //                 // If no fleets found, return a response indicating no permission
    //                 if ($fleets->isEmpty()) {
    //                     return response()->json([
    //                         'status' => false,
    //                         'message' => 'Active fleets does not exist',
    //                     ]);
    //                 }

    //                 // Enrich each fleet with additional data
    //                 foreach ($fleets as $fleet) {
    //                     $fleet->fleet_type = FleetType::find($fleet->vehicle_type);
    //                     $fleet->fleet_class = FleetClass::find($fleet->vehicle_class);
    //                     // $fleet->fleet_model = FleetModel::find($fleet->model);
    //                     // $fleet->fleet_manufacturer = FleetManufacturer::find($fleet->manufacturer);

    //                     if ($auth->fixed_role_id == 1) {
    //                         $fleet->company_name = Company::find($fleet->company_id)->company_name;
    //                     }
    //                 }

    //                 // Return the fleet list
    //                 return response()->json([
    //                     'status' => true,
    //                     'message' => 'Approved fleet list retrieved successfully.',
    //                     'response' => $fleets,
    //                 ]);
    //             } else if ($request->status == 'pending') {

    //                 // Get fleets for the company or all fleets if admin
    //                 $fleets = $company ?
    //                     Fleet::where('company_id', $company->id)->where('is_deleted', 0)->where('active_status', 0)->orderBy('created_at', 'desc')->paginate(6) :
    //                     Fleet::where('is_deleted', 0)->where('active_status', 0)->orderBy('created_at', 'desc')->paginate(6);

    //                 // If no fleets found, return a response indicating no permission
    //                 if ($fleets->isEmpty()) {
    //                     return response()->json([
    //                         'status' => false,
    //                         'message' => 'Pending fleets does not exist',
    //                     ]);
    //                 }

    //                 // Enrich each fleet with additional data
    //                 foreach ($fleets as $fleet) {
    //                     $fleet->fleet_type = FleetType::find($fleet->vehicle_type);
    //                     $fleet->fleet_class = FleetClass::find($fleet->vehicle_class);
    //                     // $fleet->fleet_model = FleetModel::find($fleet->model);
    //                     // $fleet->fleet_manufacturer = FleetManufacturer::find($fleet->manufacturer);

    //                     if ($auth->fixed_role_id == 1) {
    //                         $fleet->company_name = Company::find($fleet->company_id)->company_name;
    //                     }
    //                 }

    //                 // Return the fleet list
    //                 return response()->json([
    //                     'status' => true,
    //                     'message' => 'Pending fleet list retrieved successfully.',
    //                     'response' => $fleets,
    //                 ]);

    //             } else if ($request->status == 'rejected') {

    //                 // Get fleets for the company or all fleets if admin
    //                 $fleets = $company ?
    //                     Fleet::where('company_id', $company->id)->where('is_deleted', 0)->where('active_status', 2)->orderBy('created_at', 'desc')->paginate(6) :
    //                     Fleet::where('is_deleted', 0)->where('active_status', 2)->orderBy('created_at', 'desc')->paginate(6);

    //                 // If no fleets found, return a response indicating no permission
    //                 if ($fleets->isEmpty()) {
    //                     return response()->json([
    //                         'status' => false,
    //                         'message' => 'Rejected fleets does not exist',
    //                     ]);
    //                 }

    //                 // Enrich each fleet with additional data
    //                 foreach ($fleets as $fleet) {
    //                     $fleet->fleet_type = FleetType::find($fleet->vehicle_type);
    //                     $fleet->fleet_class = FleetClass::find($fleet->vehicle_class);
    //                     // $fleet->fleet_model = FleetModel::find($fleet->model);
    //                     // $fleet->fleet_manufacturer = FleetManufacturer::find($fleet->manufacturer);

    //                     if ($auth->fixed_role_id == 1) {
    //                         $fleet->company_name = Company::find($fleet->company_id)->company_name;
    //                     }
    //                 }

    //                 // Return the fleet list
    //                 return response()->json([
    //                     'status' => true,
    //                     'message' => 'Rejected fleet list retrieved successfully.',
    //                     'response' => $fleets,
    //                 ]);
    //             }
    //         }

    //         // Return error if the company does not exist
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Company does not exist.',
    //         ]);
    //     } else {
    //         // Return error if user does not have permission
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'You do not have permission to see the fleet list!',
    //         ]);
    //     }
    // }



    public function fleet_list_dropdown(Request $request)
    {
        $auth = Auth::user();

        // Check if user has permission based on role and company ID
        if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) && ($auth->id == $request->company_id || $auth->fixed_role_id == 1)) {

            if ($auth->fixed_role_id == 2) {
                // Validate the request
                $validator = Validator::make($request->all(), [
                    'company_id' => 'required|exists:users,id',
                ]);

                // If validation fails, return error response
                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->first(),
                    ]);
                }
            }

            if ($request->company_id) {
                $user = User::find($request->company_id);
                $company = Company::find($user->company_id);
                // Retrieve company by user ID
                // $company = Company::select('id')->where('user_id', $request->company_id)->first();
            } else {
                // Get fleets for the company or all fleets if admin
                $fleets = Fleet::where('active_status', 1)->where('is_deleted', 0)->get();
                // If no fleets found, return a response indicating no permission
                if ($fleets->isEmpty()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Fleets does not exist',
                    ]);
                }

                // Enrich each fleet with additional data
                foreach ($fleets as $fleet) {
                    $fleet->fleet_type = FleetType::find($fleet->vehicle_type);
                    $fleet->fleet_class = FleetClass::find($fleet->vehicle_class);
                }

                // Return the fleet list
                return response()->json([
                    'status' => true,
                    'message' => 'Fleet list retrieved successfully.',
                    'response' => $fleets,
                ]);
            }

            // Check if the company exists or if the user is an admin (role_id == 1)
            if ($company) {

                // Get fleets for the company or all fleets if admin
                $fleets = Fleet::where('company_id', $company->id)->where('active_status', 1)->where('is_deleted', 0)->get();
                // If no fleets found, return a response indicating no permission
                if ($fleets->isEmpty()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Fleets does not exist',
                    ]);
                }

                // Enrich each fleet with additional data
                foreach ($fleets as $fleet) {
                    $fleet->fleet_type = FleetType::find($fleet->vehicle_type);
                    $fleet->fleet_class = FleetClass::find($fleet->vehicle_class);
                }

                // Return the fleet list
                return response()->json([
                    'status' => true,
                    'message' => 'Fleet list retrieved successfully.',
                    'response' => $fleets,
                ]);
            }

            // Return error if the company does not exist
            return response()->json([
                'status' => false,
                'message' => 'Company does not exist.',
            ]);
        } else {
            // Return error if user does not have permission
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to see the fleet list!',
            ]);
        }
    }


    public function delete_fleet(Request $request, $id)
    {

        $auth = Auth::user();

        // $company = Company::select('id')->where('user_id', $company_id)->first();

        if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) && ($auth->id == $request->company_id || $auth->fixed_role_id == 1)) {


            $fleet = Fleet::find($id);

            if ($fleet) {

                $fleet->active_status = 0;
                $fleet->is_deleted = 1;
                $fleet->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Fleet deleted successfully!'
                ]);
            } else {

                return response()->json([
                    'status' => false,
                    'message' => 'Fleet not found aginst ID'
                ]);
            }
        } else {

            return response()->json([
                'status' => false,
                'message' => 'You have not permission to Delete fleet!',
            ]);
        }
    }

    public function search_fleets(Request $request)
    {
        $auth = Auth::user();

        // Check permissions
        if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) && ($auth->id == $request->company_id || $auth->fixed_role_id == 1)) {

            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'company_id' => 'nullable|numeric',
                'vehicle_id' => 'nullable',
                'car_type_id' => 'nullable|numeric',

            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                return response()->json([
                    'success' => false,
                    'message' => $errors,
                ]);
            }


            // Build the query
            $query = Fleet::query();

            // Apply filters if provided
            if ($request->company_id) {

                if ($auth->fixed_role_id == 1) {

                    $company = Company::find($request->company_id);
                } else {

                    $user = User::find($request->company_id);
                    $company = Company::find($user->company_id);
                }
                // $company = Company::select('id')->where('user_id', $request->company_id)->first();
                if (!$company) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Company not found',
                    ]);
                }
                $query->where('company_id', $company->id);
            }

            if ($request->vehicle_id) {

                $query->where('vehicle_id', 'LIKE', '%' . $request->vehicle_id . '%');
            }

            if ($request->vehicle_type) {

                // $fleet_type = FleetType::find($request->car_type_id);
                $query->where('vehicle_type', $request->vehicle_type);
            }

            if ($auth->fixed_role_id == 1) {

                $results = $query->where('is_deleted', 0)->orderByRaw('active_status ASC')->orderBy('updated_at', 'desc')->paginate(6);
            } else {

                $results = $query->where('active_status', 1)->where('is_deleted', 0)->orderBy('updated_at', 'desc')->paginate(6);
            }

            if ($results->isNotEmpty()) {

                // Enrich each fleet with additional data
                foreach ($results as $fleet) {
                    $fleet->fleet_type = FleetType::find($fleet->vehicle_type);
                    $fleet->fleet_class = FleetClass::find($fleet->vehicle_class);
                    // $fleet->fleet_model = FleetModel::find($fleet->model);
                    // $fleet->fleet_manufacturer = FleetManufacturer::find($fleet->manufacturer);

                    if ($auth->fixed_role_id == 1) {
                        $fleet->company_name = Company::find($fleet->company_id)->company_name;
                    }
                }

                // Return the fleet list
                return response()->json([
                    'status' => true,
                    'message' => 'Fleet list retrieved successfully.',
                    'response' => $results,
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Fleet list not found!',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to search fleets!',
            ]); // Return HTTP 403 Forbidden
        }
    }

    public function fleet_list_without_auth(Request $request)
    {

        // Validate the request
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,id',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        // Get fleets for the company 
        $fleets =  Fleet::where('company_id', $request->company_id)->where('is_deleted', 0)->get();

        // If no fleets found, return a response indicating no permission
        if ($fleets->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Fleets does not exist',
            ]);
        }

        // Enrich each fleet with additional data
        foreach ($fleets as $fleet) {
            $fleet->fleet_type = FleetType::find($fleet->vehicle_type);
            $fleet->fleet_class = FleetClass::find($fleet->vehicle_class);
        }

        // Return the fleet list
        return response()->json([
            'status' => true,
            'message' => 'Fleet list retrieved successfully.',
            'response' => $fleets,
        ]);
    }
}

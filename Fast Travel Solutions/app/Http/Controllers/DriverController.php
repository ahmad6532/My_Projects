<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Driver;
use App\Models\DriverOtherDocument;
use App\Models\FleetClass;
use App\Models\FleetType;
use App\Models\FleetManufacturer;
use App\Models\FleetModel;
use App\Models\RouteFare;
use App\Traits\FileUploadTrait;

use function Ramsey\Uuid\v1;

class DriverController extends Controller
{
    use FileUploadTrait;

    // public function driver_signup(Request $request)
    // {
    //     // return $request; 

    //     // $validator = Validator::make($request->all(), [
    //     //     'first_name' => 'required|string|max:255',
    //     //     'last_name' => 'required|string|max:255',
    //     //     'driver_email' => 'required|string|email|max:255|unique:drivers',
    //     //     'phone' => 'required|string|max:15', // Adjust max length as needed
    //     //     // 'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     //     'address' => 'required|string|max:255',
    //     //     'national_insurance_num' => 'nullable|string|max:50', // Adjust max length as needed
    //     //     'driver_pco_license_num' => 'required|string|max:50',
    //     //     'vehicle_pco_license_num' => 'required|string|max:50',
    //     //     'fleet_type_id' => 'required|exists:fleet_types,id',
    //     //     'fleet_manufacturer_id' => 'required',
    //     //     'fleet_model_id' => 'required',
    //     //     'vehicle_reg_num' => 'required|string|max:20', // Adjust max length as needed
    //     //     'vehicle_color' => 'required|string|max:30',
    //     //     'mot' => 'required',
    //     //     // 'vehicle_insurance' => 'nullable|string|max:255',
    //     //     // 'vehicle_insurance_expiry' => 'nullable|date|after_or_equal:today',
    //     //     'driving_license_front_pic' => 'required|image|mimes:jpeg,png,jpg,gif',
    //     //     'driving_license_back_pic' => 'required|image|mimes:jpeg,png,jpg,gif',
    //     //     'driving_pco_license_pic' => 'required|image|mimes:jpeg,png,jpg,gif',
    //     //     'vehicle_pco_license_pic' => 'required|image|mimes:jpeg,png,jpg,gif',
    //     //     // 'vehicle_insurance_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     //     'logbook_pic' => 'required|image|mimes:jpeg,png,jpg,gif',
    //     //     'vehicle_mot_pic' => 'required|image|mimes:jpeg,png,jpg,gif',

    //     // ]);



    //     // if ($validator->fails()) {
    //     //     $errors = $validator->errors()->first(); // Get the first error message

    //     //     $response = [
    //     //         'success' => false,
    //     //         'message' => $errors,
    //     //     ];

    //     //     return response()->json($response); // Return JSON response with HTTP 
    //     // }

    //     $rules = [
    //         'first_name' => 'required|string|max:255',
    //         'last_name' => 'required|string|max:255',

    //         'phone' => 'required|string|max:15', // Adjust max length as needed
    //         // 'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    //         'address' => 'required|string|max:255',
    //         'national_insurance_num' => 'nullable|string|max:50', // Adjust max length as needed
    //         'driver_pco_license_num' => 'required|string|max:50',
    //         'vehicle_pco_license_num' => 'required|string|max:50',
    //         'fleet_type_id' => 'required|exists:fleet_types,id',
    //         'fleet_manufacturer_id' => 'required',
    //         'fleet_model_id' => 'required',
    //         'vehicle_reg_num' => 'required|string|max:20', // Adjust max length as needed
    //         'vehicle_color' => 'required|string|max:30',
    //         'mot' => 'required',
    //         // 'vehicle_insurance' => 'nullable|string|max:255',
    //         // 'vehicle_insurance_expiry' => 'nullable|date|after_or_equal:today',
    //         'driving_license_front_pic' => 'required|image|mimes:jpeg,png,jpg,gif',
    //         'driving_license_back_pic' => 'required|image|mimes:jpeg,png,jpg,gif',
    //         'driving_pco_license_pic' => 'required|image|mimes:jpeg,png,jpg,gif',
    //         'vehicle_pco_license_pic' => 'required|image|mimes:jpeg,png,jpg,gif',
    //         // 'vehicle_insurance_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //         'logbook_pic' => 'required|image|mimes:jpeg,png,jpg,gif',
    //         'vehicle_mot_pic' => 'required|image|mimes:jpeg,png,jpg,gif',
    //     ];

    //     if (is_null($request->driver_id)) {
    //         $rules['driver_email'] = 'required|string|email|max:255|unique:drivers';
    //     }

    //     $validator = Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //         $errors = $validator->errors()->first(); // Get the first error message

    //         $response = [
    //             'success' => false,
    //             'message' => $errors,
    //         ];

    //         return response()->json($response); // Return JSON response with HTTP 
    //     }

    //     $type = FleetType::find($request->fleet_type_id);
    //     // $manufacturer = FleetManufacturer::find($request->fleet_manufacturer_id);
    //     // $model = FleetModel::find($request->fleet_model_id);

    //     // if ($type && $manufacturer && $model) {

    //     if ($type) {

    //         if ($request->hasFile('profile_picture')) {

    //             $imagePath = $this->handleFileUpload($request->file('profile_picture'));
    //         } else {

    //             $imagePath = NULL;
    //         }

    //         // $driving_license_front_pic = $request->file('driving_license_front_pic')->store('images', 'public');
    //         $driving_license_front_pic = $this->handleFileUpload($request->file('driving_license_front_pic'));

    //         // $driving_license_back_pic = $request->file('driving_license_back_pic')->store('images', 'public');
    //         $driving_license_back_pic = $this->handleFileUpload($request->file('driving_license_back_pic'));

    //         // $driving_pco_license_pic = $request->file('driving_pco_license_pic')->store('images', 'public');
    //         $driving_pco_license_pic = $this->handleFileUpload($request->file('driving_pco_license_pic'));

    //         // $vehicle_pco_license_pic = $request->file('vehicle_pco_license_pic')->store('images', 'public');
    //         $vehicle_pco_license_pic = $this->handleFileUpload($request->file('vehicle_pco_license_pic'));

    //         if ($request->hasFile('vehicle_insurance_pic')) {

    //             // $vehicle_insurance_pic = $request->file('vehicle_insurance_pic')->store('images', 'public');
    //             $vehicle_insurance_pic = $this->handleFileUpload($request->file('vehicle_insurance_pic'));
    //         } else {

    //             $vehicle_insurance_pic = NULL;
    //         }

    //         // $logbook_pic = $request->file('logbook_pic')->store('images', 'public');
    //         $logbook_pic = $this->handleFileUpload($request->file('logbook_pic'));

    //         // $vehicle_mot_pic = $request->file('vehicle_mot_pic')->store('images', 'public');
    //         $vehicle_mot_pic = $this->handleFileUpload($request->file('vehicle_mot_pic'));




    //         $driver = Driver::create([
    //             'first_name' => $request->first_name,
    //             'last_name' => $request->last_name,
    //             'driver_email' => $request->driver_email,
    //             'phone' => $request->phone,
    //             'profile_picture' => $imagePath,
    //             'address' => $request->address,
    //             'national_insurance_num' => $request->national_insurance_num,
    //             'driver_pco_license_num' => $request->driver_pco_license_num,
    //             'vehicle_pco_license_num' => $request->vehicle_pco_license_num,
    //             'fleet_type_id' => $request->fleet_type_id,
    //             'fleet_manufacturer_id' => $request->fleet_manufacturer_id,
    //             'fleet_model_id' => $request->fleet_model_id,
    //             'vehicle_reg_num' => $request->vehicle_reg_num,
    //             'vehicle_color' => $request->vehicle_color,
    //             'mot' => $request->mot,
    //             'vehicle_insurance' => $request->vehicle_insurance,
    //             'vehicle_insurance_expiry' => $request->vehicle_insurance_expiry,
    //             'driving_license_front_pic' => $driving_license_front_pic,
    //             'driving_license_back_pic' => $driving_license_back_pic,
    //             'driving_pco_license_pic' => $driving_pco_license_pic,
    //             'vehicle_pco_license_pic' => $vehicle_pco_license_pic,
    //             'vehicle_insurance_pic' => $vehicle_insurance_pic,
    //             'logbook_pic' => $logbook_pic,
    //             'vehicle_mot_pic' => $vehicle_mot_pic,
    //             // 'other_document_pic' => $other_document_pic,

    //         ]);

    //         $images = $request->file('other_document_pic');

    //         if ($images) {
    //             foreach ($images as $img) {
    //                 if ($img instanceof \Illuminate\Http\UploadedFile) {
    //                     // Handle file upload
    //                     $image_name = $this->handleFileUpload($img);

    //                     // Save file path to database
    //                     $post_images = new DriverOtherDocument();
    //                     $post_images->driver_id = $driver->id;
    //                     $post_images->other_document_pic = $image_name;
    //                     $post_images->save();
    //                 }
    //             }
    //         }

    //         $other_images = DriverOtherDocument::where('driver_id', $driver->id)->get();

    //         $driver->other_images =  $other_images;

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Driver created successfully',
    //             'response' => $driver
    //         ]);
    //     } else {

    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Invalid fleet type, manufacturer, or model',
    //         ]);
    //     }
    // }

    public function driver_signup(Request $request)
    {
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'national_insurance_num' => 'nullable|string|max:50',
            'driver_pco_license_num' => 'required|string|max:50',
            'vehicle_pco_license_num' => 'required|string|max:50',
            'fleet_type_id' => 'required|exists:fleet_types,id',
            'fleet_manufacturer_id' => 'required',
            'fleet_model_id' => 'required',
            'vehicle_reg_num' => 'required|string|max:20',
            'vehicle_color' => 'required|string|max:30',
            'mot' => 'required',
            // 'driving_license_front_pic' => 'required|image|mimes:jpeg,png,jpg,gif',
            // 'driving_license_back_pic' => 'required|image|mimes:jpeg,png,jpg,gif',
            // 'driving_pco_license_pic' => 'required|image|mimes:jpeg,png,jpg,gif',
            // 'vehicle_pco_license_pic' => 'required|image|mimes:jpeg,png,jpg,gif',
            // 'logbook_pic' => 'required|image|mimes:jpeg,png,jpg,gif',
            // 'vehicle_mot_pic' => 'required|image|mimes:jpeg,png,jpg,gif',
        ];

        if (is_null($request->driver_id)) {
            $rules['driver_email'] = 'required|string|email|max:255|unique:drivers';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        if (is_null($request->driver_id)) {
            $driver = new Driver();
        } else {
            $driver = Driver::find($request->driver_id);
            if (!$driver) {
                return response()->json([
                    'status' => false,
                    'message' => 'Driver not found',
                ]);
            }
        }

        $driver->first_name = $request->first_name;
        $driver->last_name = $request->last_name;
        if (is_null($request->driver_id)) {
            $driver->driver_email = $request->driver_email;
        }
        $driver->phone = $request->phone;
        $driver->address = $request->address;
        $driver->national_insurance_num = $request->national_insurance_num;
        $driver->driver_pco_license_num = $request->driver_pco_license_num;
        $driver->vehicle_pco_license_num = $request->vehicle_pco_license_num;
        $driver->fleet_type_id = $request->fleet_type_id;
        $driver->fleet_manufacturer_id = $request->fleet_manufacturer_id;
        $driver->fleet_model_id = $request->fleet_model_id;
        $driver->vehicle_reg_num = $request->vehicle_reg_num;
        $driver->vehicle_color = $request->vehicle_color;
        $driver->mot = $request->mot;
        $driver->vehicle_insurance = $request->vehicle_insurance;
        $driver->vehicle_insurance_expiry = $request->vehicle_insurance_expiry;

        if ($request->hasFile('profile_picture')) {

            $driver->profile_picture = $this->handleFileUpload($request->file('profile_picture'));

        }
        if ($request->hasFile('driving_license_front_pic')) {

            $driver->driving_license_front_pic = $this->handleFileUpload($request->file('driving_license_front_pic'));

        }
        if ($request->hasFile('driving_license_back_pic')) {

            $driver->driving_license_back_pic = $this->handleFileUpload($request->file('driving_license_back_pic'));

        }
        if ($request->hasFile('driving_pco_license_pic')) {

            $driver->driving_pco_license_pic = $this->handleFileUpload($request->file('driving_pco_license_pic'));

        }
        if ($request->hasFile('vehicle_pco_license_pic')) {

            $driver->vehicle_pco_license_pic = $this->handleFileUpload($request->file('vehicle_pco_license_pic'));

        }
        if ($request->hasFile('vehicle_insurance_pic')) {

            $driver->vehicle_insurance_pic = $this->handleFileUpload($request->file('vehicle_insurance_pic'));

        }
        if ($request->hasFile('logbook_pic')) {

            $driver->logbook_pic = $this->handleFileUpload($request->file('logbook_pic'));

        }
        if ($request->hasFile('vehicle_mot_pic')) {

            $driver->vehicle_mot_pic = $this->handleFileUpload($request->file('vehicle_mot_pic'));

        }

        // Handle file uploads
        // $driver->profile_picture = $this->handleFileUpload($request->file('profile_picture'));
        // $driver->driving_license_front_pic = $this->handleFileUpload($request->file('driving_license_front_pic'));
        // $driver->driving_license_back_pic = $this->handleFileUpload($request->file('driving_license_back_pic'));
        // $driver->driving_pco_license_pic = $this->handleFileUpload($request->file('driving_pco_license_pic'));
        // $driver->vehicle_pco_license_pic = $this->handleFileUpload($request->file('vehicle_pco_license_pic'));
        // $driver->vehicle_insurance_pic = $request->hasFile('vehicle_insurance_pic') ? $this->handleFileUpload($request->file('vehicle_insurance_pic')) : null;
        // $driver->logbook_pic = $this->handleFileUpload($request->file('logbook_pic'));
        // $driver->vehicle_mot_pic = $this->handleFileUpload($request->file('vehicle_mot_pic'));

        $driver->save();

        // Handle multiple other document pictures
        if ($request->hasFile('other_document_pic')) {
            foreach ($request->file('other_document_pic') as $img) {
                if ($img instanceof \Illuminate\Http\UploadedFile) {
                    $image_name = $this->handleFileUpload($img);
                    $document = new DriverOtherDocument();
                    $document->driver_id = $driver->id;
                    $document->other_document_pic = $image_name;
                    $document->save();
                }
            }
        }

        $other_images = DriverOtherDocument::where('driver_id', $driver->id)->get();
        $driver->other_images = $other_images;

        return response()->json([
            'status' => true,
            'message' => is_null($request->driver_id) ? 'Driver created successfully' : 'Driver updated successfully',
            'response' => $driver,
        ]);
    }


    public function fleet_types(Request $request)
    {


        $fleet_types = FleetType::where('is_deleted', 0)->get();

        if ($fleet_types) {

            if ($request->total_distance) {

                $distance = intval($request->total_distance);

                $fleetFares = RouteFare::where('min_distance', '<=', $distance)
                    ->where('max_distance', '>=', $distance)
                    ->get();

                $fares = [];

                foreach ($fleetFares as $fare) {
                    // Perform your fare calculation logic here

                    $fleet_type = FleetType::find($fare->fleet_type_id);

                    $fares[] = [
                        'fleet_type' => $fleet_type,
                        'fare' => round($fare->ride_fare * $distance, 2), // Assuming 'fare' is the column storing the fare amount
                    ];
                }

                if ($fares) {

                    return response()->json([
                        'status' => true,
                        'message' => 'Fares list According fleet types!',
                        'response' => $fares,

                    ]);
                } else {

                    return response()->json([
                        'status' => false,
                        'message' => 'Fleet Types does not exist! ',
                    ]);
                }
            } else {

                return response()->json([
                    'status' => true,
                    'message' => 'Fleet types list!',
                    'response' => $fleet_types
                ]);
            }
        } else {


            return response()->json([
                'status' => false,
                'message' => 'Fleet types does not exist!',
            ]);
        }
    }


    public function fleet_manufacturer()
    {

        $fleet_manufacturer = FleetManufacturer::all();

        if ($fleet_manufacturer) {

            return response()->json([
                'status' => true,
                'message' => 'Fleet manufacturer list!',
                'response' => $fleet_manufacturer
            ]);
        } else {

            return response()->json([
                'status' => false,
                'message' => 'Fleet manufacturer does not exist!',
            ]);
        }
    }

    public function fleet_models()
    {

        $fleet_models = FleetModel::all();

        if ($fleet_models) {

            return response()->json([
                'status' => true,
                'message' => 'Fleet models list!',
                'response' => $fleet_models
            ]);
        } else {

            return response()->json([
                'status' => false,
                'message' => 'Fleet models does not exist!',
            ]);
        }
    }

    public function fleet_classes()
    {

        $fleet_models = FleetClass::all();

        if ($fleet_models) {

            return response()->json([
                'status' => true,
                'message' => 'Fleet classes list!',
                'response' => $fleet_models
            ]);
        } else {

            return response()->json([
                'status' => false,
                'message' => 'Fleet classes does not exist!',
            ]);
        }
    }
}

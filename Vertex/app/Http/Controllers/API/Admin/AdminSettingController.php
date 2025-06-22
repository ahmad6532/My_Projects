<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Leave_setting;
use Carbon\Carbon;
use App\Models\Location;
use App\Models\Leave;
use App\Models\Leave_Type;
use App\Models\CompanySetting;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use Illuminate\Support\Facades\DB;

class AdminSettingController extends BaseController
{

    public function saveCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|unique:companies,company_name',
            'email' => 'required|email|unique:companies,email',
            'contact_person' => 'required',
            'country_id' => 'required',
            'city_id' => 'required',
            'phone' => 'required|numeric|unique:companies|min:11',
            'tel' => 'nullable|numeric|unique:companies|min:11',
            'address' => 'required|max:255',
            // 'state' => 'required'
        ], [
            'company_name.unique' => 'The company name is already taken.',
            'phone.unique' => 'Phone is already taken.',
            'email.unique' => 'The email is already taken.'
        ]);
        if ($validator->fails()) {
            return $this->sendError([], $validator->errors()->first(), 400);
        }
        $company = new company;
        $company->company_name = $request->company_name;
        $company->email = $request->email;
        $company->phone = $request->phone;
        $company->tel = $request->tel;
        $company->contact_person = $request->contact_person;
        $company->country_id = $request->country_id;
        $company->city_id = $request->city_id;
        $company->address = $request->address;
        $company->website = $request->website;

        $company->save();
        $msg = 'Company"' . ucwords($request->company_name) . '" Added Successfully';
        createLog('company_action', $msg);

        if ($company) {
            return $this->sendResponse($company, 'Company added successfully!', 200);
        } else {
            return $this->sendResponse($company, 'Compnay form not submited');
        }
    }

    public function updateCompany(Request $request)
    {
        $id = $request->company_id;
        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'email' => 'required|email|unique:companies,email,' . $id,
            'contact_person' => 'required',
            'address' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return $this->sendError([], $validator->errors()->first(), 400);
        }

        $company = Company::where('id', $id)->where('is_deleted', '0')->first();
        if (!$company) {
            return $this->sendError([], 'Company not found or has been deleted.', 404);
        }

        if ($request->hasFile('company_logo')) {
            $uploadPath = 'assets/images/companies/';
            if ($company->logo) {
                $oldLogoPath = public_path($company->logo);
                if (file_exists($oldLogoPath)) {
                    unlink($oldLogoPath);
                }
            }
            $file = $request->file('company_logo');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move($uploadPath, $filename);
            $image = $uploadPath . $filename;
        } else {
            $image = $company->logo;
        }

        $company->company_name = $request->company_name;
        $company->email = $request->email;
        $company->phone = $request->phone;
        $company->tel = $request->tel;
        $company->contact_person = $request->contact_person;
        $company->country_id = $request->country_id;
        $company->city_id = $request->city_id;
        $company->address = $request->address;
        $company->website = $request->website;
        $company->logo = $image;
        // $company->state = $request->state;
        $company->save(); // Changed from update() to save() to handle both update and create scenarios

        $msg = 'Company "' . ucwords($request->company_name) . '" Updated Successfully';
        createLog('company_action', $msg);

        return $this->sendResponse($company, 'Company updated successfully!', 200);
    }


    public function deleteCompany(Request $request)
    {
        $id = $request->company_id;
        $company = Company::where('id',$id)->where('is_deleted','0')->first();
        if($company){
            $company->is_deleted = '1';
            $company->update();
            return $this->sendResponse($company, 'Company deleted successfully!', 200);
            $msg = 'Company"' . ucwords($company->company_name) . '" Deleted Successfully';
            createLog('company_action', $msg);
        }else{
            return $this->sendResponse($company, 'Data not found!', 200);
        }
    }

    public function editCompany(Request $request)
    {
        $query = DB::table('companies')
            ->leftJoin('com_countries', 'com_countries.country_id', '=', 'companies.country_id')
            ->leftJoin('com_cities', 'com_cities.city_id', '=', 'companies.city_id')
            ->select(
                'companies.id',
                'companies.company_name',
                'companies.phone',
                'companies.country_id',
                'companies.city_id',
                'companies.website',
                'companies.logo',
                'companies.address',
                'companies.email',
                'companies.contact_person',
                'companies.tel',
                'com_countries.country_name as country_name',
                'com_cities.city_name as city_name'
            )
            ->where('companies.is_deleted', '0')
            ->where('companies.is_active', '1');

        // Apply search filter if 'search_by' is provided
        if ($request->has('search_by')) {
            $searchBy = $request->search_by;
            $query->where(function ($q) use ($searchBy) {
                $q->where('companies.company_name', 'LIKE', "%{$searchBy}%")
                ->orWhere('companies.phone', 'LIKE', "%{$searchBy}%");
            });
        }

        // Check if company_id is provided
        if ($request->has('company_id')) {
            $id = $request->company_id;
            $company = $query->where('companies.id', $id)->first();

            if (!$company) {
                return $this->sendError([], 'Data not found!', 404);
            }

            $data = [
                'company_name' => $company->company_name,
                'id' => $company->id,
                'country_id' => $company->country_id,
                'city_id' => $company->city_id,
                'country_name' => $company->country_name,
                'city_name' => $company->city_name,
                'phone' => $company->phone,
                'website' => $company->website,
                'logo' => $company->logo,
                'address' => $company->address,
                'contact_person' => $company->contact_person,
                'tel' => $company->tel,
                'email' => $company->email,
            ];

            return $this->sendResponse(['company' => $data], 'Company fetched successfully!', 200);
        } else {
            $perPage = $request->get('per_page', 10);
            $companies = $query->paginate($perPage);

            if ($companies->isEmpty()) {
                return $this->sendError([], 'Data not found!', 404);
            }

            $data = $companies->map(function($company) {
                return [
                    'company_name' => $company->company_name,
                    'id' => $company->id,
                    'country_id' => $company->country_id,
                    'city_id' => $company->city_id,
                    'country_name' => $company->country_name,
                    'city_name' => $company->city_name,
                    'phone' => $company->phone,
                    'website' => $company->website,
                    'logo' => $company->logo,
                    'address' => $company->address,
                    'contact_person' => $company->contact_person,
                    'tel' => $company->tel,
                    'email' => $company->email,
                ];
            });

            $pagination = [
                'current_page' => $companies->currentPage(),
                'companies' => $data,
                'last_page' => $companies->lastPage(),
                'per_page' => $companies->perPage(),
                'total' => $companies->total(),
                'next_page_url' => $companies->nextPageUrl(),
                'prev_page_url' => $companies->previousPageUrl(),
            ];

            return response()->json([
                'status' => 1,
                'message' => 'Companies fetched successfully!',
                'details' => $pagination
            ], 200);
        }
    }


    public function addCompany()
    {
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);

        if ($user_role == 1) {
            $companies = Company::where('is_deleted', '0')->orderBy('company_name', 'asc')->get();
        } else {
            $companies = Company::whereIn('id', $user_company_id)
                ->orderBy('company_name', 'asc')
                ->where('is_deleted', '0')
                ->get();
        }
        return view('company.add_company', compact('companies'));
    }

    public function getbranch(Request $request)
    {
        // user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);

        if ($user_role == '1') {
            $branches = Location::where('is_deleted', '0')
                ->where('company_id', $request->company_id)
                ->get();
        } else {
            $branches = Location::where('is_deleted', '0')
                ->where('company_id', $request->company_id)
                ->whereIn('id', $user_branch_id)
                ->get();
        }
        return response()->json(['success' => true, 'data' => $branches]);
    }

    public function getMultipleBranches(Request $request)
    {
        $branches = Location::where('is_deleted', '0')->whereIn('company_id', $request->company_id)->get();
        return response()->json(['success' => true, 'data' => $branches]);
    }
    public function getMultiBranches(Request $request)
    {
        if ($request->company_id) {
            $branches = Location::where('is_deleted', '0')->whereIn('company_id', $request->company_id)->get();
        }
        if ($request->company_edit) {
            $branches = Location::where('is_deleted', '0')->whereIn('company_id', $request->company_edit)->get();
        }
        return response()->json(['success' => true, 'data' => $branches]);
    }

    public function saveCompanyConfiguration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required|unique:company_settings,branch_id',
            'start_time' => 'required',
            'selectedDays' => 'required',
            'end_time' => 'required',
            'lunch_start_time' => 'required',
            'lunch_end_time' => 'required',
            'late_time' => 'required',
            'flexible_time' => 'required',
        ], [
            'branch_id.unique' => 'The Location Settings Already Taken.'
        ]);

        if ($validator->fails()) {
            return $this->sendError([], $validator->errors()->first(), 400);
        }
        $company_setting = new CompanySetting;
        $company_setting->company_id = $request->company_id;
       // $company_setting->setting_name = $request->setting_name;
        $company_setting->branch_id = $request->branch_id;
        $company_setting->start_time = $request->start_time;
        $company_setting->end_time = $request->end_time;
        $company_setting->lunch_start_time = $request->lunch_start_time;
        $company_setting->lunch_end_time = $request->lunch_end_time;
        $company_setting->late_time = $request->late_time;
        $company_setting->late_limit = $request->late_limit;
        $company_setting->flexible_time = $request->flexible_time;
        $company_setting->half_day = $request->half_day;
        $selectedDays = $request->input('selectedDays');
        $daysString = implode(',', $selectedDays);
        $company_setting->days = $daysString;
        $company_setting->save();
        // $company_setting = CompanySetting::where('id',$request->company_id)->first();
        $company = Company::where('id', $request->company_id)->first();
        $branch = Location::where('id', $request->branch_id)->first();

        $msg = 'Added New Configuration of Company "' . $company->company_name . '" and Location "' . $branch->branch_name . '" Successfully';
        createLog('global_action', $msg);

        if ($company_setting) {
            return $this->sendResponse($company_setting, 'Company configurations save successfully!', 200);
        } else {
            return $this->sendError($company_setting, 'Form not submited', 400);
        }
    }

    public function editCompanyConfiguration(Request $request)
    {
        // User information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);

        // Base query
        $query = DB::table('company_settings')->leftJoin('companies', 'company_settings.company_id', '=', 'companies.id')
            ->leftJoin('locations', 'company_settings.branch_id', '=', 'locations.id')
            ->select(
                'company_settings.id as setting_id',
                'company_settings.company_id',
                'company_settings.branch_id',
                'company_settings.start_time',
                'company_settings.end_time',
                'company_settings.days',
                'company_settings.lunch_start_time',
                'company_settings.lunch_end_time',
                'company_settings.late_time',
                'company_settings.late_limit',
                'company_settings.half_day',
                'company_settings.flexible_time',
                'company_settings.is_deleted',
                'companies.company_name',
                'locations.branch_name'
            )
            ->where('company_settings.is_deleted', '0');

        // Apply search filter if 'search_by' is provided
        if ($request->has('search_by')) {
            $searchBy = $request->search_by;
            $query->where(function ($q) use ($searchBy) {
                $q->where('companies.company_name', 'LIKE', "%{$searchBy}%")
                    ->orWhere('locations.branch_name', 'LIKE', "%{$searchBy}%");
            });
        }

        if ($request->has('configuration_id')) {
            $id = $request->configuration_id;
            $companyConfiguration = $query->where('company_settings.id', $id)->first();

            if ($companyConfiguration) {
                // Date Conversion
                $start = new \DateTime($companyConfiguration->start_time);
                $end = new \DateTime($companyConfiguration->end_time);
                $interval = $start->diff($end);
                $working_hours = $interval->h + ($interval->i / 60);
                $companyConfiguration->working_hours = $working_hours;

                // Days Counts
                $days = explode(',', $companyConfiguration->days);
                $working_days = count($days);
                $companyConfiguration->working_days = $working_days;

                return $this->sendResponse($companyConfiguration, 'Company configuration fetched successfully!', 200);
            } else {
                return $this->sendResponse(null, 'Data not found!', 200);
            }
        } else {
            // Apply pagination if 'per_page' is provided
            $perPage = $request->get('per_page', 10); // Default to 10 items per page if not provided
            $companyConfigurations = $query->paginate($perPage);

            if ($companyConfigurations->isEmpty()) {
                return $this->sendError([], 'Data not found!', 404);
            }

            foreach ($companyConfigurations as $companyConfiguration) {
                // Date Conversion
                $start = new \DateTime($companyConfiguration->start_time);
                $end = new \DateTime($companyConfiguration->end_time);
                $interval = $start->diff($end);
                $working_hours = $interval->h + ($interval->i / 60);
                $companyConfiguration->working_hours = $working_hours;

                // Days Counts
                $days = explode(',', $companyConfiguration->days);
                $working_days = count($days);
                $companyConfiguration->working_days = $working_days;
            }

            return $this->sendResponse($companyConfigurations, 'Company configurations fetched successfully!', 200);
        }
    }

    public function updateCompanyConfiguration(Request $request)
    {
        $id = $request->configuration_id;
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
            'start_time' => 'required',
            'selectedDays' => 'required',
            'end_time' => 'required',
            'lunch_start_time' => 'required',
            'lunch_end_time' => 'required',
            'late_time' => 'required',
            'flexible_time' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError([], $validator->errors(), 400);
        }
        $companyConfiguration = CompanySetting::findOrFail($id);
        $companyConfiguration->company_id = $request->company_id;
        //$companyConfiguration->setting_name = $request->setting_name;
        $companyConfiguration->branch_id = $request->branch_id;
        $companyConfiguration->start_time = $request->start_time;
        $companyConfiguration->end_time = $request->end_time;
        $companyConfiguration->lunch_start_time = $request->lunch_start_time;
        $companyConfiguration->lunch_end_time = $request->lunch_end_time;
        $companyConfiguration->late_time = $request->late_time;
        $companyConfiguration->late_limit = $request->late_limit;
        $companyConfiguration->flexible_time = $request->flexible_time;
        $companyConfiguration->half_day = $request->half_day;
        $selectedDays = $request->input('selectedDays');
        $daysString = implode(',', $selectedDays);
        $companyConfiguration->days = $daysString;
        $companyConfiguration->update();
        $company = Company::where('id', $request->company_id)->first();
        $branch = Location::where('id', $request->branch_id)->first();

        $msg = 'Updated Configuration of Company "' . $company->company_name . '" and Location "' . $branch->branch_name . '" Successfully';
        createLog('global_action', $msg);

        if ($companyConfiguration) {
            return $this->sendResponse($companyConfiguration, 'Company configuration update successfuly!', 200);
        } else {
            return $this->sendResponse($companyConfiguration, 'Data not found!', 200);
        }
    }

    public function deleteCompanyConfiguration(Request $request)
    {
        $id = $request->configuration_id;
        $deleteCompanyConfiguration = CompanySetting::findOrFail($id);
        $deleteCompanyConfiguration->is_deleted = '1';
        $deleteCompanyConfiguration->update();

        // $company = CompanySetting::where('id',$company_setting->company_id)->first();
        $company = Company::where('id', $deleteCompanyConfiguration->company_id)->first();
        $branch = Location::where('id', $deleteCompanyConfiguration->branch_id)->first();

        $msg = 'Deleted Configuration of Company "' . $company->company_name . '" and Location "' . $branch->branch_name . '" Successfully';
        createLog('global_action', $msg);
        if ($deleteCompanyConfiguration) {
            return $this->sendResponse($deleteCompanyConfiguration, 'Company configuration delete successfully!', 200);
        } else {
            return $this->sendResponse($deleteCompanyConfiguration, 'Data not found', 200);
        }
    }

    public function companyConfiguration(Request $request)
    {
        //user information
       // dd('ok');
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);
        $perPage = isset($request->per_page) ? $request->per_page : '20';
        $searchBy = isset($request->search_by) ? $request->search_by : null;

        $query = DB::table('company_settings')->leftjoin('companies', 'company_settings.company_id', '=', 'companies.id')
        ->leftjoin('locations', 'company_settings.branch_id', '=', 'locations.id')
        ->select('company_settings.*','companies.company_name', 'locations.branch_name');

        if ($user_role != 1) {
            $companySettings = $query->whereIn('company_settings.company_id', $user_company_id)
            ->whereIn('company_settings.branch_id', $user_branch_id);
        }

        if($searchBy){
            $query->where(function($query) use($searchBy){
                $query->where('companies.company_name','LIKE','%'.$searchBy.'%')
                //->orWhere('company_settings.setting_name','LIKE','%'.$searchBy.'%')
                ->orWhere('locations.branch_name','LIKE','%'.$searchBy.'%');
            });
        }

        $companySettings = $query->where('company_settings.is_deleted', '0')
        ->orderBy('company_settings.id','DESC')
        ->paginate($perPage);

        foreach ($companySettings as $setting) {

            $end_time = isset($setting->end_time) ? Carbon::parse($setting->end_time)->format('H:iA') : null;
            $start_time = isset($setting->start_time) ? Carbon::parse($setting->start_time)->format('H:iA') : null;
            $lunch_start_time = isset($setting->lunch_start_time) ? Carbon::parse($setting->lunch_start_time)->format('H:iA') : null;
            $lunch_end_time = isset($setting->lunch_end_time) ? Carbon::parse($setting->lunch_end_time)->format('H:iA') : null;

            unset($setting->end_time,$setting->start_time,$setting->lunch_start_time, $setting->lunch_end_time);

            $setting->working_hours = $start_time . ' - ' . $end_time;
            $setting->lunch_time = $lunch_start_time . ' - ' . $lunch_end_time;

            $daysString = $setting->days;
            $diffInDays = explode(",", $daysString);
            $diffInDays = count($diffInDays);
            $setting->working_days = $diffInDays. ' Days';
        }
        if ($companySettings) {
            return $this->sendResponse($companySettings, 'Company configuration fetched successfully!', 200);
        } else {
            return $this->sendResponse($companySettings, 'Data not found', 200);
        }
    }

    public function leaveSetting(Request $request)
    {
        $selectedBranch = 'all';
        $searchBy = $request->search_by;
        $perPage = isset($request->per_page) ? $request->per_page : 20;
        $leave_settings = Leave_setting::join('companies', 'leave-settings.company_id', 'companies.id')
        ->where(function ($query) use ($searchBy) {
            $query->where('companies.company_name', 'LIKE', '%' . $searchBy . '%');
        })->select('leave-settings.id', 'companies.company_name', 'leave-settings.annual_days', 'leave-settings.casual_days','leave-settings.sick_days', 'leave-settings.maternity_days')
        ->where('leave-settings.is_deleted', '0')
        ->where('leave-settings.is_active', '1')
        ->paginate($perPage);
        if (count($leave_settings) > 0) {
            return $this->sendResponse($leave_settings, 'Leaves fetched successfully!', 200);
        } else {
            return $this->sendResponse($leave_settings, 'Data not found!', 200);
        }
    }


    // public function leaveSetting(Request $request)
    // {
    //     $searchBy = $request->input('search_by', '');
    //     $perPage = $request->input('per_page', 20);

    //     $leave_settings = Leave_setting::with('company')
    //         ->whereHas('company', function ($query) use ($searchBy) {
    //             if ($searchBy) {
    //                 $query->where('company_name', 'LIKE', '%' . $searchBy . '%');
    //             }
    //         })
    //         ->where('is_deleted', '0')
    //         ->where('is_active', '1')
    //         ->paginate($perPage);


    //     if ($leave_settings->count() > 0) {
    //         return $this->sendResponse($leave_settings, 'Leaves fetched successfully!', 200);
    //     } else {
    //         return $this->sendResponse($leave_settings, 'Data not found!', 200);
    //     }
    // }


    public function saveLeaveSetting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|unique:leave-settings',
            //'annualDays' => 'required',
            // 'annual_carry_forward' => 'required',
            // 'casual_numberOfDays' => 'required',
            // 'casual_carry_forward' => 'required',
            // 'sick_leave_numberOfDays' => 'required',
            // 'maternity_numberOfDays' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError([], $validator->errors()->first(), 400);
        }
            $leave = new Leave_setting();
            $leave->company_id = $request->company_id;
            $leave->annual_days = $request->annual_days;
            $leave->casual_days = $request->casual_days;
            $leave->sick_days = $request->sick_days;
            $leave->maternity_days = $request->maternity_days;
            $leave->annual_before_days = $request->annual_before_days;
            $leave->casual_before_days = $request->casual_before_days;
            $leave->annual_carry_forward = 0;
            $leave->annual_forward_days = 0;
            $leave->casual_carry_forward = 0;
            $leave->casual_forward_days = 0;
            $leave->save();
            $company_action = Company::where('id', $request->company_id)->first();
        if ($company_action) {
            $msg = 'Company"' . ucwords(isset($company_action->company_name) ?? 'N/A') . '"Leave Setupt Added Successfully';
            createLog('company_action', $msg);
        }
        if ($leave) {
            return $this->sendResponse($leave, 'Leave settings added successfully!', 200);
        } else {
            return $this->sendError([], 'Form not submitted!', 302);
        }
    }


    public function editLeaveSetting(Request $request)
    {
        $id = $request->id;
        $edit_leave = Leave_setting::where('id', $id)->first();

        if (!$edit_leave) {
            return $this->sendResponse(null, 'Leave setting not found!', 404);
        }

        $company = Company::where('id', $edit_leave->company_id)->first();

        $edit_leave_formatted = [
            'leave_setting' => $edit_leave,
            'company_name' => $company ? $company->company_name : null,
        ];

        if ($edit_leave) {
            return $this->sendResponse($edit_leave_formatted, 'Leave setting fetched successfully!', 200);
        } else {
            return $this->sendResponse($edit_leave, 'Data not found!', 200);
        }
    }

    public function updateLeaveSetting(Request $request)
    {
        $id = $request->id;
        $value = $request->company_id;
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            // 'annualDays' => 'required',
            // 'annual_carry_forward' => 'required',
            // 'casual_numberOfDays' => 'required',
            // 'sick_leave_numberOfDays' => 'required',
            // 'maternity_numberOfDays' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError([], $validator->errors()->first(), 400);
        }
        $leave = Leave_setting::where('id', $request->id)->first();
        if ($leave) {
            $leave->company_id = $request->company_id;
            $leave->annual_days = $request->annual_days;
            $leave->casual_days = $request->casual_days;
            $leave->sick_days = $request->sick_days;
            $leave->maternity_days = $request->maternity_days;
            $leave->annual_before_days = $request->annual_before_days;
            $leave->casual_before_days = $request->casual_before_days;
            $leave->annual_carry_forward = 0;
            $leave->annual_forward_days = 0;
            $leave->casual_carry_forward = 0;
            $leave->casual_forward_days = 0;
            $company_action = Company::where('id', $leave->company_id)->first();
            $msg = 'Company"' . ucwords($company_action->company_name) . '"Leave Setupt Updated Successfully';
            createLog('company_action', $msg);
            $leave->update();
            return $this->sendResponse($leave, 'Leave setting update successfully!', 200);
        } else {
            return $this->sendResponse($leave, 'Data not found!', 200);
        }
    }

    public function deleteLeaveSetting(Request $request)
    {
        $leave = Leave_setting::where('id',$request->id)->first();
        $company_action = Company::where('id', isset($leave->company_id))->first();
        if($leave){
            $delete = $leave->delete();
            if($delete){
                if($company_action){
                    $msg = 'Company"' . ucwords(isset($company_action->company_name) ? $company_action->company_name : "N/A") . '"Leave Setupt Deleted Successfully';
                    createLog('company_action', $msg);
                }
                return $this->sendResponse([],'Leave settings delete successfully!',200);
            }
        }else{
            return $this->sendResponse([],'Data not found!',200);
        }
    }
    public function getOfficeDetails(Request $request)
    {
        $branchId = $request->input('branch_id');
        $searchTerm = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = Location::leftJoin('companies', 'companies.id', '=', 'locations.company_id')
            ->leftJoin('company_settings', 'company_settings.branch_id', '=', 'locations.id')
            ->leftJoin('com_countries', 'com_countries.country_id', '=', 'locations.country_id')
            ->leftJoin('com_cities', 'com_cities.city_id', '=', 'locations.city_id')
            ->where('locations.is_deleted', '=', '0')
            ->select('locations.*', 'companies.company_name', 'com_countries.country_name', 'com_cities.city_name', 'company_settings.days');

        if (!empty($branchId)) {
            $query->where('locations.id', $branchId);
        }

        if (!empty($searchTerm)) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('companies.company_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('locations.branch_name', 'like', '%' . $searchTerm . '%');
            });
        }

        $officeDetails = $query->paginate($perPage);

        foreach ($officeDetails as $officeDetail) {
            if (!empty($officeDetail->days)) {
                $days = explode(',', $officeDetail->days);
                $working_days = count($days);
                $officeDetail->working_days = $working_days;
            } else {
                $officeDetail->working_days = 0;
            }
        }

        if ($officeDetails->isEmpty()) {
            return response()->json([
                'message' => "Record not found",
                'data' => [],
            ], 200);
        }

        return response()->json([
            'message' => "Office Record Fetch Successfully",
            'data' => $officeDetails,
        ], 200);
    }



    public function editOfficeDetails(Request $request)
    {
        $id = $request->id;
        $query = Location::leftJoin('companies', 'companies.id', '=', 'locations.company_id')
        ->leftJoin('company_settings', 'company_settings.company_id', '=', 'companies.id')
        ->leftJoin('com_countries', 'com_countries.country_id', '=', 'companies.country_id')
        ->leftJoin('com_cities', 'com_cities.city_id', '=', 'companies.city_id')
        ->select('locations.*', 'company_settings.days', 'companies.company_name', 'com_countries.country_name', 'com_cities.city_name')
        ->where('locations.id', $id);

        $officeDetail = $query->first();

        if ($officeDetail) {

            if (!empty($officeDetail->days)) {
                $days = explode(',', $officeDetail->days);
                $working_days = count($days);
                $officeDetail->working_days = $working_days;
            } else {
                $officeDetail->working_days = 0;
            }

            return response()->json([
                'message' => "Office Record Fetch Successfully",
                'data' => $officeDetail,
            ], 200);
        } else {
            return response()->json([
                'message' => "Record not found",
                'data' => [],
            ], 404);
        }
    }

    public function saveOfficeDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_name' => 'required|string|max:255|unique:locations,branch_name',
            'company_id' => 'required|integer',
            'branch_id' => 'required|string|max:20',
            'city_id' => 'required|string|max:20',
            'country_id' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400);
        }

        $officeDetails = new Location();

        $officeDetails->branch_name = $request->branch_name;
        $officeDetails->company_id = $request->company_id;
        $officeDetails->branch_id = $request->branch_id;
        $officeDetails->city_id = $request->city_id;
        $officeDetails->country_id = $request->country_id;
        $officeDetails->is_deleted = $request->is_deleted;

        $officeDetails->save();

        return response()->json([
            'message' => 'Office Details Saved Successfully',
            'data' => $officeDetails,
        ], 200);
    }

    public function updateOfficeDetails(Request $request)
    {
        $id = $request->id;

        $validator = Validator::make($request->all(), [
            'branch_name' => 'required|string|max:255|unique:locations,branch_name,' . $id,
            'company_id' => 'required|integer',
            'branch_id' => 'required|string|max:20',
            'city_id' => 'required|string|max:20',
            'country_id' => 'required|string|max:255',
            'is_deleted' => 'required|in:0,1',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400);
        }

        $officeDetails = Location::find($id);

        if ($officeDetails) {
            $officeDetails->branch_name = $request->branch_name;
            $officeDetails->company_id = $request->company_id;
            $officeDetails->branch_id = $request->branch_id;
            $officeDetails->city_id = $request->city_id;
            $officeDetails->country_id = $request->country_id;
            $officeDetails->is_deleted = $request->is_deleted;

            $officeDetails->save();

            return response()->json([
                'message' => 'Office Details Updated Successfully',
                'data' => $officeDetails,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Record not found',
                'data' => [],
            ], 404);
        }
    }
    public function deleteOfficeDetails(Request $request)
    {
        $id = $request->id;
        $deleteRecord = Location::find($id);

        if ($deleteRecord) {
            $deleteRecord->update(['is_deleted' => '1']);
            return response()->json([
                'message' => 'Office Details Deleted Successfully',
                'data' => $deleteRecord
            ], 200);
        } else {
            return response()->json([
                'message' => 'Record not found',
                'data' => []
            ], 404);
        }
    }
    public function getCompany(Request $request)
    {
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);

        $perPage = $request->input('per_page', 20);
        $searchBy = $request->input('search_by', null);

        $query = DB::table('companies')
            ->leftJoin('com_countries', 'com_countries.country_id', '=', 'companies.country_id')
            ->leftJoin('com_cities', 'com_cities.city_id', '=', 'companies.city_id')
            ->select('companies.id','companies.company_name', 'companies.phone', 'com_countries.country_name as country_name', 'com_cities.city_name as city_name')
            ->where('companies.is_deleted', '0');

        if ($searchBy) {
            $query->where(function($q) use ($searchBy) {
                $q->where('company_name', 'LIKE', '%' . $searchBy . '%')
                ->orWhere('phone', 'LIKE', '%' . $searchBy . '%');
            });
        }

        $companySettings = $query->orderBy('id', 'DESC')->paginate($perPage);

        if ($companySettings->isEmpty()) {
            return $this->sendResponse([
                'data' => [],
            ], 'Data not found', 200);
        }

        return $this->sendResponse($companySettings, 'Company configuration fetched successfully!', 200);
    }

    public function countries(Request $request){
        $getCountries = DB::table('com_countries')->select('country_id', 'country_name')->get();

        if(!$getCountries){
            return response()->json([
                'data' => [],
                'message' => 'No record found'
            ]);
        }
        else{
            return $this->sendResponse($getCountries,'Records found successfully',200);
        }
    }
    public function cities(Request $request){

        $country_id = $request->country_id;
        $getCountries = DB::table('com_cities')->select('city_id','city_name','country_id','state_id')->where('country_id',$country_id )->get();

        if(!$getCountries || $country_id == null || $country_id == ''){
            return $this->sendResponse([],'No record found', 404);
        }
        else{
            return $this->sendResponse($getCountries,'Records found successfully',200);
        }
    }

}


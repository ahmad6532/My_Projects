<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Leave_setting;
use Carbon\Carbon;
use App\Models\Location;
use App\Models\Leave;
use App\Models\Leave_type;
use App\Models\CompanySetting;
use App\Models\company;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;

class Admin_Company_Setting_Controller extends BaseController
{
    //Company Management
    public function CompanyManagement()
    {
        $companies = Company::where('is_deleted','0')->where('is_active','1')->latest()->get();
        foreach($companies as $company){
            $company->country = Country::where('is_deleted','N')->where('country_id',$company->country_id)->first();
            $company->city = City::where('is_deleted','N')->where('city_id',$company->city_id)->first();
        }
        return view('company_management.index',compact('companies'));
    }

    //Company Management
    public function AddCompanyManage()
    {
        $com_cities = City::where('is_deleted', 'N')->orderBy('city_name','asc')->get();
        $com_countries = Country::where('is_deleted', 'N')->orderBy('country_name','asc')->get();
        return view('company_management.addcompany',compact('com_cities', 'com_countries'));
    }

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
            'company_logo' => 'required|mimes:jpeg,png|max:2048',
        ], [
            'company_logo.mimes' => 'The company logo must be a JPG or PNG image.',
            'company_logo.max' => 'The company logo must not exceed 2MB in size.',
            'company_name.unique' => 'The company name is already taken.',
            'phone.unique' => 'Phone is already taken.',
            'email.unique' => 'The email is already taken.'
        ]);
        if ($validator->fails()) {
           return $this->sendError([],$validator->errors()->first(),400);
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

        if ($request->hasFile('company_logo')) {
            $uploadPath = 'assets/images/companies/';
            $file = $request->file('company_logo');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move($uploadPath, $filename);
            $image = $uploadPath.$filename;
        }
        $company->logo = $image;
        $company->save();
        $msg = 'Company"'.ucwords($request->company_name).'" Added Successfully';
        createLog('company_action',$msg);
        
        if($company){
            return $this->sendResponse($company,'Company added successfully!',200);
        }else{
            return $this->sendResponse($company, 'Compnay form not submited');
        }
    }
    public function updateCompany(Request $request)
    {
        $id = $request->company_id;
        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'email' => 'required|email|unique:companies,email,' . $id,
            // 'phone' => 'required|numeric|unique:companies|min:11',
            // 'tel' => 'nullable|numeric|unique:companies|min:11',
            'contact_person' => 'required',
            // 'country_id' => 'required',
            // 'city_id' => 'required',
            'address' => 'required|max:255'
        ]);
    
        if ($validator->fails()) {
            return $this->sendError([],$validator->errors()->first(),400);
        }
        $company = Company::findOrFail($id);

        if ($company) {
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
                $image = $uploadPath.$filename;
            } else {
                $image = $company->logo;
            }
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
        $company->update();
        $msg = 'Company"'.ucwords($request->company_name).'" Updated Successfully';
        createLog('company_action',$msg);

        if($company){
            return $this->sendResponse($company,'Company update successfully!',200);
        }else{
            return $this->sendResponse($company,'Data not found!',200);
        }

    }
    public function deleteCompany(Request $request)
    {
        $id = $request->company_id;
        $company = Company::findOrFail($id);
        $company->is_deleted = '1';
        $company->update();
        $msg = 'Company"'.ucwords($company->company_name).'" Deleted Successfully';
        createLog('company_action',$msg);
        if($company){
            return $this->sendResponse($company,'Company deleted successfully!',200);
        }else{
            return $this->sendResponse($company,'Data not found!',200);
        }
    }

    public function editCompany(Request $request)
    {
        $id = $request->company_id;
        $companies = Company::where('is_deleted','0')->where('is_active','1')->where('id',$id)->first();
        $data = [
            'companies' => $companies,
        ];
        if($companies){
            return $this->sendResponse($data,'Company feteched successfully!',200);
        }else{
            return $this->sendResponse($data,'Data not found!',200);
        }
    }

    public function addCompany()
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        if($user_role == 1){
            $companies = Company::where('is_deleted', '0')->orderBy('company_name','asc')->get();
        }else{
            $companies = Company::whereIn('id',$user_company_id)
                ->orderBy('company_name','asc')
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
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        if($user_role == '1'){
            $branches = Location::where('is_deleted', '0')
                            ->where('company_id', $request->company_id)
                            ->get();
        }else{
            $branches = Location::where('is_deleted', '0')
                            ->where('company_id', $request->company_id)
                            ->whereIn('id',$user_branch_id)
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
        if($request->company_id){
            $branches = Location::where('is_deleted', '0')->whereIn('company_id', $request->company_id)->get();
        }
        if($request->company_edit)
        {
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
        ],[
            'branch_id.unique' => 'The Location Settings Already Taken.'
        ]);
        
        if ($validator->fails()) {
           return $this->sendError([],$validator->errors()->first(),400);
        }
        $company_setting = new CompanySetting;
        $company_setting->company_id = $request->company_id;
        $company_setting->branch_id = $request->branch_id;
        $company_setting->start_time = $request->start_time;
        $company_setting->end_time = $request->end_time;
        $company_setting->lunch_start_time = $request->lunch_start_time;
        $company_setting->lunch_end_time = $request->lunch_end_time;
        $company_setting->late_time = $request->late_time;
        $company_setting->flexible_time = $request->flexible_time;
        $company_setting->half_day = $request->half_day;
        $selectedDays = $request->input('selectedDays');
        $daysString = implode(',', $selectedDays);
        $company_setting->days = $daysString;
        $company_setting->save();
        // $company_setting = CompanySetting::where('id',$request->company_id)->first();
        $company = Company::where('id',$request->company_id)->first();
        $branch = Location::where('id',$request->branch_id)->first();

        $msg = 'Added New Configuration of Company "'.$company->company_name.'" and Location "'.$branch->branch_name.'" Successfully';
        createLog('global_action',$msg);

        if($company_setting){
            return $this->sendResponse($company_setting, 'Company configurations save successfully!',200);
        }else{
            return $this->sendError($company_setting,'Form not submited',400);
        }
    }

    public function editCompanyConfiguration(Request $request)
    {
        $id = $request->configuration_id;
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        $companyConfiguration = CompanySetting::leftjoin('companies', 'company_settings.company_id', '=', 'companies.id')
        ->leftjoin('locations', 'company_settings.branch_id', '=', 'locations.id')
        ->select('companies.company_name', 'company_settings.*', 'locations.branch_name')
        ->where('company_settings.is_deleted', '0')->find($id);

        if($user_role == 1){
            $companies = Company::where('is_deleted', '0')->get();
        }else{
            $companies = Company::whereIn('id',$user_company_id)
                ->where('is_deleted', '0')->orderBy('company_name','asc')->get();
        }
        if($companyConfiguration){
            return $this->sendResponse($companyConfiguration,'Company fetched successfully!',200);
        }else{
            return $this->sendResponse($companyConfiguration,'Data not found!',200);
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
            return $this->sendError([],$validator->errors(),400);
        }
        $companyConfiguration = CompanySetting::findOrFail($id);
        $companyConfiguration->company_id = $request->company_id;
        $companyConfiguration->branch_id = $request->branch_id;
        $companyConfiguration->start_time = $request->start_time;
        $companyConfiguration->end_time = $request->end_time;
        $companyConfiguration->lunch_start_time = $request->lunch_start_time;
        $companyConfiguration->lunch_end_time = $request->lunch_end_time;
        $companyConfiguration->late_time = $request->late_time;
        $companyConfiguration->flexible_time = $request->flexible_time;
        $companyConfiguration->half_day = $request->half_day;
        $selectedDays = $request->input('selectedDays');
        $daysString = implode(',', $selectedDays);
        $companyConfiguration->days = $daysString;
        $companyConfiguration->update();
        $company = Company::where('id',$request->company_id)->first();
        $branch = Location::where('id',$request->branch_id)->first();

        $msg = 'Updated Configuration of Company "'.$company->company_name.'" and Location "'.$branch->branch_name.'" Successfully';
        createLog('global_action',$msg);

        if($companyConfiguration){
            return $this->sendResponse($companyConfiguration,'Company configuration update successfuly!',200);
        }else{
            return $this->sendResponse($companyConfiguration,'Data not found!',200);
        }
    }

    public function deleteCompanyConfiguration(Request $request)
    {
        $id = $request->configuration_id;
        $deleteCompanyConfiguration = CompanySetting::findOrFail($id);
        $deleteCompanyConfiguration->is_deleted = '1';
        $deleteCompanyConfiguration->update();

        // $company = CompanySetting::where('id',$company_setting->company_id)->first();
        $company = Company::where('id',$deleteCompanyConfiguration->company_id)->first();
        $branch = Location::where('id',$deleteCompanyConfiguration->branch_id)->first();

        $msg = 'Deleted Configuration of Company "'.$company->company_name.'" and Location "'.$branch->branch_name.'" Successfully';
        createLog('global_action',$msg);
        if($deleteCompanyConfiguration){
            return $this->sendResponse($deleteCompanyConfiguration,'Company configuration delete successfully!',200);
        }else{
            return $this->sendResponse($deleteCompanyConfiguration,'Data not found',200);
        }
    }

    public function companyConfiguration()
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        if($user_role == 1){
            $companySettings = CompanySetting::leftjoin('companies', 'company_settings.company_id', '=', 'companies.id')
                ->leftjoin('locations', 'company_settings.branch_id', '=', 'locations.id')
                ->select('companies.company_name', 'company_settings.*', 'locations.branch_name')
                ->where('company_settings.is_deleted', '0')
                ->get();
        }else{
            $companySettings = CompanySetting::leftjoin('companies', 'company_settings.company_id', '=', 'companies.id')
                ->leftjoin('locations', 'company_settings.branch_id', '=', 'locations.id')
                ->select('companies.company_name', 'company_settings.*', 'locations.branch_name')
                ->whereIn('company_settings.company_id',$user_company_id)
                ->whereIn('company_settings.branch_id',$user_branch_id)
                ->where('company_settings.is_deleted', '0')
                ->get();
        }

        $daysCounts = [];
        foreach ($companySettings as $setting) {
            $daysString = $setting->days;
            $diffInDays = explode(",", $daysString);
            $diffInDays = count($diffInDays);
            $setting->diffInDays = $diffInDays;
            $daysCounts[] = $diffInDays;
        }
        if($companySettings){
            return $this->sendResponse($companySettings,'Company configuration fetched successfully!',200);
        }else{
            return $this->sendResponse($companySettings,'Data not found',200);
        }
    }

    public function leaveSetting()
    {
        $selectedBranch = 'all';
        $leave_setting = Leave_setting::where('is_deleted','0')->where('is_active','1')->get();
        foreach($leave_setting as $leave){
            $leave->company = Company::where('is_deleted', '0')->where('id',$leave->company_id)->first();
        }
        if($leave_setting){
            return $this->sendResponse([$leave_setting,$selectedBranch],'Leaves fetched successfully!',200);
        }else{
            return $this->sendResponse([$leave_setting,$selectedBranch],'Data not found!',200);
        }
    }
   
    public function addLeaveSetup()
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        if($user_role == 1){
            $companies = Company::where('is_deleted', '0')->orderBy('company_name','asc')->get();
        }else{
            $companies = Company::whereIn('id',$user_company_id)
                ->orderBy('company_name','asc')
                ->where('is_deleted', '0')
                ->get();
        }
        return view('leave_setting.add_setup', compact('companies'));
    }

    public function saveLeaveSetting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|unique:leave-settings,company_id',
            'annualDays' => 'required',
            'annual_carry_forward' => 'required',
            'casual_numberOfDays' => 'required',
            'casual_carry_forward' => 'required',
            'sick_leave_numberOfDays' => 'required',
            'maternity_numberOfDays' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError([], $validator->errors()->first(), 400);
        }
        $leave = new Leave_setting;
        $leave->company_id = $request->company_id;
        $leave->annual_days = $request->annualDays;
        $leave->annual_carry_forward = $request->annual_carry_forward;
        $leave->annual_forward_days = $request->annual_forwardDays;
        $leave->casual_days = $request->casual_numberOfDays;
        $leave->casual_carry_forward = $request->casual_carry_forward;
        $leave->casual_forward_days = $request->casual_forwardDays;
        $leave->sick_days = $request->sick_leave_numberOfDays;
        $leave->maternity_days = $request->maternity_numberOfDays;
        $leave->save();
        $company_action = Company::where('id',$request->company_id)->first();
        $msg = 'Company"'.ucwords($company_action->company_name).'"Leave Setupt Added Successfully';
        createLog('company_action',$msg);
        if($leave){
            return $this->sendResponse($leave,'Leave settings added successfully!',200);
        }else{
            return $this->sendError([],'Form not submitted!',302);
        }  
    }

    public function editLeaveSetting(Request $request)
    {
        $id = $request->leave_id;
        $edit_leave = Leave_setting::where('id', $id)->first();
        if ($edit_leave) {
            return $this->sendResponse($edit_leave, 'Leave setting fetched succesfully!', 200);
        } else {
            return $this->sendResponse($edit_leave, 'Leave setting fetched succesfully!', 200);
        }
    }

    public function updateLeaveSetting(Request $request)
    {
        $id = $request->leave_id;
        $value = $request->company_id;
        $validator = Validator::make($request->all(), [
            'company_id' => [
                'required',
                function ($attribute, $value, $fail) use ($request, $id) {
                    $oldCompanyId = Leave_setting::where('id', $id)->select('company_id')->first();
                    if ($value != $id) {
                        $companyExists = Leave_setting::where('company_id', $value)
                            ->where('id', '!=', $id)
                            ->exists();
                        if ($companyExists) {
                            $fail("The company ID is already associated with another record.");
                        }
                    }
                },
            ],
            
            'annualDays' => 'required',
            'annual_carry_forward' => 'required',
            'casual_numberOfDays' => 'required',
            'sick_leave_numberOfDays' => 'required',
            'maternity_numberOfDays' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError([],$validator->errors()->first(),400);
        }
        
        $leave = Leave_setting::findOrFail($id);
        $leave->company_id = $request->company_id;
        $leave->annual_days = $request->annualDays;
        $leave->annual_carry_forward = $request->annual_carry_forward;
        $leave->annual_forward_days = $request->annual_forwardDays;
        $leave->casual_days = $request->casual_numberOfDays;
        $leave->casual_carry_forward = $request->casual_carry_forward;
        $leave->casual_forward_days = $request->casual_forwardDays;
        $leave->sick_days = $request->sick_leave_numberOfDays;
        $leave->maternity_days = $request->maternity_numberOfDays;
        $company_action = Company::where('id',$leave->company_id)->first();
        $msg = 'Company"'.ucwords($company_action->company_name).'"Leave Setupt Updated Successfully';
        createLog('company_action',$msg);
        $leave->update();
        if($leave){
            return $this->sendResponse($leave,'Leave setting update successfully!',200);
        }else{
            return $this->sendResponse($leave,'Data not found!',200);
        }
    }

    public function deleteLeaveSetting(Request $request)
    {
        $id = $request->leave_id;
        $leave = Leave_setting::find($id);
        if($leave){
            $company_action = Company::where('id',$leave->company_id)->first();
            $leave->is_deleted = '1';
            $leave->update();
            $msg = 'Company"'.ucwords($company_action->company_name).'"Leave Setupt Deleted Successfully';
            createLog('company_action',$msg);
            if($leave){
                return $this->sendResponse($leave,'Leave setting delete successfully!',200);
            }else{
                return $this->sendResponse($leave,'Data not found!',200);
            }
        }else{
            return $this->sendResponse($leave,'Data not found!',200);
        }
    }

    
}

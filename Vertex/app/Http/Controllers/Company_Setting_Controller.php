<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Leave_setting;
use Carbon\Carbon;
use App\Models\Location;
use App\Models\Leave;
use App\Models\Leave_Type;
use App\Models\CompanySetting;
use App\Models\company;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class Company_Setting_Controller extends Controller
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

    public function StoreCompanyManage(Request $request)
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
            return redirect()->back()->withErrors($validator)->withInput($request->all());
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


        return redirect()->route('company.management')->with('success', 'Company Added Successfully');
    }

    public function EditCompanyManage($id)
    {
        $companies = Company::where('is_deleted','0')->where('is_active','1')->where('id',$id)->first();
        $com_cities = City::where('is_deleted', 'N')->orderBy('city_name','asc')->get();
        $com_countries = Country::where('is_deleted', 'N')->orderBy('country_name','asc')->get();
        return view('company_management.editcompany',compact('com_cities', 'com_countries','companies'));
    }

    public function UpdateCompanyManage(Request $request, $id)
    {
        // return $request->all();
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
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', "Some of the fields are missing");
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

        return redirect()->route('company.management')->with('success', 'Company Updated Successfully');
    }
    public function DeleteCompanyManage(Request $request, $id)
    {
        $leave = Company::findOrFail($id);
        $leave->is_deleted = '1';
        $leave->update();
        $msg = 'Company"'.ucwords($leave->company_name).'" Deleted Successfully';
        createLog('company_action',$msg);
        return redirect()->back()->with('success', 'Company Deleted Successfully');
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

    public function storeCompany(Request $request)
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
            'status' => 'required',
        ],[
            'branch_id.unique' => 'The Location Settings Already Taken.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', "Some of the fields are missing");
        }
        $company_setting = new Company_Setting;
        $company_setting->company_id = $request->company_id;
        $company_setting->branch_id = $request->branch_id;
        $company_setting->start_time = $request->start_time;
        $company_setting->end_time = $request->end_time;
        $company_setting->lunch_start_time = $request->lunch_start_time;
        $company_setting->lunch_end_time = $request->lunch_end_time;
        $company_setting->late_time = $request->late_time;
        $company_setting->flexible_time = $request->status;
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

        return redirect('company-setting')->with('success', 'Company Settings are Added Successfully');
    }

    public function editCompany($id)
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        $companySettings = CompanySetting::leftjoin('companies', 'company_settings.company_id', '=', 'companies.id')
        ->leftjoin('locations', 'company_settings.branch_id', '=', 'locations.id')
        ->select('companies.company_name', 'company_settings.*', 'locations.branch_name')
        ->where('company_settings.is_deleted', '0')->find($id);

        if($user_role == 1){
            $companies = Company::where('is_deleted', '0')->get();
        }else{
            $companies = Company::whereIn('id',$user_company_id)
                ->where('is_deleted', '0')->orderBy('company_name','asc')->get();
        }

        return view('company.edit_company', compact('companies', 'companySettings'));
    }

    public function updateCompany(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
            'start_time' => 'required',
            'selectedDays' => 'required',
            'end_time' => 'required',
            'lunch_start_time' => 'required',
            'lunch_end_time' => 'required',
            'late_time' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->withInput($request->all())->with('error', "Some of the fields are missing");
            ;
        }
        $company_setting = CompanySetting::findOrFail($id);
        $company_setting->company_id = $request->company_id;
        $company_setting->branch_id = $request->branch_id;
        $company_setting->start_time = $request->start_time;
        $company_setting->end_time = $request->end_time;
        $company_setting->lunch_start_time = $request->lunch_start_time;
        $company_setting->lunch_end_time = $request->lunch_end_time;
        $company_setting->late_time = $request->late_time;
        $company_setting->flexible_time = $request->status;
        $company_setting->half_day = $request->half_day;
        $selectedDays = $request->input('selectedDays');
        $daysString = implode(',', $selectedDays);
        $company_setting->days = $daysString;
        $company_setting->update();

        // $company = CompanySetting::where('id',$request->company_id)->first();
        $company = Company::where('id',$request->company_id)->first();
        $branch = Location::where('id',$request->branch_id)->first();

        $msg = 'Updated Configuration of Company "'.$company->company_name.'" and Location "'.$branch->branch_name.'" Successfully';
        createLog('global_action',$msg);

        return redirect('company-setting')->with('success', 'Company Settings are Updated Successfully');
    }

    public function destroyCompany($id)
    {
        $company_setting = CompanySetting::findOrFail($id);
        $company_setting->is_deleted = '1';
        $company_setting->update();

        // $company = CompanySetting::where('id',$company_setting->company_id)->first();
        $company = Company::where('id',$company_setting->company_id)->first();
        $branch = Location::where('id',$company_setting->branch_id)->first();

        $msg = 'Deleted Configuration of Company "'.$company->company_name.'" and Location "'.$branch->branch_name.'" Successfully';
        createLog('global_action',$msg);
        return redirect()->back()->with('success', 'Company Setting Record Deleted Successfully');
    }

    public function companySetting()
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

        return view('company.index', compact('companySettings','user'));
    }

    public function leaveSetting()
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);
        $selectedBranch = 'all';
        $branches = Location::whereIn('company_id', $user_company_id)
        ->whereIn('id', $user_branch_id)
        ->where('is_deleted', '0')
        ->get();
        $leave_setting = Leave_setting::where('is_deleted','0')->where('is_active','1')->get();
        foreach($leave_setting as $leave){
            $leave->company = Company::where('is_deleted', '0')->where('id',$leave->company_id)->first();
        }
        return view('leave_setting.index', compact('branches','selectedBranch','user','leave_setting'));
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

    public function saveLeaveSetup(Request $request)
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
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput($request->all())
                ->with('error', 'Some of the fields are missing or the company is already taken.');
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
        return redirect()->route('leave.setting')->with('success', "Leave Setting Added Successfully");
    }

    public function LeaveSettingsEdit(Request $request, $id)
    {
       $edit_leave = Leave_setting::where('id',$id)->first();
     $companies = Company::where('is_deleted', '0')->orderBy('company_name','asc')->get();
       return view('leave_setting.edit_setup',compact('edit_leave','companies'));
    }

    public function UpdateSetupSettings(Request $request, $id)
    {
        $value = $request->company_id;
        $validator = Validator::make($request->all(), [
            'company_id' => [
                'required',
                function ($attribute, $value, $fail) use ($request, $id) {
                    // Get the primary ID of the leave record from the $id parameter
                    $leaveId = $id; // Assuming 'id' is the primary key field

                    // Retrieve the old company ID in a single query
                    $oldCompanyId = Leave_setting::where('id', $leaveId)->select('company_id')->first();

                    if ($value != $oldCompanyId->company_id) {
                        // Check if the new company ID already exists in another field
                        $companyExists = Leave_setting::where('company_id', $value)
                            ->where('id', '!=', $leaveId) // Exclude the current record from the check
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
            return redirect()->back()->withErrors($validator)->withInput($request->all())->withInput($request->all())->with('error', 'Some of the fields are missing or the company is already taken.');
            ;
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
        return redirect()->route('leave.setting')->with('success', "Leave Setup Updated Successfully");
    }

    public function DeleteLeaveSetup(Request $request, $id)
    {
        $leave = Leave_setting::findOrFail($id);
        $company_action = Company::where('id',$leave->company_id)->first();
        $leave->is_deleted = '1';
        $leave->update();
        $msg = 'Company"'.ucwords($company_action->company_name).'"Leave Setupt Deleted Successfully';
        createLog('company_action',$msg);
        return redirect()->back()->with('success', 'Leave Setupt Deleted Successfully');
    }
}

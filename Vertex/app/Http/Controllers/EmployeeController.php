<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Job_type;
use App\Models\Leave;
use App\Models\Leave_setting;
use App\Models\Leave_Type;
use App\Models\Setting;
use App\Models\EmployeePromotion;
use App\Models\Emp_termination;
use App\Models\EmployeeResignation;
use App\Models\user_allowance;
use App\User;
use Carbon\Carbon;
use App\Models\emp;
use App\Models\Location;
// use App\Models\Company;
use App\Models\Company;
use App\Models\Holiday;
use App\Models\City;
use App\Models\Language;
use App\Models\Country;
use App\Models\Designation;
use App\Models\EmployeeDocument;
use Illuminate\Http\Request;
use App\Models\AccountDetail;
use App\Models\user_approval;
use App\Models\user_language;
use App\Models\EmployeeEducationDetail;
use App\Models\UserAttendence;
use App\Models\CompanySetting;
use App\Models\EmployeeDetail;
use App\Models\user_experience;
use App\Models\related_refrence;
use App\Models\EmployeeHistory;
use Illuminate\Support\Facades\DB;
use App\Models\user_family_refrence;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Traits\emailFormat;
use App\Models\NotificationManagement;
use App\Models\NotificationEmail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\EmployeeRelative;
use Illuminate\Support\Str;


class EmployeeController extends Controller
{
    public function EmployeeDirectory(Request $request)
    {
        // user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        $selectedStatus = (isset($request->selectStatus)?$request->selectStatus:'1');
        $selectedBranch = (isset($request->branch_id)?$request->branch_id:'all');

        if($user_role == 1){
            if($selectedStatus == "5"){
                if($selectedBranch == 'all'){
                    $employees = EmployeeDetail::leftjoin('locations', 'locations.id', '=', 'employee_details.branch_id')
                        ->select('locations.branch_name', 'employee_details.*')
                        ->where('employee_details.is_deleted', '1')
                        ->where('locations.is_deleted', '0')
                        ->orderBy('employee_details.emp_id', 'asc')
                        ->paginate(20);
                }else{
                    $employees = EmployeeDetail::leftjoin('locations', 'locations.id', '=', 'employee_details.branch_id')
                    ->select('locations.branch_name', 'employee_details.*')
                    ->where('employee_details.is_deleted', '1')
                    ->where('locations.is_deleted', '0')
                    ->orderBy('employee_details.emp_id', 'asc')
                    ->where('employee_details.branch_id',$selectedBranch)
                    ->paginate(20);
                }
            }else{
                if($selectedBranch == 'all'){
                    $employees = EmployeeDetail::leftjoin('locations', 'locations.id', '=', 'employee_details.branch_id')
                        ->select('locations.branch_name', 'employee_details.*')
                        ->where('employee_details.status', $selectedStatus)
                        ->where('employee_details.is_deleted', '0')
                        ->where('locations.is_deleted', '0')
                        ->orderBy('employee_details.emp_id', 'asc')
                        ->paginate(20);
                }else{
                    $employees = EmployeeDetail::leftjoin('locations', 'locations.id', '=', 'employee_details.branch_id')
                    ->select('locations.branch_name', 'employee_details.*')
                    ->where('employee_details.status', $selectedStatus)
                    ->where('employee_details.is_deleted', '0')
                    ->where('locations.is_deleted', '0')
                    ->orderBy('employee_details.emp_id', 'asc')
                    ->where('employee_details.branch_id',$selectedBranch)
                    ->paginate(20);
                }
            }
            $branches = Location::where('is_deleted', '0')->orderBy('branch_name','asc')->get();
        }else{
            if($selectedBranch == 'all'){
                $employees = EmployeeDetail::leftjoin('locations', 'locations.id', '=', 'employee_details.branch_id')
                    ->select('locations.branch_name', 'employee_details.*')
                    ->where('employee_details.status', $selectedStatus)
                    ->where('employee_details.is_deleted', '0')
                    ->where('locations.is_deleted', '0')
                    ->orderBy('employee_details.emp_id', 'asc')
                    ->whereIn('employee_details.company_id',$user_company_id)
                    ->whereIn('employee_details.branch_id',$user_branch_id)
                    ->paginate(20);
            }else{
                $employees = EmployeeDetail::leftjoin('locations', 'locations.id', '=', 'employee_details.branch_id')
                    ->select('locations.branch_name', 'employee_details.*')
                    ->where('employee_details.status', $selectedStatus)
                    ->where('employee_details.is_deleted', '0')
                    ->where('locations.is_deleted', '0')
                    ->orderBy('employee_details.emp_id', 'asc')
                    ->whereIn('employee_details.company_id',$user_company_id)
                    ->where('employee_details.branch_id',$selectedBranch)
                    ->paginate(20);
            }

            $branches = Location::whereIn('company_id',$user_company_id)
            ->whereIn('id',$user_branch_id)
            ->where('is_deleted', '0')
            ->orderBy('branch_name','asc')
            ->get();

        }
        foreach($employees as $employee) {
            $employee->approved_leave_days = Leave::where('is_approved', '1')
                ->where('is_deleted', '0')
                ->where('emp_id', $employee->id)
                ->sum('approved_days');
        }
        foreach ($employees as $key => $emp_desig) {
            $emp_desig->emp_desig = user_approval::where('emp_id', $emp_desig->id)->first();
            if($emp_desig->emp_desig){
                $emp_desig->designation_name = Designation::where('id',$emp_desig->emp_desig->designation_id)->first();
                if($emp_desig->designation_name){
                    $emp_desig->department = Department::where('id',$emp_desig->designation_name->department_id)->first();
                }else{
                    $emp_desig->department = '';
                }
            }else{
                $emp_desig->designation_name = '';
            }
        }
        // $total = array_sum($employees);
        // return $employees;
        return view('directory.index', compact('employees','user','branches','selectedBranch','selectedStatus'));
    }

    public function addApprovals()
    {
        $designations = Designation::orderBy('name','asc')->get();
        $job_status = Job_type::orderBy('job_status','asc')->get();
        return view('directory.add.approval',compact('designations','job_status'));
    }

    public function createEmployeee()
    {
        // user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        if($user_role == 1){
            $companies = Company::where('is_deleted', '0')->orderBy('company_name','asc')->get();
            $branches = Location::where('is_deleted', '0')->orderBy('branch_name','asc')->get();
        }else{
            $companies = Company::whereIn('id',$user_company_id)
                ->orderBy('company_name','asc')
                ->get();
            $branches = Location::whereIn('company_id',$user_company_id)
                ->whereIn('id',$user_branch_id)
                ->where('is_deleted', '0')
                ->orderBy('branch_name','asc')
                ->get();
        }

        $countries = Country::orderBy('country_name','asc')->get();
        return view('directory.add.employee', compact('companies', 'branches', 'countries'));
    }

    public function getcities(Request $request)
    {
        $cities = City::where('country_id', $request->country_id)
            ->select('city_id', 'city_name')
            ->orderBy('city_name','asc')
            ->get();
        return response()->json(["success" => true, "data" => $cities]);
    }

    public function getsearchedEmployee(Request $request)
    {
        // user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $selectStatus = $request->selectStatus;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);
        if($user_role == 1){
            if(isset($request->emp_name) && $request->branch_id == 'all'){
                if($selectStatus == '5'){
                    $fetchEmps = EmployeeDetail::where('emp_name', 'LIKE', '%' . $request->emp_name . '%')
                    ->where('is_deleted','1')
                    ->select('id', 'branch_id', 'emp_id', 'emp_name', 'company_id','status', 'is_active', 'is_deleted')
                    // ->where('is_active','1')
                    ->orderBy('emp_id', 'asc')
                    ->get();
                }else{
                    $fetchEmps = EmployeeDetail::where('emp_name', 'LIKE', '%' . $request->emp_name . '%')
                    ->where('status',$selectStatus)
                    ->where('is_deleted','0')
                    ->select('id', 'branch_id', 'emp_id', 'emp_name', 'company_id','status', 'is_active', 'is_deleted')
                    // ->where('is_active','1')
                    ->orderBy('emp_id', 'asc')
                    ->get();
                }
            }elseif(isset($request->emp_name) && $request->branch_id){
                if($selectStatus == '5'){
                    $fetchEmps = EmployeeDetail::where('branch_id', 'LIKE', $request->branch_id . '%')
                    ->where('is_deleted','1')
                    ->where('emp_name', 'LIKE', '%' . $request->emp_name . '%')
                    ->select('id', 'branch_id', 'emp_id', 'emp_name', 'company_id','status', 'is_active', 'is_deleted')
                    // ->where('is_active','1')
                    ->orderBy('emp_id', 'asc')
                    ->get();
                }else{
                    $fetchEmps = EmployeeDetail::where('branch_id', 'LIKE', $request->branch_id . '%')
                    ->where('status',$selectStatus)
                    ->where('is_deleted','0')
                    ->where('emp_name', 'LIKE', '%' . $request->emp_name . '%')
                    ->select('id', 'branch_id', 'emp_id', 'emp_name', 'company_id','status', 'is_active', 'is_deleted')
                    // ->where('is_active','1')
                    ->orderBy('emp_id', 'asc')
                    ->get();
                    // return $fetchEmps;
                }
            }elseif($request->branch_id != 'all'){
                if($selectStatus == '5'){
                    $fetchEmps = EmployeeDetail::where('branch_id', 'LIKE', $request->branch_id . '%')
                    ->where('is_deleted','1')
                    ->select('id', 'branch_id', 'emp_id', 'emp_name', 'company_id','status', 'is_active', 'is_deleted')
                    // ->where('is_active','1')
                    ->orderBy('emp_id', 'asc')
                    ->get();
                }else {
                    $fetchEmps = EmployeeDetail::where('branch_id', 'LIKE', $request->branch_id . '%')
                    ->where('status',$selectStatus)
                    ->where('is_deleted','0')
                    ->select('id', 'branch_id', 'emp_id', 'emp_name', 'company_id','status', 'is_active', 'is_deleted')
                    // ->where('is_active','1')
                    ->orderBy('emp_id', 'asc')
                    ->get();
                }
            }elseif ($request->branch_id === 'all') {
                if($selectStatus == '5'){
                    $fetchEmps = EmployeeDetail::where('is_deleted','1')
                    ->select('id', 'branch_id', 'emp_id', 'emp_name', 'company_id','status', 'is_active', 'is_deleted')
                    // ->where('is_active','1')
                    ->orderBy('emp_id', 'asc')
                    ->get();
                }else{
                    $fetchEmps = EmployeeDetail::where('status',$selectStatus)
                    ->where('is_deleted','0')
                    ->select('id', 'branch_id', 'emp_id', 'emp_name', 'company_id','status', 'is_active', 'is_deleted')
                    // ->where('is_active','1')
                    ->orderBy('emp_id', 'asc')
                    ->get();
                }
            }
            foreach($fetchEmps as $employee) {
                $employee->approved_leave_days = Leave::where('is_approved', '1')
                    ->where('is_deleted', '0')
                    ->where('emp_id', $employee->id)
                    ->sum('approved_days');
            }
            foreach ($fetchEmps as $emp) {
                $branch = Location::where('id', $emp->branch_id)->first();
                $emp['branch_name'] = $branch ? ucwords($branch['branch_name']) : '';
                $joining_date = user_approval::where('emp_id', $emp->id)->select('joining_date')->first();
                $approval = user_approval::where('emp_id', $emp->id)->first();

                $emp['ApprovedByCEO'] = $approval ? $approval['approved_by_CEO'] : null;

                if ($joining_date != null && $joining_date != "") {
                    $emp['joining_date'] = $joining_date->joining_date;
                } else {
                    $emp['joining_date'] = '';
                }
                if ($emp->is_deleted == 1) {
                    $fetchEmp[] = $emp;
                } else {
                    $fetchEmp[] = $emp;

                }
            }
        }else{
            if(isset($request->emp_name) && $request->branch_id == 'all'){
                    $fetchEmps = EmployeeDetail::whereIn('company_id',$user_company_id)
                    ->whereIn('branch_id',$user_branch_id)
                    ->where('emp_name', 'LIKE', '%' . $request->emp_name . '%')
                    ->where('status',$selectStatus)
                    ->where('is_deleted','0')
                    ->select('id', 'branch_id', 'emp_id', 'emp_name', 'company_id','status', 'is_active', 'is_deleted')
                    // ->where('is_active','1')
                    ->orderBy('emp_id', 'asc')
                    ->get();
            }elseif(isset($request->emp_name) && $request->branch_id){
                $fetchEmps = EmployeeDetail::whereIn('company_id',$user_company_id)
                ->whereIn('branch_id',$user_branch_id)
                ->where('branch_id', 'LIKE', $request->branch_id . '%')
                ->where('status',$selectStatus)
                ->where('is_deleted','0')
                ->where('emp_name', 'LIKE', '%' . $request->emp_name . '%')
                ->select('id', 'branch_id', 'emp_id', 'emp_name', 'company_id', 'is_active', 'is_deleted')
                // ->where('is_active','1')
                ->orderBy('emp_id', 'asc')
                ->get();
            }elseif($request->branch_id != 'all'){
                $fetchEmps = EmployeeDetail::whereIn('company_id',$user_company_id)
                ->whereIn('branch_id',$user_branch_id)
                ->where('branch_id', 'LIKE', $request->branch_id . '%')
                ->where('status',$selectStatus)
                ->where('is_deleted','0')
                ->select('id', 'branch_id', 'emp_id', 'emp_name', 'company_id', 'is_active', 'is_deleted')
                // ->where('is_active','1')
                ->orderBy('emp_id', 'asc')
                ->get();
            }elseif ($request->branch_id === 'all') {
                $fetchEmps = EmployeeDetail::whereIn('company_id',$user_company_id)
                ->whereIn('branch_id',$user_branch_id)
                ->where('status',$selectStatus)
                ->where('is_deleted','0')
                ->select('id', 'branch_id', 'emp_id', 'emp_name', 'company_id','status', 'is_active', 'is_deleted')
                // ->where('is_active','1')
                ->orderBy('emp_id', 'asc')
                ->get();
            }
            foreach($fetchEmps as $employee) {
                $employee->approved_leave_days = Leave::where('is_approved', '1')
                    ->where('is_deleted', '0')
                    ->where('emp_id', $employee->id)
                    ->sum('approved_days');
            }
            foreach ($fetchEmps as $emp) {
                $branch = Location::where('id', $emp->branch_id)->first();
                $emp['branch_name'] = $branch ? ucwords($branch['branch_name']) : '';
                $joining_date = user_approval::where('emp_id', $emp->id)->select('joining_date')->first();
                $approval = user_approval::where('emp_id', $emp->id)->first();

                $emp['ApprovedByCEO'] = $approval ? $approval['approved_by_CEO'] : null;

                if ($joining_date != null && $joining_date != "") {
                    $emp['joining_date'] = $joining_date->joining_date;
                } else {
                    $emp['joining_date'] = '';
                }
                if ($emp->is_deleted == 1) {
                    continue;
                } else {
                    $fetchEmp[] = $emp;

                }
            }
        }
        foreach ($fetchEmps as $key => $emp_desig) {
            $emp_desig->emp_desig = user_approval::where('emp_id', $emp_desig->id)->first();
            if($emp_desig->emp_desig){
                $emp_desig->designation_name = Designation::where('id',$emp_desig->emp_desig->designation_id)->first();
                if($emp_desig->designation_name){
                    $emp_desig->department = Department::where('id',$emp_desig->designation_name->department_id)->first();
                }else{
                    $emp_desig->department = '';
                }
            }else{
                $emp_desig->designation_name = '';
            }
        }
        if (isset($fetchEmp) && count($fetchEmp) > 0) {
            return response()->json(["success" => true, "data" => ["emp" => $fetchEmp,"role_id"=>$user_role]]);
        } else {
            return response()->json(["success" => false, "data" => 'No Record found', "role_id"=>$user_role]);
        }
    }

    public function storeEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'emp_id' => 'required|unique:employee_details',
            'personal_email' => 'required|email|unique:employee_details',
            'emp_phone' => 'required|numeric|unique:employee_details|min:11|digits:11',
            'cnic' => 'required|numeric|unique:employee_details|min:13',
            'name' => 'required|max:25',
            'bloodgroup' => 'max:25',
            'fathername' => 'max:25',
            'mothername' => 'max:25',
            'address' => 'max:255',
            'spouse' => 'max:25',
            'religion' => 'max:20',
            'register_no' => 'max:20',
            'license_number' => 'max:20',
            'company_id' => 'required',
            'branch_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', "Some of the fields are missing");
        }

        $image = '';
        $uploadPath = 'assets/images/users/images/';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('assets/images/users/images/', $filename);
            $image = $uploadPath . $filename;
        }

        $addUser = EmployeeDetail::create([
            'company_id' => $request->input('company_id'),
            'branch_id' => $request->branch_id,
            'emp_id' => $request->emp_id,
            'personal_email' => $request->personal_email,
            'emp_name' => ucwords($request->name),
            'father_name' => ucwords($request->fathername) ?? null,
            'mother_name' => ucwords($request->mothername) ?? null,
            'emp_address' => $request->address,
            'emp_phone' => $request->emp_phone,
            'emp_gender' => $request->input('gender'),
            'cnic' => $request->cnic,
            'dob' => Carbon::parse($request->dateofbirth)->format('Y-m-d'),
            'added_by' => Auth::user()->fullname,
            'emp_image' => $request->userImage,
            'nationality' => $request->nationality,
            'city_of_birth' => $request->city,
            'religion' => ucwords($request->religion),
            'blood_group' => $request->bloodgroup,
            'marital_status' => $request->marital_status,
            'spouse_name' => ucwords($request->spouse),
            'is_independant' => $request->accomodation,
            'has_home' => $request->accomodation_specify,
            'has_transport' => $request->has_transport,
            'transport_type' => $request->transport_type,
            'registration_no' => $request->register_no,
            'driving_license' => $request->has_license,
            'license_no' => $request->license_number,
            'emp_image' => $image,
        ]);
        $uploadPath = public_path('assets/images/users/documents/');
        $data = [];

        if ($request->hasFile('content_list')) {
            $descriptions = $request->input('document_discription', []);

            foreach ($request->file('content_list') as $index => $file) {
                if ($file && $file->isValid()) {
                    $ext = $file->getClientOriginalExtension();
                    $filename = time() . '_' . $index . '.' . $ext;
                    $file->move($uploadPath, $filename);

                    $description = isset($descriptions[$index]) ? $descriptions[$index] : '';

                    $data[] = [
                        'image' => 'assets/images/users/documents/' . $filename,
                        'description' => $description
                    ];
                }
            }
        }

        foreach ($data as $document) {
            $emp_document = new Emp_document;
            $emp_document->emp_id = $addUser->id;
            $emp_document->document_path = $document['image'];
            $emp_document->discription = $document['description'];
            $emp_document->save();
        }
        if (empty($addUser)) {
            return redirect()->back()->with('error', "Form not submitted!");
        }
        Session::put('employee_id', $addUser->id);

        $msg = '"'.ucwords($request->name).'" Added Successfully';
        createLog('employee_action',$msg);

        return redirect()->route('add.education')->with('success', "Form Submitted Succesfully!");
    }

    public function storeAccountDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'bank_name' => 'required',
            'account_number' => 'required',
            'ifsc_code' => 'required',
            'pan' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', "Some of the fields are missing");
        }

        $addUser = AccountDetail::create([
            'emp_id' => $request->employee_id,
            'bank_name' => $request->bank_name,
            'account_no' => $request->account_number,
            'ifsc_code' => $request->ifsc_code,
            'pan_no' => $request->pan,
        ]);
        if (empty($addUser)) {
            return redirect()->back()->with('error', "Form not submitted due to some technical issues");
        }

        $employee = EmployeeDetail::where('id',$request->employee_id)->first();
        $msg = '"'.ucwords($employee->emp_name).'" Account Added Successfully';
        createLog('employee_action',$msg);

        return redirect()->route('add.approval')->with('success', "Account Details submitted  Succesfully");
    }

    public function storeEmployeeEducation(Request $request)
    {
        $emp_degrees = $request->emp_degree;
        $major_subs = $request->major_sub;
        $grade_divisions = $request->grade_division;
        $degree_froms = $request->degree_from;
        $degree_tos = $request->degree_to;
        $institutes = $request->institute;
        $other_qualification = $request->other_qualifications;
        for ($i = 0; $i < count($emp_degrees); $i++) {
            $emp_education = new EmployeeEducationDetail;
            $emp_education->emp_id = $request->employee_id;
            $emp_education->degree = $emp_degrees[$i];
            $emp_education->subject = $major_subs[$i];
            $emp_education->grade = $grade_divisions[$i];
            $emp_education->division = $grade_divisions[$i]; // Assuming division is the same as grade for now
            $emp_education->degree_from = $degree_froms[$i];
            $emp_education->degree_to = $degree_tos[$i];
            $emp_education->institution = $institutes[$i];
            $emp_education->save();
        }

        if ($other_qualification != null && $other_qualification != "") {
            $otherEdu = new EmployeeEducationDetail();
            $otherEdu->emp_id = $request->employee_id;
            $otherEdu->other_qualifications = $other_qualification;
            $otherEdu->save();
        }

        if (isset($request->emp_languages)) {
            foreach($request->emp_languages as $language){
                $getlanguage = Language::where('language_name', $language)->select('id')->first();
                if($getlanguage){
                    $language_id = $getlanguage->id;
                    $exists = user_language::where('emp_id', $request->employee_id)->where('language_id', $language_id)->first();
                    if($exists){
                        $exists->update([
                            'language_id' => $language_id,
                        ]);
                    } else {
                        user_language::create([
                            'emp_id' => $request->employee_id,
                            'language_id' => $language_id,
                        ]);
                    }
                }
            }
        }

        $langData = array();

        if (!empty($request->input('other_emp_language'))) {
            $langsArray=[];
            $emp_lang = $request->other_emp_language;
            $cleaned_langs = preg_replace('/\s*,\s*/', ',', $emp_lang);
            $langsArray = explode(',', ucfirst($cleaned_langs));

            foreach($langsArray as $langs){
                $languageExists = Language::where('language_name', $langs)->select('id')->first();
                if ($languageExists) {
                    $otherLang = $languageExists->id;
                    array_push($langData, $otherLang);
                } else {
                    $AddLang = Language::create([
                        'language_name' => $langs,
                    ]);
                    $otherLang = $AddLang->id;
                    array_push($langData, $otherLang);
                }
            }
        }

        $newdata = array_unique($langData, SORT_REGULAR);

        for ($i = 0; $i < count($newdata); ++$i) {
            $exists = user_language::where('emp_id', $request->employee_id)->where('language_id', $newdata[$i])->first();
            if($exists){
                $exists->update([
                    'language_id' => $newdata[$i],
                ]);
            } else {
                user_language::create([
                    'emp_id' => $request->employee_id,
                    'language_id' => $newdata[$i],
                ]);
            }
        }

        $employee = EmployeeDetail::where('id',$request->employee_id)->first();
        $msg = '"'.ucwords($employee->emp_name).'" Education Added Successfully';
        createLog('employee_action',$msg);

        return redirect()->route('add.employment')->with('success', "Form Submitted Successfully");
    }

    public function storeEmployeeExperience(Request $request)
    {
        $organization = $request->organization;
        $prev_position = $request->prev_position;
        $prev_salary = $request->prev_salary;
        $exp_from = $request->exp_from;
        $exp_to = $request->exp_to;
        $reason_for_leaving = $request->reason_for_leaving;
        $court_convic = $request->any_conviction;

        for ($i = 0; $i < count($organization); ++$i) {
            if($organization[$i] != null){
                user_experience::create([
                    'emp_id' => $request->employee_id,
                    'organization' => $organization[$i],
                    'prev_position' => $prev_position[$i],
                    'prev_salary' => $prev_salary[$i],
                    'exp_from' => $exp_from[$i],
                    'exp_to' => $exp_to[$i],
                    'reason_for_leaving' => $reason_for_leaving[$i],
                ]);
            }
        }

        if ($court_convic != null && $court_convic != "") {
            $otherconvic = new user_experience();
            $otherconvic->emp_id = $request->employee_id;
            $otherconvic->court_conviction = $court_convic;
            $otherconvic->save();
        }
        if (($request->input('prev_employed') == '1')) {
            if($request->emp_position != null){
                EmployeeHistory::create([
                    'user_id' => Auth::user()->id,
                    'emp_id' => $request->employee_id,
                    'emp_position' => $request->emp_position,
                    'prev_emp_no' => $request->prev_emp_no,
                    'emp_location' => $request->emp_location,
                    'date_from' => $request->date_from,
                    'date_to' => $request->date_to,
                ]);
            }
        }

        $employee = EmployeeDetail::where('id',$request->employee_id)->first();
        $msg = '"'.ucwords($employee->emp_name).'" Employement Added Successfully';
        createLog('employee_action',$msg);

        return redirect()->route('add.references')->with('success', "Form submitted Successfully");
    }

    public function storeEmployeeFamilyData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'memeber_name' => 'required',
            'memeber_relation' => 'required',
            'memeber_occupation' => 'required',
        ]);

        // if ($validator->fails()) {
        //     Session::flash('error', $validator->errors()->first());
        //     Session::flash('alert-class', 'alert-danger');
        // }

        $memeber_name = $request->memeber_name;
        $phone_number = $request->phone_number;
        $memeber_relation = $request->memeber_relation;
        $memeber_age = $request->memeber_age;
        $memeber_occupation = $request->memeber_occupation;
        $place_of_work = $request->place_of_work;
        $emergency_contact = $request->checkbox_value;

        for ($i = 0; $i < count($memeber_name); ++$i) {
            if($memeber_name[$i] != null){
                user_family_refrence::create([
                    'emp_id' => $request->employee_id,
                    'memeber_name' => $memeber_name[$i],
                    'phone_number' => $phone_number[$i],
                    'memeber_relation' => $memeber_relation[$i],
                    'memeber_age' => $memeber_age[$i],
                    'memeber_occupation' => $memeber_occupation[$i],
                    'place_of_work' => $place_of_work[$i],
                    'emergency_contact' => $emergency_contact[$i],
                ]);
            }
        }

        if (($request->input('yesrelative') == '1')) {
            if($request->relative_name != null){
                EmployeeRelative::create([
                    'emp_id' => $request->employee_id,
                    'relative_name' => $request->relative_name,
                    'relative_position' => $request->relative_position,
                    'relative_dept' => $request->relative_dept,
                    'relative_location' => $request->relative_location,
                    'relative_relation' => $request->relative_relation,
                ]);
            }
        }

        if (($request->input('hasrefrence') == '1')) {
            $ref_name = $request->refrence_name;
            $ref_position = $request->ref_position;
            $ref_address = $request->ref_address;
            $ref_phone = $request->ref_phone;

            for ($i = 0; $i < 2; ++$i) {
                if($ref_name[$i] != null){
                    related_refrence::create([
                        'emp_id' => $request->employee_id,
                        'refrence_name' => $ref_name[$i],
                        'ref_position' => $ref_position[$i],
                        'ref_address' => $ref_address[$i],
                        'ref_phone' => $ref_phone[$i],
                    ]);
                }
            }
        }

        $employee = EmployeeDetail::where('id',$request->employee_id)->first();
        $msg = '"'.ucwords($employee->emp_name).'" References Added Successfully';
        createLog('employee_action',$msg);

        return redirect()->route('add.account.detail')->with('success', "Form Submitted Successfully");
    }

    public function storeEmployeeApproval(Request $request)
    {
        $role_id = '3';
        $aproval = new user_approval();
        $aproval->user_id = Auth::user()->id;
        $aproval->emp_id = $request->employee_id;
        $aproval->designation_id = $request->designation_id;
        $aproval->joining_date = date('Y-m-d', strtotime($request->joining_date));
        $aproval->phone_issued = $request->phone_issued;
        $aproval->starting_sal = $request->starting_sal;
        $aproval->job_status_id = $request->job_status_id;
        $aproval->save();

        $employee = EmployeeDetail::where('id', $request->employee_id)->first();
        if($aproval){
            if ($request->emp_email) {
                $employee->update(['emp_email' => $request->emp_email]);

                //create employee as user
                $user = new User;
                $user->role_id = $role_id;
                $user->company_id = $employee->company_id;
                $user->branch_id = $employee->branch_id;
                $user->emp_id = $employee->id;
                $user->email = $employee->emp_email;
                $user->fullname = $employee->emp_name;
                $joiningDate = strtotime($request->joining_date);
                $expiryDate = strtotime('+1 week', $joiningDate);
                $user->expiry_date = date('Y-m-d', $expiryDate);
                $password = Str::random(8);
                $user->password = Hash::make($password);
                $user->is_active = '1';
                $user->save();

                // Send the email with the password
                Mail::send('email.user_password_email', ['password' => $password,'user' => $employee->emp_name,'email' => $request->emp_email,'expiry_date'=>$expiryDate], function($message) use($request){
                    $emailServicesFromName = Setting::where('perimeter', 'smtp_from_name')->first();
                    $emailServicesFromEmail = Setting::where('perimeter', 'smtp_from_email')->first();
                    $message->from($emailServicesFromEmail->value,$emailServicesFromName->value);
                    $message->to($request->emp_email);
                    $message->subject('Welcome to ');
                });
            }
        }
        $data = array();
        $type = "New Employee Added";
        $branch = $employee->branch_id;
        $data['emp_name'] = $employee->emp_name;
        $data['employee_email'] = $employee->emp_email;
        $data['joining_date'] = $request->joining_date;
        $data['employee_phone'] = $employee->emp_phone;
        $createNotification = new NotificationController();
        $createNotification->generateNotification($type,$data,$branch);

        $msg = '"'.ucwords($employee->emp_name).'" Approval Added Successfully';
        createLog('employee_action',$msg);

        return redirect('/employee/directory')->with('success', "Employee Record added Successfully");
    }

    public function delete(Request $request) {
        $emp_documents = EmployeeDocument::findOrFail($request->id);
        $employee = EmployeeDetail::where('id',$emp_documents->emp_id)->first();
        $emp_documents->delete();
        $msg = '"'.ucwords($employee->emp_name).'" Documents Deleted Successfully';
        createLog('employee_action',$msg);
        return response()->json(['success'=>true]);
    }

    public function editEmployeeData($emp_id,$edit=null)
    {
        // user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        $employee_id = base64_decode($emp_id);

        if($user_role == 1){
            $companies = Company::where('is_deleted', '0')->orderBy('company_name','asc')->get();
            $branches = Location::where('is_deleted', '0')->orderBy('branch_name','asc')->get();
        }else{
            $companies = Company::whereIn('id',$user_company_id)->orderBy('company_name','asc')->get();
            $branches = Location::whereIn('company_id',$user_company_id)
                ->whereIn('id',$user_branch_id)
                ->where('is_deleted', '0')
                ->orderBy('branch_name','asc')
                ->get();
        }

        $countries = Country::orderBy('country_name','asc')->get();
        $EmpDetails = EmployeeDetail::findOrFail($employee_id);
        $emp_documents = EmployeeDocument::where('emp_id',$employee_id)->get();
        if($edit == null){
            return view('directory.edit.Editemployee', compact('emp_documents','employee_id', 'branches', 'companies', 'countries', 'EmpDetails'));
        }else{
            return view('directory.views.Empview', compact('emp_documents','employee_id', 'branches', 'companies', 'countries', 'EmpDetails'));
        }
    }

    public function editAccount($emp_id, $edit = null)
    {
        $employee_id = base64_decode($emp_id);
        $accountDetails = AccountDetail::where('emp_id',$employee_id)->first();

        if($edit == null){
            return view('directory.edit.editaccount', compact('employee_id', 'accountDetails'));
        }else{
            return view('directory.views.account_detail', compact('employee_id', 'accountDetails'));
        }
    }

    public function updateEmployeeDetails(Request $request, $id)
    {
        $emp_id = base64_decode($id);
        $validator = Validator::make($request->all(), [
            'emp_id' => 'required|unique:employee_details,emp_id,' . $emp_id,
            'personal_email' => 'required|unique:employee_details,personal_email,' . $emp_id,
            'emp_phone' => 'required|min:10|max:11|unique:employee_details,emp_phone,' . $emp_id,
            'cnic' => 'required|min:10|unique:employee_details,cnic,' . $emp_id,
            'name' => 'required',
            'spouse' => 'max:25',
            'religion' => 'max:20',
            'register_no' => 'max:20',
            'license_number' => 'max:20',
            'branch_id' => 'required',
            'company_id' => 'required',
            'country_id' => 'required',
            'gender' => 'required',
        ]);
        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withinput($request->all())->with('error', "Some of the fields are missing");
        }
        $UpdateEmp = EmployeeDetail::findOrFail($emp_id);

        if ($UpdateEmp) {
            $uploadPath = 'assets/images/users/images/';
                if ($UpdateEmp->emp_image) {
                    $oldLogoPath = public_path($UpdateEmp->emp_image);
                    if (file_exists($oldLogoPath)) {
                        unlink($oldLogoPath);
                    }
                }
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;
                $file->move($uploadPath, $filename);
                $image = $uploadPath . $filename;
            } else {
                $image = $UpdateEmp->emp_image;
            }

        }

        EmployeeDetail::where('id', $emp_id)->update([
            'company_id' => $request->input('company_id'),
            'branch_id' => $request->branch_id,
            'emp_id' => $request->emp_id,
            'personal_email' => $request->personal_email,
            'emp_name' => ucwords($request->name),
            'father_name' => ucwords($request->fathername),
            'mother_name' => ucwords($request->mothername),
            'emp_address' => $request->address,
            'emp_phone' => $request->emp_phone,
            'emp_gender' => $request->input('gender'),
            'cnic' => $request->cnic,
            'dob' => Carbon::parse($request->dateofbirth)->format('Y-m-d'),
            'added_by' => Auth::user()->fullname,
            'nationality' => $request->country_id,
            'city_of_birth' => $request->city_id,
            'religion' => ucwords($request->religion),
            'blood_group' => $request->bloodgroup,
            'marital_status' => $request->marital_status,
            'spouse_name' => ucwords($request->spouse),
            'is_independant' => $request->accomodation,
            'has_home' => $request->accomodation_specify,
            'has_transport' => $request->has_transport,
            'transport_type' => $request->transport_type,
            'registration_no' => $request->register_no,
            'driving_license' => $request->has_license,
            'license_no' => $request->license_number,
            'emp_image' => $image,
        ]);
        $uploadPath = public_path('assets/images/users/documents/');
        $data = [];
        if ($request->hasFile('content_list')) {
            $descriptions = $request->input('document_discription', []);
            foreach ($request->file('content_list') as $index => $file) {
                if ($file && $file->isValid()) {
                    $ext = $file->getClientOriginalExtension();
                    $filename = time() . '_' . $index . '.' . $ext;
                    $file->move($uploadPath, $filename);
                    $description = isset($descriptions[$index]) ? $descriptions[$index] : '';
                    $data[] = [
                        'image' => 'assets/images/users/documents/' . $filename,
                        'description' => $description
                    ];
                }
            }
        }
        foreach ($data as $document) {
            $emp_document = EmployeeDocument::where('emp_id', $emp_id)
                ->where('discription', $document['description'])
                ->first();
            if ($emp_document) {
                $oldImagePath = public_path($emp_document->document_path);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
                $emp_document->document_path = $document['image'];
                $emp_document->save();
            } else {
                $emp_document = new EmployeeDocument;
                $emp_document->emp_id = $emp_id;
                $emp_document->document_path = $document['image'];
                $emp_document->discription = $document['description'];
                $emp_document->save();
            }
        }

        $msg = '"'.ucwords($UpdateEmp->emp_name).'" Updated Successfully';
        createLog('employee_action',$msg);

        if(isset($request->edit) == 'preview'){
            return redirect()->route('add.education')->with('success', "Record Saved Successfully");
        }else{
            return redirect('/employee/directory/edit-education/'.$id)->with('success', "Record Updated Successfully");
        }
    }

    public function profileDetail(Request $request, $emp_id)
    {
        $id = base64_decode($emp_id);
        // user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        $searched_date = isset($request->searchDate)?Carbon::parse($request->searchDate)->format('Y-m'):Carbon::now()->format('Y-m');

        $EmpDetails = EmployeeDetail::findOrFail($id);
        $cities = City::where('city_id',$EmpDetails->city_of_birth)->value('city_name');
        $countries = Country::where('country_id', $EmpDetails->nationality)->value('country_name');
        $EmpAccount = AccountDetail::where('emp_id',$id)->first();
        if($EmpAccount == "" && $EmpAccount == NUll){
            Session::flash('success', 'Please update profile first');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back();
        }
        $empEducation = EmployeeEducationDetail::where('emp_id', $EmpDetails->id)->where('is_deleted', '0')->get();
        if($empEducation == "" && $EmpAccount == NUll){
            Session::flash('success', 'Please Update profile first');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back();
        }
        $emp_primary = user_family_refrence::where('emp_id', $EmpDetails->id)->where('emergency_contact','1')->where('is_deleted', '0')->orderBy('id','asc')->first();
        $empRelation = user_family_refrence::where('emp_id', $EmpDetails->id)->where('is_deleted', '0')->get();
        $emp_secondry = user_family_refrence::where('emp_id', $EmpDetails->id)->where('emergency_contact','1')->where('is_deleted', '0')->orderBy('id','desc')->first();
        $empHistory = user_experience::where('emp_id', $EmpDetails->id)->where('is_deleted', '0')->get();
        $empAprovel = user_approval::with('designation')->where('emp_id', $EmpDetails->id)->where('is_deleted', '0')->first();
        $documents = EmployeeDocument::where('emp_id',$id)->get();
        if($user_role == 1){
            $companies = Company::where('is_deleted','0')->orderBy('company_name','asc')->get();
        }else{
            $companies = Company::whereIn('id',$user_company_id)->orderBy('company_name','asc')->get();
        }
        //LEAVE RECORD
        $leaves = Leave::where('is_approved', '1')
            ->where('is_deleted', '0')
            ->where('emp_id', $EmpDetails->id)
            ->get();
            foreach ($leaves as $key => $leave) {
                $leave->leave_types = Leave_Type::where('id',$leave->leave_type)->first();
            }
            foreach ($leaves as $key => $leave) {
                $leave->total_leaves = Leave_setting::where('is_active','1')->where('is_deleted','0')
                ->where('company_id',$leave->company_id)->first();
            }
                $totalApprovedDays = 0;
                $approvedDaysByType = [];

                foreach ($leaves as $leave) {
                    $leaveType = $leave->leave_types->types;
                    $approvedDays = $leave->approved_days;
                    $totalApprovedDays += $approvedDays;
                    if (!isset($approvedDaysByType[$leaveType])) {
                        $approvedDaysByType[$leaveType] = 0;
                    }
                    $approvedDaysByType[$leaveType] += $approvedDays;
                }
        $employee_approval = DB::table('emp_approvals')->where('emp_id', $id)->first();
        if ($employee_approval) {
            //USER DETAILS
            $month = Carbon::now()->format('m');
            $getUserDetails = EmployeeDetail::find($id);

            //HOLIDAYS RECORD
            $holidays = Holiday::where('company_id',$getUserDetails->company_id)->where('branch_id', $getUserDetails->branch_id)
                ->where('is_active', '1')
                ->where('is_deleted', '0')
                ->where(function ($query) use ($month) {
                    $query->whereMonth('start_date', $month)
                        ->orWhereMonth('end_date', $month);
                })
                ->get();

            $holidayArray = [];

            if ($holidays) {
                foreach ($holidays as $holiday) {
                    $startDate = Carbon::parse($holiday->start_date);
                    $endDate = Carbon::parse($holiday->end_date);
                    while ($startDate->lte($endDate)) {
                        if ($startDate->format('m') == Carbon::now()->format('m')) {
                            $holidayArray[$startDate->toDateString()] = 'Holiday';
                        }
                        $startDate->addDay();
                    }
                }
            }
            $countOfHolidays = count($holidayArray);

            //COMPANY DETAILS
            $company_details = CompanySetting::where('company_id',$getUserDetails->company_id)->where('branch_id', $getUserDetails->branch_id)->first();
            if ($company_details) {
                //OFFICE TIMINGS
                $getStartTime = $company_details->start_time;
                $getEndTime = $company_details->end_time;
                $getOfficeHours = strtotime(Carbon::parse($getEndTime)) - strtotime(Carbon::parse($getStartTime));
                //LUNCH TIMINGS
                $lunchStartTime = $company_details->lunch_start_time;
                $lunchEndTime = $company_details->lunch_end_time;
                $getBreakHours = strtotime(Carbon::parse($lunchEndTime)) - strtotime(Carbon::parse($lunchStartTime));
                //WEEKLY WORKING DAYS
                $diffInDays = explode(",", $company_details->days);
                $getWeeklyWorkingDays = count($diffInDays);
                $getTotalOfficeHours = gmdate('H', abs($getOfficeHours));
            } else {
                $getTotalOfficeHours = 0;
            }

            if (Location::where('id', $getUserDetails->branch_id)->where('company_id', $getUserDetails->company_id)->exists()) {
                $getUserDetails->branch_name = Location::where('id', $getUserDetails->branch_id)->where('company_id', $getUserDetails->company_id)->first()['branch_name'];
                $getUserDetails->branche_id = Location::where('id', $getUserDetails->branch_id)->where('company_id', $getUserDetails->company_id)->first()['branch_id'];
            } else {
                $getUserDetails->branch_name = "";
                $getUserDetails->branche_id = "";
            }
            if (user_approval::where('emp_id', $getUserDetails->id)->exists()) {
                $getUserDetails->employeeDesignation = user_approval::where('emp_id', $getUserDetails->id)->first()['designation_id'];
            } else {
                $getUserDetails->employeeDesignation = "";
            }
            if (isset($getUserDetails->join_date)) {
                $getUserDetails->join_date = Carbon::createFromFormat('Y-m-d', $getUserDetails->join_date)->format('d F Y');
            }
            $getUserDetails->dob = Carbon::createFromFormat('Y-m-d', $getUserDetails->dob)->format('d F Y');

            //TIME UTILIZATION SECTION
            $getCurrentDate = Carbon::now()->format('d F Y');
            $todaysRecord = null;
            $overbreak = null;
            $workingHours = null;
            $workPercantage = null;

            //GET TODAY'S WORKING RECORD
            $todaysRecord = UserAttendence::where('emp_id', $id)->whereDate('created_at', Carbon::now()->format('Y-m-d'))->first();
            if (isset($todaysRecord)) {
                if (isset($todaysRecord) && isset($todaysRecord->check_in) && isset($todaysRecord->check_out)) {
                    $checkIn = Carbon::parse($todaysRecord->check_in);
                    $checkOut = Carbon::parse($todaysRecord->check_out);
                    $timeDifference = strtotime($checkOut) - strtotime($checkIn);
                    $getTimeDiff = $getOfficeHours - $timeDifference;
                    if ($getTimeDiff < 0) {
                        $overbreak = gmdate('H:i', abs($getTimeDiff));
                    }
                }
                if (isset($todaysRecord) && $todaysRecord->check_in != null && $todaysRecord->check_in != "") {
                    $todaysRecord->check_in = Carbon::createFromFormat('H:i:s', $todaysRecord->check_in);
                    $checkInn = Carbon::parse($todaysRecord->check_in);
                    $currentTime = Carbon::now();
                    if ($todaysRecord->check_out != null && $todaysRecord->check_out != "") {
                        $checkOutt = Carbon::parse($todaysRecord->check_out);
                        $activeHours = strtotime($checkOutt) - strtotime($checkInn);
                    } else {
                        $activeHours = strtotime($currentTime) - strtotime($checkInn);
                    }
                    $workingHours = gmdate('H', abs($activeHours));
                    if ($company_details) {
                        $workPercantage = ($workingHours * 100) / $getTotalOfficeHours;
                    } else {
                        $workPercantage = null;
                    }
                } else {
                    $workPercantage = null;
                    $workingHours = null;
                    $todaysRecord->check_in = null;
                }
                if (isset($todaysRecord) && $todaysRecord->check_out != null && $todaysRecord->check_out != "") {

                    $todaysRecord->check_out = Carbon::createFromFormat('H:i:s', $todaysRecord->check_out);
                } else {
                    $todaysRecord->check_out = null;
                }
            }
            //WEEKLY WORKING HOURS
            $startDate = Carbon::now()->startOfWeek()->toDateString();
            $fetchWeekData = UserAttendence::where('emp_id', $id)->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<', Carbon::parse($startDate)->addDays(5)->toDateString())->select('check_in', 'check_out')->get();
            $weekWorkkingHour = null;
            $weekWorkkingMin = null;
            $monthWorkkingHour = null;
            $monthWorkkingMin = null;
            foreach ($fetchWeekData as $data) {
                if ($data->check_in != null && $data->check_out != null) {
                    $weekCheckIn = Carbon::parse($data->check_in);
                    $weekCheckOut = Carbon::parse($data->check_out);
                    $timeDifference = strtotime($weekCheckOut) - strtotime($weekCheckIn);
                    $weekWorkkingHour += gmdate('H', abs($timeDifference));
                    $weekWorkkingMin += gmdate('i', abs($timeDifference));
                }
            }
            $weeklyUsersTime = round($weekWorkkingHour + ($weekWorkkingMin / 60));
            if ($company_details) {
                $totalWeeklyHours = $getTotalOfficeHours * $getWeeklyWorkingDays;
                $weekTimePercentage = ($weeklyUsersTime * 100) / $totalWeeklyHours;
            } else {
                $totalWeeklyHours = 0;
                $weekTimePercentage = 0;
            }

            $weeklyData = [
                'weeklyUsersTime' => $weeklyUsersTime,
                'totalWeeklyHours' => $totalWeeklyHours,
                'weekTimePercentage' => $weekTimePercentage,
            ];
            //MONTHLY WORKING HOURS
            $MonthstartDate = Carbon::now()->startOfMonth()->toDateString();
            if (isset($request->date)) {
                $MonthstartDate = date('Y-m-d', strtotime($request->date));
                $fetchMonthData = UserAttendence::where('emp_id', $id)->whereBetween('created_at', [$MonthstartDate, Carbon::now()])->select('check_in', 'check_out')->get();
            } elseif (isset($request->month)) {
                $MonthstartDate = date('Y-m-d', strtotime($request->month));
                $fetchMonthData = UserAttendence::where('emp_id', $id)->whereBetween('created_at', [$MonthstartDate, Carbon::now()])->select('check_in', 'check_out')->get();
            } else {
                $fetchMonthData = UserAttendence::where('emp_id', $id)->whereBetween('created_at', [$MonthstartDate, Carbon::now()])->select('check_in', 'check_out')->get();
            }
            foreach ($fetchMonthData as $data) {
                $monthCheckIn = Carbon::parse($data->check_in);
                $monthCheckOut = Carbon::parse($data->check_out);
                $timeDifference = strtotime($monthCheckOut) - strtotime($monthCheckIn);
                $monthWorkkingHour += gmdate('H', abs($timeDifference));
                $monthWorkkingMin += gmdate('i', abs($timeDifference));
            }
            $currentMonth = Carbon::now()->month;
            $totalDays = Carbon::now()->daysInMonth;
            $workingDays = 0;
            for ($day = 1; $day <= $totalDays; $day++) {
                $date = Carbon::createFromDate(null, $currentMonth, $day);
                if ($date->isWeekday()) {
                    $workingDays++;
                }
            }
            $workingDays = $workingDays - $countOfHolidays;
            $monthlyUsersTime = round($monthWorkkingHour + ($monthWorkkingMin / 60));
            if ($company_details) {
                $totalMonthlyHours = $getTotalOfficeHours * $workingDays;
                $monthTimePercentage = ($monthlyUsersTime * 100) / $totalMonthlyHours;
            } else {
                $totalMonthlyHours = 0;
                $monthTimePercentage = 0;
            }
            $monthlyData = [
                'monthlyuserTime' => $monthlyUsersTime,
                'totalMonthlyHours' => $totalMonthlyHours,
                'monthTimePercentage' => $monthTimePercentage,
            ];
            //EMPLOYEE  ATTENDANCE
            $userSearchedMonth = isset($request->month) ? $request->month : date('F Y');
            $getEmployeeAttendance = $this->ProfileAttendance($id, $month = null);
            $branch_id =  Location::where('id',$EmpDetails->branch_id)->first();
            return view('directory.employee_profile', compact('cities','countries','leaves','totalApprovedDays','approvedDaysByType','branch_id','documents','EmpAccount','getEmployeeAttendance', 'empHistory', 'empEducation', 'empRelation', 'emp_secondry', 'emp_primary', 'empAprovel', 'companies', 'getTotalOfficeHours', 'userSearchedMonth', 'id', 'getUserDetails', 'getCurrentDate', 'todaysRecord', 'overbreak', 'workingHours', 'workPercantage', 'weeklyData', 'monthlyData', 'EmpDetails','holidayArray'));
        } else {
            return abort(404);
        }
    }

    public function searchEmployeeAttendance(Request $request)
    {
        $getEmployeeAttendance = $this->ProfileAttendance($request->employee_id, $request->month);
        return response()->json(['success'=>true,'data'=>$getEmployeeAttendance]);
    }

    public function ProfileAttendance($id, $month)
    {
        $emp = EmployeeDetail::with('resignations','leaves','holidays')->where('id', $id)->first();
        $company_setting = CompanySetting::where('company_id',$emp->company_id)
            ->where('branch_id', $emp->branch_id)
            ->where('is_deleted', '0')
            ->first();

        if ($company_setting) {
            $half_day = $company_setting->half_day;
        } else {
            $half_day = 0;
        }

        $month = Carbon::parse($month)->format('Y-m');
        $carbonDate = Carbon::createFromFormat('Y-m', $month);
        $month = $carbonDate->format('m');
        $year = $carbonDate->format('Y');
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $dates = [];
        $UserAttendancedata = [];
        $leavesArray = [];
        $holidaysArray = [];

        while ($startDate->lte($endDate)) {
            $dates[] = $startDate->format('Y-m-d');
            $startDate->addDay();
        }

        //get leaves
        foreach ($emp->leaves as $leaves) {
            if (date('Y-m',strtotime($leaves['from_date'])) <= date('Y-m',strtotime($year-$month))|| date('Y-m',strtotime($leaves['to_date'])) <= date('Y-m',strtotime($year-$month))) {
                $startDate = Carbon::parse($leaves['from_date']);
                $endDate = Carbon::parse($leaves['to_date']);
                while ($startDate->lte($endDate)) {
                    if ($startDate->format('m') == $month && $startDate->format('Y') == $year) {
                        $leavesArray[$startDate->toDateString()] = 'Leave';
                    }
                    // Move to the next date
                    $startDate->addDay();
                }
            }
        }
        $eligibleHolidays = Holiday::where('is_deleted', '0')
        ->where('is_active', '1')
        ->get();
        foreach ($eligibleHolidays as $holiday) {
            $holidayCompanyIds = explode(',', $holiday->company_id);
            $holidayBranchIds = explode(',', $holiday->branch_id);

            if (in_array($emp->company_id, $holidayCompanyIds) && in_array($emp->branch_id, $holidayBranchIds)) {
                $startDate = Carbon::parse($holiday->start_date);
                $endDate = Carbon::parse($holiday->end_date);

                while ($startDate->lte($endDate)) {
                    if ($startDate->format('m') == $month && $startDate->format('Y') == $year) {
                        $holidaysArray[$startDate->toDateString()] = [
                            $holiday->event_name
                        ];
                    }
                    $startDate->addDay();
                }
            }
        }
        foreach ($dates as $date) {
            if (UserAttendence::where('emp_id', $id)->whereDate('created_at', $date)->exists()) {
                $UserAttendancedata1 = UserAttendence::where('emp_id', $id)->whereDate('created_at', $date)->first();
                if ($UserAttendancedata1->check_in != null) {
                    $UserAttendancedata1->check_in = Carbon::createFromFormat('H:i:s', $UserAttendancedata1->check_in);
                } else {
                    $UserAttendancedata1->check_in = null;
                }
                if ($UserAttendancedata1->check_out != null) {
                    $UserAttendancedata1->check_out = Carbon::createFromFormat('H:i:s', $UserAttendancedata1->check_out);
                } else {
                    $UserAttendancedata1->check_out = null;
                }
                $created_at = Carbon::parse($UserAttendancedata1->created_at);
                $UserAttendancedata1->newDate = $created_at->format('d F Y');
                $UserAttendancedata1->isWeekDay = $created_at->isWeekend();
                if ($UserAttendancedata1->check_in != null && $UserAttendancedata1->check_out != null) {
                    $startTime = Carbon::parse($UserAttendancedata1->check_in);
                    $endTime = Carbon::parse($UserAttendancedata1->check_out);
                    $duration = $endTime->diffInMinutes($startTime);

                    $UserAttendancedata1->totalProduction = floor($duration / 60) . 'h:' . ($duration - floor($duration / 60) * 60) . 'm';
                    if ($UserAttendancedata1->isWeekDay == true) {
                        $UserAttendancedata1->Present = "Weekend";
                    } else if ($duration <= ($half_day)*60) {
                        $UserAttendancedata1->Present = "Half Day";
                    } else {
                        $UserAttendancedata1->Present = "Full Day";
                    }
                } else {
                    $UserAttendancedata1->Present = "Present";
                }
                array_push($UserAttendancedata, $UserAttendancedata1);
            } else {
                $attend = '';
                $UserAttendancedata1 = new UserAttendence(); // Create a new instance
                if(array_key_exists($date,$holidaysArray)){
                    $attend = "Holiday";
                }elseif(array_key_exists($date,$leavesArray)){
                    $attend = "Leave";
                }elseif($UserAttendancedata1->isWeekDay == true){
                    $attend = "Weekend";
                }else{
                    $attend = "Absent";
                }
                $newDate = Carbon::parse($date);
                $UserAttendancedata1->newDate = $newDate->format('d F Y');
                $UserAttendancedata1->isWeekDay = $newDate->isWeekend();
                $UserAttendancedata1->check_in = null;
                $UserAttendancedata1->check_out = null;
                $UserAttendancedata1->totalProduction = null;
                $UserAttendancedata1->Present =  $attend;

                array_push($UserAttendancedata, $UserAttendancedata1);
            }
        }
        return $UserAttendancedata;
    }

    public function updateEmergency(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'member_name' => 'required',
            'relation' => 'required',
            'phone_number' => 'required'
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput($request->all())->with('error', "Some of the fields are missing");
        }
        $emergency = user_family_refrence::findOrFail($request->id);
        $emergency->memeber_name = $request->member_name;
        $emergency->phone_number = $request->phone_number;
        $emergency->memeber_relation = $request->relation;
        $emergency->update();
        $employee = EmployeeDetail::where('id',$emergency->emp_id)->first();
        $msg = '"'.ucwords($employee->emp_name).'"Family Updated Successfully';
        createLog('employee_action',$msg);

        return redirect()->back()->with('success', 'Contact Updated Successfully');
    }

    public function editEmergency(Request $request)
    {
        $emergency = user_family_refrence::where('id', $request->id)
                ->whereNotNull('emergency_contact')
                ->get();
        return response()->json(['success' => true, 'data' => $emergency]);
    }

    public function editBankAccount(Request $request)
    {
        $account = AccountDetail::where('emp_id', $request->employee_id)->where('is_deleted','0')->first();
        return response()->json(['success' => true, 'data' => $account]);
    }

    public function editEmployeeEducation($emp_id,$edit = null)
    {
        $employee_id = base64_decode($emp_id);
        $EmpLanguages = user_language::with('language')->where('emp_id', $employee_id)->get();
        $language_name = [];
        foreach ($EmpLanguages as $languages) {
            $language_name[] = $languages->language->language_name;
        }
        $EmpEducation = EmployeeEducationDetail::where('emp_id', $employee_id)->where('other_qualifications', null)->get();
        $Empotherqalifications = EmployeeEducationDetail::where('emp_id', $employee_id)->where('other_qualifications', '!=', null)->first();
        if($edit == null){
            return view('directory.edit.editEmpEducation', compact('employee_id', 'EmpEducation', 'language_name', 'Empotherqalifications'));
        }else{
            return view('directory.views.Empeducationview', compact('employee_id', 'EmpEducation', 'language_name', 'Empotherqalifications'));
        }
    }

    public function updateEmployeeEducation(Request $request, $id)
    {
        $emp_id = base64_decode($id);
        $Edu_id = $request->edu_id;
        $emp_degree = $request->emp_degree;
        $major_sub = $request->major_sub;
        $grade_division = $request->grade_division;
        $degree_from = $request->degree_from;
        $degree_to = $request->degree_to;
        $institue = $request->institute;
        $other_qualification = $request->other_qualifications;

        for ($i = 0; $i < count($emp_degree); ++$i) {
            if (isset($Edu_id[$i])) {
                EmployeeEducationDetail::where('emp_id', $emp_id)->where('id', $Edu_id[$i])->update([
                    'degree' => $emp_degree[$i],
                    'subject' => $major_sub[$i],
                    'grade' => $grade_division[$i],
                    'division' => $grade_division[$i],
                    'degree_from' => $degree_from[$i],
                    'degree_to' => $degree_to[$i],
                    'institution' => $institue[$i],
                ]);
            } else {
                if($emp_degree[$i] != null){
                    EmployeeEducationDetail::create([
                        'emp_id' => $emp_id,
                        'degree' => $emp_degree[$i],
                        'subject' => $major_sub[$i],
                        'grade' => $grade_division[$i],
                        'division' => $grade_division[$i],
                        'degree_from' => $degree_from[$i],
                        'degree_to' => $degree_to[$i],
                        'institution' => $institue[$i],
                    ]);
                }
            }
        }

        if ($other_qualification != null && $other_qualification != "") {
            if (!empty($request->other_qali_ID)) {
                $otherEdu = EmployeeEducationDetail::where('emp_id', $emp_id)->where('id', $request->other_qali_ID)->first();
                $otherEdu->other_qualifications = $request->other_qualifications;
                $otherEdu->update();
            } else {
                $otherEdu = new EmployeeEducationDetail;
                $otherEdu->emp_id = $emp_id;
                $otherEdu->other_qualifications = $request->other_qualifications;
                $otherEdu->save();
            }
        }

        if (isset($request->emp_languages)) {
            foreach($request->emp_languages as $language){
                $getlanguage = Language::where('language_name', $language)->select('id')->first();
                if($getlanguage){
                    $language_id = $getlanguage->id;
                    $exists = user_language::where('emp_id', $emp_id)->where('language_id', $language_id)->first();
                    if($exists){
                        $exists->update([
                            'language_id' => $language_id,
                        ]);
                    } else {
                        user_language::create([
                            'emp_id' => $emp_id,
                            'language_id' => $language_id,
                        ]);
                    }
                }
            }
        }

        $langData = array();

        if (!empty($request->input('other_emp_language'))) {
            $langsArray=[];
            $emp_lang = $request->other_emp_language;
            $cleaned_langs = preg_replace('/\s*,\s*/', ',', $emp_lang);
            $langsArray = explode(',', ucfirst($cleaned_langs));

            foreach($langsArray as $langs){
                $languageExists = Language::where('language_name', $langs)->select('id')->first();
                if ($languageExists) {
                    $otherLang = $languageExists->id;
                    array_push($langData, $otherLang);
                } else {
                    $AddLang = Language::create([
                        'language_name' => $langs,
                    ]);
                    $otherLang = $AddLang->id;
                    array_push($langData, $otherLang);
                }
            }
        }

        $newdata = array_unique($langData, SORT_REGULAR);

        for ($i = 0; $i < count($newdata); ++$i) {
            $exists = user_language::where('emp_id', $emp_id)->where('language_id', $newdata[$i])->first();
            if($exists){
                $exists->update([
                    'language_id' => $newdata[$i],
                ]);
            } else {
                user_language::create([
                    'emp_id' => $emp_id,
                    'language_id' => $newdata[$i],
                ]);
            }
        }

        $employee = EmployeeDetail::where('id',$emp_id)->first();
        $msg = '"'.ucwords($employee->emp_name).'" Education Updated Successfully';
        createLog('employee_action',$msg);

        if(isset($request->edit) == 'preview'){
            return redirect()->route('add.employment')->with('success', "Record Saved Successfully");
        }else{
            return redirect('/employee/directory/edit-experiences/'.$id)->with('success', "Record Updated Successfully");
        }
    }

    public function destroyEducation(Request $request){
        EmployeeEducationDetail::where('id',$request->id)->delete();
        $msg =  'Education Deleted Successfully';
        createLog('employee_action',$msg);
        return response()->json('success',true);
    }

    public function editEmployeeExperiences($emp_id,$edit=null)
    {
        $employee_id = base64_decode($emp_id);
        $EmpExperience = user_experience::where('emp_id', $employee_id)->where('court_conviction', null)->where('organization', '!=', null)->get();
        $EmpByViion = EmployeeHistory::where('emp_id', $employee_id)->get();
        $anyconvic = user_experience::where('emp_id', $employee_id)->where('court_conviction', '!=', null)->first();

        if($edit == null){
            return view('directory.edit.editEmpExperiences', compact('employee_id', 'EmpExperience', 'EmpByViion', 'anyconvic'));
        }else{
            return view('directory.views.Empexperienceview', compact('employee_id', 'EmpExperience', 'EmpByViion', 'anyconvic'));
        }
    }

    public function updateEmployeeExperience(Request $request, $id)
    {
        $emp_id = base64_decode($id);
        $Exp_id = $request->exp_id;
        $organization = $request->organization;
        $prev_position = $request->prev_position;
        $prev_salary = $request->prev_salary;
        $exp_from = $request->exp_from;
        $exp_to = $request->exp_to;
        $reason_for_leaving = $request->reason_for_leaving;
        $court_convic = $request->any_conviction;
        for ($i = 0; $i < count($organization); ++$i) {
            if (isset($Exp_id[$i])) {
                user_experience::where('emp_id', $emp_id)->where('id', $Exp_id[$i])->update([
                    'organization' => $organization[$i],
                    'prev_position' => $prev_position[$i],
                    'prev_salary' => $prev_salary[$i],
                    'exp_from' => $exp_from[$i],
                    'exp_to' => $exp_to[$i],
                    'reason_for_leaving' => $reason_for_leaving[$i],
                ]);
            } else {
                if($organization[$i] != null){
                    user_experience::create([
                        'emp_id' => $emp_id,
                        'organization' => $organization[$i],
                        'prev_position' => $prev_position[$i],
                        'prev_salary' => $prev_salary[$i],
                        'exp_from' => $exp_from[$i],
                        'exp_to' => $exp_to[$i],
                        'reason_for_leaving' => $reason_for_leaving[$i],
                    ]);
                }
            }
        }
        if ($court_convic != null && $court_convic != "") {
            if (!empty($request->any_convic_ID)) {
                $any_court_convic = user_experience::where('emp_id', $emp_id)->where('id', $request->any_convic_ID)->first();
                $any_court_convic->court_conviction = $court_convic;
                $any_court_convic->update();
            } else {
                $otherconvic = new user_experience();
                $otherconvic->emp_id = $emp_id;
                $otherconvic->court_conviction = $court_convic;
                $otherconvic->save();
            }
        }

        $find = EmployeeHistory::where('emp_id', $emp_id)->first();
        if (!empty($find)) {
            EmployeeHistory::where('emp_id', $emp_id)->update([
                'user_id' => Auth::user()->id,
                'emp_position' => $request->emp_position,
                'prev_emp_no' => $request->prev_emp_no,
                'emp_location' => $request->emp_location,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
            ]);
        } else {
            if (($request->input('prev_employed') == '1')) {
                EmployeeHistory::create([
                    'user_id' => Auth::user()->id,
                    'emp_id' => $emp_id,
                    'emp_position' => $request->emp_position,
                    'prev_emp_no' => $request->prev_emp_no,
                    'emp_location' => $request->emp_location,
                    'date_from' => $request->date_from,
                    'date_to' => $request->date_to,
                ]);
            }
        }

        $employee = EmployeeDetail::where('id',$emp_id)->first();
        $msg = '"'.ucwords($employee->emp_name).'" Employement Updated Successfully';
        createLog('employee_action',$msg);

        if(isset($request->edit) == 'preview'){
            return redirect()->route('add.references')->with('success', "Record Saved Successfully");
        }else{
            return redirect('/employee/directory/edit-references/'.$id)->with('success', "Record Updated Successfully");
        }
    }

    public function updateAccountDetail(Request $request, $id)
    {
        $emp_id = base64_decode($id);
        $validator = Validator::make($request->all(), [
            'bank_name' => 'required',
            'account_number' => 'required',
            'ifsc_code' => 'required',
            'pan' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', "Some of the fields are missing");
        }
        $updateAccount = AccountDetail::where('emp_id',$emp_id)->first();
        if($updateAccount){
            $updateAccount->bank_name = $request->bank_name;
            $updateAccount->account_no = $request->account_number;
            $updateAccount->ifsc_code = $request->ifsc_code;
            $updateAccount->pan_no = $request->pan;
            $updateAccount->update();
        }else{
            if($request->bank_name != null){
                $createNew = new AccountDetail;
                $createNew->emp_id = $emp_id;
                $createNew->bank_name = $request->bank_name;
                $createNew->account_no = $request->account_number;
                $createNew->ifsc_code = $request->ifsc_code;
                $createNew->pan_no = $request->pan;
                $createNew->save();
            }
        }

        $employee = EmployeeDetail::where('id',$emp_id)->first();
        $msg = '"'.ucwords($employee->emp_name).'" Account Updated Successfully';
        createLog('employee_action',$msg);

        if(isset($request->edit) == 'preview'){
            return redirect()->route('add.approval')->with('success', "Record Saved Successfully");
        }else{
            return redirect('/employee/directory/edit-approval/'.$id)->with('success', "Record Updated Successfully");
        }
    }
    public function updateAccountDetailProfile(Request $request, $id)
    {
        $emp_id = base64_decode($id);
        $validator = Validator::make($request->all(), [
            'bank_name' => 'required',
            'account_number' => 'required',
            'ifsc_code' => 'required',
            'pan' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', "Some of the fields are missing");
        }
        $updateAccount = AccountDetail::where('emp_id',$emp_id)->first();
        if($updateAccount){
            $updateAccount->bank_name = $request->bank_name;
            $updateAccount->account_no = $request->account_number;
            $updateAccount->ifsc_code = $request->ifsc_code;
            $updateAccount->pan_no = $request->pan;
            $updateAccount->update();
        }else{
            if($request->bank_name != null){
                $createNew = new AccountDetail;
                $createNew->emp_id = $emp_id;
                $createNew->bank_name = $request->bank_name;
                $createNew->account_no = $request->account_number;
                $createNew->ifsc_code = $request->ifsc_code;
                $createNew->pan_no = $request->pan;
                $createNew->save();
            }
        }

        $employee = EmployeeDetail::where('id',$emp_id)->first();
        $msg = '"'.ucwords($employee->emp_name).'" Account Updated Successfully';
        createLog('employee_action',$msg);

        if(isset($request->edit) == 'preview'){
            return redirect()->route('add.approval')->with('success', "Record Saved Successfully");
        }else{
            return redirect()->back()->with('success', "Account Details Updated Successfully");
        }
    }

    public function editEmployeeRefrences($emp_id, $edit=null)
    {
        $employee_id = base64_decode($emp_id);
        $EmpRefrences = user_family_refrence::where('emp_id', $employee_id)->get();
        $EmpRelative = EmployeeRelative::where('emp_id', $employee_id)->get();
        $EmpRelatedRef = related_refrence::where('emp_id', $employee_id)->get();

        if($edit == null){
            return view('directory.edit.editEmpRefrences', compact('employee_id', 'EmpRefrences', 'EmpRelative', 'EmpRelatedRef'));
        }else{
            return view('directory.views.Emprefrencesview', compact('employee_id', 'EmpRefrences', 'EmpRelative', 'EmpRelatedRef'));
        }
    }

    public function updateEmployeeRefrences(Request $request, $id)
    {
        $emp_id = base64_decode($id);
        $Ref_id = $request->Ref_id;
        $memeber_name = $request->memeber_name;
        $phone_number = $request->phone_number;
        $memeber_relation = $request->memeber_relation;
        $memeber_age = $request->memeber_age;
        $memeber_occupation = $request->memeber_occupation;
        $place_of_work = $request->place_of_work;
        $emergency_contact = $request->checkbox_value;

        for ($i = 0; $i < count($memeber_name); ++$i) {
            if (isset($Ref_id[$i])) {
                user_family_refrence::where('emp_id', $emp_id)->where('id', $Ref_id[$i])->update([
                    'memeber_name' => $memeber_name[$i],
                    'phone_number' => $phone_number[$i],
                    'memeber_relation' => $memeber_relation[$i],
                    'memeber_age' => $memeber_age[$i],
                    'memeber_occupation' => $memeber_occupation[$i],
                    'place_of_work' => $place_of_work[$i],
                    'emergency_contact' => $emergency_contact[$i],
                ]);
            } else {
                if($memeber_name[$i] != null){
                    user_family_refrence::create([
                        'emp_id' => $emp_id,
                        'memeber_name' => $memeber_name[$i],
                        'phone_number' => $phone_number[$i],
                        'memeber_relation' => $memeber_relation[$i],
                        'memeber_age' => $memeber_age[$i],
                        'memeber_occupation' => $memeber_occupation[$i],
                        'place_of_work' => $place_of_work[$i],
                        'emergency_contact' => $emergency_contact[$i],
                    ]);
                }
            }
        }

        $find = EmployeeRelative::where('emp_id', $emp_id)->first();
        if (!empty($find)) {
            if (($request->input('yesrelative') == '1')) {
                EmployeeRelative::where('emp_id', $emp_id)->update([
                    'relative_name' => $request->relative_name,
                    'relative_position' => $request->relative_position,
                    'relative_dept' => $request->relative_dept,
                    'relative_location' => $request->relative_location,
                    'relative_relation' => $request->relative_relation
                ]);
            }
        } else {
            if (($request->input('yesrelative') == '1')) {
                EmployeeRelative::create([
                    'emp_id' => $emp_id,
                    'relative_name' => $request->relative_name,
                    'relative_position' => $request->relative_position,
                    'relative_dept' => $request->relative_dept,
                    'relative_location' => $request->relative_location,
                    'relative_relation' => $request->relative_relation,
                ]);
            }
        }

        $find = related_refrence::where('emp_id', $emp_id)->first();
        if (!empty($find)) {
            if (($request->input('hasrefrence') == '1')) {
                $ref_id = $request->refrence_id;
                $ref_name = $request->refrence_name;
                $ref_position = $request->ref_position;
                $ref_address = $request->ref_address;
                $ref_phone = $request->ref_phone;

                for ($i = 0; $i < count($ref_name); ++$i) {
                    related_refrence::where('emp_id', $emp_id)->where('id', $ref_id[$i])->update([
                        'refrence_name' => $ref_name[$i],
                        'ref_position' => $ref_position[$i],
                        'ref_address' => $ref_address[$i],
                        'ref_phone' => $ref_phone[$i],
                    ]);
                }
            }
        } else {
            if (($request->input('hasrefrence') == '1')) {
                $ref_name = $request->refrence_name;
                $ref_position = $request->ref_position;
                $ref_address = $request->ref_address;
                $ref_phone = $request->ref_phone;

                for ($i = 0; $i < 2; ++$i) {
                    related_refrence::create([
                        'emp_id' => $emp_id,
                        'refrence_name' => $ref_name[$i],
                        'ref_position' => $ref_position[$i],
                        'ref_address' => $ref_address[$i],
                        'ref_phone' => $ref_phone[$i],
                    ]);
                }
            }
        }

        $employee = EmployeeDetail::where('id',$emp_id)->first();
        $msg = '"'.ucwords($employee->emp_name).'" References Updated Successfully';
        createLog('employee_action',$msg);

        if(isset($request->edit) == 'preview'){
            return redirect()->route('add.account.detail')->with('success', "Record Saved Successfully");
        }else{
            return redirect('/employee/directory/edit-account-detail/'.$id)->with('success', "Record Updated Successfully");
        }
    }

    public function editEmployeeApproval($emp_id)
    {
        $employee_id = base64_decode($emp_id);
        $EmpApproval = user_approval::where('emp_id', $employee_id)->first();
        $empDetail = EmployeeDetail::select('id','emp_email')->where('id', $employee_id)->first();
        $designations = Designation::orderBy('name','asc')->get();
        $job_status = Job_type::orderBy('job_status','asc')->get();

        return view('directory.edit.editEmpApproval', compact('job_status','employee_id', 'EmpApproval','empDetail','designations'));
    }

    public function updateEmployeeApproval(Request $request, $id)
    {
        $role_id = '3';
        $emp_id = base64_decode($id);
        $aproval = user_approval::where('emp_id', $emp_id)->first();
        if ($aproval) {
            $aproval->designation_id = $request->designation_id;
            $aproval->joining_date = date('Y-m-d',strtotime($request->joining_date));
            $aproval->phone_issued = $request->phone_issued;
            $aproval->starting_sal = $request->starting_sal;
            $aproval->job_status_id = $request->job_status_id;
            $aproval->update();
        } else {
            if($request->designation_id != null){
                user_approval::create([
                    'user_id' => Auth::user()->id,
                    'emp_id' => $emp_id,
                    'designation_id' => $request->designation_id,
                    'joining_date' => date('Y-m-d',strtotime($request->joining_date)),
                    'phone_issued' => $request->phone_issued,
                    'starting_sal' => $request->starting_sal,
                    'job_status_id' => $request->job_status_id,
                ]);
            }
        }

        $employee = EmployeeDetail::where('id',$emp_id)->first();
        $User =  User::where('emp_id',$emp_id)->first();
        if(isset($request->emp_email)){
            //if employee exists and emp_email is changed then it will update to the email
            if($request->emp_email != $employee->emp_email){
                $employee->update(['emp_email'=>$request->emp_email]);
            }
            //if user exists and email is changed then it will update to the email
            if($User){
                if($request->emp_email != $User->email){
                    $User->update(['email'=>$request->emp_email]);
                }
            }else{
                $user = new User;
                $user->role_id = $role_id;
                $user->company_id = $employee->company_id;
                $user->branch_id = $employee->branch_id;
                $user->emp_id = $employee->id;
                $user->email = $employee->emp_email;
                $user->fullname = $employee->emp_name;
                $joiningDate = strtotime($request->joining_date);
                $expiryDate = strtotime('+1 week', $joiningDate);
                $user->expiry_date = date('Y-m-d', $expiryDate);
                $password = Str::random(8);
                $user->password = Hash::make($password);
                $user->is_active = '1';
                $user->save();
                // Send the email with the password
                Mail::send('email.user_password_email', ['password' => $password,'user' => $employee->emp_name,'email' => $request->emp_email,'expiry_date'=>$expiryDate], function($message) use($request){
                    $emailServicesFromName = Setting::where('perimeter', 'smtp_from_name')->first();
                    $emailServicesFromEmail = Setting::where('perimeter', 'smtp_from_email')->first();
                    $message->from($emailServicesFromEmail->value,$emailServicesFromName->value);
                    $message->to($request->emp_email);
                    $message->subject('Welcome to ');
                });
            }
        }else{
            $employee->update(['emp_email'=>$request->emp_email]);
            if($User){
                $User->update(['email'=>'']);
            }
        }
        $msg = '"'.ucwords($employee->emp_name).'" Approval Updated Successfully';
        createLog('employee_action',$msg);

        return redirect()->back()->with('success', "Record Updated Successfully");
    }

    public function changeEmployeeApprovalStatus(Request $request)
    {
        $emp_id = $request->emp_id;
        $status = $request->status;

        $role_id = auth()->user()->role_id;

        $aproval = user_approval::where('emp_id', $emp_id)->first();
        $employee_detail = EmployeeDetail::where('id', $emp_id)->first();

        if ($aproval && $employee_detail) {
            if($status == 'approved'){
                if ($role_id == '1'){
                    $aproval->approved_by_CEO = '1';
                    $aproval->is_active = '1';
                    $employee_detail->status = '1';
                    $employee_detail->is_active = '1';
                }else if ($role_id == '2'){
                    $aproval->approved_by_HR = '1';
                }
            }else if($status == 'declined'){
                if ($role_id == '1'){
                    $aproval->approved_by_CEO = '0';
                    $aproval->is_active = '0';
                    $employee_detail->status = '2';
                    $employee_detail->is_active = '0';
                }else if ($role_id == '2'){
                    $aproval->approved_by_HR = '0';
                }
            }else if($status == 'resigned'){
                if ($role_id == '1'){
                    $employee_detail->status = '3';
                }
            }else if($status == 'terminate'){
                if ($role_id == '1'){
                    $employee_detail->status = '4';
                }
            }
            $aproval->update();
            $employee_detail->update();

            $msg = '"'.ucwords($employee_detail->emp_name).'" Status "'.$status.'" Updated';
            createLog('employee_action',$msg);

            return response()->json(['success'=>true,'message'=>"Status Updated Successfully!"]);
        }

        return response()->json(['success'=>false,'message'=>"Status Not Updated"]);
    }

    public function destroy($id)
    {
        $emp_id = base64_decode($id);
        $delEmp = EmployeeDetail::findOrFail($emp_id);
        $delEmp->is_deleted = "1";
        $delEmp->update();
        $empEdu = EmployeeEducationDetail::where('emp_id', $emp_id)->get();
        if($empEdu){
            foreach ($empEdu as $key => $empup) {
                $empup->update(['is_deleted' => '1']);
            }
        }
        $empPro = EmployeePromotion::where('emp_id',$emp_id)->get();
        if($empPro){
            foreach ($empPro as $key => $empup) {
                $empup->update(['is_deleted' => '1']);
            }
        }
        $empAccount = AccountDetail::where('emp_id',$emp_id)->get();
        if($empAccount){
            foreach ($empAccount as $key => $empup) {
                $empup->update(['is_deleted' => '1']);
            }
        }
        $empTem = Emp_termination::where('emp_id',$emp_id)->get();
        if($empTem){
            foreach ($empTem as $key => $empup) {
                $empup->update(['is_deleted' => '1']);
            }
        }
        $empRes = EmployeeResignation::where('emp_id',$emp_id)->get();
        if($empRes){
            foreach ($empRes as $key => $empup) {
                $empup->update(['is_deleted' => '1']);
            }
        }
        $empLeave = Leave::where('emp_id',$emp_id)->get();
        if($empLeave){
            foreach ($empLeave as $key => $empup) {
                $empup->update(['is_deleted' => '1']);
            }
        }
        $empLangh = user_language::where('emp_id',$emp_id)->get();
        if($empLangh){
            foreach ($empLangh as $key => $empup) {
                $empup->update(['is_deleted' => '1']);
            }
        }
        $empFamily = user_family_refrence::where('emp_id',$emp_id)->get();
        if($empFamily){
            foreach ($empFamily as $key => $empup) {
                $empup->update(['is_deleted' => '1']);
            }
        }
        $empExp = user_experience::where('emp_id',$emp_id)->get();
        if($empExp){
            foreach ($empExp as $key => $empup) {
                $empup->update(['is_deleted' => '1']);
            }
        }
        user_approval::where('emp_id',$emp_id)->update(['is_deleted' => '1']);
        user_allowance::where('emp_id',$emp_id)->update(['is_deleted' => '1']);
        $msg = '"'.ucwords($delEmp->emp_name).'" deleted';
        createLog('employee_action',$msg);

        return redirect()->back()->with('success', 'Record Deleted Successfully');
    }
    public function restore($id)
    {
        $emp_id = base64_decode($id);
        $delEmp = EmployeeDetail::findOrFail($emp_id);
        $delEmp->is_deleted = "0";
        $delEmp->update();
        $empEdu = EmployeeEducationDetail::where('emp_id', $emp_id)->get();
        if($empEdu){
            foreach ($empEdu as $key => $empup) {
                $empup->update(['is_deleted' => '0']);
            }
        }
        $empPro = EmployeePromotion::where('emp_id',$emp_id)->get();
        if($empPro){
            foreach ($empPro as $key => $empup) {
                $empup->update(['is_deleted' => '0']);
            }
        }
        $empAccount = AccountDetail::where('emp_id',$emp_id)->get();
        if($empAccount){
            foreach ($empAccount as $key => $empup) {
                $empup->update(['is_deleted' => '0']);
            }
        }
        $empTem = Emp_termination::where('emp_id',$emp_id)->get();
        if($empTem){
            foreach ($empTem as $key => $empup) {
                $empup->update(['is_deleted' => '0']);
            }
        }
        $empRes = EmployeeResignation::where('emp_id',$emp_id)->get();
        if($empRes){
            foreach ($empRes as $key => $empup) {
                $empup->update(['is_deleted' => '0']);
            }
        }
        $empLeave = Leave::where('emp_id',$emp_id)->get();
        if($empLeave){
            foreach ($empLeave as $key => $empup) {
                $empup->update(['is_deleted' => '0']);
            }
        }
        $empLangh = user_language::where('emp_id',$emp_id)->get();
        if($empLangh){
            foreach ($empLangh as $key => $empup) {
                $empup->update(['is_deleted' => '0']);
            }
        }
        $empFamily = user_family_refrence::where('emp_id',$emp_id)->get();
        if($empFamily){
            foreach ($empFamily as $key => $empup) {
                $empup->update(['is_deleted' => '0']);
            }
        }
        $empExp = user_experience::where('emp_id',$emp_id)->get();
        if($empExp){
            foreach ($empExp as $key => $empup) {
                $empup->update(['is_deleted' => '0']);
            }
        }
        user_approval::where('emp_id',$emp_id)->update(['is_deleted' => '0']);
        user_allowance::where('emp_id',$emp_id)->update(['is_deleted' => '0']);
        $msg = '"'.ucwords($delEmp->emp_name).'" Restore';
        createLog('employee_action',$msg);

        return redirect()->back()->with('success', 'Record Restore Successfully');
    }
    public function EmployeeHardDelete(Request $request, $id)
    {
        $user = auth()->user();
        if (Hash::check($request->password, $user->password)){
            $emp_id = base64_decode($id);
            $delEmp = EmployeeDetail::findOrFail($emp_id);
            $delEmp->delete();
            $delEmpDoc = EmployeeDocument::where('emp_id',$emp_id)->get();
            if($delEmpDoc){
                foreach ($delEmpDoc as $key => $empDoc) {
                    $empDoc->delete();
                }
            }
            $delEmpEdu = EmployeeEducationDetail::where('emp_id',$emp_id)->get();
            if($delEmpEdu){
                foreach ($delEmpEdu as $key => $empEdu) {
                    $empEdu->delete();
                }
            }
            $empAccount = AccountDetail::where('emp_id',$emp_id)->get();
            if($empAccount){
                foreach ($empAccount as $key => $empup) {
                    $empup->delete();
                }
            }
            $delEmpProm = EmployeePromotion::where('emp_id',$emp_id)->get();
            if($delEmpProm){
                foreach ($delEmpProm as $key => $empPro) {
                    $empPro->delete();
                }
            }
            $delEmpTerm = Emp_termination::where('emp_id',$emp_id)->get();
            if($delEmpTerm){
                foreach ($delEmpTerm as $key => $empTerm) {
                    $empTerm->delete();
                }
            }
            $delEmpResi = EmployeeResignation::where('emp_id',$emp_id)->get();
            if($delEmpResi){
                foreach ($delEmpResi as $key => $empResi) {
                    $empResi->delete();
                }
            }
            $delEmpLeave = Leave::where('emp_id',$emp_id)->get();
            if($delEmpLeave){
                foreach ($delEmpLeave as $key => $empLeave) {
                    $empLeave->delete();
                }
            }
            $delEmpLang = user_language::where('emp_id',$emp_id)->get();
            if($delEmpLang){
                foreach ($delEmpLang as $key => $empLang) {
                    $empLang->delete();
                }
            }
            $delEmpRef = user_family_refrence::where('emp_id',$emp_id)->get();
            if($delEmpRef){
                foreach ($delEmpRef as $key => $empRef) {
                    $empRef->delete();
                }
            }
            $delEmpExp = user_experience::where('emp_id',$emp_id)->get();
            if($delEmpExp){
                foreach ($delEmpExp as $key => $empExp) {
                    $empExp->delete();
                }
            }
            $delEmpAppro = user_approval::where('emp_id',$emp_id)->get();
            if($delEmpAppro){
                foreach ($delEmpAppro as $key => $empAppro) {
                    $empAppro->delete();
                }
            }
            $delEmpAllow = user_allowance::where('emp_id',$emp_id)->get();
            if($delEmpAllow){
                foreach ($delEmpAllow as $key => $empAllow) {
                    $empAllow->delete();
                }
            }
            $msg = '"'.ucwords($delEmp->emp_name).'" Permanent Delete';
            createLog('employee_action',$msg);

            return redirect()->back()->with('success', 'Record Deleted Permanent Successfully');
        }else{
            return redirect()->back()->with(['error'=>'Your Password Incorrect.']);
        }
    }
}

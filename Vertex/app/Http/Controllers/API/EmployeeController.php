<?php

namespace App\Http\Controllers\API;
use App\Models\AttendanceDetail;
use App\Models\UserDailyRecord;
use App\Traits\ProfileImage;
use App\User;
use DateTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use URL;
use App\Models\Company;
use App\Models\Location;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Models\user_approval;
use App\Models\user_language;
use App\Models\EmployeeEducationDetail;
use App\Models\EmployeeDetail;
use App\Models\user_experience;
use App\Models\City;
use App\Models\Country;
use App\Models\EmployeeDocument;
use App\Models\Leave;
use App\Models\Leave_Type;
use App\Models\Leave_setting;
use App\Models\Holiday;
use App\Models\CompanySetting;
use App\Models\UserAttendence;
use App\Models\Related_refrence;
use App\Models\EmployeeHistory;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use App\Models\user_family_refrence;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use App\Models\EmployeeRelative;
use App\Models\AccountDetail;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Controllers\API\BaseController as BaseController;

class EmployeeController extends BaseController
{
    use HasApiTokens, Notifiable, ProfileImage;

    public function EmployeeDirectory()
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);

        if ($user_role == '1') {
            $employees = EmployeeDetail::where('status', '1')->where('is_deleted', '0')
                ->orderBy('emp_id', 'asc')
                ->get(['id', 'emp_id', 'emp_email', 'company_id']);
        } else {
            $employees = EmployeeDetail::whereIn('company_id', $user_company_id)
                ->whereIn('branch_id', $user_branch_id)
                ->where('status', '1')
                ->where('is_deleted', '0')
                ->orderBy('emp_id', 'asc')
                ->get(['id', 'emp_id', 'emp_email', 'company_id']);
        }

        foreach ($employees as $emp) {
            $joining_date = user_approval::where('emp_id', $emp->id)->select('joining_date')->first();
            $company_name = Company::where('id', $emp->company_id)->select('company_name')->first();

            if ($company_name != null && $company_name != "") {
                $emp['company_name'] = $company_name->company_name;
            } else {
                $emp['company_name'] = '';
            }

            if ($joining_date != null && $joining_date != "") {
                $emp['joining_date'] = $joining_date->joining_date;
            } else {
                $emp['joining_date'] = '';
            }

            $get_user = user_approval::where('emp_id', $emp->id)->first();
            if (isset($get_user)) {
                $approved_by_ceo = user_approval::where('emp_id', $emp->id)->first()['approved_by_CEO'];
                if ($approved_by_ceo == '1') {
                    $emp['Status'] = 'Active';
                } else {
                    $emp['Status'] = 'Terminated';
                }
            } else {
                $emp['Status'] = "";
            }
        }

        if (isset($employees) && count($employees) > 0) {
            return $this->sendResponse($employees, 'Employees list fetched successfully.');
        } else {
            return $this->sendResponse([], 'No Record found');
        }
    }

    public function SyncEmployeesData()
    {
        $employees = EmployeeDetail::where('status', '1')->where('is_deleted', '0')
            ->orderBy('emp_id', 'desc')
            ->get(['id', 'emp_id', 'fingerprint', 'emp_name'])
            ->map(function ($employee) {
                $employee->fingerprint = json_decode($employee->fingerprint, true);
                return $employee;
            });

        if (isset($employees) && count($employees) > 0) {
            return $this->sendResponse($employees, 'Employees Data fetched successfully.');
        } else {
            return $this->sendResponse([], 'No Record found');
        }
    }

    public function searchEmployeebyName(Request $request)
    {
        $employees = EmployeeDetail::where('status', '1')->where('is_deleted', '0')->where('emp_name', 'like', $request->name . '%')->get(['id', 'emp_id', 'emp_email', 'company_id']);
        foreach ($employees as $emp) {
            $joining_date = user_approval::where('emp_id', $emp->id)->select('joining_date')->first();
            $company_name = Company::where('id', $emp->company_id)->select('company_name')->first();
            if ($company_name != null && $company_name != "") {
                $emp['company_name'] = $company_name->company_name;
            } else {
                $emp['company_name'] = '';
            }
            if ($joining_date != null && $joining_date != "") {
                $emp['joining_date'] = $joining_date->joining_date;
            } else {
                $emp['joining_date'] = '';
            }
            $get_user = user_approval::where('emp_id', $emp->id)->first();
            if (isset($get_user)) {
                $approved_by_ceo = user_approval::where('emp_id', $emp->id)->first()['approved_by_CEO'];
                if ($approved_by_ceo == '1') {
                    $emp['Status'] = 'Active';
                } else {
                    $emp['Status'] = 'Terminated';
                }
            } else {
                $emp['Status'] = "";
            }
        }
        if (isset($employees) && count($employees) > 0) {
            return $this->sendResponse($employees, 'Employee data fetched successfully.');
        } else {
            return $this->sendResponse([], 'No Record found');
        }
    }

    public function addEmployeeDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'emp_id' => 'required|unique:employee_details',
            'emp_email' => 'required|email|unique:employee_details',
            'emp_phone' => 'required|numeric|unique:employee_details|min:10|digits:11',
            'cnic' => 'required|numeric|unique:employee_details|min:10',
            'blood_group' => 'max:25',
            'father_name' => 'max:25',
            'mother_name' => 'max:25',
            'emp_address' => 'max:255',
            'spouse' => 'max:25',
            'religion' => 'max:20',
            'registration_no' => 'max:20',
            'license_no' => 'max:20',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if ($request->hasFile('emp_image')) {
            $file = $request->file('emp_image');
            $filename = $file->getClientOriginalName();
            $file->move('assets/images/employees/', $filename);
            $image = $filename;
        } else {
            $image = null;
        }
        $Empdetails = new EmployeeDetail();
        $Empdetails->company_id = $request->company_id;
        $Empdetails->branch_id = $request->branch_id;
        $Empdetails->emp_id = $request->emp_id;
        $Empdetails->emp_name = $request->emp_name;
        $Empdetails->emp_email = $request->emp_email;
        $Empdetails->emp_gender = $request->emp_gender;
        $Empdetails->emp_phone = $request->emp_phone;
        $Empdetails->cnic = $request->cnic;
        $Empdetails->added_by = $request->added_by;
        $Empdetails->father_name = $request->father_name;
        $Empdetails->mother_name = $request->mother_name;
        $Empdetails->emp_image = $image;
        $Empdetails->emp_address = $request->emp_address;
        $Empdetails->nationality = $request->nationality;
        $Empdetails->city_of_birth = $request->city_of_birth;
        $Empdetails->Religion = $request->Religion;
        $Empdetails->blood_group = $request->blood_group;
        $Empdetails->marital_status = $request->marital_status;
        $Empdetails->spouse_name = $request->spouse;
        $Empdetails->is_independant = $request->is_independant;
        $Empdetails->has_home = $request->home_owned;
        $Empdetails->transport_type = $request->transport;
        $Empdetails->transport_type = $request->transport_type;
        $Empdetails->registration_no = $request->registration_no;
        $Empdetails->driving_license = $request->driving_license;
        $Empdetails->license_no = $request->license_no;
        $Empdetails->fingerprint = $request->fingerprint;
        $Empdetails->is_active = $request->is_active;
        $Empdetails->save();
        $user = User::create([
            'role_id' => 3,
            'company_id' => $Empdetails->company_id,
            'branch_id' => $Empdetails->branch_id,
            'emp_id' => $Empdetails->id,
            'email' => $Empdetails->emp_email,
            'fullname' => $Empdetails->emp_name,
            'password' => Hash::make(12345),
            'is_active' => $Empdetails->is_active,
            'expiry_date' => 2023 - 06 - 19
        ]);

        return $this->sendResponse($Empdetails, 'Employee details are added  successfully.');
    }

    public function addEmpEducationDetails(Request $request)
    {
        $input = $request->all();
        $EmpEdu = new EmployeeEducationDetail();
        $EmpEdu->emp_id = $request->emp_id;
        $EmpEdu->degree = $request->degree;
        $EmpEdu->subject = $request->subject;
        $EmpEdu->grade = $request->grade;
        $EmpEdu->division = $request->division;
        $EmpEdu->degree_from = $request->degree_from;
        $EmpEdu->degree_to = $request->degree_to;
        $EmpEdu->institution = $request->institution;
        $EmpEdu->save();

        if ($request->other_qualifications != null && $request->other_qualifications != "") {
            $otherEdu = new EmployeeEducationDetail();
            $otherEdu->emp_id = $request->emp_id;
            $otherEdu->other_qualifications = $request->other_qualifications;
            $otherEdu->save();
        }
        if (!empty($otherEdu)) {
            $EmpEdu['other_qualifications'] = $otherEdu->other_qualifications;
        }
        return $this->sendResponse($EmpEdu, 'Employee Education Details are added  successfully.');
    }

    public function addEmplanguages(Request $request)
    {
        // return "hello";
        $input = $request->all;
        $data = array();
        if (!empty($request->input('emp_language1'))) {
            $emplang1 = $request->emp_language1;
            $getlanguage1 = Language::where('language_name', $emplang1)->select('id')->first();
            $lang1 = $getlanguage1->id;
            array_push($data, $lang1);
            //  return $data;
        }
        if (!empty($request->input('emp_language2'))) {
            $emplang2 = $request->emp_language2;
            $getlanguage2 = Language::where('language_name', $emplang2)->select('id')->first();
            $lang2 = $getlanguage2->id;
            array_push($data, $lang2);
            // return $data;
            // return $getlanguage2;
        }
        if (!empty($request->input('emp_language3'))) {
            $emp_lang = ucfirst($request->emp_language3);
            $getlanguage = Language::where('language_name', $emp_lang)->select('id')->first();

            if ($getlanguage) {
                $lang3 = $getlanguage->id;
                array_push($data, $lang3);
                // return $data;
            } else {
                $AddLang = Language::create([
                    'language_name' => $emp_lang,
                ]);
                $lang3 = $AddLang->id;
                array_push($data, $lang3);
                // return $data;
            }
        }

        $newdata = array_unique($data, SORT_REGULAR);

        for ($i = 0; $i < count($newdata); ++$i) {
            $success['emp_id'] = $request->emp_id;
            $success['language_id'] = $newdata[$i];
            $emp_languages = user_language::create($success);
        }

        return $this->sendResponse("", 'Employee Languages are added  successfully.');
    }
    public function addEmpExperiences(Request $request)
    {


        $Empexperience = new user_experience();
        $Empexperience->emp_id = $request->emp_id;
        $Empexperience->organization = $request->organization;
        $Empexperience->prev_position = $request->prev_position;
        $Empexperience->prev_salary = $request->prev_salary;
        $Empexperience->exp_from = $request->exp_from;
        $Empexperience->exp_to = $request->exp_to;
        $Empexperience->reason_for_leaving = $request->reason_for_leaving;
        $Empexperience->save();

        if ($request->court_conviction != null && $request->court_conviction != "") {
            $otherconvic = new user_experience();
            $otherconvic->emp_id = $request->emp_id;
            $otherconvic->court_conviction = $request->court_conviction;
            $otherconvic->save();
        }
        $Empexperience['court_conviction'] = $otherconvic->court_conviction;

        return $this->sendResponse($Empexperience, 'Employee Experience Details are added  successfully.');
    }
    public function addprevEmpbyviion(Request $request)
    {
        $input = $request->all();

        $EmpbyViion = EmployeeHistory::create($input);

        return $this->sendResponse($EmpbyViion, 'Employee Previous Employement  Details are added  successfully.');
    }
    public function addfamilyDetails(Request $request)
    {
        $input = $request->all();

        $EmpbyViion = user_family_refrence::create($input);


        return $this->sendResponse($EmpbyViion, 'Employee Family Details are added  successfully.');
    }
    public function addrelativeinViion(Request $request)
    {

        $input = $request->all();

        $EmpbyViion = EmployeeRelative::create($input);
        return $this->sendResponse($EmpbyViion, 'Employee Family Details are added  successfully.');
    }
    public function addrelatedRefrences(Request $request)
    {

        $input = $request->all();

        $EmpbyViion = related_refrence::create($input);
        return $this->sendResponse($EmpbyViion, 'Employee Refrences Details are added  successfully.');
    }
    public function addapprovalDetails(Request $request)
    {
        // $input = $request->all();
        $aproval = new user_approval();
        $aproval->user_id = Auth::user()->id;
        $aproval->emp_id = $request->emp_id;
        $aproval->job_position = $request->job_position;
        $aproval->joining_date = $request->joining_date;
        $aproval->phone_issued = $request->phone_issued;
        $aproval->starting_sal = $request->starting_sal;
        if ($request->input('approved_by_HR') !== null) {
            $aproval->approved_by_HR = $request->input('approved_by_HR');
        }
        if ($request->input('approved_by_PM') !== null) {
            $aproval->approved_by_PM = $request->input('approved_by_PM');
        }
        if ($request->input('approved_by_CEO') !== null) {
            $aproval->approved_by_CEO = $request->input('approved_by_CEO');
        }
        $aproval->save();

        return $this->sendResponse($aproval, 'Employee Approval  Details are added  successfully.');
    }
    public function deleteEmployeedetails(Request $request)
    {

        $getid = EmployeeDetail::where('id', $request->id)->first();
        if ($getid) {
            $getid->is_deleted = '1';
            $getid->update();
            $message = "Employee Record deleted successfully!";
            return $this->sendResponse([], $message);
        } else {
            return $this->sendResponse([], "Employee Record  does not exsit!!!");
        }
    }

    public function updateEmpDetails(Request $request)
    {

        // $validator = Validator::make($request->all(),[
        //     'emp_id'=> 'required|unique:employee_details,emp_id,'.$request->id,
        //     'emp_email'  => 'required|unique:employee_details,emp_email,'.$request->id,
        //     'emp_phone'=>'required|min:10|max:11|unique:employee_details,emp_phone,'.$request->id,
        //     'cnic'=>'required|min:10|unique:employee_details,cnic,'.$request->id,
        //     'spouse'=>'nullable|max:25',
        //     'religion'=>'nullable|max:20',
        //     'registration_no'=>'nullable|max:20',
        //     'license_no'=>'nullable|max:20',
        // ]);


        // if($validator->fails()){
        //     $validator= $validator->errors()->first();

        //     return $this->sendError('Validation Error.',$validator);
        // }
        // return $request->all();
        $input = $request->all();
        $checkid = EmployeeDetail::where('is_deleted', '0')->where('id', $request->id)->first();
        if ($checkid) {
            if ($checkid->is_deleted == 1) {
                return $this->sendResponse([], "User does not exsit!");
            } else {
                if (isset($input['company_id'])) {
                    $checkid->company_id = $input['company_id'];
                }
                if (isset($input['emp_id'])) {
                    $checkid->emp_id = $input['emp_id'];
                }
                if (isset($input['emp_email'])) {
                    $checkid->emp_email = $input['emp_email'];
                }
                if (isset($input['emp_name'])) {
                    $checkid->emp_name = $input['emp_name'];
                }
                if (isset($input['father_name'])) {
                    $checkid->father_name = $input['father_name'];
                }
                if (isset($input['mother_name'])) {
                    $checkid->mother_name = $input['mother_name'];
                }
                if (isset($input['emp_address'])) {
                    $checkid->emp_address = $input['emp_address'];
                }
                if (isset($input['emp_phone'])) {
                    $checkid->emp_phone = $input['emp_phone'];
                }
                if (isset($input['emp_gender'])) {
                    $checkid->emp_gender = $input['emp_gender'];
                }
                if (isset($input['cnic'])) {
                    $checkid->cnic = $input['cnic'];
                }
                if (isset($input['added_by'])) {
                    $checkid->added_by = $input['added_by'];
                }
                if (isset($input['emp_image'])) {
                    if ($request->hasFile('emp_image')) {
                        $file = $request->file('emp_image');
                        $filename = $file->getClientOriginalName();
                        $file->move('assets/images/employees/', $filename);
                        $image = $filename;
                        // return $image;

                    } else {
                        $image = $checkid->emp_image;
                    }
                    $checkid->emp_image = $image;
                }
                if (isset($input['nationality'])) {
                    $checkid->nationality = $input['nationality'];
                }
                if (isset($input['city_of_birth'])) {
                    $checkid->city_of_birth = $input['city_of_birth'];
                }
                if (isset($input['Religion'])) {
                    $checkid->Religion = $input['Religion'];
                }
                if (isset($input['blood_group'])) {
                    $checkid->blood_group = $input['blood_group'];
                }
                if (isset($input['marital_status'])) {
                    $checkid->marital_status = $input['marital_status'];
                }
                if (isset($input['spouse'])) {
                    $checkid->spouse = $input['spouse'];
                }
                if (isset($input['is_independant'])) {
                    $checkid->is_independant = $input['is_independant'];
                }
                if (isset($input['home_owned'])) {
                    $checkid->home_owned = $input['home_owned'];
                }
                if (isset($input['transport'])) {
                    $checkid->transport = $input['transport'];
                }
                if (isset($input['transport_type'])) {
                    $checkid->transport_type = $input['transport_type'];
                }
                if (isset($input['registration_no'])) {
                    $checkid->registration_no = $input['registration_no'];
                }
                if (isset($input['driving_license'])) {
                    $checkid->driving_license = $input['driving_license'];
                }
                if (isset($input['license_no'])) {
                    $checkid->license_no = $input['license_no'];
                }
                $checkid->update();
                // return $checkid;
                $message = "Emplyee Details are updated successfully!";
                return $this->sendResponse($checkid, $message);
            }
        } else {
            return $this->sendResponse([], "Employee record does not exsit!!!");
        }
    }
    public function updateEmpEducationDetails(Request $request)
    {
        // return $request->all();
        $input = $request->all();
        $checkid = EmployeeEducationDetail::where('is_deleted', '0')->where(['emp_id' => $request->emp_id, 'id' => $request->id])->first();

        if ($checkid) {
            if (isset($input['degree'])) {
                $checkid->degree = $input['degree'];
            }
            if (isset($input['subject'])) {
                $checkid->subject = $input['subject'];
            }
            if (isset($input['grade'])) {
                $checkid->grade = $input['grade'];
            }
            if (isset($input['degree_from'])) {
                $checkid->degree_from = $input['degree_from'];
            }
            if (isset($input['degree_to'])) {
                $checkid->degree_to = $input['degree_to'];
            }
            if (isset($input['institution'])) {
                $checkid->institution = $input['institution'];
            }
            $checkid->update();
        }
        //
        if ($request->other_qualifications != null && $request->other_qualifications != "") {
            if (!empty($request->Qalification_ID)) {
                $otherEdu = EmployeeEducationDetail::where('emp_id', $request->emp_id)->where('id', $request->Qalification_ID)->first();
                $otherEdu->other_qualifications = $request->other_qualifications;
                $otherEdu->update();
            }
            $result = ['Education' => $checkid, 'other_qualification_id' => $otherEdu->id, 'other_qualifications' => $otherEdu->other_qualifications];
            if (isset($result) && count($result) > 0) {
                $message = "Emplyee Education Details are updated successfully!";
                return $this->sendResponse($result, $message);
            } else {
                return $this->sendResponse([], "Employee record does not exsit!!!");
            }
        }
        // return $checkid;
    }

    public function updateEmpLanguages(Request $request)
    {
        $data = array();
        if (!empty($request->has('emp_language1'))) {
            $emplang1 = ucfirst($request->emp_language1);
            // $getlanguage1=Language::where('language_name',$emplang1)->select('id')->first();
            // $lang1=$getlanguage1->id;
            array_push($data, $emplang1);
            //  return $data;
        }
        if (!empty($request->has('emp_language2'))) {
            $emplang2 = ucfirst($request->emp_language2);
            array_push($data, $emplang2);
            //  return $data;
        }
        if (!empty($request->has('emp_language3'))) {
            $emplang3 = ucfirst($request->emp_language3);
            array_push($data, $emplang3);
            //  return $data;
        }
        foreach ($data as $lang) {
            $languages = Language::where('language_name', $lang)->first();
            if ($languages != null) {
                $getid[] = $languages->id;
            } else {
                if (!empty($lang)) {
                    $AddLang = Language::create([
                        'language_name' => $lang,
                    ]);
                    $getid[] = $AddLang->id;
                }
            }
        }
        foreach ($getid as $ids) {
            $fExists = user_language::where('emp_id', $request->emp_id)->where('language_id', $ids)->first();
            if (isset($fExists)) {
                continue;
            } else {
                user_language::create(['emp_id' => $request->emp_id, 'language_id' => $ids]);
            }
        }

        $message = "Emplyee Languages Details are updated successfully!";
        return $this->sendResponse([], $message);
    }
    public function updateEmpExperiences(Request $request)
    {
        // return $request->all();
        $input = $request->all();
        $checkid = user_experience::where('is_deleted', '0')->where(['emp_id' => $request->emp_id, 'id' => $request->id])->first();
        if ($checkid) {
            if (isset($input['organization'])) {
                $checkid->organization = $input['organization'];
            }
            if (isset($input['prev_position'])) {
                $checkid->prev_position = $input['prev_position'];
            }
            if (isset($input['prev_salary'])) {
                $checkid->prev_salary = $input['prev_salary'];
            }
            if (isset($input['exp_from'])) {
                $checkid->exp_from = $input['exp_from'];
            }
            if (isset($input['exp_to'])) {
                $checkid->exp_to = $input['exp_to'];
            }
            if (isset($input['reason_for_leaving'])) {
                $checkid->reason_for_leaving = $input['reason_for_leaving'];
            }
            $checkid->update();
        }

        if ($request->court_conviction != null && $request->court_conviction != "") {
            if (!empty($request->any_convic_ID)) {
                $any_court_convic = user_experience::where('emp_id', $request->emp_id)->where('id', $request->any_convic_ID)->first();
                $any_court_convic->court_conviction = $request->court_conviction;
                $any_court_convic->update();
            }
            $result = ['Experience' => $checkid, 'conviction_id' => $any_court_convic->id, 'court_conviction' => $any_court_convic->court_conviction];
            if (isset($result) && count($result) > 0) {
                $message = "Emplyee Experience Details are updated successfully!";
                return $this->sendResponse($result, $message);
            } else {
                return $this->sendResponse([], "Employee record does not exsit!!!");
            }
        }
    }
    public function updateprevEmpbyviion(Request $request)
    {
        // return $request->all();
        $input = $request->all();
        $checkid = EmployeeHistory::where('is_deleted', '0')->where(['emp_id' => $request->emp_id, 'id' => $request->id])->first();
        //    return $checkid;
        if ($checkid) {
            if (isset($input['user_id'])) {
                $checkid->user_id = $input['user_id'];
            }
            if (isset($input['emp_position'])) {
                $checkid->emp_position = $input['emp_position'];
            }
            if (isset($input['prev_emp_no'])) {
                $checkid->prev_emp_no = $input['prev_emp_no'];
            }
            if (isset($input['emp_location'])) {
                $checkid->emp_location = $input['emp_location'];
            }
            if (isset($input['date_from'])) {
                $checkid->date_from = $input['date_from'];
            }
            if (isset($input['date_to'])) {
                $checkid->date_to = $input['date_to'];
            }
            //    return $checkid;
            $checkid->update();
            //    return $checkid;
            $message = "Emplyee Experience Details are updated successfully!";
            return $this->sendResponse($checkid, $message);
        } else {
            return $this->sendResponse([], "Employee record does not exsit!!!");
        }
    }
    public function updatefamilyDetails(Request $request)
    {
        // return $request->all();
        $input = $request->all();
        $checkid = user_family_refrence::where('is_deleted', '0')->where(['emp_id' => $request->emp_id, 'id' => $request->id])->first();
        //    return $checkid;
        if ($checkid) {
            if (isset($input['memeber_name'])) {
                $checkid->memeber_name = $input['memeber_name'];
            }
            if (isset($input['memeber_relation'])) {
                $checkid->memeber_relation = $input['memeber_relation'];
            }
            if (isset($input['memeber_age'])) {
                $checkid->memeber_age = $input['memeber_age'];
            }
            if (isset($input['memeber_occupation'])) {
                $checkid->memeber_occupation = $input['memeber_occupation'];
            }
            if (isset($input['place_of_work'])) {
                $checkid->place_of_work = $input['place_of_work'];
            }
            //    return $checkid;
            $checkid->update();
            //    return $checkid;
            $message = "Emplyee Family Details are updated successfully!";
            return $this->sendResponse($checkid, $message);
        } else {
            return $this->sendResponse([], "Employee record does not exsit!!!");
        }
    }
    public function updaterelativeinViion(Request $request)
    {
        // return $request->all();
        $input = $request->all();
        $checkid = EmployeeRelative::where('is_deleted', '0')->where(['emp_id' => $request->emp_id, 'id' => $request->id])->first();
        //    return $checkid;
        if ($checkid) {
            if (isset($input['relative_name'])) {
                $checkid->relative_name = $input['relative_name'];
            }
            if (isset($input['relative_position'])) {
                $checkid->relative_position = $input['relative_position'];
            }
            if (isset($input['relative_dept'])) {
                $checkid->relative_dept = $input['relative_dept'];
            }
            if (isset($input['relative_location'])) {
                $checkid->relative_location = $input['relative_location'];
            }
            if (isset($input['relative_relation'])) {
                $checkid->relative_relation = $input['relative_relation'];
            }
            //    return $checkid;
            $checkid->update();
            //    return $checkid;
            $message = "Emplyee Family Details are updated successfully!";
            return $this->sendResponse($checkid, $message);
        } else {
            return $this->sendResponse([], "Employee record does not exsit!!!");
        }
    }
    public function updaterelatedRefrences(Request $request)
    {
        // return $request->all();
        $input = $request->all();
        $checkid = related_refrence::where('is_deleted', '0')->where(['emp_id' => $request->emp_id, 'id' => $request->id])->first();
        //    return $checkid;
        if ($checkid) {
            if (isset($input['refrence_name'])) {
                $checkid->refrence_name = $input['refrence_name'];
            }
            if (isset($input['ref_position'])) {
                $checkid->ref_position = $input['ref_position'];
            }
            if (isset($input['ref_address'])) {
                $checkid->ref_address = $input['ref_address'];
            }
            if (isset($input['ref_phone'])) {
                $checkid->ref_phone = $input['ref_phone'];
            }

            //    return $checkid;
            $checkid->update();
            //    return $checkid;
            $message = "Emplyee Refrences Details are updated successfully!";
            return $this->sendResponse($checkid, $message);
        } else {
            return $this->sendResponse([], "Employee record does not exsit!!!");
        }
    }
    public function updateapprovalDetails(Request $request)
    {
        // return $request->all();
        $input = $request->all();
        $checkid = user_approval::where('is_deleted', '0')->where('emp_id', $request->emp_id)->first();
        //    return $checkid;
        if ($checkid) {
            if (isset($input['user_id'])) {
                $checkid->user_id = $input['user_id'];
            }
            if (isset($input['job_position'])) {
                $checkid->job_position = $input['job_position'];
            }
            if (isset($input['joining_date'])) {
                $checkid->joining_date = $input['joining_date'];
            }
            if (isset($input['phone_issued'])) {
                $checkid->phone_issued = $input['phone_issued'];
            }
            if (isset($input['starting_sal'])) {
                $checkid->starting_sal = $input['starting_sal'];
            }
            if (isset($input['approved_by_HR'])) {
                $checkid->approved_by_HR = $input['approved_by_HR'];
            }
            if (isset($input['approved_by_PM'])) {
                $checkid->approved_by_PM = $input['approved_by_PM'];
            }
            if (isset($input['approved_by_CEO'])) {
                $checkid->approved_by_CEO = $input['approved_by_CEO'];
            }

            //    return $checkid;
            $checkid->update();
            //    return $checkid;
            $message = "Emplyee Approval Details are updated successfully!";
            return $this->sendResponse($checkid, $message);
        } else {
            return $this->sendResponse([], "Employee record does not exsit!!!");
        }
    }

    public function viewEmployeedetails(Request $request)
    {
        // return $request->name;
        $result = EmployeeDetail::where('is_deleted', '0')->where('id', $request->id)->select('id', 'emp_id', 'emp_name', 'emp_email', 'emp_image', 'company_id', 'emp_gender', 'emp_phone', 'emp_phone', 'cnic', 'father_name', 'mother_name', 'emp_address', 'nationality', 'city_of_birth', 'Religion', 'blood_group', 'marital_status', 'spouse', 'is_independant', 'home_owned', 'transport', 'transport_type', 'registration_no', 'driving_license', 'license_no')->get();

        if (isset($result) && count($result) > 0) {
            return $this->sendResponse($result, 'Employee data fetched successfully.');
        } else {
            return $this->sendResponse([], 'No Record found');
        }
    }
    public function viewEmployeeEducationdetails(Request $request)
    {
        $education = EmployeeEducationDetail::where('is_deleted', '0')->where('emp_id', $request->emp_id)->where('other_qualifications', null)->select('id', 'emp_id', 'degree', 'subject', 'grade', 'degree_from', 'degree_to', 'institution')->get();
        $other_edu = EmployeeEducationDetail::where('emp_id', $request->emp_id)->where('other_qualifications', '!=', null)->get(['id', 'other_qualifications']);
        $result = [
            'education' => $education,
            'other_education' => $other_edu
        ];

        if (isset($result) && count($result) > 0) {
            return $this->sendResponse($result, 'Employee data fetched successfully.');
        } else {
            return $this->sendResponse([], 'No Record found');
        }
    }
    public function viewEmployeelanguagesdetails(Request $request)
    {
        $languages = user_language::where('is_deleted', '0')->where('emp_id', $request->emp_id)->select('id', 'emp_id', 'language_id')->get();
        foreach ($languages as $item) {
            $item['language_name'] = Language::where('id', $item->language_id)->first()['language_name'];
            $languages[] = $item;
        }
        if (isset($languages) && count($languages) > 0) {
            return $this->sendResponse($languages, 'Employee data fetched successfully.');
        } else {
            return $this->sendResponse([], 'No Record found');
        }
    }
    public function viewEmployeeExperiencedetails(Request $request)
    {
        $experience = user_experience::where('is_deleted', '0')->where('emp_id', $request->emp_id)->where('court_conviction', null)->where('organization', '!=', null)->select('id', 'emp_id', 'organization', 'prev_position', 'prev_salary', 'exp_from', 'exp_to', 'reason_for_leaving')->get();
        $any_convic = user_experience::where('emp_id', $request->emp_id)->where('court_conviction', '!=', null)->get(['id', 'court_conviction']);
        $result = [
            'Experince' => $experience,
            'other_details' => $any_convic
        ];
        if (isset($result) && count($result) > 0) {
            return $this->sendResponse($result, 'Employee data fetched successfully.');
        } else {
            return $this->sendResponse([], 'No Record found');
        }
    }
    public function viewViionEmplomentdetails(Request $request)
    {
        $result = EmployeeHistory::where('is_deleted', '0')->where('emp_id', $request->emp_id)->select('id', 'emp_id', 'emp_position', 'prev_emp_no', 'emp_location', 'date_from', 'date_to')->get();

        if (isset($result) && count($result) > 0) {
            return $this->sendResponse($result, 'Employee data fetched successfully.');
        } else {
            return $this->sendResponse([], 'No Record found');
        }
    }
    public function viewEmployeefamilydetails(Request $request)
    {
        $result = user_family_refrence::where('is_deleted', '0')->where('emp_id', $request->emp_id)->select('id', 'emp_id', 'memeber_name', 'memeber_relation', 'memeber_age', 'memeber_occupation', 'place_of_work')->get();

        if (isset($result) && count($result) > 0) {
            return $this->sendResponse($result, 'Employee data fetched successfully.');
        } else {
            return $this->sendResponse([], 'No Record found');
        }
    }
    public function viewrelativeViionEmployeementdetails(Request $request)
    {
        $result = EmployeeRelative::where('is_deleted', '0')->where('emp_id', $request->emp_id)->select('id', 'emp_id', 'relative_name', 'relative_position', 'relative_dept', 'relative_location', 'relative_relation')->get();

        if (isset($result) && count($result) > 0) {
            return $this->sendResponse($result, 'Employee data fetched successfully.');
        } else {
            return $this->sendResponse([], 'No Record found');
        }
    }
    public function viewrelatedRefrencesdetails(Request $request)
    {
        $result = related_refrence::where('is_deleted', '0')->where('emp_id', $request->emp_id)->select('id', 'emp_id', 'refrence_name', 'ref_position', 'ref_address', 'ref_phone')->get();

        if (isset($result) && count($result) > 0) {
            return $this->sendResponse($result, 'Employee data fetched successfully.');
        } else {
            return $this->sendResponse([], 'No Record found');
        }
    }
    public function viewApprovaldetails(Request $request)
    {
        $result = user_approval::where('is_deleted', '0')->where('emp_id', $request->emp_id)->first();

        if (isset($result)) {
            return $this->sendResponse($result, 'Employee data fetched successfully.');
        } else {
            return $this->sendResponse([], 'No Record found');
        }
    }

    public function EmployeeProfile(Request $request)
    {
        try {
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
            $baseImageURl = 'api/storage/app/';
            $documentPath = 'api/storage/app/';
            $liveBaseUrl = url('/');

            $id = $request->id;
            $searched_date = isset($request->searchDate) ? Carbon::parse($request->searchDate)->format('Y-m') : Carbon::now()->format('Y-m');

            $EmpDetails = EmployeeDetail::where('id', $id)->first();
            $emp_documents = EmployeeDocument::where('emp_id', $id)->get();

            $empDocuments = [];

            if ($emp_documents) {
                foreach ($emp_documents as $document) {
                    $empDocuments[] = $liveBaseUrl . '/' . $documentPath . $document->document_path;
                }
            }

            $cities = City::where('city_id', $EmpDetails->city_of_birth)->value('city_name');
            $countries = Country::where('country_id', $EmpDetails->nationality)->value('country_name');
            $EmpAccount = AccountDetail::where('emp_id', $id)->first();

            $empEducation = EmployeeEducationDetail::where('emp_id', $EmpDetails->id)->where('is_deleted', '0')->get();

            $empPrimary = user_family_refrence::where('emp_id', $EmpDetails->id)->where('emergency_contact', '1')->where('is_deleted', '0')->orderBy('id', 'asc')->first();
            $empRelation = user_family_refrence::where('emp_id', $EmpDetails->id)->where('is_deleted', '0')->get();
            $empSecondary = user_family_refrence::where('emp_id', $EmpDetails->id)->where('emergency_contact', '1')->where('is_deleted', '0')->orderBy('id', 'desc')->first();
            $empHistory = user_experience::where('emp_id', $EmpDetails->id)->where('is_deleted', '0')->get();
            $empApproval = user_approval::with('designation')->where('emp_id', $EmpDetails->id)->where('is_deleted', '0')->first();
            $documents = EmployeeDocument::where('emp_id', $id)->get();

            $EmpcompanyDetails = DB::table('companies')
                ->where('id', $EmpDetails->company_id)
                ->where('is_deleted', '0')
                ->first();

            $companies = Company::where('is_deleted', '0')->orderBy('company_name', 'asc')->get();

            $employeeApproval = DB::table('emp_approvals')->where('emp_id', $id)->first();

            $getUserDetails = EmployeeDetail::find($id);

            if (
                Location::where('id', $getUserDetails->branch_id)
                    ->where('company_id', $getUserDetails->company_id)->exists()
            ) {
                $location = Location::where('id', $getUserDetails->branch_id)
                    ->where('company_id', $getUserDetails->company_id)->first();
                $getUserDetails->branch_name = $location->branch_name ?? '';
                $getUserDetails->branch_id = $location->branch_id ?? '';
            } else {
                $getUserDetails->branch_name = '';
                $getUserDetails->branch_id = '';
            }

            $getUserDetails->employeeDesignation = user_approval::where('emp_id', $getUserDetails->id)->value('designation_id') ?? '';

            if (isset($getUserDetails->join_date)) {
                $getUserDetails->join_date = Carbon::createFromFormat('Y-m-d', $getUserDetails->join_date)->format('d F Y');
            }
            $getUserDetails->dob = Carbon::createFromFormat('Y-m-d', $getUserDetails->dob)->format('d F Y');
            $totalLeaveRecords = 0;
            $empLeaves = Leave::where('emp_id', $id)
                ->whereYear('from_date', Carbon::now()->format('Y'))
                ->where('is_approved', '1')
                ->where('is_deleted', '0')
                ->get();


            if ($empLeaves) {
                $totalLeaveRecords = $empLeaves->sum('approved_days');
            }
            $start_date = date('Y-m-01');
            if ($request->date) {
                $todayDate = Carbon::parse($request->date);
            } else {
                $todayDate = Carbon::today();
            }
            $currentYear = $todayDate->year;
            $currentMonth = $todayDate->month;
            $number_of_days = Carbon::create($currentYear, $currentMonth)->daysInMonth;
            $emp = EmployeeDetail::with([
                'empToDailyRecord' => function ($emp) use ($currentYear, $currentMonth) {
                    $emp->whereYear('dated', $currentYear)
                        ->whereMonth('dated', $currentMonth)
                        ->orderBy('dated', 'asc');
                },
                'resignations',
                'leaves',
                'holidays'
            ]);
            $employee = $emp->where('id', $id)->first();
          $company_detail = CompanySetting::where('branch_id', $employee->branch_id)->where('company_id', $employee->company_id)
                ->where('is_deleted', '0')
                ->first();
            $company_start_time = new DateTime($company_detail->start_time);
            $company_end_time = new DateTime($company_detail->end_time);
            $difference = $company_start_time->diff($company_end_time);
            $com_total_hours = $difference->h + $difference->i / 60;
            $company_total_hours = number_format($com_total_hours, 1);
            $workingDays = ($company_detail ? explode(',', strtolower($company_detail->days)) : []);

            // get user daily record -------------------------------------------------------------------------
            $todaysRecord = UserDailyRecord::where('emp_id', $id)
                ->whereDate('dated', Carbon::now()->format('Y-m-d'))
                ->first();
            $todaysRecords = [];
            $empWorkingHours = 0;
            $todayTime = Carbon::now();
            if ($todaysRecord) {
                if ($todaysRecord->check_in != null && $todaysRecord->check_out != null) {

                    $empHours = $todaysRecord->working_hours;
                    $hours = floor($empHours);
                    $minutes = ($empHours - $hours) * 60;
                    $minutes = round($minutes);
                    $empWorkingHours = sprintf('%02dh:%02dm', $hours, $minutes);
                } elseif ($todaysRecord->check_in != null && $todaysRecord->check_out == null) {
                    $check_in = Carbon::parse($todaysRecord->check_in);
                    $difference = $todayTime->diff($check_in);
                    $empWorkingHours = sprintf('%02dh:%02dm', $difference->h, $difference->i);

                }
                $totalComHours = $company_total_hours;
                $hours = floor($totalComHours);
                $minutes = ($totalComHours - $hours) * 60;
                $minutes = round($minutes);

                $todaysRecords = [
                    'WorkingHours' => $empWorkingHours,
                    'totalHours' => sprintf('%02dh:%02dm', $hours, $minutes),
                    'todayDate' => $todayTime->format('d M Y'),
                    'check_in' => $todaysRecord->check_in,
                    'check_out' => $todaysRecord->check_out
                ];
            }

            // get user weekly record -----------------------------------------------------------------------------
            $start_Date = Carbon::now()->startOfWeek();
            $end_Date = Carbon::now()->endOfWeek();
            $weeklyData = [];
            $total_employee_hours = 0;
            $company_working_days = 0;
            $com_hours = 0;

            $attendanceData = $employee->empToDailyRecord;

            while ($start_Date->lte($end_Date)) {
                $emp_total_hours = 0;
                $attendanceStatus = '';

                // Skip future dates
                if ($start_Date > Carbon::today()) {
                    $start_Date->addDay();
                    continue;
                }


                // Calculate working hours only for present days
                foreach ($attendanceData as $dailyAttendance) {
                    if ($start_Date->format('Y-m-d') == $dailyAttendance->dated) {
                        if ($dailyAttendance->check_in != null && $dailyAttendance->check_out != null) {
                            $emp_total_hours = $dailyAttendance->working_hours;
                            $total_employee_hours += $emp_total_hours;
                            $company_working_days++;
                        }
                        //  elseif ($dailyAttendance->check_in != null && $dailyAttendance->check_out == null) {
                        //     $emp_hr = new DateTime($dailyAttendance->check_in);
                        //     // dd($emp_hr);
                        //     $difference = $todayTime->diff($emp_hr);
                        //     $hours = $difference->h + ($difference->i / 60);
                        //     $emp_total_hours = number_format($hours, 1);
                        //     $total_employee_hours += $emp_total_hours;
                        //     $company_working_days++;
                        // }

                    }
                }

                $start_Date->addDay();
            }

            // Calculate total company hours
            $com_hours = $company_total_hours * $company_working_days;

            $weeklyData = [
                'weeklyHours' => number_format($total_employee_hours, 1),
                'weeklyTotalHours' => number_format($com_hours, 1),
            ];



            // get monthly record ---------------------------------------------------------------------------------------
            $monthlyData = [];
            $employeeAttendance = $employee->empToDailyRecord;
            $company_month_days = 0;
            $emp_month_total_hours = 0;
            $company_month_hour = 0;

            $start_date = Carbon::parse($start_date);

            for ($i = 0; $i < $number_of_days; $i++) {
                $current_date = $start_date->copy()->addDays($i);
                $date = $current_date->format('Y-m-d');
                $match_date = Carbon::parse($date);
                $emp_month_hour = 0;
                $isHoliday = false;

                // Skip future dates
                if ($match_date->greaterThan(Carbon::today())) {
                    continue;
                }


                // Sum working hours for present days only
                foreach ($employeeAttendance as $dailyAttendance) {
                    if ($date == $dailyAttendance->dated) {
                        if ($dailyAttendance->check_in != null && $dailyAttendance->check_out != null) {
                            $emp_total_hours = $dailyAttendance->working_hours;
                            $emp_month_hour += $emp_total_hours;
                            $company_month_days++;
                        } 
                        // elseif ($dailyAttendance->check_in != null && $dailyAttendance->check_out == null) {
                        //     $emp_hr = new DateTime($dailyAttendance->check_in);
                        //     $difference = $todayTime->diff($emp_hr);
                        //     $hours = $difference->h + ($difference->i / 60);
                        //     $emp_total_hours = number_format($hours, 1);
                        //     $emp_month_hour += $emp_total_hours;
                        //     $company_month_days++;
                        // }
                    }

                }

                // Add to total employee hours and increment working days
                $emp_month_total_hours += $emp_month_hour;
            }

            $company_month_hour = $company_total_hours * $company_month_days;
            $monthlyData = [
                'monthlyUserTime' => number_format($emp_month_total_hours, 1),
                'totalMonthlyHours' => number_format($company_month_hour, 1),
            ];



            $emp_primary = user_family_refrence::where('emp_id', $EmpDetails->id)->where('emergency_contact', '1')->where('is_deleted', '0')->orderBy('id', 'asc')->first();
            $empRelation = user_family_refrence::where('emp_id', $EmpDetails->id)->where('is_deleted', '0')->get();
            $emp_secondry = user_family_refrence::where('emp_id', $EmpDetails->id)->where('is_deleted', '0')->orderBy('id', 'desc')->first();

            $branchDetails = DB::table('locations')->where('id', $EmpDetails->branch_id)->first();

            $joinDateRecord = DB::table('emp_approvals')
                ->where('emp_id', $EmpDetails->id)
                ->where('is_deleted', '0')
                ->select('joining_date')
                ->first();

            $joinDate = $joinDateRecord ? $joinDateRecord->joining_date : null;

            if ($emp_secondry && $emp_secondry->phone_number) {

            } else {
                $emp_secondry = null;
            }
            $employee = EmployeeDetail::where('id', $id)->first();
            $employee->imagePath = $this->imgFunc($employee->emp_image, $employee->emp_gender);

            return response()->json([
                'status' => 'success',
                'message' => 'Profile details fetched successfully',
                'data' => [
                    'empDetails' => $EmpDetails ?? null,
                    'empImage' => $employee->imagePath ?? null,
                    'empDocuments' => $empDocuments ?? null,
                    'branchDetails' => $branchDetails ?? null,
                    'joinDate' => $joinDate ?? null,
                    'emp_primary' => $emp_primary ?? null,
                    'emp_secondry' => $emp_secondry ?? null,
                    'empRelation' => $empRelation ?? null,
                    'EmpcompanyDetails' => $EmpcompanyDetails ?? null,
                    'cities' => $cities ?? null,
                    'countries' => $countries ?? null,
                    'empAccount' => $EmpAccount ?? null,
                    'empApproval' => $empApproval ?? null,
                    'empEducation' => $empEducation ?? null,
                    'empHistory' => $empHistory ?? null,
                    'companies' => $companies ?? null,
                    'todaysRecord' => $todaysRecords ?? null,
                    'totalLeaveRecords' => $totalLeaveRecords ?? null,
                    'weeklyData' => $weeklyData ?? null,
                    'monthlyData' => $monthlyData ?? null,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getEmployeeAttendance(Request $request)
    {
        $id = $request->input('id');
        $month = $request->input('month');
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $start_date = $request->get('month') ? date('Y-m-01', strtotime($month)) : date('Y-m-01');
        list($currentYear, $currentMonth) = explode('-', $month);
        $number_of_days = Carbon::create($currentYear, $currentMonth)->daysInMonth;
        $emp = EmployeeDetail::with([
            'empToDailyRecord' => function ($emp) use ($currentYear, $currentMonth) {
                $emp->whereYear('dated', $currentYear)
                    ->whereMonth('dated', $currentMonth)
                    ->orderBy('dated', 'asc');
            },
            'resignations',
            'leaves',
            'holidays'
        ]);
        if ($search) {
            $emp->where(function ($emp) use ($search) {
                $emp->where('employee_details.emp_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('employee_details.emp_id', 'LIKE', '%' . $search . '%');
            });
        }
        $employee = $emp->where('id', $id)->first();

        $empResignation = $employee->resignations ? $employee->resignations->is_approved : null;
        $resignationDate = $empResignation == '1' ? Carbon::parse($employee->resignations->resignation_date)->addDay() : null;

        $empTermination = $employee->terminations ? $employee->terminations->is_approved : null;
        $terminationDate = $empTermination == '1' ? Carbon::parse($employee->terminations->termination_date)->addDay() : null;

        $joining_date = $employee->approval ? Carbon::parse($employee->approval->joining_date)->subDay() : null;

        $company_detail = CompanySetting::where('branch_id', $employee->branch_id)->where('company_id', $employee->company_id)
            ->where('is_deleted', '0')
            ->first();

        $late_time = new DateTime($company_detail->late_time);

        $workingDays = ($company_detail ? explode(',', strtolower($company_detail->days)) : []);

        $employeeAttendance = $employee->empToDailyRecord;
        $response = [];
        for ($i = 0; $i < $number_of_days; $i++) {

            $current_date = strtotime($start_date);
            $next_date = strtotime("+" . $i . " day", $current_date);
            $date = date('Y-m-d', $next_date);
            $match_date = new DateTime($date);
            $attendanceStatus = '-';
            $emp_total_hours = 0;

            if ($match_date > Carbon::today()) {
                $data = [
                    'employee_id' => $employee->emp_id,
                    'date' => $match_date->format('d M Y'),
                    'check_in' => null,
                    'check_out' => null,
                    'working_hours' => null,
                    'attendance_status' => $attendanceStatus,
                    'check_in_lati' => null,
                    'check_out_longi' => null,
                    'check_in_address' => null,
                    'check_out_address' => null,
                    'check_in_image' => null,
                    'check_out_image' => null,
                ];
                array_push($response, $data);

                continue;
            }

            if (!in_array(strtolower($match_date->format('l')), $workingDays)) {
                $attendanceStatus = 'Weekend';
            }
            // else {
            //     $company_working_days++;
            // }

            $eligibleHolidays = Holiday::where('is_deleted', '0')
                ->where('is_active', '1')
                ->get();

            foreach ($eligibleHolidays as $holiday) {
                $holidayCompanyIds = explode(',', $holiday->company_id);
                $holidayBranchIds = explode(',', $holiday->branch_id);
                $startDate = Carbon::parse($holiday->start_date);
                $endDate = Carbon::parse($holiday->end_date);

                if (in_array($employee->company_id, $holidayCompanyIds) && in_array($employee->branch_id, $holidayBranchIds)) {
                    while ($startDate->lte($endDate)) {
                        if ($startDate->format('Y-m-d') == $date) {
                            $attendanceStatus = 'Holiday';
                            // $company_working_days--;

                            break 2;
                        }
                        $startDate->addDay();
                    }
                }
            }

            $emp_check_in = null;
            $emp_check_out = null;
            $check_in_lati = null;
            $check_out_longi = null;
            $check_in_address = null;
            $check_out_address = null;
            $check_in_image = null;
            $check_out_image = null;
            foreach ($employeeAttendance as $dailyAttendance) {

                if ($date == $dailyAttendance->dated) {
                    if ($dailyAttendance->leave && $dailyAttendance->leave_type != null && $dailyAttendance->check_in == null) {
                        $attendanceStatus = $dailyAttendance->leave;
                        break;
                    } elseif ($dailyAttendance->check_in != null && $dailyAttendance->check_out != null) {
                        $emp_check_in = new DateTime($dailyAttendance->check_in);
                        $emp_check_out = new DateTime($dailyAttendance->check_out);

                        $hours_data = $dailyAttendance->working_hours ?? null;
                        $hours_data = (float) $hours_data;
                        $total_hours = floor($hours_data);
                        $total_minutes = round(($hours_data - $total_hours) * 60);
                        $emp_total_hours = sprintf("%02dh : %02dm", $total_hours, $total_minutes);

                        if ($late_time >= $emp_check_in) {
                            $attendanceStatus = 'Present';
                        } else {
                            $attendanceStatus = 'Late';
                        }
                        $attendance_detail = AttendanceDetail::where('daily_record_id', $dailyAttendance->id)->first();
                        if ($attendance_detail) {
                            $check_in_lati = $attendance_detail->check_in_lati;
                            $check_out_longi = $attendance_detail->check_out_longi;
                            $check_in_address = $attendance_detail->check_in_address;
                            $check_out_address = $attendance_detail->check_out_address;
                            $check_in_image = $attendance_detail->check_in_image;
                            $check_out_image = $attendance_detail->check_out_image;
                        }
                        break;

                    } elseif ($dailyAttendance->check_in != null && $dailyAttendance->check_out == null) {
                        $emp_check_in = new DateTime($dailyAttendance->check_in);
                        if ($late_time >= $emp_check_in) {
                            $attendanceStatus = 'Present';
                        } else {
                            $attendanceStatus = 'Late';
                        }
                    }
                }
            }

            if ($joining_date != null && $date == $joining_date->format('Y-m-d')) {
                $attendanceStatus = 'New Joining';
            } elseif ($resignationDate != null && $date == $resignationDate->format('Y-m-d')) {
                $attendanceStatus = 'Resigned';
            } elseif ($terminationDate != null && $date == $terminationDate->format('Y-m-d')) {
                $attendanceStatus = 'Terminated';
            } elseif ($joining_date < $match_date && !in_array($attendanceStatus, ['Present', 'full leave', 'half leave', 'short leave', 'Holiday', 'Weekend', 'Late', 'New Joining', 'Resigned', 'Terminated'])) {
                $attendanceStatus = 'Absent';
            }


            $data = [
                'employee_id' => $employee->emp_id,
                'date' => $match_date->format('d M Y'),
                'check_in' => $emp_check_in ? $emp_check_in->format('H:i:s') : null,
                'check_out' => $emp_check_out ? $emp_check_out->format('H:i:s') : null,
                'working_hours' => $emp_total_hours ?? null,
                'attendance_status' => $attendanceStatus ?? null,
                'check_in_lati' => $check_in_lati ?? null,
                'check_out_longi' => $check_out_longi ?? null,
                'check_in_address' => $check_in_address ?? null,
                'check_out_address' => $check_out_address ?? null,
                'check_in_image' => $check_in_image ?? null,
                'check_out_image' => $check_out_image ?? null,
            ];

            array_push($response, $data);
        }

        // Paginate the results
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect($response);
        $currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values()->all();
        $totalItems = $collection->count();

        $paginatedData = new LengthAwarePaginator($currentPageItems, $totalItems, $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $request->query()
        ]);

        return response()->json($paginatedData);
    }
    public function employeeAsset(Request $request)
    {
        $emp_id = $request->emp_id;
        $page = $request->input('page', 1);
        $per_page = $request->input('per_page', 10);
        $offset = ($page - 1) * $per_page;
        $searchBy = $request->input('searchBy', '');

        $query = DB::table('assets')
            ->leftJoin('asset_types', 'asset_types.id', '=', 'assets.asset_type_id')
            ->leftJoin('locations', 'locations.id', '=', 'assets.branch_id')
            ->where('assets.is_deleted', 0)
            ->where('assets.emp_id', $emp_id)
            ->select('assets.id', 'assets.asset_name', 'assets.asset_id', 'asset_types.name as asset_type', 'assets.asset_price', 'locations.branch_name', 'assets.guarantee_date', 'assets.status', 'assets.assigned_date');

            if($searchBy){
                $query->where(function ($q) use ($searchBy){
                    $q->where('assets.asset_name', 'LIKE', '%' . $searchBy . '%')
                      ->orWhere('asset_types.name', 'LIKE', '%' . $searchBy . '%')
                      ->orWhere('assets.asset_id', 'LIKE', '%' . $searchBy . '%');
                });
            }

            $total = $query->count();

            $assets = $query->offset($offset)->limit($per_page)->get();
            $last_page = ceil($total / $per_page);
            $previous_page = $page > 1 ? $page - 1 : null;
            $next_page = $page < $last_page ? $page + 1 : null;

            $base_url = $request->url();
            $query_params = $request->query();
            $previous_page_url = $previous_page ? $base_url . '?' . http_build_query(array_merge($query_params, ['current_page' => $previous_page])) : null;
            $next_page_url = $next_page ? $base_url . '?' . http_build_query(array_merge($query_params, ['current_page' => $next_page])) : null;

            $pagination = [
                'total' => $total,
                'page' => $page,
                'per_page' => $per_page,
                'last_page' => $last_page,
                'previous_page' => $previous_page,
                'next_page' => $next_page,
                'previous_page_url' => $previous_page_url,
                'next_page_url' => $next_page_url
            ];

            return response()->json([
                'status' => 1,
                'message' => 'Employee Assets Fetch All',
                'data' => $assets,
                'pagination' => $pagination
            ]);
    }
}

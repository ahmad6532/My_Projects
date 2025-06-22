<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\DocumentsNames;
use App\Models\ZkRoledEmployee;
use App\Models\ZKSyncEmp;
use App\Traits\ProfileImage;
use Illuminate\Support\Facades\Storage;
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
use App\Models\PayPeriod;
use App\Models\Country;
use App\Models\EmpSalary;
use App\Models\EmpCompensation;
use App\Models\EmpCompensationDetails;
use App\User;
use Carbon\Carbon;
use App\Models\Location;
use App\Models\Company;
use App\Models\Holiday;
use App\Models\City;
use App\Models\Language;
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
use App\Models\Related_refrence;
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
use App\Http\Controllers\API\Admin\NotificationController;
use Illuminate\Support\Str;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Emp_salary;
use App\Models\SubDepartment;
use App\Models\SalaryComponent;
use App\Models\SalaryComponentType;
use Exception;

use function PHPUnit\Framework\isEmpty;

class AdminEmployeeController extends BaseController
{
    use ProfileImage;
    // public function employeeList(Request $request)
    // {
    //     try {
    //         $user = auth()->user();
    //         $user_role = $user->role_id;
    //         $user_company_id = explode(',', $user->company_id);
    //         $user_branch_id = explode(',', $user->branch_id);
    //         $selectedStatus = $request->selectStatus ?? '';
    //         $selectedBranch = $request->branch_id ?? $user_branch_id;
    //         $searchBy = $request->search_by ?? null;
    //         $perPage = $request->per_page ?? 10; // Default per page limit

    //         // Ensure selectedBranch is an array
    //         if (!is_array($selectedBranch)) {
    //             $selectedBranch = explode(',', $selectedBranch);
    //         }

    //         // Initialize the query
    //         $query = EmployeeDetail::leftJoin('locations', 'locations.id', '=', 'employee_details.branch_id')
    //         ->leftJoin('emp_documents', 'emp_documents.emp_id', '=', 'employee_details.id')
    //         ->select('locations.branch_name', 'employee_details.*', 'emp_documents.document_path') // Selecting all columns from emp_documents
    //         ->where('locations.is_deleted', '0')
    //         ->where('employee_details.is_deleted', '0');

    //         if ($searchBy) {
    //             $query->where(function ($query) use ($searchBy) {
    //                 $query->where('employee_details.emp_name', 'LIKE', '%' . $searchBy . '%')
    //                     ->orWhere('employee_details.id', 'LIKE', '%' . $searchBy . '%');
    //             });
    //         }

    //         // Apply branch filter
    //         if (!empty($selectedBranch) && $selectedBranch !== $user_branch_id) {
    //             $query->whereIn('employee_details.branch_id', $selectedBranch);
    //         }

    //         // Apply status filter only if selectedStatus is not empty
    //         if ($selectedStatus !== '') {
    //             if ($selectedStatus == "5") {
    //                 $query->where('employee_details.is_deleted', '1');
    //             } else {
    //                 $query->where('employee_details.status', $selectedStatus);
    //             }
    //         }

    //         // Apply company filter for admin role

    //         if ($user_role != '1') {
    //             $query->whereIn('employee_details.company_id', $user_company_id);
    //         }

    //         // Order by emp_id ascending
    //         $query->orderBy('employee_details.id', 'desc');

    //         // Paginate the results based on user role
    //         if ($user_role == '1') {
    //             $employees = $query->paginate($perPage);
    //         } else {
    //             $employees = $query->get();
    //         }
    //         // Additional data processing for each employee
    //         foreach ($employees as $employee) {
    //             // Fetch additional details (if needed)
    //             $employee->approved_leave_days = Leave::where('is_approved', '1')
    //                 ->where('is_deleted', '0')
    //                 ->where('emp_id', $employee->id)
    //                 ->sum('approved_days');

    //             // Fetch user approval details
    //             $user_approval = user_approval::where('emp_id', $employee->id)->first();
    //             if ($user_approval) {
    //                 $designation = Designation::find($user_approval->designation_id);
    //                 if ($designation) {
    //                     $employee->designation_name = $designation->name;
    //                     $employee->joining_date = date('m-d-Y', strtotime($user_approval->joining_date));
    //                     $department = Department::find($designation->department_id);
    //                     if ($department) {
    //                         $employee->department_name = $department->name;
    //                     }
    //                 }
    //             } else {
    //                 // $employee->designation_name = '';
    //                 // $employee->joining_date = '';
    //                 // $employee->department_name = '';
    //             }

    //             $employee->emp_image = asset($employee->emp_image);
    //             unset($employee->updated_at);
    //         }

    //         $data = [
    //             'details' => [
    //                 'employees' => [
    //                     'current_page' => $employees->currentPage(),
    //                     'next_page_url' => $employees->nextPageUrl(),
    //                     'path' => $employees->path(),
    //                     'per_page' => $employees->perPage(),
    //                     'prev_page_url' => $employees->previousPageUrl(),
    //                     'to' => $employees->count() > 0 ? $employees->lastItem() : null,
    //                     'total' => $employees->total(),
    //                     'total_pages' => $employees->lastPage(),
    //                     'data' => $employees->isEmpty() ? [] : $employees->toArray()['data'], // Extract only 'data'
    //                 ],
    //             ],
    //         ];

    //         return response()->json($data);

    //     } catch (\Exception $e) {
    //         \Log::error('Error in employeeList function: ' . $e->getMessage());
    //         return response()->json(['error' => 'Failed to fetch employees.'], 500);
    //     }
    // }
    public function employeeList(Request $request)
    {
        try {
            $user = auth()->user();
            $user_role = $user->role_id;
            $user_company_id = explode(',', $user->company_id);
            $user_branch_id = explode(',', $user->branch_id);

            $selectedStatus = $request->selectStatus ?? '';
            $selectedBranch = $request->branch_id ?? $user_branch_id;
            $searchBy = $request->search_by ?? null;
            $perPage = $request->per_page ?? 10;

            if (!is_array($selectedBranch)) {
                $selectedBranch = explode(',', $selectedBranch);
            }

            $query = EmployeeDetail::leftJoin('locations', 'locations.id', '=', 'employee_details.branch_id')
                ->leftJoin('companies', 'companies.id', '=', 'employee_details.company_id')
                ->leftJoin('emp_account_details', 'emp_account_details.emp_id', '=', 'employee_details.id')
                ->select('locations.branch_name', 'employee_details.*', 'companies.company_name', 'emp_account_details.bank_name', 'emp_account_details.account_no')
                ->where('locations.is_deleted', '0')
                ->where('employee_details.is_deleted', '0');

            if ($user_role != '1') {
                $query->whereIn('employee_details.company_id', $user_company_id)
                    ->whereIn('employee_details.branch_id', $user_branch_id);
            }
            if ($searchBy) {
                $query->where(function ($query) use ($searchBy) {
                    $query->where('employee_details.emp_name', 'LIKE', '%' . $searchBy . '%')
                        ->orWhere('employee_details.emp_id', 'LIKE', '%' . $searchBy . '%');
                });
            }

            if (!empty($selectedBranch) && $selectedBranch !== $user_branch_id) {
                $query->whereIn('employee_details.branch_id', $selectedBranch);
            }

            if ($selectedStatus !== '') {
                if ($selectedStatus == "5") {
                    $query->where('employee_details.is_deleted', '1');
                } else {
                    $query->where('employee_details.status', $selectedStatus);
                }
            }

            $query->orderBy('employee_details.id', 'desc');

            $employees = $query->paginate($perPage);

            foreach ($employees as $employee) {
                // $documents = EmployeeDocument::where('emp_id', $employee->id)->get();
                // foreach ($documents as $document) {
                //     $document->document_path = url('api/storage/app/' . $document->document_path);
                // }
                // $employee->documents = $documents;

                $employee->approved_leave_days = Leave::where('is_approved', '1')
                    ->where('is_deleted', '0')
                    ->where('emp_id', $employee->id)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('approved_days');

                $user_approval = user_approval::where('emp_id', $employee->id)->first();
                if ($user_approval) {
                    $designation = Designation::find($user_approval->designation_id);
                    if ($designation) {
                        $employee->designation_name = $designation->name;
                        $employee->joining_date = date('m-d-Y', strtotime($user_approval->joining_date));
                        $department = Department::find($designation->department_id);
                        if ($department) {
                            $employee->department_name = $department->name;
                        }
                    }
                }

                $employee->imagePath = $this->imgFunc($employee->emp_image, $employee->emp_gender);
            }

            $data = [
                'details' => [
                    'employees' => [
                        'current_page' => $employees->currentPage(),
                        'next_page_url' => $employees->nextPageUrl(),
                        'path' => $employees->path(),
                        'per_page' => $employees->perPage(),
                        'prev_page_url' => $employees->previousPageUrl(),
                        'to' => $employees->count() > 0 ? $employees->lastItem() : null,
                        'total' => $employees->total(),
                        'total_pages' => $employees->lastPage(),
                        'data' => $employees->items(),
                    ],
                ],
            ];

            return response()->json($data);

        } catch (\Exception $e) {
            \Log::error('Error in employeeList function: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch employees.'], 500);
        }
    }


    public function addApproval()
    {
        $designations = Designation::orderBy('name', 'asc')->select('id', 'department_id', 'name')->get();
        $job_status = Job_type::orderBy('job_status', 'asc')->select('id', 'job_status')->get();
        $data = [
            'designations' => $designations,
            'job_status' => $job_status,
        ];
        if (!empty($data)) {
            return $this->sendResponse($data, 'Approvals data feteched successfully!');
        } else {
            return $this->sendError([], 'Data not found!');
        }
    }


    public function getCompanies()
    {
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);

        if ($user_role == '1') {
            $query = Company::select('id', 'company_name', 'phone', 'country_id', 'city_id')
                ->where('is_deleted', '0')
                ->orderBy('company_name', 'asc');
        } else {
            $query = Company::select('id', 'company_name', 'phone', 'country_id', 'city_id')
                ->where('is_deleted', '0')
                ->orderBy('company_name', 'asc')
                ->whereIn('id', $user_company_id);
        }

        // if ($user_role != '1') {
        //     $query->whereIn('id', $user_company_id);
        // }

        $companies = $query->get();

        foreach ($companies as $company) {
            $country = Country::where('country_id', $company->country_id)->first();
            $city = City::where('city_id', $company->city_id)
                ->where('country_id', $company->country_id)
                ->first();

            if ($country) {
                $company->country_name = $country->country_name;
            } else {
                $company->country_name = 'N/A'; // or any default value you prefer
            }

            if ($city) {
                $company->city_name = $city->city_name;
            } else {
                $company->city_name = 'N/A'; // or any default value you prefer
            }
        }

        if ($companies->isNotEmpty()) {
            return $this->sendResponse($companies, 'Companies fetched successfully!');
        } else {
            return $this->sendError([], 'Data not found!');
        }
    }


    public function getBranches(Request $request)
    {
        // user information
        $user = auth()->user();
        // $user_company_id = explode(',', $user->company_id);
        // $query = Location::where('is_deleted', '0');
        // $company_ids = isset($request->company_id) && !empty($request->company_id) ? $request->company_id : $user_company_id;
        // if ($user->role_id != '1') {
        //     $query->whereIn('company_id', $company_ids);
        // } elseif ($user->role_id == '1' && isset($request->company_id) && !empty($request->company_id)) {
        //     $query->whereIn('company_id', $company_ids);
        // }
        // $getBranches = $query->get();


        $user_company_ids = explode(',', $user->company_id);
        $user_branch_ids = explode(',', $user->branch_id);


        // if ($user->role_id == '1') {
        $query = Location::where('is_deleted', '0');
        $company_ids = isset($request->company_id) && !empty($request->company_id) ? $request->company_id : $user_company_ids;

        if (isset($request->company_id) && !empty($request->company_id)) {
            $query->whereIn('company_id', $company_ids);
        }
        // } else {
        //     $query = Location::where('is_deleted', '0')
        //         ->whereIn('company_id', $user_company_ids)
        //         ->whereIn('id', $user_branch_ids);
        // }
        if ($user->role_id != '1') {
            $query->whereIn('id', $user_branch_ids);
        }
        $getBranches = $query->get();

        if (!empty($getBranches)) {
            return $this->sendResponse($getBranches, 'Branches fetched successfully!');
        } else {
            return $this->sendError([], 'Data not found!');
        }
    }

    public function getCountries()
    {
        $countries = Country::where('is_deleted', 'N')
            ->orderBy('country_name', 'asc')
            ->select('country_id', 'country_name', 'phoneCode')
            ->get();

        if (!empty($countries)) {
            return $this->sendResponse($countries, 'Countries fetched successfully!');
        } else {
            return $this->sendError([], 'Data not found!');
        }
    }

    public function getCities(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError([], $validator->errors()->first(), 400);
        }
        $cities = City::where('country_id', $request->country_id)
            ->select('city_id', 'city_name')
            ->orderBy('city_name', 'asc')
            ->get();

        if (!empty($cities)) {
            return $this->sendResponse($cities, 'Cities feteched successfully!');
        } else {
            return $this->sendError([], 'Cities Not Found!');
        }
    }

    public function getJobStatus()
    {
        $job_status = Job_type::select('id', 'job_status')->get();

        if (!empty($job_status)) {
            return $this->sendResponse($job_status, 'Jobs Status fetched successfully!');
        } else {
            return $this->sendError([], 'Data not found!');
        }
    }

    public function getDesignations()
    {
        $designations = Designation::select('id', 'name', 'department_id')->get();

        if (!empty($designations)) {
            return $this->sendResponse($designations, 'Designations fetched successfully!');
        } else {
            return $this->sendError([], 'Data not found!');
        }
    }

    public function getDepartments()
    {
        $departments = Department::select('id', 'name')->get();

        if (!empty($departments)) {
            return $this->sendResponse($departments, 'Departments fetched successfully!');
        } else {
            return $this->sendError([], 'Data not found!');
        }
    }
    //Getting the employees data ++++
    public function getEmployees()
    {
        $employees = EmployeeDetail::select('emp_id', 'emp_name')->get();

        if (!empty($employees)) {
            return $this->sendResponse($employees, 'Employees fetched successfully!');
        } else {
            return $this->sendError([], 'Data not found!');
        }
    }

    public function getSearchedEmployee(Request $request)
    {
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);
        $fetchEmps = [];

        $selectStatus = isset($request->selectStatus) && !empty($request->selectStatus) ? $request->selectStatus : '1';
        $branchs_id = isset($request->branch_id) && !empty($request->branch_id) ? $request->branch_id : 'all';
        $branch_id = implode(',', $branchs_id);

        if ($user_role == '1') {
            if (isset($request->emp_name) && $branch_id == 'all') {
                if ($selectStatus == '5') {
                    $fetchEmps = EmployeeDetail::where('emp_name', 'LIKE', '%' . $request->emp_name . '%')
                        ->where('is_deleted', '1')
                        ->select('id', 'branch_id', 'emp_id', 'emp_name', 'company_id', 'status', 'is_active', 'is_deleted')
                        ->orderBy('emp_id', 'asc')
                        ->get();
                } else {
                    $fetchEmps = EmployeeDetail::where('emp_name', 'LIKE', '%' . $request->emp_name . '%')
                        ->where('status', $selectStatus)
                        ->where('is_deleted', '0')
                        ->select('id', 'branch_id', 'emp_id', 'emp_name', 'company_id', 'status', 'is_active', 'is_deleted')
                        ->orderBy('emp_id', 'asc')
                        ->get();
                }
            } elseif (isset($request->emp_name) && $branch_id) {
                if ($selectStatus == '5') {
                    $fetchEmps = EmployeeDetail::where('branch_id', 'LIKE', $branch_id . '%')
                        ->where('is_deleted', '1')
                        ->where('emp_name', 'LIKE', '%' . $request->emp_name . '%')
                        ->select('id', 'branch_id', 'emp_id', 'emp_name', 'company_id', 'status', 'is_active', 'is_deleted')
                        ->orderBy('emp_id', 'asc')
                        ->get();
                } else {
                    $fetchEmps = EmployeeDetail::where('branch_id', 'LIKE', $branch_id . '%')
                        ->where('status', $selectStatus)
                        ->where('is_deleted', '0')
                        ->where('emp_name', 'LIKE', '%' . $request->emp_name . '%')
                        ->select('id', 'branch_id', 'emp_id', 'emp_name', 'company_id', 'status', 'is_active', 'is_deleted')
                        ->orderBy('emp_id', 'asc')
                        ->get();
                }
            } elseif ($branch_id != 'all') {
                if ($selectStatus == '5') {
                    $fetchEmps = EmployeeDetail::where('branch_id', 'LIKE', $branch_id . '%')
                        ->where('is_deleted', '1')
                        ->select('id', 'branch_id', 'emp_id', 'emp_name', 'company_id', 'status', 'is_active', 'is_deleted')
                        ->orderBy('emp_id', 'asc')
                        ->get();
                } else {
                    $fetchEmps = EmployeeDetail::where('branch_id', 'LIKE', $branch_id . '%')
                        ->where('status', $selectStatus)
                        ->where('is_deleted', '0')
                        ->select('id', 'branch_id', 'emp_id', 'emp_name', 'company_id', 'status', 'is_active', 'is_deleted')
                        ->orderBy('emp_id', 'asc')
                        ->get();
                }
            } elseif ($request->branch_id === 'all') {
                if ($selectStatus == '5') {

                    $fetchEmps = EmployeeDetail::where('is_deleted', '1')
                        ->select('id', 'branch_id', 'emp_id', 'emp_name', 'company_id', 'status', 'is_active', 'is_deleted')
                        ->orderBy('emp_id', 'asc')
                        ->get();
                } else {

                    $fetchEmps = EmployeeDetail::where('status', $selectStatus)
                        ->where('is_deleted', '0')
                        ->select('id', 'branch_id', 'emp_id', 'emp_name', 'company_id', 'status', 'is_active', 'is_deleted')
                        ->orderBy('emp_id', 'asc')
                        ->get();
                }
            }
            foreach ($fetchEmps as $employee) {
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
        } else {
            if (isset($request->emp_name) && $branch_id == 'all') {
                $fetchEmps = EmployeeDetail::whereIn('company_id', $user_company_id)
                    ->whereIn('branch_id', $user_branch_id)
                    ->where('emp_name', 'LIKE', '%' . $request->emp_name . '%')
                    ->where('status', $selectStatus)
                    ->where('is_deleted', '0')
                    ->orderBy('emp_id', 'asc')
                    ->get();
            } elseif (isset($request->emp_name) && $branch_id) {
                $fetchEmps = EmployeeDetail::whereIn('company_id', $user_company_id)
                    ->whereIn('branch_id', $user_branch_id)
                    ->where('branch_id', 'LIKE', $branch_id . '%')
                    ->where('status', $selectStatus)
                    ->where('is_deleted', '0')
                    ->where('emp_name', 'LIKE', '%' . $request->emp_name . '%')
                    ->orderBy('emp_id', 'asc')
                    ->get();
            } elseif ($branch_id != 'all') {
                $fetchEmps = EmployeeDetail::whereIn('company_id', $user_company_id)
                    ->whereIn('branch_id', $user_branch_id)
                    ->whereIn('branch_id', $branch_id)
                    ->where('status', $selectStatus)
                    ->where('is_deleted', '0')
                    ->orderBy('emp_id', 'asc')
                    ->get();
            } elseif ($branch_id === 'all') {
                $fetchEmps = EmployeeDetail::whereIn('company_id', $user_company_id)
                    ->whereIn('branch_id', $user_branch_id)
                    ->where('status', $selectStatus)
                    ->where('is_deleted', '0')
                    ->orderBy('emp_id', 'asc')
                    ->get();
            }
            foreach ($fetchEmps as $employee) {
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
            $approvals = user_approval::where('emp_id', $emp_desig->id)->first();
            if ($approvals) {
                $emp_designation = Designation::where('id', $approvals->designation_id)->first();
                $emp_desig->designation_name = $emp_designation ? $emp_designation->name : '';
                $department = Department::where('id', $emp_designation->department_id)->first();
                $emp_desig->department_name = $department ? $department->name : '';
            } else {
                $emp_desig->designation_name = '';
                $emp_desig->department_name = '';
            }
        }

        if (isset($fetchEmp) && count($fetchEmp) > 0) {
            return $this->sendResponse($fetchEmp, 'Employee searched successfully!');
        } else {
            return $this->sendResponse([], 'Data not found');
        }
    }

    public function storeEmployee(Request $request)
    {
        DB::beginTransaction();
        try {
            $employee = null;
            $id = $request->input('id', null);

            // Validation rules
            $rules = [
                'personal_email' => 'required|email|unique:employee_details,personal_email,' . $id . '|max:255',
                'emp_email' => 'nullable|email|max:255',
                'emp_id' => 'required|unique:employee_details,emp_id,' . $id,
                'emp_name' => 'sometimes|string',
                'father_name' => 'sometimes|string',
                'mother_name' => 'sometimes|string',
                'emp_address' => 'sometimes|string',
                'emp_phone' => 'sometimes|string|unique:employee_details,emp_phone,' . $id,
                'emp_gender' => 'sometimes',
                'cnic' => 'sometimes|string|unique:employee_details,cnic,' . $id,
                'dob' => 'sometimes|date',
                'nationality' => 'sometimes|string',
                'city_of_birth' => 'sometimes|string',
                'religion' => 'sometimes|string',
                'blood_group' => 'sometimes|string',
                'marital_status' => 'nullable',
                'spouse_name' => 'nullable|string',
                'is_independant' => 'required',
                'has_home' => 'sometimes|boolean',
                'has_transport' => 'nullable|boolean',
                'transport_type' => 'nullable|string',
                'registration_no' => 'nullable',
                'driving_license' => 'nullable',
                'license_no' => 'nullable',
                // 'documents.*' => 'nullable|file|mimes:jpeg,png,gif,pdf,doc,docx,xls,xlsx|max:10240',
            ];

            // Validate request
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 0,
                    'message' => $validator->errors(),
                ], 422);
            }

            // Handle image upload
            $fullImageUrl = null;
            if ($request->hasFile('emp_image')) {
                $fileImage = $request->file('emp_image');
                $imageName = time() . '-' . $fileImage->getClientOriginalName();
                $fullImagePath = $fileImage->storeAs('images/users', $imageName);
                $fullImageUrl = 'images/users/' . $imageName;
            }

            // Check if updating or creating new employee
            if ($request->has('id')) {
                $employee = EmployeeDetail::find($request->input('id'));
            }

            // Update existing employee
            if ($employee) {
                if ($fullImageUrl && $employee->emp_image && Storage::exists($employee->emp_image)) {
                    Storage::delete($employee->emp_image);
                }

                $employee->fill($request->only([
                    'company_id',
                    'emp_id',
                    'branch_id',
                    'personal_email',
                    'emp_email',
                    'emp_name',
                    'father_name',
                    'mother_name',
                    'emp_address',
                    'emp_phone',
                    'emp_gender',
                    'cnic',
                    'dob',
                    'nationality',
                    'city_of_birth',
                    'religion',
                    'blood_group',
                    'marital_status',
                    'spouse_name',
                    'is_independant',
                    'has_home',
                    'has_transport',
                    'transport_type',
                    'registration_no',
                    'driving_license',
                    'license_no'
                ]));

                if ($fullImageUrl) {
                    $employee->emp_image = $fullImageUrl;
                }

                $employee->save();
            } else { // Create new employee
                $data = $request->only([
                    'company_id',
                    'emp_id',
                    'branch_id',
                    'personal_email',
                    'emp_email',
                    'emp_name',
                    'father_name',
                    'mother_name',
                    'emp_address',
                    'emp_phone',
                    'emp_gender',
                    'cnic',
                    'dob',
                    'nationality',
                    'city_of_birth',
                    'religion',
                    'blood_group',
                    'marital_status',
                    'spouse_name',
                    'is_independant',
                    'has_home',
                    'has_transport',
                    'transport_type',
                    'registration_no',
                    'driving_license',
                    'license_no'
                ]);

                $data['added_by'] = Auth::user()->fullname;
                $data['emp_image'] = $fullImageUrl;

                if ($request->has('dob')) {
                    $data['dob'] = Carbon::parse($request->input('dob'))->format('Y-m-d');
                }

                $employee = EmployeeDetail::create($data);
            }
            DB::commit();

            // Prepare response data
            $employeeDetails = $employee->toArray();
            if (isset($employeeDetails['dob'])) {
                $employeeDetails['dob'] = Carbon::parse($employeeDetails['dob'])->format('Y-m-d');
            }
            $employeeDetails['updated_at'] = Carbon::now()->format('d-m-Y h:i A');

            return response()->json([
                'status' => 1,
                'message' => 'Employee stored or updated successfully!',
                'details' => $employeeDetails,
                // 'documents' => $employee->documents,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 0,
                'message' => 'Failed to store or update employee.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteDocumentWhileStoring(Request $request)
    {
        $document = EmployeeDocument::where('id', $request->document_id)->first();
        if ($document) {
            $deletedDocument = $document->delete();
            if ($deletedDocument) {
                return $this->sendResponse([], 'Document delete successfully!', 200);
            } else {
                return $this->sendError([], 'Document not deleted!', );
            }
        } else {
            return $this->sendError([], 'Data not found!', 404);
        }
    }

    public function storeAccountDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'bank_name' => 'required',
            'account_number' => 'required',
            'branch_name' => 'required',
            'account_holder_name' => 'required',
            'iban' => 'required',
            'swift_code' => 'nullable',
        ]);
        if ($validator->fails()) {
            return $this->sendError([], $validator->errors(), 400);
        }
        $existingRecords = AccountDetail::where('emp_id', $request->employee_id)->get();
        if (!$existingRecords->isEmpty()) {
            AccountDetail::where('emp_id', $request->employee_id)->delete();
        }

        $addUser = AccountDetail::create([
            'emp_id' => $request->employee_id,
            'bank_name' => $request->bank_name,
            'account_no' => $request->account_number,
            'branch_name' => $request->branch_name,
            'swift_code' => $request->swift_code,
            'iban_code' => $request->iban_code,
            'acc_holder_name' => $request->account_holder_name,
            'iban_code' => $request->iban
        ]);
        $employee = EmployeeDetail::where('id', $request->employee_id)->first();
        if ($employee && $employee->email != null) {
            $msg = '"' . ucwords($employee->emp_name) . '" Account Added Successfully';
            createLog('employee_action', $msg);
        }
        if (!empty($addUser)) {
            return $this->sendResponse($addUser, 'Accounts Details Added Successfully!');
        } else {
            return $this->sendError([], 'Form not submited! Some technical issue occured!');
        }
    }

    public function storeEmployeeEducation(Request $request)
    {
        $existingRecords = EmployeeEducationDetail::where('emp_id', $request->employee_id)->get();

        if (!$existingRecords->isEmpty()) {
            EmployeeEducationDetail::where('emp_id', $request->employee_id)->delete();
        }

        $data = [];
        $emp_degrees = $request->emp_degree;
        $major_subs = $request->major_sub;
        $grade_divisions = $request->grade_division;
        $degree_froms = $request->degree_from;
        $degree_tos = $request->degree_to;
        $institutes = $request->institute;
        $other_qualification = $request->other_qualifications;
        $emp_language = $request->emp_language;

        DB::beginTransaction();
        try {
            // Insert new employee education details
            for ($i = 0; $i < count($emp_degrees); $i++) {
                $emp_education = new EmployeeEducationDetail;
                $emp_education->emp_id = $request->employee_id;
                $emp_education->degree = $emp_degrees[$i];
                $emp_education->subject = $major_subs[$i];
                $emp_education->grade = $grade_divisions[$i];
                $emp_education->division = $grade_divisions[$i];
                $emp_education->degree_from = $degree_froms[$i];
                $emp_education->degree_to = $degree_tos[$i];
                $emp_education->institution = $institutes[$i];
                if (!empty($other_qualification)) {
                    $emp_education->other_qualifications = $other_qualification;
                }
                $emp_education->save();
                $data['employee_education'][] = $emp_education;
            }

            if (!empty($emp_language)) {
                // Delete existing languages
                user_language::where('emp_id', $request->employee_id)->delete();

                // Insert new languages
                foreach ($emp_language as $language) {
                    user_language::create([
                        'emp_id' => $request->employee_id,
                        'language_id' => $language
                    ]);
                }
            } else {
                // If $emp_language is empty or null, delete all existing languages for this employee
                user_language::where('emp_id', $request->employee_id)->delete();
            }


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError([], $e->getMessage(), 500);
        }

        // Log action if employee is found
        $employee = EmployeeDetail::find($request->employee_id);
        if ($employee) {
            $msg = '"' . ucwords($employee->emp_name ?? 'N/A') . '" Education Added Successfully';
            createLog('employee_action', $msg);
        }

        return $this->sendResponse($data, 'Employee Education Added Successfully!');
    }

    public function storeEmployeeExperience(Request $request)
    {
        $storedData = [];
        $emp_id = $request->employee_id;
        $organization = $request->organization;
        $prev_position = $request->prev_position;
        $prev_salary = $request->prev_salary;
        $exp_from = $request->exp_from;
        $exp_to = $request->exp_to;
        $reason_for_leaving = $request->reason_for_leaving;
        $court_convic = $request->any_conviction;

        DB::beginTransaction();
        try {

            user_experience::where('emp_id', $emp_id)->delete();

            for ($i = 0; $i < count($organization); $i++) {
                if (!is_null($organization[$i])) {
                    $saveOrganization = new user_experience();
                    $saveOrganization->emp_id = $emp_id;
                    $saveOrganization->organization = $organization[$i];
                    $saveOrganization->prev_position = $prev_position[$i];
                    $saveOrganization->prev_salary = $prev_salary[$i];
                    $saveOrganization->exp_from = $exp_from[$i];
                    $saveOrganization->exp_to = $exp_to[$i];
                    $saveOrganization->reason_for_leaving = $reason_for_leaving[$i];
                    $saveOrganization->court_conviction = $court_convic;

                    $saveOrganization->save();

                    $data = user_experience::where('emp_id', $emp_id)->get();
                    $storedData['employee_experience'][] = $data;
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError([], $e->getMessage());
        }

        // Create a log entry if the employee exists
        $employee = EmployeeDetail::find($emp_id);
        if ($employee) {
            $msg = '"' . ucwords($employee->emp_name ?? 'N/A') . '" Employment Added Successfully';
            createLog('employee_action', $msg);
        }

        if (!empty($storedData)) {
            return $this->sendResponse($storedData, 'Employment Added Successfully!');
        } else {
            return $this->sendError([], 'Employment Not Added Successfully!');
        }
    }

    public function storeEmployeeFamilyData(Request $request)
    {
        $employeeId = $request->employee_id;
        $userFaimlyReference = user_family_refrence::where('emp_id', $employeeId)->exists();
        $employeeRelative = EmployeeRelative::where('emp_id', $employeeId)->exists();
        $relatedReference = Related_refrence::where('emp_id', $employeeId)->exists();

        if ($userFaimlyReference) {
            user_family_refrence::where('emp_id', $employeeId)->delete();
        }
        if ($employeeRelative) {
            EmployeeRelative::where('emp_id', $employeeId)->delete();
        }
        if ($relatedReference) {
            Related_refrence::where('emp_id', $employeeId)->delete();
        }

        $data = [];
        $validator = Validator::make($request->all(), [
            'memeber_name' => 'required',
            'memeber_relation' => 'required',
            'memeber_occupation' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError([], $validator->errors());
        }
        $memeber_name = $request->memeber_name;
        $phone_number = $request->phone_number;
        $memeber_relation = $request->memeber_relation;
        $memeber_age = $request->memeber_age;
        $memeber_occupation = $request->memeber_occupation;
        $place_of_work = $request->place_of_work;
        $emergency_contact = $request->emergency_contact;
        DB::beginTransaction();
        try {
            for ($i = 0; $i < count($memeber_name); ++$i) {
                if ($memeber_name[$i] != null) {
                    $data['faimly_reference'][] = user_family_refrence::create([
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

            if (($request->input('has_relative') == '1') && $request->relative_name != null) {
                $data['employee_relative'] = EmployeeRelative::create([
                    'emp_id' => $request->employee_id,
                    'relative_name' => $request->relative_name,
                    'relative_position' => $request->relative_position,
                    'relative_dept' => $request->relative_dept,
                    'relative_location' => $request->relative_location,
                    'relative_relation' => $request->relative_relation,
                ]);
            }

            if (($request->input('has_reference') == '1')) {
                $ref_name = $request->refrence_name;
                $ref_position = $request->ref_position;
                $ref_address = $request->ref_address;
                $ref_phone = $request->ref_phone;

                for ($i = 0; $i < count($ref_name); ++$i) {
                    if ($ref_name[$i] != null) {
                        $data['employee_reference'][] = Related_refrence::create([
                            'emp_id' => $request->employee_id,
                            'refrence_name' => $ref_name[$i],
                            'ref_position' => $ref_position[$i],
                            'ref_address' => $ref_address[$i],
                            'ref_phone' => $ref_phone[$i],
                        ]);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError([], $e->getMessage());
        }

        if (!empty($data)) {
            return $this->sendResponse($data, 'Employee faimly update successfully!', 200);
        } else {
            return $this->sendError([], 'Data not found!', 404);
        }
    }

    public function storeEmployeeApproval(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'designation_id' => 'required',
            'department_id' => 'required',
            'joining_date' => 'required',
            'starting_sal' => 'required',
            'job_status_id' => 'required',
            // 'sub_department_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError([], $validator->errors()->first(), 400);
        }

        $emp_id = $request->employee_id;
        $existingRecords = user_approval::where('emp_id', $emp_id)->get();
        if (!$existingRecords->isEmpty()) {
            user_approval::where('emp_id', $emp_id)->delete();
        }

        DB::beginTransaction();
        try {
            $approvalData = [];
            $role_id = '3';
            $aproval = new user_approval();
            $aproval->user_id = Auth::user()->id;
            $aproval->emp_id = $request->employee_id;
            $aproval->report_to = (int) $request->report_to ?? null;
            $aproval->designation_id = $request->designation_id;
            $aproval->joining_date = date('Y-m-d', strtotime($request->joining_date));
            $aproval->phone_issued = $request->phone_issued ?? null;
            $aproval->starting_sal = $request->starting_sal;
            $aproval->job_status_id = $request->job_status_id;
            $aproval->department_id = $request->department_id;
            // $aproval->sub_department_id = $request->sub_department_id;
            $aproval->save();
            $approvalData['approvals'][] = $aproval;

            if ($request->emp_email) {
                $employee = EmployeeDetail::where('id', $request->employee_id)->first();
                $employee->update([
                    'emp_email' => $request->emp_email
                ]);
            }
            // $joiningTimestamp = strtotime($aproval->joining_date);
            // $expiryDate = strtotime('+1 week', $joiningTimestamp);


            // if ($employee && $request->has('emp_email')) {
            //     Mail::send('email.welcomeUser', [
            //         'emp_name' => $employee->emp_name,
            //         'email' => $request->emp_email,
            //         'expiry_date' => $expiryDate
            //     ], function ($message) use ($request) {
            //         $emailServicesFromName = Setting::where('perimeter', 'smtp_from_name')->first();
            //         $emailServicesFromEmail = Setting::where('perimeter', 'smtp_from_email')->first();
            //         $message->from($emailServicesFromEmail->value, $emailServicesFromName->value);
            //         $message->to($request->emp_email);
            //         $message->subject('Welcome to [Your Company]');
            //     });
            // }

            $data = [];
            $type = "New Employee Added";
            $branch = $employee->branch_id ?? null;
            $data['emp_name'] = $employee->emp_name ?? "N/A";
            $data['employee_email'] = $employee->emp_email ?? "N/A";
            $data['joining_date'] = $request->joining_date;
            $data['employee_phone'] = $employee->emp_phone ?? 'N/A';
            $createNotification = new NotificationController();
            $createNotification->generateNotification($type, $data, $branch);

            $msg = '"' . ucwords($employee->emp_name ?? "N/A") . '" Approval Added Successfully';

            DB::commit();
            createLog('employee_action', $msg);

            if (!empty($approvalData)) {
                return $this->sendResponse($approvalData, 'Employee Record Added Successfully!');
            } else {
                return $this->sendError([], 'Employee Record Not Added Successfully!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError([], 'Failed to add employee record: ' . $e->getMessage(), 500);
        }
    }

    public function editEmployeeData(Request $request)
    {
        $data = [];
        $liveBaseUrl = url('/');

        $user = auth()->user();
        $user_company_id = explode(',', $user->company_id);
        $employee_id = $request->id;

        // Fetch the company details
        $companies = Company::whereIn('id', $user_company_id)->orderBy('company_name', 'asc')->get();

        // Fetch employee details
        $EmpDetails = EmployeeDetail::where('id', $employee_id)->first();
        if (!$EmpDetails) {
            return $this->sendError([], 'Data not found!', 404);
        }

        $gender = $EmpDetails->emp_gender;
        $employeeImage = $this->imgProfileFunc($EmpDetails->emp_image, $gender);

        // Fetch employee documents
        $emp_documents = EmployeeDocument::where('emp_id', $employee_id)->get();
        $empDocuments = [];
        foreach ($emp_documents as $document) {
            $empDocuments[] = $liveBaseUrl . '/api/storage/' . $document->document_path;
        }

        // Update document paths in the response
        foreach ($emp_documents as $document) {
            $document->document_path = $liveBaseUrl . '/api/storage/images/users/' . $document->document_path;
        }

        // Populate response data
        $data['EmpDetails'] = $EmpDetails;
        $data['employeeImage'] = $employeeImage;
        $data['empDocuments'] = $empDocuments;
        $data['employee_id'] = $employee_id;
        $data['EmpDetails']['company_name'] = Company::where('id', $EmpDetails->company_id)->value('company_name');
        $data['EmpDetails']['branch_name'] = Location::where('id', $EmpDetails->branch_id)->value('branch_name');

        // Return response based on data presence
        if (!empty($data)) {
            return $this->sendResponse($data, 'Employee Data Fetched Successfully!');
        } else {
            return $this->sendError('Data Not found!');
        }
    }

    public function imgProfileFunc($image, $gender)
    {
        $imagePath = storage_path('app/' . $image);

        if (file_exists($imagePath) && !empty($image)) {
            $image = url('/api/storage/app/' . $image);
        } else {
            if ($gender === 'M') {
                $image = url('api/storage/app/default/male.png');
            } else {
                $image = url('api/storage/app/default/female.png');
            }
        }
        return $image;
    }

    public function editAccount(Request $request)
    {
        $employee_id = $request->employee_id;
        $accountDetails = AccountDetail::where('emp_id', $employee_id)->first();
        $data['employee_id'] = $employee_id;
        $data['accountDetails'] = $accountDetails;
        if (!empty($accountDetails)) {
            return $this->sendResponse($data, 'Employee account fetched successfully!');
        } else {
            return $this->sendError([], 'Data not found!', 404);
        }
    }

    public function updateEmployeeDetails(Request $request)
    {
        $id = $request->id;
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|numeric',
            'branch_id' => 'required|numeric',
            'emp_name' => 'required|max:25',
            'emp_id' => 'required|unique:employee_details,emp_id,' . $id,
            'father_name' => 'required|max:25',
            'mother_name' => 'required|max:25',
            'personal_email' => 'required|email|unique:employee_details,personal_email,' . $id,
            'emp_gender' => 'sometimes',
            'emp_address' => 'required|max:255',
            'dob' => 'required|date',
            'emp_phone' => 'required|unique:employee_details,emp_phone,' . $id,
            'nationality' => 'sometimes|string',
            'city_of_birth' => 'sometimes|string',
            'cnic' => 'required|numeric|digits:13|unique:employee_details,cnic,' . $id,
            'religion' => 'max:20',
            'blood_group' => 'max:5',
            'spouse_name' => 'max:25',
            'is_independant' => 'required|boolean',
            'has_home' => 'sometimes|boolean',
            'has_transport' => 'nullable|boolean',
            'transport_type' => 'nullable',
            'registration_no' => 'nullable',
            'driving_license' => 'nullable',
            'license_no' => 'nullable',
            'emp_image' => 'nullable|image|max:2048',
            // 'documents.*' => 'nullable|file|mimes:jpeg,png,gif,pdf,doc,docx,xls,xlsx|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors(),
            ], 400);
        }

        DB::beginTransaction();
        try {
            $updateEmployee = EmployeeDetail::find($id);
            $oldBranch = $updateEmployee->branch_id;
            if (!$updateEmployee) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Employee data not found!',
                ], 404);
            }

            if ($request->hasFile('emp_image')) {
                if ($updateEmployee->emp_image && Storage::exists($updateEmployee->emp_image)) {
                    Storage::delete($updateEmployee->emp_image);
                }
                $file = $request->file('emp_image');
                $imageName = $file->getClientOriginalName();
                $imagePath = $file->storeAs('images/users/', $imageName);
                $imagePublicPath = 'images/users/' . $imageName;
                $updateEmployee->emp_image = $imagePublicPath;
            }

            $updateEmployee->fill([
                'company_id' => $request->input('company_id'),
                'branch_id' => $request->input('branch_id'),
                'emp_id' => $request->input('emp_id'),
                'personal_email' => $request->input('personal_email'),
                'emp_name' => ucwords($request->input('emp_name')),
                'father_name' => ucwords($request->input('father_name')),
                'mother_name' => ucwords($request->input('mother_name')),
                'emp_address' => $request->input('emp_address'),
                'emp_phone' => $request->input('emp_phone'),
                'emp_gender' => $request->input('emp_gender'),
                'cnic' => $request->input('cnic'),
                'dob' => Carbon::parse($request->input('dob'))->format('Y-m-d'),
                'added_by' => Auth::user()->fullname,
                'nationality' => $request->input('nationality'),
                'city_of_birth' => $request->input('city_of_birth'),
                'religion' => ucwords($request->input('religion')),
                'blood_group' => $request->input('blood_group'),
                'marital_status' => $request->input('marital_status'),
                'spouse_name' => ucwords($request->input('spouse_name')),
                'is_independant' => $request->input('is_independant'),
                'has_home' => $request->has('has_home') ? $request->input('has_home') : 0,
                'has_transport' => $request->input('has_transport'),
                'transport_type' => $request->input('transport_type'),
                'registration_no' => $request->input('registration_no'),
                'driving_license' => $request->input('driving_license'),
                'license_no' => $request->input('license_no')
            ]);

            $updateEmployee->save();

            if ($updateEmployee->status == '1') {
                if ($updateEmployee->branch_id != $oldBranch) {
                    $existingRecord = ZKSyncEmp::where('emp_id', $updateEmployee->id)
                        ->whereNotNull('old_branch')
                        ->where('action', 'delete')
                        ->first();

                    if ($existingRecord) {
                        $existingRecord->update([
                            'old_branch' => $oldBranch,
                            'synced' => '0',
                        ]);
                    } else {
                        ZKSyncEmp::create([
                            'emp_id' => $updateEmployee->id,
                            'old_branch' => $oldBranch,
                            'action' => 'delete',
                            'synced' => '0',
                        ]);
                    }
                }
                ZKSyncEmp::updateOrCreate([
                    'emp_id' => $updateEmployee->id,
                    'old_branch' => null,
                ], [
                    'emp_id' => $updateEmployee->id,
                    'synced' => '0',
                    'action' => 'create',
                    'old_branch' => null,
                ]);
            }
            $empRole = ZkRoledEmployee::where('emp_id', $updateEmployee->id)->first();
            if ($empRole) {
                $empRole->update([
                    'synced' => '0',
                    'action' => 'create'
                ]);
            }
            // Handle document upload
            /*
            if ($request->hasFile('documents')) {
                // Delete old documents if exists
                $existingDocuments = EmployeeDocument::where('emp_id', $updateEmployee->id)->get();
                foreach ($existingDocuments as $document) {
                    if (Storage::exists('public/' . str_replace('assets/images/users/documents/', '', $document->document_path))) {
                        Storage::delete('public/' . str_replace('assets/images/users/documents/', '', $document->document_path));
                    }
                    $document->delete();
                }

                $documents = $request->file('documents');
                foreach ($documents as $document) {
                    $documentName = $document->getClientOriginalName();
                    $documentPath = $document->storeAs('public/assets/images/users/documents', $documentName);
                    $documentPublicPath = 'assets/images/users/documents/' . $documentName;
                    $empDocument = new EmployeeDocument;
                    $empDocument->emp_id = $updateEmployee->id;
                    $empDocument->document_path = $documentPublicPath;
                    $empDocument->save();
                }
            }
            */

            DB::commit();

            $employee = EmployeeDetail::with('documents')->find($id);

            return response()->json([
                'status' => 1,
                'message' => 'Employee details updated successfully!',
                'employee' => $employee,
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 0,
                'message' => 'Failed to update employee details.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function profileDetail(Request $request, $emp_id)
    {
        $id = base64_decode($emp_id);
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);

        $searched_date = isset($request->searchDate) ? Carbon::parse($request->searchDate)->format('Y-m') : Carbon::now()->format('Y-m');

        $EmpDetails = EmployeeDetail::findOrFail($id);
        $cities = City::where('city_id', $EmpDetails->city_of_birth)->value('city_name');
        $countries = Country::where('country_id', $EmpDetails->nationality)->value('country_name');
        $EmpAccount = AccountDetail::where('emp_id', $id)->first();
        if ($EmpAccount == "" && $EmpAccount == NUll) {
            Session::flash('success', 'Please update profile first');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back();
        }
        $empEducation = EmployeeEducationDetail::where('emp_id', $EmpDetails->id)->where('is_deleted', '0')->get();
        if ($empEducation == "" && $EmpAccount == NUll) {
            Session::flash('success', 'Please Update profile first');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back();
        }
        $emp_primary = user_family_refrence::where('emp_id', $EmpDetails->id)->where('emergency_contact', '1')->where('is_deleted', '0')->orderBy('id', 'asc')->first();
        $empRelation = user_family_refrence::where('emp_id', $EmpDetails->id)->where('is_deleted', '0')->get();
        $emp_secondry = user_family_refrence::where('emp_id', $EmpDetails->id)->where('emergency_contact', '1')->where('is_deleted', '0')->orderBy('id', 'desc')->first();
        $empHistory = user_experience::where('emp_id', $EmpDetails->id)->where('is_deleted', '0')->get();
        $empAprovel = user_approval::with('designation')->where('emp_id', $EmpDetails->id)->where('is_deleted', '0')->first();
        $documents = EmployeeDocument::where('emp_id', $id)->get();
        if ($user_role == 1) {
            $companies = Company::where('is_deleted', '0')->orderBy('company_name', 'asc')->get();
        } else {
            $companies = Company::whereIn('id', $user_company_id)->orderBy('company_name', 'asc')->get();
        }
        $leaves = Leave::where('is_approved', '1')
            ->where('is_deleted', '0')
            ->where('emp_id', $EmpDetails->id)
            ->get();
        foreach ($leaves as $key => $leave) {
            $leave->leave_types = Leave_Type::where('id', $leave->leave_type)->first();
        }
        foreach ($leaves as $key => $leave) {
            $leave->total_leaves = Leave_setting::where('is_active', '1')->where('is_deleted', '0')
                ->where('company_id', $leave->company_id)->first();
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
            $holidays = Holiday::where('company_id', $getUserDetails->company_id)->where('branch_id', $getUserDetails->branch_id)
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
            $company_details = CompanySetting::where('company_id', $getUserDetails->company_id)->where('branch_id', $getUserDetails->branch_id)->first();
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
            $branch_id = Location::where('id', $EmpDetails->branch_id)->first();
            return view('directory.employee_profile', compact('cities', 'countries', 'leaves', 'totalApprovedDays', 'approvedDaysByType', 'branch_id', 'documents', 'EmpAccount', 'getEmployeeAttendance', 'empHistory', 'empEducation', 'empRelation', 'emp_secondry', 'emp_primary', 'empAprovel', 'companies', 'getTotalOfficeHours', 'userSearchedMonth', 'id', 'getUserDetails', 'getCurrentDate', 'todaysRecord', 'overbreak', 'workingHours', 'workPercantage', 'weeklyData', 'monthlyData', 'EmpDetails', 'holidayArray'));
        } else {
            return abort(404);
        }
    }

    public function searchEmployeeAttendance(Request $request)
    {
        $getEmployeeAttendance = $this->ProfileAttendance($request->employee_id, $request->month);
        if ($getEmployeeAttendance) {
            return $this->sendResponse($getEmployeeAttendance, 'Employee attendance search successfully!', 200);
        } else {
            return $this->sendResponse($getEmployeeAttendance, 'Data not found!', 200);
        }
    }

    public function ProfileAttendance($id, $month)
    {
        $emp = EmployeeDetail::with('resignations', 'leaves', 'holidays')->where('id', $id)->first();
        $company_setting = CompanySetting::where('company_id', $emp->company_id)
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
            if (date('Y-m', strtotime($leaves['from_date'])) <= date('Y-m', strtotime($year - $month)) || date('Y-m', strtotime($leaves['to_date'])) <= date('Y-m', strtotime($year - $month))) {
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
                    } else if ($duration <= ($half_day) * 60) {
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
                if (array_key_exists($date, $holidaysArray)) {
                    $attend = "Holiday";
                } elseif (array_key_exists($date, $leavesArray)) {
                    $attend = "Leave";
                } elseif ($UserAttendancedata1->isWeekDay == true) {
                    $attend = "Weekend";
                } else {
                    $attend = "Absent";
                }
                $newDate = Carbon::parse($date);
                $UserAttendancedata1->newDate = $newDate->format('d F Y');
                $UserAttendancedata1->isWeekDay = $newDate->isWeekend();
                $UserAttendancedata1->check_in = null;
                $UserAttendancedata1->check_out = null;
                $UserAttendancedata1->totalProduction = null;
                $UserAttendancedata1->Present = $attend;

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
        $employee = EmployeeDetail::where('id', $emergency->emp_id)->first();
        $msg = '"' . ucwords($employee->emp_name) . '"Family Updated Successfully';
        createLog('employee_action', $msg);

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
        $account = AccountDetail::where('emp_id', $request->employee_id)->where('is_deleted', '0')->first();
        return response()->json(['success' => true, 'data' => $account]);
    }

    public function editEmployeeEducation(Request $request)
    {
        $emp_id = $request->employee_id;
        $EmpLanguages = user_language::with('language')->where('emp_id', $emp_id)->get();
        $language_name = [];
        foreach ($EmpLanguages as $languages) {
            $language_name[] = $languages->language_id;
        }
        $EmpEducation = EmployeeEducationDetail::where('emp_id', $emp_id)->get();
        if ($EmpEducation->isEmpty()) {
            return $this->sendError([], 'Data not found!', 404);
        }
        $Empotherqalifications = EmployeeEducationDetail::where('emp_id', $emp_id)->value('other_qualifications');
        $data['employee_id'] = $emp_id;
        $data['EmpEducation'] = $EmpEducation;
        $data['emp_language'] = $language_name;
        $data['other_qualifications'] = $Empotherqalifications;

        if (!empty($data)) {
            return $this->sendResponse($data, 'Employee education fetched successfully!');
        } else {
            return $this->sendError('Employee Education Not Found!');
        }
    }

    public function updateEmployeeEducation(Request $request)
    {

        $data = [];
        $emp_id = $request->employee_id;
        $emp_degrees = $request->emp_degree;
        $major_subs = $request->major_sub;
        $grade_divisions = $request->grade_division;
        $degree_froms = $request->degree_from;
        $degree_tos = $request->degree_to;
        $institutes = $request->institute;
        $other_qualification = $request->other_qualifications;
        $emp_language = $request->emp_language;

        DB::beginTransaction();
        try {
            // Delete existing education details for the employee
            EmployeeEducationDetail::where('emp_id', $emp_id)->delete();

            // Insert new education details
            for ($i = 0; $i < count($emp_degrees); $i++) {
                $emp_edu_detail = new EmployeeEducationDetail();
                $emp_edu_detail->emp_id = $emp_id;
                $emp_edu_detail->degree = $emp_degrees[$i];
                $emp_edu_detail->subject = $major_subs[$i];
                $emp_edu_detail->grade = $grade_divisions[$i];
                $emp_edu_detail->division = $grade_divisions[$i];
                $emp_edu_detail->degree_from = $degree_froms[$i];
                $emp_edu_detail->degree_to = $degree_tos[$i];
                $emp_edu_detail->institution = $institutes[$i];
                $emp_edu_detail->other_qualifications = $other_qualification;
                $emp_edu_detail->save();
                $data['employee_education'][] = $emp_edu_detail;
            }

            if (!empty($emp_language)) {
                // Delete existing languages
                user_language::where('emp_id', $request->employee_id)->delete();

                // Insert new languages
                foreach ($emp_language as $language) {
                    user_language::create([
                        'emp_id' => $request->employee_id,
                        'language_id' => $language
                    ]);
                }
            } else {
                user_language::where('emp_id', $request->employee_id)->delete();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError([], $e->getMessage(), 500);
        }

        // Log action if employee is found
        $employee = EmployeeDetail::find($emp_id);
        if ($employee) {
            $msg = '"' . ucwords($employee->emp_name ?? 'N/A') . '" Education Updated Successfully';
            createLog('employee_action', $msg);
        }

        // Return success response with details if updated or created
        if (!empty($data)) {
            return $this->sendResponse($data, 'Employee Education Detail Updated Successfully!');
        } else {
            return $this->sendError([], 'Form Not Submitted!', 400);
        }
    }

    // delete education when user update the employee education
    public function deleteEducationWhileUpdate(Request $request)
    {
        $educationDetails = EmployeeEducationDetail::where('id', $request->education_id)->first();

        if ($educationDetails) {
            $deleteEducation = $educationDetails->delete();
            if ($deleteEducation) {
                return $this->sendResponse([], 'Education delete successfully!', 200);
            } else {
                return $this->sendResponse([], 'Intenal server error!', 500);
            }
        } else {
            return $this->sendError([], 'Data not found!', 404);
        }
    }

    public function destroyEducation(Request $request)
    {
        if (!empty($request->document_id)) {
            $deletedDocs = EmployeeEducationDetail::where('id', $request->document_id)->delete();
            $msg = 'Education Deleted Successfully';
            createLog('employee_action', $msg);
            if ($deletedDocs) {
                return $this->sendResponse($deletedDocs, 'Emplopyee education deleted successfully!', 200);
            } else {
                return $this->sendResponse($deletedDocs, 'Data Not Found', 200);
            }
        }
    }

    public function editEmployeeExperiences(Request $request)
    {
        $employee_id = $request->employee_id;

        $employeeExperince = user_experience::select('id', 'emp_id', 'organization', 'prev_position', 'prev_salary', 'exp_from', 'exp_to', 'reason_for_leaving', 'court_conviction')->where('emp_id', $employee_id)->get();
        $Empotherqalifications = user_experience::where('emp_id', $employee_id)->value('court_conviction');
        if ($employeeExperince->isEmpty()) {
            return $this->sendError([], 'Data not found!', 404);
        } else {
            $previousEmployeement = EmployeeHistory::select('id', 'emp_id', 'emp_position', 'prev_emp_no', 'emp_location', 'date_from', 'date_to')->where('emp_id', $employee_id)->get();
        }
        $data['employee_experince'] = $employeeExperince;
        $data['previous_employeement'] = $previousEmployeement;
        $data['court_conviction'] = $Empotherqalifications;

        if (!empty($data['employee_experince'])) {
            return $this->sendResponse($data, 'Employee detail fetched successfully!', 200);
        }
    }

    public function updateEmployeeExperience(Request $request)
    {
        $data = [];
        $emp_id = $request->employee_id;
        $Exp_id = $request->experience_id;
        $prev_employed = $request->prev_employed;
        $organization = $request->organization;
        $prev_position = $request->prev_position;
        $prev_salary = $request->prev_salary;
        $exp_from = $request->exp_from;
        $exp_to = $request->exp_to;
        $reason_for_leaving = $request->reason_for_leaving;
        $court_convic = $request->any_conviction;

        $existingRecords = user_experience::where('emp_id', $emp_id)->get();
        if (!$existingRecords->isEmpty()) {
            user_experience::where('emp_id', $emp_id)->delete();
        }

        if (!empty($request->organization)) {
            foreach ($request->organization as $index => $organization) {
                if (!empty($organization)) {
                    $createUserExperience = user_experience::create([
                        'emp_id' => $emp_id,
                        'organization' => $organization,
                        'prev_position' => $request->prev_position[$index] ?? null,
                        'prev_salary' => $request->prev_salary[$index] ?? null,
                        'exp_from' => $request->exp_from[$index] ?? null,
                        'exp_to' => $request->exp_to[$index] ?? null,
                        'reason_for_leaving' => $request->reason_for_leaving[$index] ?? null,
                        'court_conviction' => $request->any_conviction,
                    ]);

                    $database = user_experience::where('emp_id', $emp_id)->get();
                    $data['user_experience'][] = $database;
                }
            }
        } else {
            return response()->json([
                'message' => "Record not Found, 404",
                'status' => false,
            ]);
        }

        $employeeHistory = EmployeeHistory::where('emp_id', $emp_id)->first();
        if ($employeeHistory) {
            $employeeHistory->user_id = Auth::user()->id;
            $employeeHistory->emp_position = $request->emp_position;
            $employeeHistory->prev_emp_no = $request->prev_emp_no;
            $employeeHistory->emp_location = $request->emp_location;
            $employeeHistory->date_from = $request->date_from;
            $employeeHistory->date_to = $request->date_to;
            $employeeHistory->save();
        } else {
            if ($prev_employed == '1') {
                $employeeHistory = new EmployeeHistory();
                $employeeHistory->user_id = Auth::user()->id;
                $employeeHistory->emp_position = $request->emp_position;
                $employeeHistory->prev_emp_no = $request->prev_emp_no;
                $employeeHistory->emp_location = $request->emp_location;
                $employeeHistory->date_from = $request->date_from;
                $employeeHistory->date_to = $request->date_to;
                $employeeHistory->save();
            }
        }

        $employee = EmployeeDetail::where('id', $emp_id)->first();
        $Empotherqalifications = user_experience::where('emp_id', $emp_id)->value('court_conviction');

        if ($employee) {
            $msg = '"' . ucwords($employee->emp_name ?? "N/A") . '" Employment Updated Successfully';
            createLog('employee_action', $msg);
        }

        $data['employee_history'] = $employeeHistory;
        $data['court_conviction'] = $Empotherqalifications;
        $data['prev_employed'] = $prev_employed;

        if (!empty($data)) {
            return $this->sendResponse($data, 'Employee experience updated successfully!', 200);
        } else {
            return $this->sendResponse($data, 'Data not found', 200);
        }
    }

    // delete employement when user update the employee education
    public function deleteEmploymentWhileUpdate(Request $request)
    {
        $employement = user_experience::where('id', $request->emloyement_id)->first();
        if ($employement) {
            $deleteEmployment = $employement->delete();
            if ($deleteEmployment) {
                return $this->sendResponse([], 'Employement delete successfully!', 200);
            } else {
                return $this->sendError([], 'Internal server error', 500);
            }
        } else {
            return $this->sendError([], 'Data not found!', 404);
        }
    }

    // delete references when user update the employee education
    public function deleteReferencesWhileUpdate(Request $request)
    {
        $employement = user_family_refrence::where('id', $request->reference_id)->first();
        if ($employement) {
            $deleteEmployment = $employement->delete();
            if ($deleteEmployment) {
                return $this->sendResponse([], 'Reference delete successfully!', 200);
            } else {
                return $this->sendError([], 'Internal server error', 500);
            }
        } else {
            return $this->sendError([], 'Data not found!', 404);
        }
    }


    public function updateAccountDetail(Request $request)
    {
        $data = [];
        $emp_id = $request->employee_id;
        $validator = Validator::make($request->all(), [
            'bank_name' => 'required',
            'account_number' => 'required',
            'ifsc_code' => 'required',
            'pan' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', "Some of the fields are missing");
        }
        $updateAccount = AccountDetail::where('emp_id', $emp_id)->first();
        if ($updateAccount) {
            $updateAccount->bank_name = $request->bank_name;
            $updateAccount->account_no = $request->account_number;
            $updateAccount->update();
            $data['account_detail'] = $updateAccount;
        } else {
            if ($request->bank_name != null) {
                $addUser = AccountDetail::create([
                    'emp_id' => $request->employee_id,
                    'bank_name' => $request->bank_name,
                    'account_no' => $request->account_number,
                    'branch_name' => $request->branch_name,
                    'swift_code' => $request->swift_code,
                    'iban_code' => $request->iban_code,
                    'acc_holder_name' => $request->account_holder_name,
                    'iban_code' => $request->iban
                ]);
                $data['created_account'] = $addUser;
            }
        }

        $employee = EmployeeDetail::where('id', $emp_id)->first();
        if ($employee) {
            $msg = '"' . ucwords(isset($employee->emp_name) ? $employee->emp_name : "N/A") . '" Account Updated Successfully';
            createLog('employee_action', $msg);
        }
        if (!empty($data)) {
            return $this->sendResponse($data, 'Employee account update successfully!', 200);
        } else {
            return $this->sendResponse($data, 'Data not found!', 200);
        }
    }

    public function editEmployeeRefrences(Request $request)
    {
        $data = [];
        $employee_id = $request->employee_id;
        $EmpRefrences = user_family_refrence::where('emp_id', $employee_id)->get();
        $EmpRelative = EmployeeRelative::where('emp_id', $employee_id)->get();
        $EmpRelatedRef = Related_refrence::where('emp_id', $employee_id)->get();
        if ($EmpRefrences->isEmpty() && $EmpRelative && $EmpRelatedRef) {
            return $this->sendError([], 'Data not found!', 404);
        }
        $data['employee_id'] = $employee_id;
        $data['empRefrences'] = $EmpRefrences;
        $data['empRelative'] = $EmpRelative;
        $data['empRelatedRef'] = $EmpRelatedRef;
        if (!empty($data)) {
            return $this->sendResponse($data, 'Employee reference fetched successfully!');
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
                if ($memeber_name[$i] != null) {
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

        $find = Related_refrence::where('emp_id', $emp_id)->first();
        if (!empty($find)) {
            if (($request->input('hasrefrence') == '1')) {
                $ref_id = $request->refrence_id;
                $ref_name = $request->refrence_name;
                $ref_position = $request->ref_position;
                $ref_address = $request->ref_address;
                $ref_phone = $request->ref_phone;

                for ($i = 0; $i < count($ref_name); ++$i) {
                    Related_refrence::where('emp_id', $emp_id)->where('id', $ref_id[$i])->update([
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
                    Related_refrence::create([
                        'emp_id' => $emp_id,
                        'refrence_name' => $ref_name[$i],
                        'ref_position' => $ref_position[$i],
                        'ref_address' => $ref_address[$i],
                        'ref_phone' => $ref_phone[$i],
                    ]);
                }
            }
        }

        $employee = EmployeeDetail::where('id', $emp_id)->first();
        $msg = '"' . ucwords($employee->emp_name) . '" References Updated Successfully';
        createLog('employee_action', $msg);

        if (isset($request->edit) == 'preview') {
            return redirect()->route('add.account.detail')->with('success', "Record Saved Successfully");
        } else {
            return redirect('/employee/directory/edit-account-detail/' . $id)->with('success', "Record Updated Successfully");
        }
    }

    public function editEmployeeApproval(Request $request)
    {
        $data = [];
        $employee_id = $request->employee_id;
        $EmpApproval = user_approval::where('emp_id', $employee_id)->first();
        $empDetail = EmployeeDetail::select('id', 'emp_email')->where('id', $employee_id)->first();
        $designations = Designation::orderBy('name', 'asc')->get();
        $job_status = Job_type::orderBy('job_status', 'asc')->get();
        if (empty($EmpApproval)) {
            return $this->sendError([], 'Data not found!', 404);
        }
        $data['job_status'] = $job_status;
        $data['employee_id'] = $employee_id;
        $data['EmpApproval'] = $EmpApproval;
        $data['empDetail'] = $empDetail;
        $data['designations'] = $designations;
        if (!empty($data)) {
            return $this->sendResponse($data, 'Employee Employement Fetched Successfully!');
        } else {
            return $this->sendError('Employee Employement Not Found!');
        }
    }

    public function updateEmployeeApproval(Request $request)
    {
        $data = [];
        $role_id = '3';
        $emp_id = $request->employee_id;
        DB::beginTransaction();
        try {
            $aproval = user_approval::where('emp_id', $emp_id)->first();

            if ($aproval) {
                $aproval->designation_id = $request->designation_id;
                $aproval->joining_date = date('Y-m-d', strtotime($request->joining_date));
                $aproval->phone_issued = $request->phone_issued;
                $aproval->starting_sal = $request->starting_sal;
                $aproval->job_status_id = $request->job_status_id;
                $aproval->department_id = $request->department_id;
                $aproval->update();
                $data['update_approval'] = $aproval;
            } else {
                if ($request->designation_id != null) {
                    $createApproval = user_approval::create([
                        'user_id' => Auth::user()->id,
                        'emp_id' => $emp_id,
                        'designation_id' => $request->designation_id,
                        'joining_date' => date('Y-m-d', strtotime($request->joining_date)),
                        'phone_issued' => $request->phone_issued,
                        'starting_sal' => $request->starting_sal,
                        'job_status_id' => $request->job_status_id,
                    ]);
                    $data['created_approval'] = $createApproval;
                }
            }

            $employee = EmployeeDetail::where('id', $emp_id)->first();
            $User = User::where('emp_id', $emp_id)->first();
            if (isset($request->emp_email)) {
                //if employee exists and emp_email is changed then it will update to the email
                if ($request->emp_email != $employee->emp_email) {
                    $employee->update(['emp_email' => $request->emp_email]);
                }
                //if user exists and email is changed then it will update to the email
                if ($User) {
                    if ($request->emp_email != $User->email) {
                        $User->update(['email' => $request->emp_email]);
                    }
                } else {
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
                    $data['user'] = $user;
                    // Send the email with the password
                    Mail::send('email.user_password_email', ['password' => $password, 'user' => $employee->emp_name, 'email' => $request->emp_email, 'expiry_date' => $expiryDate], function ($message) use ($request) {
                        $emailServicesFromName = Setting::where('perimeter', 'smtp_from_name')->first();
                        $emailServicesFromEmail = Setting::where('perimeter', 'smtp_from_email')->first();
                        $message->from($emailServicesFromEmail->value, $emailServicesFromName->value);
                        $message->to($request->emp_email);
                        $message->subject('Welcome to ');
                    });
                }
            } else {
                $employee->update(['emp_email' => $request->emp_email]);
                if ($User) {
                    $User->update(['email' => '']);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            return $this->sendError([], $e->getMessage(), 500);
        }
        $msg = '"' . ucwords($employee->emp_name) . '" Approval Updated Successfully';
        createLog('employee_action', $msg);

        if (!empty($data)) {
            return $this->sendResponse($data, 'Employee approval update successfully!', 200);
        } else {
            return $this->sendResponse($data, 'Data not found!', 200);
        }
    }

    public function updateEmployeeReference(Request $request)
    {

        $employeeId = $request->employee_id;
        $userFaimlyReference = user_family_refrence::where('emp_id', $employeeId)->exists();
        $employeeRelative = EmployeeRelative::where('emp_id', $employeeId)->exists();
        $relatedReference = Related_refrence::where('emp_id', $employeeId)->exists();

        if ($userFaimlyReference) {
            user_family_refrence::where('emp_id', $employeeId)->delete();
        }
        if ($employeeRelative) {
            EmployeeRelative::where('emp_id', $employeeId)->delete();
        }
        if ($relatedReference) {
            Related_refrence::where('emp_id', $employeeId)->delete();
        }

        $data = [];
        $validator = Validator::make($request->all(), [
            'memeber_name' => 'required',
            'memeber_relation' => 'required',
            'memeber_occupation' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError([], $validator->errors());
        }
        $memeber_name = $request->memeber_name;
        $phone_number = $request->phone_number;
        $memeber_relation = $request->memeber_relation;
        $memeber_age = $request->memeber_age;
        $memeber_occupation = $request->memeber_occupation;
        $place_of_work = $request->place_of_work;
        $emergency_contact = $request->emergency_contact;
        DB::beginTransaction();
        try {
            for ($i = 0; $i < count($memeber_name); ++$i) {
                if ($memeber_name[$i] != null) {
                    $data['faimly_reference'][] = user_family_refrence::create([
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

            if (($request->input('has_relative') == '1') && $request->relative_name != null) {
                $data['employee_relative'] = EmployeeRelative::create([
                    'emp_id' => $request->employee_id,
                    'relative_name' => $request->relative_name,
                    'relative_position' => $request->relative_position,
                    'relative_dept' => $request->relative_dept,
                    'relative_location' => $request->relative_location,
                    'relative_relation' => $request->relative_relation,
                ]);
            }

            if (($request->input('has_reference') == '1')) {
                $ref_name = $request->refrence_name;
                $ref_position = $request->ref_position;
                $ref_address = $request->ref_address;
                $ref_phone = $request->ref_phone;

                for ($i = 0; $i < count($ref_name); ++$i) {
                    if ($ref_name[$i] != null) {
                        $data['employee_reference'][] = Related_refrence::create([
                            'emp_id' => $request->employee_id,
                            'refrence_name' => $ref_name[$i],
                            'ref_position' => $ref_position[$i],
                            'ref_address' => $ref_address[$i],
                            'ref_phone' => $ref_phone[$i],
                        ]);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError([], $e->getMessage());
        }

        if (!empty($data)) {
            return $this->sendResponse($data, 'Employee faimly update successfully!', 200);
        } else {
            return $this->sendError([], 'Data not found!', 404);
        }
    }

    public function changeEmployeeApprovalStatus(Request $request)
    {
        $emp_id = $request->emp_id;
        $status = '';
        $role_id = auth()->user()->role_id;

        switch ($request->status) {
            case '0':
                $status = 'pending';
                break;
            case '1':
                $status = 'approved';
                break;
            case '2':
                $status = 'declined';
                break;
            case '3':
                $status = 'resigned';
                break;
            case '4':
                $status = 'terminated';
                break;
            case '5':
                $status = 'delete';
                break;
            default:
                return $this->sendResponse(null, 'Invalid status provided', 400);
        }

        $approval = user_approval::where('emp_id', $emp_id)->first();
        $employee_detail = EmployeeDetail::where('id', $emp_id)->first();

        if (!$approval) {
            return response()->json([
                'details' => null,
                'status' => 0,
                'message' => 'Approval data not found. Please add approval details.',
                'success' => false,
            ], 404);
        }


        if ($approval && $employee_detail) {
            if ($status == 'approved') {
                if ($role_id == '1') {
                    $approval->approved_by_CEO = '1';

                } elseif ($role_id == '2') {
                    $approval->approved_by_HR = '1';
                }
                $approval->is_active = '1';
                $employee_detail->status = '1';
                $employee_detail->is_active = '1';
            } elseif ($status == 'declined') {
                if ($role_id == '1') {
                    $approval->approved_by_CEO = '0';

                } elseif ($role_id == '2') {
                    $approval->approved_by_HR = '0';
                }
                $approval->is_active = '0';
                $employee_detail->status = '2';
                $employee_detail->is_active = '0';
            } elseif ($status == 'resigned') {
                if ($role_id == '1') {
                    $employee_detail->status = '3';
                }
            } elseif ($status == 'terminated') {
                if ($role_id == '1') {
                    $employee_detail->status = '4';
                }
            } elseif ($status == 'pending') {
                if ($role_id == '1') {
                    $employee_detail->status = '0';
                }
            }
            $approval->update();
            $employee_detail->update();
            if ($status != 'pending' || $status != 'declined') {
                ZKSyncEmp::updateOrCreate([
                    'emp_id' => $employee_detail->id,
                    'old_branch' => null
                ], [
                    'emp_id' => $employee_detail->id,
                    'synced' => '0',
                    'action' => 'create',
                    'old_branch' => null
                ]);
                $empRole = ZkRoledEmployee::where('emp_id', $employee_detail->id)->first();
                if ($empRole) {
                    $empRole->update([
                        'synced' => '0',
                        'action' => 'create'
                    ]);
                }
            } else {
                $empRole = ZkRoledEmployee::where('emp_id', $employee_detail->id)->first();
                if ($empRole) {
                    $empRole->update([
                        'synced' => '0',
                        'action' => 'delete'
                    ]);
                }
            }

            $msg = '"' . ucwords($employee_detail->emp_name) . '" Status "' . $status . '" Updated';
            createLog('employee_action', $msg);

            return $this->sendResponse($approval, 'Approval updated successfully!', 200);
        }

        return $this->sendResponse(null, 'Employee detail not found', 400);
    }


    public function destroy(Request $request)
    {
        $emp_id = $request->employee_id;
        $delEmp = EmployeeDetail::where('id', $emp_id)->first();
        if (empty($delEmp)) {
            return $this->sendError([], 'Data not found!', 404);
        }
        $delEmp->is_deleted = "1";
        $delEmp->update();
        ZKSyncEmp::updateOrCreate([
            'emp_id' => $delEmp->id,
            'old_branch' => null
        ], [
            'emp_id' => $delEmp->id,
            'synced' => '0',
            'action' => 'delete',
            'old_branch' => null
        ]);
        $empRole = ZkRoledEmployee::where('emp_id', $delEmp->id)->first();
        if ($empRole) {
            $empRole->update([
                'synced' => '0',
                'action' => 'delete'
            ]);
        }
        $empEdu = EmployeeEducationDetail::where('emp_id', $emp_id)->get();
        if ($empEdu) {
            foreach ($empEdu as $key => $empup) {
                $empup->update(['is_deleted' => '1']);
            }
        }
        $empPro = EmployeePromotion::where('emp_id', $emp_id)->get();
        if ($empPro) {
            foreach ($empPro as $key => $empup) {
                $empup->update(['is_approved' => '1']);
            }
        }
        $empAccount = AccountDetail::where('emp_id', $emp_id)->get();
        if ($empAccount) {
            foreach ($empAccount as $key => $empup) {
                $empup->update(['is_deleted' => '1']);
            }
        }
        $empTem = Emp_termination::where('emp_id', $emp_id)->get();
        if ($empTem) {
            foreach ($empTem as $key => $empup) {
                $empup->update(['is_deleted' => '1']);
            }
        }
        $empRes = EmployeeResignation::where('emp_id', $emp_id)->get();
        if ($empRes) {
            foreach ($empRes as $key => $empup) {
                $empup->update(['is_deleted' => '1']);
            }
        }
        $empLeave = Leave::where('emp_id', $emp_id)->get();
        if ($empLeave) {
            foreach ($empLeave as $key => $empup) {
                $empup->update(['is_deleted' => '1']);
            }
        }
        $empLangh = user_language::where('emp_id', $emp_id)->get();
        if ($empLangh) {
            foreach ($empLangh as $key => $empup) {
                $empup->update(['is_deleted' => '1']);
            }
        }
        $empFamily = user_family_refrence::where('emp_id', $emp_id)->get();
        if ($empFamily) {
            foreach ($empFamily as $key => $empup) {
                $empup->update(['is_deleted' => '1']);
            }
        }
        $empExp = user_experience::where('emp_id', $emp_id)->get();
        if ($empExp) {
            foreach ($empExp as $key => $empup) {
                $empup->update(['is_deleted' => '1']);
            }
        }
        user_approval::where('emp_id', $emp_id)->update(['is_deleted' => '1']);
        user_allowance::where('emp_id', $emp_id)->update(['is_deleted' => '1']);
        $msg = '"' . ucwords($delEmp->emp_name) . '" deleted';
        createLog('employee_action', $msg);
        if ($delEmp) {
            return $this->sendResponse($delEmp, 'Employee delete successfully!', 200);
        } else {
            return $this->sendResponse($delEmp, 'Data not found!', 200);
        }
    }
    public function restore(Request $request)
    {
        $emp_id = $request->employee_id;
        $delEmp = EmployeeDetail::findOrFail($emp_id);
        $delEmp->is_deleted = "0";
        $delEmp->update();
        ZKSyncEmp::updateOrCreate([
            'emp_id' => $delEmp->id,
            'old_branch' => null
        ], [
            'emp_id' => $delEmp->id,
            'synced' => '0',
            'action' => 'create',
            'old_branch' => null
        ]);
        $empRole = ZkRoledEmployee::where('emp_id', $delEmp->id)->first();
        if ($empRole) {
            $empRole->update([
                'synced' => '0',
                'action' => 'create'
            ]);
        }
        $empEdu = EmployeeEducationDetail::where('emp_id', $emp_id)->get();
        if ($empEdu) {
            foreach ($empEdu as $key => $empup) {
                $empup->update(['is_deleted' => '0']);
            }
        }
        $empPro = EmployeePromotion::where('emp_id', $emp_id)->get();
        if ($empPro) {
            foreach ($empPro as $key => $empup) {
                $empup->update(['is_approved' => '0']);
            }
        }
        $empAccount = AccountDetail::where('emp_id', $emp_id)->get();
        if ($empAccount) {
            foreach ($empAccount as $key => $empup) {
                $empup->update(['is_deleted' => '0']);
            }
        }
        $empTem = Emp_termination::where('emp_id', $emp_id)->get();
        if ($empTem) {
            foreach ($empTem as $key => $empup) {
                $empup->update(['is_deleted' => '0']);
            }
        }
        $empRes = EmployeeResignation::where('emp_id', $emp_id)->get();
        if ($empRes) {
            foreach ($empRes as $key => $empup) {
                $empup->update(['is_deleted' => '0']);
            }
        }
        $empLeave = Leave::where('emp_id', $emp_id)->get();
        if ($empLeave) {
            foreach ($empLeave as $key => $empup) {
                $empup->update(['is_deleted' => '0']);
            }
        }
        $empLangh = user_language::where('emp_id', $emp_id)->get();
        if ($empLangh) {
            foreach ($empLangh as $key => $empup) {
                $empup->update(['is_deleted' => '0']);
            }
        }
        $empFamily = user_family_refrence::where('emp_id', $emp_id)->get();
        if ($empFamily) {
            foreach ($empFamily as $key => $empup) {
                $empup->update(['is_deleted' => '0']);
            }
        }
        $empExp = user_experience::where('emp_id', $emp_id)->get();
        if ($empExp) {
            foreach ($empExp as $key => $empup) {
                $empup->update(['is_deleted' => '0']);
            }
        }
        user_approval::where('emp_id', $emp_id)->update(['is_deleted' => '0']);
        user_allowance::where('emp_id', $emp_id)->update(['is_deleted' => '0']);
        $msg = '"' . ucwords($delEmp->emp_name) . '" Restore';
        createLog('employee_action', $msg);

        if ($delEmp) {
            return $this->sendResponse($delEmp, 'Employee restore successfully!');
        } else {
            return $this->sendResponse($delEmp, 'Data not found!');
        }
    }

    public function employeeHardDelete(Request $request)
    {
        $user = auth()->user();
        if (Hash::check($request->password, $user->password)) {
            $emp_id = $request->employee_id;
            $delEmp = EmployeeDetail::findOrFail($emp_id);
            ZKSyncEmp::updateOrCreate([
                'old_branch' => null,
                'emp_id' => $delEmp->id,
            ], [
                'emp_id' => $delEmp->id,
                'synced' => '0',
                'action' => 'delete',
                'old_branch' => null
            ]);
            $empRole = ZkRoledEmployee::where('emp_id', $delEmp->id)->first();
            if ($empRole) {
                $empRole->update([
                    'synced' => '0',
                    'action' => 'delete'
                ]);
            }
            $delEmp->delete();
            $delEmpDoc = EmployeeDocument::where('emp_id', $emp_id)->get();
            if ($delEmpDoc) {
                foreach ($delEmpDoc as $key => $empDoc) {
                    $empDoc->delete();
                }
            }
            $delEmpEdu = EmployeeEducationDetail::where('emp_id', $emp_id)->get();
            if ($delEmpEdu) {
                foreach ($delEmpEdu as $key => $empEdu) {
                    $empEdu->delete();
                }
            }
            $empAccount = AccountDetail::where('emp_id', $emp_id)->get();
            if ($empAccount) {
                foreach ($empAccount as $key => $empup) {
                    $empup->delete();
                }
            }
            $delEmpProm = EmployeePromotion::where('emp_id', $emp_id)->get();
            if ($delEmpProm) {
                foreach ($delEmpProm as $key => $empPro) {
                    $empPro->delete();
                }
            }
            $delEmpTerm = Emp_termination::where('emp_id', $emp_id)->get();
            if ($delEmpTerm) {
                foreach ($delEmpTerm as $key => $empTerm) {
                    $empTerm->delete();
                }
            }
            $delEmpResi = EmployeeResignation::where('emp_id', $emp_id)->get();
            if ($delEmpResi) {
                foreach ($delEmpResi as $key => $empResi) {
                    $empResi->delete();
                }
            }
            $delEmpLeave = Leave::where('emp_id', $emp_id)->get();
            if ($delEmpLeave) {
                foreach ($delEmpLeave as $key => $empLeave) {
                    $empLeave->delete();
                }
            }
            $delEmpLang = user_language::where('emp_id', $emp_id)->get();
            if ($delEmpLang) {
                foreach ($delEmpLang as $key => $empLang) {
                    $empLang->delete();
                }
            }
            $delEmpRef = user_family_refrence::where('emp_id', $emp_id)->get();
            if ($delEmpRef) {
                foreach ($delEmpRef as $key => $empRef) {
                    $empRef->delete();
                }
            }
            $delEmpExp = user_experience::where('emp_id', $emp_id)->get();
            if ($delEmpExp) {
                foreach ($delEmpExp as $key => $empExp) {
                    $empExp->delete();
                }
            }
            $delEmpAppro = user_approval::where('emp_id', $emp_id)->get();
            if ($delEmpAppro) {
                foreach ($delEmpAppro as $key => $empAppro) {
                    $empAppro->delete();
                }
            }
            $delEmpAllow = user_allowance::where('emp_id', $emp_id)->get();
            if ($delEmpAllow) {
                foreach ($delEmpAllow as $key => $empAllow) {
                    $empAllow->delete();
                }
            }
            $msg = '"' . ucwords($delEmp->emp_name) . '" Permanent Delete';
            createLog('employee_action', $msg);

            if ($delEmp) {
                return $this->sendResponse($delEmp, 'Employee parmanent delete successfully!', 200);
            } else {
                return $this->sendResponse($delEmp, 'Data not found!', 200);
            }
        } else {
            return redirect()->back()->with(['error' => 'Your Password Incorrect.']);
        }
    }

    public function designationList(Request $request)
    {
        $searchBy = $request->search_by;
        $query = Designation::withCount('approvals');

        if ($searchBy && $searchBy != null) {
            $query->orWhere(function ($query) use ($searchBy) {
                $query->orWhere('name', 'LIKE', '%' . $searchBy . '%')
                    ->orWhere('id', 'LIKE', '%' . $searchBy . '%');
            });
        }
        if ($request->pagination == 1) {
            $designations = $query->orderBy('id', 'desc')->paginate(20);
            return $this->sendResponse($designations, 'Designations fetched successfully!');
        } else {
            $data = [];
            $designations = $query->orderBy('id', 'desc')->get();
            $data['data'] = $designations;
            return response()->json([
                'status' => 1,
                'success' => true,
                'details' => $data
            ]);
        }
    }

    public function deleteDesignation(Request $request)
    {
        $designation = Designation::where('id', $request->designation_id)->first();
        if ($designation) {
            $deletedDesignation = $designation->delete();
            if ($deletedDesignation) {
                return $this->sendResponse([], 'Designation delete successfully!', 200);
            } else {
                return $this->sendError([], 'Internal server error!', 200);
            }
        } else {
            return $this->sendResponse([], 'Data not found!', 404);
        }
    }

    public function saveDesignation(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|unique:designations,name',
        ], [
            'name.required' => 'Name is required. ',
            'name.unique' => 'Name is already taken.'
        ]);

        if ($validate->fails()) {
            return $this->sendError([], $validate->errors(), 400);
        }

        $data = new Designation;
        $data->name = $request->name;
        $data->save();

        $msg = 'Designation "' . ucwords($request->name) . '" added successfully';
        createLog('employee_action', $msg);

        if ($data) {
            return $this->sendResponse($data, 'Designation added successfully!');
        } else {
            return $this->sendError([], 'Something went wrong!');
        }
    }

    public function editDesignation(Request $request)
    {
        $designation = Designation::where('id', $request->designation_id)->first();
        if (!empty($designation)) {
            return $this->sendResponse($designation, 'Designation fetched successfully!', 200);
        } else {
            return $this->sendResponse($designation, 'Data not found!', 404);
        }
    }

    public function updateDesignation(Request $request)
    {
        $designation = Designation::find($request->id);
        $designationNameChanged = ($request->name != $designation->name);

        $rules = [
            'name' => 'required' . ($designationNameChanged ? '|unique:designations,name,' . $request->id : '')
        ];

        $messages = [
            'name.required' => 'Name is required.',
            'name.unique' => 'Name is already taken.'
        ];

        $validate = Validator::make($request->all(), $rules, $messages);

        if ($validate->fails()) {
            return $this->sendError([], $validate->errors(), 400);
        }

        // Rest of your update logic
        $data = Designation::where('id', $request->id)->first();
        $data->name = $request->name;
        $data->department_id = $request->department_id;
        $data->update();

        $msg = 'Designation "' . ucwords($data->name) . '" updated as "' . ucwords($request->desg_name) . '"';
        createLog('employee_action', $msg);

        if ($data) {
            return $this->sendResponse($data, 'Designation updated successfully!');
        } else {
            return $this->sendError([], 'Something went wrong!');
        }
    }

    public function searchDesignation(Request $request)
    {
        $searchValue = strtolower($request->input('searchValue'));

        $fetchData = Designation::where(function ($query) use ($searchValue) {
            $query->whereRaw('LOWER(designations.name) LIKE ?', ['%' . $searchValue . '%'])
                ->orWhereRaw('LOWER(departments.name) LIKE ?', ['%' . $searchValue . '%']);
        })
            ->orderBy('designations.id', 'desc')
            ->leftJoin('departments', 'designations.department_id', '=', 'departments.id')
            ->select('designations.name', 'departments.name as department_name', 'designations.updated_at', 'designations.id', 'designations.department_id')
            ->get();

        if ($fetchData->count() > 0) {
            return $this->sendResponse($fetchData, 'Data fetched successfully!');
        } else {
            return $this->sendError([], 'Data not found!');
        }
    }

    public function departmentList(Request $request)
    {
        $searchBy = $request->search_by;
        $query = Department::query();
        $query->where('is_deleted', '0');
        if (!empty($searchBy)) {
            $query->where('name', 'LIKE', '%' . $searchBy . '%');
        }

        $departments = $query->orderBy('id', 'desc')->paginate(20);
        $departments->map(function ($department) {
            $department->head_emp_name = $department->departToEmp->emp_name ?? null;
            unset($department->departToEmp);
            return $department;
        });

        return response()->json([
            'status' => true,
            'success' => 1,
            'response' => $departments,
        ]);
    }


    public function saveDepartment(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|unique:departments,name',
            'emp_id' => 'required|exists:employee_details,id',
        ], [
            'name.required' => 'The department name is required.',
            'name.unique' => 'The department name is already taken.'
        ]);

        if ($validate->fails()) {
            return $this->sendError([], $validate->errors(), 400);
        }
        DB::beginTransaction();
        try {
            $data = new Department;
            $data->name = $request->name;
            $data->head_emp_id = $request->emp_id;
            $data->save();
            $msg = 'Department "' . ucwords($request->name) . '" added successfully';
            createLog('department_action', $msg);
            DB::commit();
            return response()->json([
                'status' => true,
                'success' => 1,
                'message' => 'Department added Successfully...'
            ]);
        } catch (\Exception $e) {
            return $this->sendError([], $e->getMessage(), 500);
        }
    }

    public function deleteDepartment(Request $request)
    {
        $department = Department::where('id', $request->department_id)->first();
        if ($department) {
            $department->update([
                'is_deleted' => '1'
            ]);
            $msg = 'Department "' . ucwords($department->name) . '" deleted successfully';
            createLog('department_action', $msg);
            return response()->json([
                'status' => true,
                'success' => 1,
                'message' => 'Department deleted Successfully...'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'success' => 0,
                'message' => 'Department not found'
            ]);
        }
    }

    public function editDepartment(Request $request)
    {
        $department = Department::find($request->department_id);
        if (!$department) {
            return response()->json([
                'status' => true,
                'success' => 1,
                'message' => 'Department not found',
            ]);
        }
        $department->head_name = $department->departToEmp->emp_name;
        unset($department->departToEmp);
        return response()->json([
            'status' => true,
            'success' => 1,
            'response' => $department,
        ]);

    }

    public function updateDepartment(Request $request)
    {
        // dd($request->all());
        $department = Department::find($request->department_id);
        $departmentNameChanged = ($department && $request->name != $department->name) ? $department->name : "";

        $rules = [
            'name' => 'required' . ($departmentNameChanged ? '|unique:departments,name,' . $request->id : ''),
        ];

        $messages = [
            'name.required' => 'The department name is required.',
            'name.unique' => 'The department name is already taken.'
        ];

        $validate = Validator::make($request->all(), $rules, $messages);
        if ($validate->fails()) {
            return $this->sendError([], $validate->errors(), 400);
        }
        DB::beginTransaction();
        try {
            $data = Department::where('id', $request->department_id)->first();
            if ($data) {
                $data->name = $request->name;
                $data->head_emp_id = $request->emp_id;
                $data->update();

                $msg = 'Department ' . $request->name . ' updated successfully...';
                createLog('department_action', $msg);
                DB::commit();
                return response()->json([
                    'status' => true,
                    'success' => 1,
                    'message' => 'Department updated successfully...',
                ]);
            } else {
                return $this->sendResponse([], 'Department not found!', 404);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError([], $e->getMessage(), 500);
        }
    }

    public function searchDepartment(Request $request)
    {
        $searchValue = strtolower($request->input('searchValue'));

        $fetchData = Department::where(function ($query) use ($searchValue) {
            $query->whereRaw('LOWER(departments.name) LIKE ?', ['%' . $searchValue . '%']);
        })
            ->orderBy('departments.id', 'asc')
            ->select('departments.name', 'departments.updated_at', 'departments.id')
            ->get();
        if ($fetchData->count() > 0) {
            return response()->json(["success" => true, "data" => $fetchData]);
        } else {
            return response()->json(["success" => false, "data" => 'No Record found']);
        }
    }

    public function resignationList(Request $request)
    {
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);
        $searchBy = isset($request->search_by) ? $request->search_by : '';

        $selectBranch = isset($request->selectBranch) ? $request->selectBranch : 'all';
        $query = EmployeeResignation::select('employee_details.emp_name', 'employee_details.emp_id as employee_id', 'employee_details.emp_image', 'emp_resignations.*')
            ->join('employee_details', 'employee_details.id', 'emp_resignations.emp_id');
        if ($user_role == '1') {
            if ($selectBranch != 'all') {
                $query->where('employee_details.branch_id', $selectBranch);
            }
        } else {
            $query->whereIn('employee_details.company_id', $user_company_id);

            if ($selectBranch == 'all') {
                $query->whereIn('employee_details.branch_id', $user_branch_id);
            } else {
                $query->where('employee_details.branch_id', $selectBranch);
            }
        }

        if (isset($searchBy) && $searchBy != '') {
            $query->where(function ($query) use ($searchBy) {
                $query->where('employee_details.emp_name', 'LIKE', '%' . $searchBy . '%')
                    ->orWhere('employee_details.emp_id', 'LIKE', '%' . $searchBy . '%');
            });
        }

        $resignations = $query->orderBy('emp_resignations.id', 'desc')->paginate(20);

        if ($resignations->isEmpty()) {
            return response()->json([
                'status' => 1,
                'message' => 'Employees Resignations list!',
                'details' => [
                    'data' => [
                        'current_page' => $resignations->currentPage(),
                        'data' => [],
                        'first_page_url' => $resignations->url(1),
                        'from' => $resignations->firstItem(),
                        'last_page' => $resignations->lastPage(),
                        'last_page_url' => $resignations->url($resignations->lastPage()),
                        'links' => $resignations->linkCollection()->toArray(),
                        'next_page_url' => $resignations->nextPageUrl(),
                        'path' => $resignations->path(),
                        'per_page' => $resignations->perPage(),
                        'prev_page_url' => $resignations->previousPageUrl(),
                        'to' => $resignations->lastItem(),
                        'total' => $resignations->total(),
                    ],
                ],
            ]);
        }
        foreach ($resignations as $resignation) {
            $employee = EmployeeDetail::where('id', $resignation->emp_id)->select('emp_name', 'emp_image', 'emp_gender', 'company_id')->first();
            $user_approval = user_approval::where('id', $resignation->emp_id)->select('designation_id', 'department_id')->first();

            if ($user_approval) {
                $designation = Designation::where('id', $user_approval->designation_id)->select('name')->first();
                $department = Department::where('id', $user_approval->department_id)->select('name')->first();

                $resignation->designation_name = $designation ? $designation->name : '';
                $resignation->department_name = $department ? $department->name : '';
            } else {
                $resignation->designation_name = '';
                $resignation->department_name = '';
            }

            $companies = DB::table('companies')->where('id', $resignation->company_id)->select('company_name')->first();
            $resignation->company_name = $companies ? $companies->company_name : '';
            $branch = Location::where('id', $resignation->branch_id)->select('branch_name')->first();
            $resignation->emp_name = $employee ? $employee->emp_name : '';
            $resignation->branch_name = $branch ? $branch->branch_name : '';
            if ($employee) {
                $imagePath = $this->imgFunc($employee->emp_image, $employee->emp_gender);
                $resignation->imagePath = $imagePath;
            } else {
                $resignation->imagePath = '';
            }
        }

        $data = [];
        $data['data'] = $resignations;
        $data['selectBranch'] = $selectBranch;

        return $this->sendResponse($data, 'Resignations fetched successfully!');
    }


    public function saveResignation(Request $request)
    {
        $createdData = null;
        $validate = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
            'emp_id' => 'required',
            'resignation_date' => 'required',
            'notice_date' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $resignationDate = $request->input('resignation_date');
                    $noticeDate = $value;
                    if ($noticeDate >= $resignationDate) {
                        $fail('The notice date must be earlier than the resignation date.');
                    }
                },
            ],
            'reason' => 'required'
        ]);
        if ($validate->fails()) {
            return $this->sendError([], $validate->errors()->first(), 400);
        }
        DB::beginTransaction();
        try {
            $record = EmployeeResignation::where('emp_id', $request->emp_id)->first();
            if (!$record) {
                $create = new EmployeeResignation;
                $create->company_id = $request->company_id;
                $create->branch_id = $request->branch_id;
                $create->emp_id = $request->emp_id;
                $create->resignation_date = Carbon::parse($request->resignation_date)->format('Y-m-d');
                $create->notice_date = Carbon::parse($request->notice_date)->format('Y-m-d');
                $create->reason = $request->reason;
                $create->save();
                DB::commit();
                $createdData = $create;

                $employee = EmployeeDetail::where('emp_id', $request->emp_id)->first();
                if ($employee) {
                    $msg = 'Resignation for "' . isset($employee->emp_name) ? ucwords($employee->emp_name) : "" . '" added successfully';
                    createLog('employee_action', $msg);
                }
                $data = array();
                // $getEmployee = EmployeeDetail::find($request->emp_id);

                $userApproval = user_approval::where('emp_id', $request->emp_id)->first();
                $getDesignationName = null;
                if ($employee != null) {
                    $email = ($employee->emp_email != null) ? $employee->emp_email : "";
                    $user = User::where('email', $email)->select('id')->first();
                } else {
                    $user = null;
                }
                if ($userApproval && isset($userApproval['designation_id'])) {
                    $designation = Designation::where('id', $userApproval['designation_id'])->first();

                    if ($designation && isset($designation['name'])) {
                        $getDesignationName = $designation['name'];
                    }
                }
                $type = "Employee Resignation";
                $branch = $request->branch_id;
                if ($user) {
                    $data['emp_name'] = $employee->emp_name;
                    $data['user_id'] = $user;
                    $data['employee_personal_email'] = $employee->personal_email;
                    $data['emp_position'] = $getDesignationName;
                    $data['last_date'] = Carbon::parse($request->resignation_date)->format('Y-m-d');
                    $createNotification = new NotificationController();
                    $createNotification->generateNotification($type, $data, $branch);
                }
                $resignationSaved = EmployeeResignation::where('id', $createdData->id)->first();
                if ($resignationSaved) {
                    return $this->sendResponse($resignationSaved, 'Resignation request submitted!');
                } else {
                    return $this->sendError([], 'Something went wrong!');
                }
            } else {
                return $this->sendError([], 'Resignation Form already exists!');
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError([], $e->getMessage(), 500);
        }
    }

    public function editResignation(Request $request)
    {
        $resignations = EmployeeResignation::where('id', $request->id)->get();

        foreach ($resignations as $key => $resignation) {
            $employee = EmployeeDetail::where('id', $resignation->emp_id)->first();
            if ($employee) {
                $resignation->emp_name = $employee->emp_name ? $employee->emp_name : "N/A";
            }

            $company = Company::where('id', $resignation->company_id)->first();
            if ($company) {
                $resignation->company_name = $company->company_name ? $company->company_name : "N/A";
            }

            $branch = Location::where('id', $resignation->branch_id)->first();
            if ($company) {
                $resignation->branch_name = $branch->branch_name ? $branch->branch_name : "N/A";
            }
        }

        if ($resignations) {
            return $this->sendResponse($resignations, 'Resignation fetched successfully!');
        } else {
            return $this->sendError([], 'Data not found!');
        }
    }

    public function updateResignation(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
            'emp_id' => 'required',
            'resignation_date' => 'required',
            'notice_date' => 'required',
            'reason' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors(),
            ], 400);
        }

        $data = EmployeeResignation::where('id', $request->id)->first();
        if (!$data) {
            return $this->sendError([], 'Resignation record not found for emp_id: ' . $request->id);
        }

        // Update fields
        $data->company_id = $request->company_id;
        $data->branch_id = $request->branch_id;
        $data->resignation_date = Carbon::parse($request->resignation_date)->format('Y-m-d');
        $data->notice_date = Carbon::parse($request->notice_date)->format('Y-m-d');
        $data->reason = $request->reason;
        $data->save();

        if ($data) {
            $employee = EmployeeDetail::where('id', $request->id)->first();
            $msg = 'Resignation for updated successfully';
            return $this->sendResponse($data, 'Resignation updated successfully!');
        } else {
            return $this->sendError([], 'Failed to update resignation.');
        }
    }

    public function deleteResignation(Request $request)
    {
        $resignation_data = EmployeeResignation::where('id', $request->id)->first();
        $resignation_data->delete();

        if ($resignation_data) {
            $employee = EmployeeDetail::where('id', $resignation_data->emp_id)->first();
            $employee->status = "1";
            $employee->update();

            if ($employee) {
                $msg = 'Resignation for deleted successfully';
                createLog('employee_action', $msg);
            }

            return $this->sendResponse($resignation_data, 'Resignation deleted successfully!');
        } else {
            return $this->sendError([], 'Something went wrong!');
        }
    }

    public function changeResignationStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError([], $validator->errors(), 400);
        } else {
            $id = $request->id;
            $status = $request->status;

            if ($status == "0") {
                $statusValue = "0";
            }
            if ($status == "1") {
                $statusValue = "1";
            }

            $data = EmployeeResignation::where('id', $id)->first();
            //dd($data->emp_id);
            if ($data) {
                $data->is_approved = $statusValue;
                $data->update();

                $user_detail = EmployeeDetail::where('id', $data->emp_id)->first();
                // dd($user_detail);

                if ($user_detail->status == "1") {
                    $user_detail->status = "3";
                }
                $user_detail->update();
                if ($status == "1") {

                    $empRole = ZkRoledEmployee::where('emp_id', $user_detail->id)->first();
                    if ($empRole) {
                        $empRole->update([
                            'synced' => '0',
                            'action' => 'delete'
                        ]);
                    }
                }
                return $this->sendResponse(
                    [
                        'resignation' => $data,
                        'user_detail' => $user_detail
                    ],
                    'Resignation status updated successfully!',
                    200
                );
            } else {
                return $this->sendResponse([], 'Data not found!', 200);
            }
        }
    }


    public function searchResignation(Request $request)
    {
        $searchValue = strtolower($request->input('searchValue'));
        $selectBranch = $request->input('selectBranch');
        if ($selectBranch == 'all') {
            $fetchData = EmployeeResignation::where(function ($query) use ($searchValue, $selectBranch) {
                $query->where(function ($query) use ($searchValue, $selectBranch) {
                    $query->WhereHas('employee_detail', function ($query) use ($searchValue) {
                        $query->whereRaw('LOWER(employee_details.emp_name) LIKE ?', ['%' . $searchValue . '%'])
                            ->orWhereRaw('LOWER(employee_details.emp_id) LIKE ?', ['%' . $searchValue . '%']);
                    })
                        ->orWhereHas('branch', function ($query) use ($searchValue, $selectBranch) {
                            $query->whereRaw('LOWER(locations.branch_name) LIKE ?', ['%' . $searchValue . '%']);
                        });
                });
            })
                ->orderBy('emp_resignations.id', 'asc')
                ->leftJoin('locations', 'emp_resignations.branch_id', '=', 'locations.id')
                ->leftJoin('employee_details', 'emp_resignations.emp_id', '=', 'employee_details.id')
                ->leftJoin('emp_approvals', 'emp_resignations.emp_id', 'emp_approvals.emp_id')
                ->select('employee_details.emp_id', 'locations.branch_name', 'emp_approvals.designation_id', 'employee_details.emp_name', 'emp_resignations.id', 'emp_resignations.resignation_date', 'emp_resignations.notice_date', 'emp_resignations.is_approved')
                // ->where('emp_resignations.branch_id', $selectBranch)
                ->paginate(15);
        } else {
            $fetchData = EmployeeResignation::where(function ($query) use ($searchValue, $selectBranch) {
                $query->where(function ($query) use ($searchValue, $selectBranch) {
                    $query->WhereHas('employee_detail', function ($query) use ($searchValue) {
                        $query->whereRaw('LOWER(employee_details.emp_name) LIKE ?', ['%' . $searchValue . '%'])
                            ->orWhereRaw('LOWER(employee_details.emp_id) LIKE ?', ['%' . $searchValue . '%']);
                    })
                        ->orWhereHas('branch', function ($query) use ($searchValue, $selectBranch) {
                            $query->whereRaw('LOWER(locations.branch_name) LIKE ?', ['%' . $searchValue . '%']);
                        });
                });
            })
                ->orderBy('emp_resignations.id', 'asc')
                ->leftJoin('locations', 'emp_resignations.branch_id', '=', 'locations.id')
                ->leftJoin('employee_details', 'emp_resignations.emp_id', '=', 'employee_details.id')
                ->leftJoin('emp_approvals', 'emp_resignations.emp_id', 'emp_approvals.emp_id')
                ->select('employee_details.emp_id', 'locations.branch_name', 'emp_approvals.designation_id', 'employee_details.emp_name', 'emp_resignations.id', 'emp_resignations.resignation_date', 'emp_resignations.notice_date', 'emp_resignations.is_approved')
                ->where('emp_resignations.branch_id', $selectBranch)
                ->paginate(15);
        }
        // }
        foreach ($fetchData as $designation) {
            if ($designation->desgn) {
                $designation->desgn = Designation::where('id', $designation->designation_id)->first();
            } else {
                $designation->desgn = "N/A";
            }
        }
        if (count($fetchData) > 0) {
            return $this->sendResponse($fetchData, 'Regisnation search successfully!', 200);
        } else {
            return $this->sendResponse([], 'Data not found!', 200);
        }
    }

    public function getBranchEmployees(Request $request)
    {
        $user = Auth::user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);

        $company_ids = isset($request->company_id) && !empty($request->company_id) ? $request->company_id : $user_company_id;
        $branch_ids = isset($request->branch_id) && !empty($request->branch_id) ? $request->branch_id : $user_branch_id;
        $query = EmployeeDetail::leftjoin('companies', 'employee_details.company_id', '=', 'companies.id')
            ->select('companies.company_name', 'employee_details.*', DB::raw("IFNULL(employee_details.dob, CURRENT_TIMESTAMP) as dob"))
            ->where('employee_details.is_deleted', '0')
            ->where('employee_details.status', '1');

        if ($user_role == '1' && isset($request->company_id) && isset($request->branch_id)) {
            $query->where('employee_details.company_id', $company_ids)
                ->whereIn('employee_details.branch_id', $branch_ids);

        } else {
            $query->whereIn('employee_details.company_id', $company_ids)
                ->whereIn('employee_details.branch_id', $branch_ids);
        }

        $result = $query->get();

        if (count($result) > 0) {
            return $this->sendResponse($result, 'Employees fetched successfully!', 200);
        } else {
            return $this->sendResponse([], 'Data not found!', 200);
        }
    }

    public function saveDocuments(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name_id' => 'required',
            'description' => 'required',
            'documents' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $employee = EmployeeDetail::where('id', $request->emp_id)->first();

        if ($validate->fails()) {
            return response()->json([
                'message' => $validate->errors(),
                'status' => false,
            ], 404);
        } else {
            $employee_documents = new EmployeeDocument;
            $employee_documents->emp_id = $request->emp_id;
            $employee_documents->name_id = $request->name_id;
            $employee_documents->discription = $request->description;
            $employee_documents->save();
        }
        if ($request->hasFile('documents')) {
            $file = $request->file('documents');
            $fileName = $file->getClientOriginalName();
            $fileStore = $file->storeAs('documents/' . $request->emp_id, $fileName);

            $employee_documents->document_path = $fileStore;
            $employee_documents->save();
        }
        ;

        $msg = '"' . ucwords($employee->emp_name) . '" Documents Save Successfully';
        createLog('documnet_save', $msg);

        return response()->json([
            'message' => "Documents save Successfully",
            'status' => true,
            'data' => $employee_documents,
        ]);
    }
    public function updateDocuments(Request $request)
    {
        $id = $request->id;

        $validate = Validator::make($request->all(), [
            'name_id' => 'required',
            'description' => 'required',
            'documents' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => $validate->errors(),
                'status' => false,
            ], 404);
        } else {

            $emp_document_id = DB::table('emp_documents')->where('id', $id)->first();
            $employee = EmployeeDetail::where('id', $emp_document_id->emp_id)->first();
            if ($emp_document_id) {

                DB::table('emp_documents')->where('id', $id)->update([
                    'name_id' => $request->name_id,
                    'discription' => $request->description,
                ]);

                if ($request->hasFile('documents')) {
                    $file = $request->file('documents');
                    $fileName = $file->getClientOriginalName();
                    $fileStore = $file->storeAs('documents/' . $emp_document_id->emp_id, $fileName);
                }
                DB::table('emp_documents')->where('id', $id)->update([
                    'document_path' => $fileStore,
                ]);

                $msg = '"' . ucwords($employee->emp_name) . '" Documents Update Successfully';
                createLog('documnet_update', $msg);

                return response()->json([
                    'message' => "Documents save Successfully",
                    'status' => true,
                    'data' => $emp_document_id,
                ]);

            } else {
                return response()->json([
                    'message' => "Documents not found",
                    'status' => false,
                    'data' => [],
                ]);
            }
        }
    }
    public function deleteDocuments(Request $request)
    {
        $id = $request->id;
        $emp_documents = EmployeeDocument::find($id);

        if (!$emp_documents) {
            return $this->sendError([], 'Document not found!', 404);
        }
        $employee = EmployeeDetail::where('id', $emp_documents->emp_id)->first();
        $employeeDeleted = $emp_documents->delete();
        $msg = '"' . ucwords($employee->emp_name) . '" Documents Deleted Successfully';
        createLog('employee_action', $msg);

        if ($employeeDeleted) {
            return response()->json(['message' => "Documnet Deleted Successfully.", 'data' => $emp_documents, 'status' => true]);
        } else {
            return response()->json(['message' => "Documnet not found.", 'data' => [], 'status' => false]);
        }
    }

    public function editDocuments(Request $request)
    {
        $id = $request->id;
        $baseUrl = url('/');
        $addUrl = '/api/storage/app/';
        $getDocument = DB::table('emp_documents')->where('id', $id)->first();
        if ($getDocument) {
            $getDocumentImage = $baseUrl . $addUrl . $getDocument->document_path;
            return response()->json([
                'status' => true,
                'message' => "Documnets fetch successfully",
                'data' => $getDocument,
                'image_path' => $getDocumentImage
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Documnets not found",
                'data' => []
            ]);
        }
    }
    public function getEmployeeDocuments(Request $request)
    {
        $id = $request->id;
        $urlPath = url('/'); // Base URL
        $extraPath = 'api/storage/app'; // Extra path to append

        // Retrieve employee documents with eager loading for docToName relation
        $employeeDocuments = EmployeeDocument::with('docToName')->where('emp_id', $id)->get();

        // Check if documents are found
        if ($employeeDocuments->isNotEmpty()) {
            // Transform the documents to include name_id and image_url
            $employeeDocuments->transform(function ($document) use ($urlPath, $extraPath) {
                // Assign the related document name
                $document->name_id = $document->docToName->document_name ?? null;

                // Construct the image URL
                $document->image_url = $urlPath . '/' . $extraPath . '/' . $document->document_path;

                return $document;
            });

            return response()->json([
                'status' => true,
                'message' => "Documents fetched successfully.",
                'documents' => $employeeDocuments,
            ]);
        }

        // If no documents found, return a response with an empty array
        return response()->json([
            'status' => false,
            'message' => "Documents not found.",
            'documents' => []
        ]);
    }


    public function empPayroll(Request $request)
    {
        $request->validate([
            'pay_period_id' => 'required|integer',
            'joining_date' => 'required|date',
            'salary_type_id' => 'required|integer',
            'working_hours' => 'nullable|numeric',
            'salary_per_hour' => 'nullable|numeric',
            'working_days' => 'nullable|numeric',
            'total_salary' => 'nullable|numeric',
            'emp_id' => 'required|integer',
            'compensations' => 'required|array',
            'compensations.*.type_id' => 'required|integer',
            'compensations.*.amount' => 'required|numeric',
            'compensations.*.is_taxable' => 'required|boolean'
        ]);

        DB::beginTransaction();

        try {
            $empSalary = EmpSalary::create([
                'emp_id' => $request->emp_id,
                'pay_period_id' => $request->pay_period_id,
                'joining_date' => $request->joining_date,
                'salary_type_id' => $request->salary_type_id,
                'working_hours' => $request->working_hours,
                'salary_per_hour' => $request->salary_per_hour,
                'working_days' => $request->working_days,
                'total_salary' => $request->total_salary
            ]);

            $savedCompensations = [];

            foreach ($request->compensations as $compensation) {
                $compensationDetail = EmpCompensationDetails::where('id', $compensation['type_id'])->first();
                if ($compensationDetail) {
                    $savedCompensation = EmpCompensation::create([
                        'emp_id' => $request->emp_id,
                        'type_id' => $compensationDetail->id,
                        'amount' => $compensation['amount'],
                        'type_of' => $compensationDetail->type_of,
                        'is_taxable' => $compensation['is_taxable']
                    ]);

                    $savedCompensations[] = $savedCompensation;

                } else {
                    DB::rollBack();

                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid type_id provided'
                    ], 400);
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Records saved successfully',
                'salary' => $empSalary,
                'compensations' => $savedCompensations
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while saving records',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getAllowanceRecord(Request $request)
    {
        $record = EmpCompensationDetails::where('type_of', 'allowance')->select('id', 'name')->get();

        if ($record) {
            return response()->json([
                'status' => true,
                'data' => $record,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => [],
            ]);
        }
    }
    public function getContributionRecord(Request $request)
    {
        $record = EmpCompensationDetails::where('type_of', 'contribution')->select('id', 'name')->get();

        if ($record) {
            return response()->json([
                'status' => true,
                'data' => $record,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => [],
            ]);
        }
    }
    public function getDeductionRecord(Request $request)
    {
        $record = EmpCompensationDetails::where('type_of', 'deduction')->select('id', 'name')->get();

        if ($record) {
            return response()->json([
                'status' => true,
                'data' => $record,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => [],
            ]);
        }
    }

    public function getCompensationRecord(Request $request)
    {
        $record = EmpCompensationDetails::where('id', $request->id)->select('id', 'name', 'amount')->get();

        if ($record) {
            return response()->json([
                'status' => true,
                'data' => $record,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => [],
            ]);
        }
    }
    public function getPayPeriod(Request $request)
    {

        $record = PayPeriod::select('id', DB::raw("CONCAT(pay_type, ' - ', name) as name"))->get();
        if ($record) {
            return response()->json([
                'status' => true,
                'data' => $record,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => [],
            ]);
        }
    }

    public function savePayPeriod(Request $request)
    {

        $validatior = Validator::make($request->all(), [
            'pay_period' => 'required',
            'pay_period_name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'distribute_date' => 'nullable',
        ]);

        if ($validatior->fails()) {
            return response()->json([
                'status' => true,
                'errors' => $validatior->errors()
            ]);

        } else {
            $record = PayPeriod::create([
                'pay_type' => $request->pay_period,
                'name' => $request->pay_period_name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'distribute_date' => $request->distribute_date,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Pay Period Create Successfully',
                'data' => $record
            ]);
        }
    }
    public function deleteCompensationRecord(Request $request)
    {
        $record = EmpCompensation::findOrFail($request->id);

        if ($record) {
            $record->is_deleted = 1;
            $record->save();

            return response()->json([
                'status' => true,
                'message' => 'Record Deleted Successfully.',
                'data' => $record
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Record Not Found.',
                'data' => []
            ]);
        }
    }
    public function editPayPeriod(Request $request)
    {
        try {
            $empCompensations = DB::table('employee_compensation')
                ->leftJoin('employee_compensation_details', 'employee_compensation_details.id', '=', 'employee_compensation.type_id')
                ->select(
                    'employee_compensation.id',
                    'employee_compensation.emp_id',
                    'employee_compensation.amount',
                    'employee_compensation.type_of',
                    'employee_compensation.is_taxable',
                    'employee_compensation.type_id',
                    'employee_compensation_details.name as type_name'
                )
                ->where('employee_compensation.emp_id', $request->emp_id)
                ->where('employee_compensation.is_deleted', 0)
                ->get();

            $empSalary = DB::table('employee_salary')
                ->leftJoin('pay_period', 'pay_period.id', '=', 'employee_salary.pay_period_id')
                ->select(
                    'employee_salary.id as salary_id',
                    'employee_salary.pay_period_id',
                    'employee_salary.working_hours',
                    'employee_salary.salary_per_hour',
                    'employee_salary.working_days',
                    'employee_salary.total_salary',
                    'pay_period.name as pay_period_name'
                )
                ->where('employee_salary.emp_id', $request->emp_id)
                ->get();

            // Prepare response
            return response()->json([
                'status' => true,
                'message' => $empCompensations->isNotEmpty() ? 'Employee records fetched successfully.' : 'No records found.',
                'empCompensations' => $empCompensations,
                'empSalary' => $empSalary,
            ]);

        } catch (\Exception $e) {
            // Handle potential exceptions
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching the records.',
                'error' => $e->getMessage(),
                'empCompensations' => [],
                'empSalary' => [],
            ], 500);
        }
    }
    public function updatePayPeriod(Request $request)
    {
        $request->validate([
            'pay_period_id' => 'required|integer',
            'joining_date' => 'required|date',
            'salary_type_id' => 'required|integer',
            'working_hours' => 'nullable|numeric',
            'salary_per_hour' => 'nullable|numeric',
            'working_days' => 'nullable|numeric',
            'total_salary' => 'nullable|numeric',
            'emp_id' => 'required|integer',
            'compensations' => 'required|array',
            'compensations.*.type_id' => 'required|integer',
            'compensations.*.amount' => 'required|numeric',
            'compensations.*.is_taxable' => 'required|boolean'
        ]);

        DB::beginTransaction();

        try {
            $empSalary = EmpSalary::updateOrCreate(
                ['emp_id' => $request->emp_id],
                [
                    'pay_period_id' => $request->pay_period_id,
                    'joining_date' => $request->joining_date,
                    'salary_type_id' => $request->salary_type_id,
                    'working_hours' => $request->working_hours,
                    'salary_per_hour' => $request->salary_per_hour,
                    'working_days' => $request->working_days,
                    'total_salary' => $request->total_salary
                ]
            );

            $savedCompensations = [];

            foreach ($request->compensations as $compensation) {
                $compensationDetail = EmpCompensationDetails::find($compensation['type_id']);

                if ($compensationDetail) {
                    $savedCompensation = EmpCompensation::updateOrCreate(
                        [
                            'emp_id' => $request->emp_id,
                            'type_id' => $compensationDetail->id
                        ],
                        [
                            'amount' => $compensation['amount'],
                            'type_of' => $compensationDetail->type_of,
                            'is_taxable' => $compensation['is_taxable']
                        ]
                    );

                    $savedCompensations[] = $savedCompensation;

                } else {
                    DB::rollBack();

                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid type_id provided'
                    ], 400);
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Records updated successfully',
                'salary' => $empSalary,
                'compensations' => $savedCompensations
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while updating records',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function savePayDetails(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'employee_details_id' => 'required',
            'starting_salary' => 'required|numeric|min:0',
            'pay_period' => 'required|string|max:255',
            'salary_type' => 'required|string|max:255',
            'first_working_date' => 'required|date',
            'components' => 'sometimes|array', // Change to 'sometimes' to make components optional
            'components.*.component_type_id' => 'required_with:components|exists:salary_component_types,id',
            'components.*.amount' => 'required_without:components.*.percentage|numeric|min:0',
            'components.*.percentage' => 'required_without:components.*.amount|numeric|min:0|max:100',
        ]);
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422); // Unprocessable Entity status code
        }
        // Check if salary record already exists for the given employee
        $salary = Emp_salary::where('employee_details_id', $request->employee_details_id)->first();
        if ($salary) {
            // Update existing salary record
            $salary->basic_salary = $request->starting_salary;
            $salary->pay_period = $request->pay_period;
            $salary->salary_type = $request->salary_type;
            $salary->first_working_date = $request->first_working_date;
            $salary->updated_at = now();
        } else {
            // Create a new Salary record
            $salary = new Emp_salary();
            $salary->employee_details_id = $request->employee_details_id;
            $salary->basic_salary = $request->starting_salary;
            $salary->pay_period = $request->pay_period;
            $salary->salary_type = $request->salary_type;
            $salary->first_working_date = $request->first_working_date;
            $salary->created_at = now();
            $salary->updated_at = now();
        }
        // Save Salary record
        // $salary->save();
        // Process and store or update salary components if provided
        $componentsData = [];
        $incomingComponentIds = [];
        if ($request->has('components')) {
            foreach ($request->components as $component) {
                $amount = isset($component['amount'])
                    ? $component['amount']
                    : ($component['percentage'] / 100) * $salary->basic_salary;
                // Check if the component already exists
                $salaryComponent = SalaryComponent::where('employee_details_id', $request->employee_details_id)
                    ->where('component_type_id', $component['component_type_id'])
                    ->first();
                if ($salaryComponent) {
                    // Update the existing component
                    $taxable = isset($component['tax_applicable'])
                        ? true
                        : false;
                    $salaryComponent->percentage = $component['percentage'] ?? null;
                    $salaryComponent->amount = $amount;
                    $salaryComponent->tax_applicable = $taxable;
                    $salaryComponent->updated_at = now();
                    $salaryComponent->save();
                } else {
                    // Create a new component
                    $salaryComponent = SalaryComponent::create([
                        'percentage' => $component['percentage'] ?? null,
                        'employee_details_id' => $request->employee_details_id,
                        'component_type_id' => $component['component_type_id'],
                        'amount' => $amount,
                        'tax_applicable' => $component['tax_applicable'] ?? false,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
                $incomingComponentIds[] = $salaryComponent->id;
                $componentsData[] = $salaryComponent;
            }
            // Remove components that are not included in the incoming request
            SalaryComponent::where('employee_details_id', $request->employee_details_id)
                ->whereNotIn('id', $incomingComponentIds)
                ->delete();
        }
        // Calculate gross salary based on the dynamic component types
        $componentTypeIds = SalaryComponentType::whereIn('type', ['Allowance'])->pluck('id');
        $grossSalary = $request->starting_salary + SalaryComponent::where('employee_details_id', $request->employee_details_id)
            ->whereIn('component_type_id', $componentTypeIds)
            ->sum('amount');
        $detuction_contributions_ids = SalaryComponentType::whereIn('type', ['Deduction', 'Contribution'])->pluck('id');
        if ($detuction_contributions_ids) {
            $deductions_contributions = SalaryComponent::where('employee_details_id', $request->employee_details_id)
                ->whereIn('component_type_id', $detuction_contributions_ids)
                ->sum('amount');
        }
        // Calculate taxable salary (excluding non-taxable components)
        $taxableComponentTypeIds = SalaryComponentType::whereIn('type', ['Allowance', 'Contribution', 'Deduction'])->pluck('id');
        $taxableSalary = $grossSalary - SalaryComponent::where('employee_details_id', $request->employee_details_id)
            ->where('tax_applicable', false)
            ->sum('amount');
        // Calculate tax and net salary
        $tax = 0;
        $netSalary = $grossSalary - $deductions_contributions - $tax;

        // Update salary record with net salary
        $salary->taxable_salary = $taxableSalary;
        $salary->net_salary = $netSalary;
        $salary->save();
        return response()->json([
            'status' => 1,
            'message' => $salary->wasRecentlyCreated ? "Salary details added successfully" : "Salary details updated successfully",
            'grossSalary' => $grossSalary,
            'taxableSalary' => $taxableSalary,
            'net_salary' => $netSalary,
            'tax' => $tax,
            'components' => $componentsData
        ], 200);
    }







    // Method For saving SalaryComponentTypes
    public function saveSalaryComponentsTypes(Request $request)
    {
        // Validate the incoming request data

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|in:Allowance,Contribution,Deduction',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => 'Errors',
                'errors' => $validator->errors()
            ], 422); // Unprocessable Entity status code
        }
        // Create a new SalaryComponentType entry
        $salaryComponentType = SalaryComponentType::create([
            'name' => $request->input('name'),
            'type' => $request->input('type'),
        ]);

        // Return a JSON response with the created data and a success message
        // return $this->sendResponse($salaryComponentType,);
        return response()->json([
            'status' => 1,
            'message' => "Salary Component Type added successfully",
            'data' => $salaryComponentType
        ], 201); // 201 status code for created resource
    }
    // Method for getting Components Types Data based on name

    public function editPayDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_details_id' => 'required'
        ]);
        $employeeId = $request->employee_details_id;
        // Fetch the salary record for the given employee ID

        $salary = Emp_salary::with(['components.componentType'])->where('employee_details_id', $employeeId)->first();

        if ($salary) {

            $salary->components->transform(function ($component) {
                $component->type = $component->componentType->type;
                unset($component->componentType); // Remove the entire 'componentType' object
                return $component;
            });
            // Fetch all component types for the dropdowns
            $componentTypes = SalaryComponentType::all();

            // Build the response
            $response = [
                'status' => 1,
                'success' => 1,
                'message' => 'Data Fetched Successfully',
                'data' => [
                    'salary' => $salary,
                    'componentTypes' => $componentTypes
                ]
            ];

            // Return the JSON response
            return response()->json($response);
        } else {
            return response()->json([
                'status' => 1,
                'success' => 0,
                'message' => 'Data Not Found',
            ], 200);
        }
    }
    public function getComponentsTypes(Request $request)
    {
        // dd($type);
        // Validate that the type is one of the allowed values
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:Allowance,Contribution,Deduction',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 200); // Unprocessable Entity status code
        }
        $type = $request->type;

        if (!in_array($type, ['Allowance', 'Contribution', 'Deduction'])) {
            return response()->json(['error' => 'Invalid type provided'], 200); // 400 Bad Request
        }

        // Fetch names based on the selected type
        $types = SalaryComponentType::where('type', $type)->get();

        // Check if any names were found
        if ($types->isEmpty()) {
            return response()->json([
                'status' => 1,
                'message' => 'No components found for the selected type'
            ], 200); // 404 Not Found
        }
        // Return the list of names as a JSON response

        return response()->json([
            'status' => 1,
            'message' => "Types Fetched Successfully",
            'data' => $types,
        ], 200); // 200 OK
    }

    // /**
    //  * Calculate the tax based on the taxable salary.
    //  *
    //  * @param  float  $taxableSalary
    //  * @return float
    //  */
    private function calculateTax($taxableSalary)
    {
        // Example tax brackets and rates (adjust as per your tax rules)
        $taxRate = 0;
        // if ($taxableSalary <= 50000) {
        //     $taxRate = 0.05;  // 5% taxes
        // } elseif ($taxableSalary <= 100000) {
        //     $taxRate = 0.10;  // 10% tax
        // } elseif ($taxableSalary <= 150000) {
        //     $taxRate = 0.15;  // 15% tax
        // } else {
        //     $taxRate = 0.20;  // 20% tax
        // }

        // Calculate the tax amount
        $tax = 0;

        return round($tax, 2);  // Return the tax amount rounded to 2 decimal places
    }


    public function salaryComponents()
    {
        // Retrieve all records from the salary_component_types table
        $components = SalaryComponentType::all();

        // Return the data as JSON
        if ($components) {
            return response()->json([
                'status' => 1,
                'message' => 'Salary components get successfully',
                'components' => $components
            ], 200);
        } else {
            return response()->json([
                'status' => 1,
                'message' => 'Salary components Not Found'
            ], 200);
        }
    }
    public function logRecords(Request $request)
    {
        $searchBy = $request->input('searchBy', '');
        $perPage = $request->input('per_page', 10);

        $query = DB::table('logs')
            ->leftJoin('users', 'users.id', '=', 'logs.user_id')
            ->select('logs.type', 'logs.msg', 'users.email', 'logs.id', 'logs.updated_at');

        if ($searchBy) {
            $query->where('logs.type', 'LIKE', '%' . $searchBy . '%')
                ->orWhere('logs.msg', 'LIKE', '%' . $searchBy . '%')
                ->orWhere('users.email', 'LIKE', '%' . $searchBy . '%');
        }

        $logs = $query->orderBy('logs.id', 'desc')
            ->paginate($perPage);

        return response()->json([
            'status' => 1,
            'message' => 'All Logs Records',
            'data' => $logs->items(),
            'pagination' => [
                'total' => $logs->total(),
                'per_page' => $logs->perPage(),
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'next_page_url' => $logs->nextPageUrl(),
                'prev_page_url' => $logs->previousPageUrl(),
            ]
        ]);
    }
    public function logDelete(Request $request)
    {

        $dateFrom = Carbon::parse($request->dateFrom)->toDateString();
        $dateTo = Carbon::parse($request->dateTo)->toDateString();

        $query = DB::table('logs')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->delete();

        if ($query > 0) {
            return response()->json([
                'message' => $query . " Records deleted successfully",
                'status' => 1,
            ]);
        } else {
            return response()->json([
                'message' => "No records found in the given date range",
                'status' => 0,
            ]);
        }
    }

    // get list of head of departmnets

    public function getHeadList()
    {
        $user = Auth::user();
        $companyId = array_filter(explode(',', $user->company_id));
        $branchId = array_filter(explode(',', $user->branch_id));

        $query = EmployeeDetail::query();
        $query->select('id', 'emp_name');
        if (!empty($branchId) && !empty($companyId)) {
            $query->whereIn('branch_id', $branchId)
                ->whereIn('company_id', $companyId);
        }
        $query->where('is_deleted', '0')
            ->where('status', '1')
            ->where('is_active', '1');
        $data = $query->get();
        return response()->json([
            'status' => true,
            'success' => 1,
            'response' => $data
        ]);
    }

    public function getDocNameList()
    {
        return response()->json([
            'status' => true,
            'success' => 1,
            'response' => DocumentsNames::all()
        ]);
    }

    public function downloadEmployeeList(Request $request)
    {
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);

        $availableFields = [
            'emp_id' => 'employee_details.emp_id',
            'emp_name' => 'employee_details.emp_name',
            'designation' => 'designations.name as designation',
            'joining_date' => 'emp_approvals.joining_date',
            'branch_name' => 'locations.branch_name',
            'approved_leave_days' => 'leave_counts.approved_leave_days',
            'department_name' => 'departments.name',
            'status' => 'employee_details.status',
            'cnic' => 'employee_details.cnic',
            'email' => 'employee_details.emp_email',
            'phone' => 'employee_details.emp_phone',
            'bank_name' => 'emp_account_details.bank_name',
            'account_number' => 'emp_account_details.account_no',
        ];

        $requestedFields = $request->input('fields', array_keys($availableFields));
        $selectedFields = [];

        foreach ($requestedFields as $field) {
            if (isset($availableFields[$field])) {
                $selectedFields[] = $availableFields[$field];
            }
        }

        if ($user_role == 1) {
            $branches = Location::where('is_deleted', '0')->get();
        } else {
            $branches = Location::whereIn('company_id', $user_company_id)
                ->whereIn('id', $user_branch_id)
                ->where('is_deleted', '0')
                ->get();
        }

        // Joining leaves as a subquery to calculate approved leave days
        $employeeQuery = EmployeeDetail::leftJoin('emp_approvals', 'emp_approvals.emp_id', '=', 'employee_details.id')
            ->leftJoin('departments', 'departments.id', '=', 'emp_approvals.department_id')
            ->leftJoin('designations', 'designations.id', '=', 'emp_approvals.designation_id')
            ->leftJoin('emp_account_details', 'emp_account_details.emp_id', '=', 'employee_details.id')

            ->leftJoin('locations', 'locations.id', '=', 'employee_details.branch_id')
            ->leftJoinSub(function ($query) {
                $query->select('emp_id', \DB::raw('SUM(approved_days) as approved_leave_days'))
                    ->from('emp_leaves')
                    ->where('is_approved', '1')
                    ->where('is_deleted', '0')
                    ->whereYear('created_at', \Carbon\Carbon::now()->year)
                    ->groupBy('emp_id');
            }, 'leave_counts', 'leave_counts.emp_id', '=', 'employee_details.id')
            ->where('employee_details.is_deleted', '0')
            ->where('status', '1')
            ->select($selectedFields)
            ->orderBy('emp_id', 'asc');

        if ($user_role != 1) {
            $employeeQuery->whereIn('employee_details.company_id', $user_company_id)
                ->whereIn('employee_details.branch_id', $user_branch_id);
        }

        $employees = $employeeQuery->get();

        $csvFileName = 'user_details_' . time() . '.csv';
        $csvFilePath = storage_path('app/public/' . $csvFileName);

        $file = fopen($csvFilePath, 'w');
        if ($file === false) {
            return $this->sendResponse(null, 'Failed to create the CSV file.', 500);
        }

        // Write CSV header
        $header = array_keys(array_intersect_key($availableFields, array_flip($requestedFields)));
        fputcsv($file, $header);

        // Write employee data with leave count
        foreach ($employees as $employee) {
            $row = [];
            foreach ($requestedFields as $field) {
                $row[] = $employee->$field ?? '';
            }
            fputcsv($file, $row);
        }

        fclose($file);

        $downloadLink = url('api/download-attendance-file?file_path=' . $csvFileName);
        return $this->sendResponse($downloadLink, 'User details CSV file generated successfully!', 200);
    }

    public function downloadEmployeeDesignationList(Request $request)
    {
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);

        // Define available fields and map them to the Designation table columns
        $availableFields = [
            'name' => 'designations.name',
            'created_at' => 'designations.created_at',
        ];

        // Get requested fields, defaulting to all available fields if none specified
        $requestedFields = $request->input('fields', array_keys($availableFields));
        $selectedFields = [];

        foreach ($requestedFields as $field) {
            if (isset($availableFields[$field])) {
                $selectedFields[] = $availableFields[$field];
            }
        }

        // Fetch the designation data with selected fields
        $CompanyDesignations = Designation::select($selectedFields)->get();

        // Define the CSV file name and path
        $csvFileName = 'emp_designation_' . time() . '.csv';
        $csvFilePath = storage_path('app/public/' . $csvFileName);

        // Create the CSV file
        $file = fopen($csvFilePath, 'w');
        if ($file === false) {
            return $this->sendResponse(null, 'Failed to create the CSV file.', 500);
        }

        // Write the header to the CSV
        $header = array_keys(array_intersect_key($availableFields, array_flip($requestedFields)));
        fputcsv($file, $header);

        // Write each designation row to the CSV file
        foreach ($CompanyDesignations as $designation) {
            $row = [];
            foreach ($requestedFields as $field) {
                // Map each requested field to its actual value from the designation
                $row[] = $designation->{explode('.', $availableFields[$field])[1]} ?? '';
            }
            fputcsv($file, $row);
        }

        fclose($file);

        // Return the download link
        $downloadLink = url('api/download-attendance-file?file_path=' . $csvFileName);
        return $this->sendResponse($downloadLink, 'User details CSV file generated successfully!', 200);
    }

    public function downloadEmployeeResignationList(Request $request)
    {
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);

        $availableFields = [
            'emp_id' => 'employee_details.emp_id',
            'emp_name' => 'employee_details.emp_name',
            'designation' => 'designations.name as designation',
            'branch_name' => 'locations.branch_name',
            'company_name' => 'companies.company_name',
            'notice_date' => 'emp_resignations.notice_date',
        ];

        $requestedFields = $request->input('fields', array_keys($availableFields));
        $selectedFields = [];

        foreach ($requestedFields as $field) {
            if (isset($availableFields[$field])) {
                $selectedFields[] = $availableFields[$field];
            }
        }

        if ($user_role == 1) {
            $branches = Location::where('is_deleted', '0')->get();
        } else {
            $branches = Location::whereIn('company_id', $user_company_id)
                ->whereIn('id', $user_branch_id)
                ->where('is_deleted', '0')
                ->get();
        }

        $employeeQuery = EmployeeDetail::leftJoin('emp_approvals', 'emp_approvals.emp_id', '=', 'employee_details.id')
            ->leftJoin('designations', 'designations.id', '=', 'emp_approvals.designation_id')
            ->leftJoin('locations', 'locations.id', '=', 'employee_details.branch_id')
            ->leftJoin('companies', 'companies.id', '=', 'employee_details.company_id')
            ->leftJoin('emp_resignations', 'emp_resignations.emp_id', '=', 'employee_details.id')
            ->where('employee_details.is_deleted', '0')
            ->where('status', '3')
            ->select($selectedFields)
            ->orderBy('emp_id', 'asc');

        if ($user_role != 1) {
            $employeeQuery->whereIn('employee_details.company_id', $user_company_id)
                ->whereIn('employee_details.branch_id', $user_branch_id);
        }

        $employees = $employeeQuery->get();

        $csvFileName = 'user_details_' . time() . '.csv';
        $csvFilePath = storage_path('app/public/' . $csvFileName);

        $file = fopen($csvFilePath, 'w');
        if ($file === false) {
            return $this->sendResponse(null, 'Failed to create the CSV file.', 500);
        }

        $header = array_keys(array_intersect_key($availableFields, array_flip($requestedFields)));
        fputcsv($file, $header);

        foreach ($employees as $employee) {
            $row = [];
            foreach ($requestedFields as $field) {
                $row[] = $employee->$field ?? '';
            }
            fputcsv($file, $row);
        }

        fclose($file);

        $downloadLink = url('api/download-attendance-file?file_path=' . $csvFileName);
        return $this->sendResponse($downloadLink, 'User details CSV file generated successfully!', 200);
    }
}



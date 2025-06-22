<?php

namespace App\Http\Controllers\API;


use App\Models\Location;
use App\Models\EmployeeDetail;
use App\Models\Leave;
use App\Models\Leave_Type;
use App\Models\Leave_setting;
use App\Models\Setting;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\SystemResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Models\Config;
use App\Models\company;
use App\Models\Role;
use App\Http\Controllers\NotificationController;
use App\Models\NotificationEmail;

class AdminController extends BaseController
{
    use HasApiTokens, Notifiable;

    public function Viewsmtpdetails()
    {
        $smtp_data = Setting::whereIn('perimeter', [
            'smtp_host',
            'smtp_port',
            'smtp_encryption',
            'smtp_user_name',
            'smtp_password',
            'smtp_from_email',
            'smtp_from_name'
        ])->pluck('value', 'perimeter');
        $data = [
            'smtp_host' => $smtp_data->get('smtp_host') ?? null ?? null,
            'smtp_port' => $smtp_data->get('smtp_port') ?? null,
            'encryption_type' => $smtp_data->get('smtp_encryption') ?? null,
            'smtp_user_name' => $smtp_data->get('smtp_user_name') ?? null,
            'smtp_password' => $smtp_data->get('smtp_password') ?? null,
            'smtp_from_email' => $smtp_data->get('smtp_from_email') ?? null,
            'smtp_from_name' => $smtp_data->get('smtp_from_name') ?? null,
        ];

        return $this->sendResponse($data, 'SMTP details are fetched Successfully');
    }

    public function addSMTPdetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'smtp_host' => 'required',
            'smtp_port' => 'required',
            'smtp_encryption' => 'required',
            'smtp_user_name' => 'required',
            'smtp_password' => 'required',
            'smtp_from_email' => 'required',
            'smtp_from_name' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        try {
            DB::beginTransaction();
            $settings = [
                'smtp_host' => $request->smtp_host,
                'smtp_port' => $request->smtp_port,
                'smtp_encryption' => $request->smtp_encryption,
                'smtp_user_name' => $request->smtp_user_name,
                'smtp_password' => $request->smtp_password,
                'smtp_from_email' => $request->smtp_from_email,
                'smtp_from_name' => $request->smtp_from_name,
            ];
            $data = [];
            foreach ($settings as $key => $value) {
                $each_data = Setting::updateOrCreate(
                    ['perimeter' => $key],
                    ['value' => $value]
                );
                array_push($data, $each_data);
            }
            DB::commit();
            return $this->sendResponse($data, 'SMTP details are updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendResponse($e, "");
        }

    }

    public function getCompanyData()
    {
        $response = array();
        $data = array();
        $results = Company::where('is_active', '1')->where('is_deleted', '0')->get();
        foreach ($results as $result) {
            $data['company_name'] = $result->company_name;
            $data['company_logo'] = $result->logo;
            array_push($response, $data);
        }
        $message = "Company Data Fetch successfully";
        return $this->sendResponse($response, $message);
    }

    public function getLeaveTypes()
    {
        $user = Auth::user();
        $empData = EmployeeDetail::find($user->emp_id);
        if (!$empData) {
            return $this->sendError([], 'Employee not found!', 200);
        }
        $join_date = Carbon::parse($empData->approval->joining_date);
        $today = Carbon::today();
        $months = $join_date->diffInMonths($today);

        $currentYear = Carbon::now()->year;
        $eligibleLeaveTypeIds = [];

        $allTypes = Leave_Type::all();

        foreach ($allTypes as $type) {
            $leaveRecord = Leave::whereYear('from_date', '=', $currentYear)
                ->where('emp_id', $empData->id)
                ->where('leave_type', $type->id)
                ->where('is_approved', '1')
                ->orderBy('id', 'desc')
                ->first();

            if ((!$leaveRecord || $leaveRecord->remaining > 0) && $type->id != 5) {
                $eligibleLeaveTypeIds[] = $type->id;
            }
        }
        $array_collection = collect($eligibleLeaveTypeIds);
        if (!$array_collection->contains(2) && !$array_collection->contains(3)) {
            $array_collection->push(5);
        }
        $query = Leave_Type::query();
        if ($empData->emp_gender == 'M') {
            $query->where('id', '!=', 4);
        }

        if ($empData->approval && $empData->approval->approvalToJobStatus) {
            if ($empData->approval->approvalToJobStatus->id != 3) {
                $array_collection = collect([]);
                $array_collection->push(5);
                $query->whereIn('id', $array_collection);
            } else {
                $query->whereIn('id', $array_collection);
            }
        } else {
            return $this->sendError([], 'Job Status not found!', 200);
        }

        if ($months <= 5) {
            $query->where('id', '!=', 1);
        }

        $leaveTypes = $query->select('id', 'types')->get();

        if ($leaveTypes->isNotEmpty()) {
            return $this->sendResponse($leaveTypes, 'Leave types fetched successfully!', 200);
        } else {
            return $this->sendError([], 'Data not found!', 200);
        }
    }

    public function saveLeave(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'leave_type' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'leave_status' => 'required',
            'remarks' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $leave_type = $request->leave_type;
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $difference = $startDate->diffInDays($endDate) + 1;
        $employee = Auth::user();
        // $companyId = explode(',', $employee->company_id);
        // $branchId = explode(',', $employee->branch_id);
        if (!$employee) {
            return $this->sendError('Record not found.');
        }
        $employee_record = EmployeeDetail::where('id', $employee->emp_id)->first();
        if ($employee_record) {
            $leaveSetting = Leave_setting::where('company_id', $employee_record->company_id)
                ->where('is_active', '1')
                ->where('is_deleted', '0')->first();
            if ($leaveSetting) {
                $balanceLeaves = $leaveSetting->annual_days + $leaveSetting->casual_days + $leaveSetting->sick_days;
                if (!$balanceLeaves) {
                    return $this->sendError('', 'Leave setting not found for the company.');
                }
                $today = Carbon::today();
                $days = $startDate->diffInDays($today);
                if ($request->leave_type == 2 && $days < $leaveSetting->casual_before_days) {
                    return response()->json([
                        'status' => 0,
                        'success' => false,
                        'message' => 'You cannot apply casual leave before ' . $leaveSetting->casual_before_days . ' days',
                    ]);
                }
                if ($request->leave_type == 1 && $days < $leaveSetting->annual_before_days) {
                    return response()->json([
                        'status' => 0,
                        'success' => false,
                        'message' => 'You cannot apply annual leave before ' . $leaveSetting->annual_before_days . ' days',
                    ]);
                }

                $pendingLeaves = Leave::where('emp_id', $employee_record->id)
                    ->where(function ($query) use ($request) {
                        $query->whereBetween('from_date', [Carbon::parse($request->start_date), Carbon::parse($request->end_date)])
                            ->orWhereBetween('to_date', [Carbon::parse($request->start_date), Carbon::parse($request->end_date)]);
                    })
                    // ->whereNull('is_approved')
                    ->first();
                if ($pendingLeaves) {
                    return $this->sendError('', 'You already have requested leave/s on ' . Carbon::parse($pendingLeaves->from_date)->format('d-m-Y'));
                }
                $numberOfDays = 0;
                if ($leave_type == '1') {
                    $numberOfDays = $leaveSetting->annual_days ? $leaveSetting->annual_days : '0';
                } elseif ($leave_type == '2') {
                    $numberOfDays = $leaveSetting->casual_days ? $leaveSetting->casual_days : '0';
                } elseif ($leave_type == '3') {
                    $numberOfDays = $leaveSetting->sick_days ? $leaveSetting->sick_days : '0';
                } elseif ($leave_type == '4') {
                    $numberOfDays = $leaveSetting->maternity_days ? $leaveSetting->maternity_days : '0';
                }
                $leaveSum = Leave::where('is_approved', '1')
                    ->where('leave_type', $leave_type)
                    ->where('is_deleted', '0')
                    ->where('company_id', $leaveSetting->company_id)
                    ->where('emp_id', $employee_record->id)
                    ->whereYear('from_date', Carbon::now()->year)
                    ->sum('approved_days');
                if ($leaveSum == 0 || $leave_type == 5) {
                    $remainingLeaves = $numberOfDays;
                } else {
                    $remainingLeaves = $numberOfDays - $leaveSum;
                }

                if ($difference > $remainingLeaves && $leave_type != '5') {
                    return $this->sendError('', 'You have ' . $remainingLeaves . ' balanced leaves ! Cannot apply more than balance');
                }

                $applyLeave = Leave::create(
                    [
                        'company_id' => $employee_record->company_id,
                        'branch_id' => $employee_record->branch_id,
                        'emp_id' => $employee_record->id,
                        'leave_type' => $request->leave_type,
                        'remaining' => $remainingLeaves,
                        'leave_status' => $request->leave_status,
                        'from_date' => Carbon::parse($request->start_date)->format('Y-m-d'),
                        'to_date' => Carbon::parse($request->end_date)->format('Y-m-d'),
                        'requested_days' => $difference,
                        'approved_days' => $difference,
                        'remarks' => $request->remarks,
                    ]
                );
                if ($applyLeave) {
                    $getLeaveName = Leave_Type::where('id', $request->leave_type)->first()->types;
                    $data = array();
                    $type = "Leave";
                    $branch = (int) $employee_record->branch_id;
                    $data['emp_name'] = $employee_record ? $employee_record->emp_name : $employee_record->emp_id;
                    $data['from_date'] = $applyLeave->from_date;
                    $data['to_date'] = $applyLeave->to_date;
                    $data['leave_type'] = $getLeaveName;
                    $createNotification = new NotificationController();
                    $createNotification->generateNotification($type, $data, $branch);
                }
                $msg = '"' . $user->fullname . '" Applied Leave From Mobile';
                createLog('leave-action', $msg);
                return $this->sendResponse($applyLeave, 'Leave Request Added Successfully.');
            }
            return $this->sendResponse('', 'Leave Setting not found.');
        } else {
            return $this->sendError('', 'Employee not found.');
        }


    }

    public function leaveRequest(Request $request)
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);

        if ($user_role == '1') {
            $leaves = Leave::leftjoin('employee_details', 'emp_leaves.emp_id', '=', 'employee_details.id')
                ->leftjoin('branches', 'emp_leaves.branch_id', '=', 'branches.id')
                ->leftjoin('leave_types', 'emp_leaves.leave_type', '=', 'leave_types.id')
                ->select('employee_details.emp_name', 'employee_details.emp_image', 'employee_details.emp_gender', 'emp_leaves.*', 'branches.branch_name', 'employee_details.emp_id', 'leave_types.types')
                ->where('emp_leaves.is_deleted', '0')
                ->orderBy('emp_leaves.emp_id', 'asc')
                ->get();
            $branches = Location::where('is_deleted', '0')->orderBy('branch_name', 'asc')->get();
        } else {
            $leaves = Leave::leftjoin('employee_details', 'emp_leaves.emp_id', '=', 'employee_details.id')
                ->leftjoin('branches', 'emp_leaves.branch_id', '=', 'branches.id')
                ->leftjoin('leave_types', 'emp_leaves.leave_type', '=', 'leave_types.id')
                ->select('employee_details.emp_name', 'employee_details.emp_image', 'employee_details.emp_gender', 'emp_leaves.*', 'branches.branch_name', 'employee_details.emp_id', 'leave_types.types')
                ->whereIn('emp_leaves.company_id', $user_company_id)
                ->whereIn('emp_leaves.branch_id', $user_branch_id)
                ->where('emp_leaves.is_deleted', '0')
                ->orderBy('emp_leaves.emp_id', 'asc')
                ->get();
            $branches = Location::whereIn('company_id', $user_company_id)
                ->whereIn('id', $user_branch_id)
                ->where('is_deleted', '0')
                ->orderBy('branch_name', 'asc')
                ->get();
        }

        $Leave_types = Leave_Type::orderBy('types', 'asc')->get();
        return response()->json([
            'leaves' => $leaves,
            'branches' => $branches,
            'leave_types' => $Leave_types,
        ]);
    }

    public function getUserLeaves()
    {

        $empId = Auth::user()->emp_id;
        $empData = EmployeeDetail::find($empId);
        if ($empData) {
            $branchId = $empData->branch_id;
            $companyId = $empData->company_id;
            $companySetting = Leave_setting::where('company_id', $companyId)->where('is_active', '1')->where('is_deleted', '0')->first();
            if ($companySetting) {
                $data = [];
                $balanceLeaves = $companySetting->annual_days + $companySetting->casual_days + $companySetting->sick_days;
                $data['totalLeaves'] = $balanceLeaves;
                $totalLeaves = $balanceLeaves;
                $pastLeaves = [];
                $upcomingLeaves = [];
                $pendingLeaves = 0;
                $approvedLeaves = 0;
                $declinedLeaves = 0;
                $sickLeaves = 0;
                $casualLeaves = 0;
                $annualLeaves = 0;

                $userLeaveExists = Leave::where('company_id', $companyId)->where('emp_id', $empId)->exists();
                if ($userLeaveExists) {
                    $getUserLeaves = Leave::where('company_id', $companyId)
                        ->where('branch_id', $branchId)
                        ->where('emp_id', $empId)
                        ->whereYear('from_date', Carbon::now()->year)
                        ->latest('from_date')
                        ->get();

                    foreach ($getUserLeaves as $leaves) {
                        if ($leaves->approved_by != null) {
                            $leaves->approved_by = Role::where('id', $leaves->approved_by)->first()->role_name;
                        }
                        if ($leaves->is_approved == '1') {
                            if($leaves->leave_type != '5'){
                            $balanceLeaves = $balanceLeaves - $leaves->approved_days;
                            }
                            if ($leaves->leave_type == '2') {
                                $casualLeaves += $leaves->approved_days;
                            }
                            if ($leaves->leave_type == '3') {
                                $sickLeaves += $leaves->approved_days;
                            }
                            if ($leaves->leave_type == '1') {
                                $annualLeaves += $leaves->approved_days;
                            }
                        }
                        $leaves->leave_type = ucfirst(Leave_Type::where('id', $leaves->leave_type)->first()['types']);
                        if ($leaves->is_approved == NULL) {
                            $startDate = Carbon::parse($leaves->from_date);
                            $endDate = Carbon::parse($leaves->to_date);
                            $pendingLeaves += $startDate->diffInDays($endDate) + 1;
                        }
                        if ($leaves->is_approved == '1') {
                            $leaves->is_approved = 'Approved';
                        } elseif ($leaves->is_approved == '0') {
                            $leaves->is_approved = 'Declined';
                        } else {
                            $leaves->is_approved = 'Pending';
                        }
                        if ($leaves->approved_days == null) {
                            $leaves->approved_days = $leaves->requested_days;
                        }
                        $endDate = Carbon::parse($leaves->to_date)->format('Y-m-d');
                        if ($endDate >= Carbon::now()->format('Y-m-d')) {
                            $upcomingLeaves[] = $leaves;
                        } else {
                            $pastLeaves[] = $leaves;
                        }
                    }
                    $approvedLeaves = Leave::where('company_id', $companyId)
                        ->where('branch_id', $branchId)
                        ->where('emp_id', $empId)
                        ->whereYear('created_at', Carbon::now()->year)
                        ->where('is_approved', '1')
                        ->sum('approved_days');
                    $declinedLeaves = Leave::where('company_id', $companyId)
                        ->where('branch_id', $branchId)
                        ->where('emp_id', $empId)
                        ->whereYear('created_at', Carbon::now()->year)
                        ->where('is_approved', '0')
                        ->sum('approved_days');
                }
                $data['totalLeaves'] = $totalLeaves;
                $data['balanceLeaves'] = $balanceLeaves;
                $data['approvedLeaves'] = $approvedLeaves;
                $data['pendingLeaves'] = $pendingLeaves;
                $data['declinedLeaves'] = $declinedLeaves;
                $data['totalAnnual'] = $companySetting->annual_days;
                $data['annualLeaves'] = $annualLeaves;
                $data['totalCasual'] = $companySetting->casual_days;
                $data['casualLeaves'] = $casualLeaves;
                $data['totalSick'] = $companySetting->sick_days;
                $data['sickLeaves'] = $sickLeaves;
                $data['upcomingLeaves'] = $upcomingLeaves;
                $data['pastLeaves'] = $pastLeaves;

                return $this->sendResponse($data, "Leaves fetched successfully");
            } else {
                return $this->sendResponse([], 'The leaves against the user\s company are not added yet!');
            }
        } else {
            return $this->sendResponse([], 'Employee not found');
        }


    }


    // only for testing so save system response
    public function saveSystemResponse(Request $request){
        SystemResponse::create([
            'message' => $request->message
        ]);
        return 'success';
    }
}

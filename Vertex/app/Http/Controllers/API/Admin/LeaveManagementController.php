<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Models\ApprovalSetting;
use App\Models\ApprovalStatus;
use App\Models\SystemResponse;
use App\Models\UserDailyRecord;
use App\Traits\CheckApprovalStatus;
use App\Traits\ProfileImage;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Leave;
use App\Models\Leave_Type;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeDetail;
//use App\Http\Controllers\NotificationController;
use App\Http\Controllers\API\Admin\NotificationController;
use App\Models\Company;
use App\Models\Leave_setting;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LeaveManagementController extends BaseController
{
    use ProfileImage, CheckApprovalStatus;
    // public function leaveRequest(Request $request)
    // {
    //     //user information
    //     $user = auth()->user();
    //     $user_role = $user->role_id;
    //     $user_company_id = explode(',', $user->company_id);
    //     $user_branch_id = explode(',', $user->branch_id);
    //     $searchBy = isset($request->search_by) ? $request->search_by : '';
    //     $selectedBranch = isset($request->selectBranch) ? $request->selectBranch : 'all';
    //     $query = Leave::leftjoin('employee_details', 'emp_leaves.emp_id', '=', 'employee_details.id')
    //     ->leftjoin('locations', 'emp_leaves.branch_id', '=', 'locations.id')
    //     ->leftjoin('leave_types', 'emp_leaves.leave_type', '=', 'leave_types.id')
    //     ->leftjoin('leave-settings', 'emp_leaves.company_id', '=', 'leave-settings.company_id')
    //     ->select(
    //         'emp_leaves.id',
    //         'employee_details.emp_id',
    //         'employee_details.emp_name',
    //         'employee_details.emp_image',
    //         'locations.branch_name',
    //         'emp_leaves.leave_type',
    //         'emp_leaves.remaining',
    //         'emp_leaves.from_date',
    //         'emp_leaves.to_date',
    //         'emp_leaves.requested_days',
    //         'emp_leaves.approved_by',
    //     );
    //     if ($user_role == '1') { // super-admin
    //         if ($selectedBranch != 'all') {
    //             $leaves = $query->where('emp_leaves.branch_id', $selectedBranch);
    //         }
    //     } else {
    //         if ($selectedBranch == 'all') {
    //             $leaves = $query->whereIn('emp_leaves.company_id', $user_company_id)
    //                 ->whereIn('emp_leaves.branch_id', $user_branch_id);
    //         } else {
    //             $leaves = $query->whereIn('emp_leaves.company_id', $user_company_id)
    //                 ->where('emp_leaves.branch_id', $selectedBranch);
    //         }
    //     }
    //     if($searchBy != ''){
    //         $query->where(function ($query) use ($searchBy) {
    //             $query->where('employee_details.emp_name', 'LIKE', '%' . $searchBy . '%')
    //                 ->orWhere('employee_details.emp_id', 'LIKE', '%' . $searchBy . '%')
    //                 ->orWhere('leave_types.types', 'LIKE', '%' . $searchBy . '%')
    //                 ->orWhere('locations.branch_name','LIKE','%'.$searchBy.'%');
    //         });
    //     }

    //     $leaves = $query->where('emp_leaves.is_deleted', '0')->orderBy('emp_leaves.from_date', 'desc')->paginate(20);

    //     foreach ($leaves as $leave) {
    //         if ($leave->approved_by != null && $leave->approved_by != '') {
    //             $leave->leave_type = Leave_type::where('id', isset($leave->leave_type))->first()['types'];
    //             $leave->approver = User::where('id', isset($leave->approved_by))->first()['fullname'];
    //             $leave->emp_image = asset($leave->emp_image);
    //             $leave->remaining = isset($leave->remaining) ? $leave->remaining : "N/A";
    //             unset($leave->approved_by);
    //         } else {
    //             $leave->leave_type = 'N/A';
    //             $leave->approver = 'N/A';
    //             $leave->emp_image = asset($leave->emp_image);
    //             unset($leave->approved_by);

    //         }
    //     }

    //     $leaveTypes = Leave_type::orderBy('types', 'asc')->paginate(20);
    //     $data = [];
    //     $data['leaves'] = $leaves;
    //     //$data['leave_types'] = $leaveTypes;
    //     if (count($data['leaves']) > 0) {
    //         return $this->sendResponse($data, 'Leave fetched successfully!', 200);
    //     } else {
    //         return $this->sendResponse($data, 'Data not found!', 200);
    //     }
    // }

    public function leaveRequest(Request $request)
    {
        // User information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);
        $searchBy = $request->search_by ?? '';
        $selectedBranch = $request->selectBranch ?? 'all';

        $perPage = $request->per_page ?? 10; // Default to 20 if not specified

        // Base query
        $query = Leave::with(['employee:id,emp_id,emp_name,emp_image,emp_gender', 'location:id,branch_name', 'leaveType:id,types', 'approver:id,fullname'])
            ->select(
                'id',
                'emp_id',
                'branch_id',
                'leave_type',
                'remaining',
                'from_date',
                'to_date',
                'requested_days',
                'is_approved',
                'approved_by',
                'company_id',
                'created_at'
            );

        if ($user_role == '1') {
            if ($selectedBranch != 'all') {
                $query->where('branch_id', $selectedBranch);
            }
        } else {
            $query->whereIn('company_id', $user_company_id);
            if ($selectedBranch != 'all') {
                $query->where('branch_id', $selectedBranch);
            } else {
                $query->whereIn('branch_id', $user_branch_id);
            }
        }

        if ($searchBy != '') {
            $query->where(function ($query) use ($searchBy) {
                $query->whereHas('employee', function ($q) use ($searchBy) {
                    $q->where('emp_name', 'LIKE', '%' . $searchBy . '%')
                        ->orWhere('emp_id', 'LIKE', '%' . $searchBy . '%');
                })
                    ->orWhereHas('leaveType', function ($q) use ($searchBy) {
                        $q->where('types', 'LIKE', '%' . $searchBy . '%');
                    })
                    ->orWhereHas('location', function ($q) use ($searchBy) {
                        $q->where('branch_name', 'LIKE', '%' . $searchBy . '%');
                    });
            });
        }
        $leaves = $query->where('is_deleted', '0')
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        $leavesData = $leaves->map(function ($leave) {
            $userImage = $leave->employee ? $this->imgFunc($leave->employee->emp_image, $leave->employee->emp_gender) : null;
            return [
                'id' => $leave->id,
                'employee_id' => $leave->employee ? $leave->employee->id : null,
                'emp_id' => $leave->employee ? $leave->employee->emp_id : null,
                'emp_name' => $leave->employee ? $leave->employee->emp_name : null,
                'emp_image' => $userImage,
                'branch_name' => $leave->location ? $leave->location->branch_name : null,
                'leave_type' => $leave->leaveType ? $leave->leaveType->types : null,
                'leave_status' => $leave->is_approved,
                'remaining' => $leave->remaining,
                'from_date' => $leave->from_date,
                'to_date' => $leave->to_date,
                'requested_days' => $leave->requested_days,
                'is_approved' => $leave->is_approved == '0' || $leave->is_approved == null ? $this->returnApprovalStatus(12, $leave->id) ?? 'Pending' : ($leave->is_approved == '1' ? 'Approved' : ($leave->is_approved == '2' ? 'Rejected' : 'Unknown')),// pass module id and record id in trait
                'approved_by' => $leave->approver ? $leave->approver->fullname : null,
                'applied_at' => Carbon::parse($leave->created_at)->format('Y-m-d'),

            ];
        });


        $data = [
            'leaves' => $leavesData,
            'pagination' => [
                'total' => $leaves->total(),
                'count' => $leaves->count(),
                'per_page' => $leaves->perPage(),
                'current_page' => $leaves->currentPage(),
                'total_pages' => $leaves->lastPage(),
                'next_page_url' => $leaves->nextPageUrl(),
                'prev_page_url' => $leaves->previousPageUrl()
            ]
        ];

        if (count($data['leaves']) > 0) {
            return $this->sendResponse($data, 'Leave fetched successfully!', 200);
        } else {
            return $this->sendError([], 'Data not found!', 500);
        }
    }


    public function saveLeaveRequest(Request $request)
    {
        try {
            $user = auth()->user();
            $from_date = Carbon::parse($request->from_date);
            $to_date = Carbon::parse($request->to_date);
            $diffInDays = $from_date->diffInDays($to_date) + 1;
            $from_date = $from_date->format('Y-m-d');
            $to_date = $to_date->format('Y-m-d');

            $response = SystemResponse::where('id', 1)->first();
            $validate = Validator::make($request->all(), [
                'company_id' => 'required',
                'branch_id' => 'required',
                'emp_id' => 'required',
                'leave_type' => 'required',
                'leave_status' => 'required',
                'remaining_leaves' => [
                    'required',
                    function ($attribute, $value, $fail) use ($diffInDays, $request, $response) {
                        if ($request->leave_type != 5) {
                            if ($diffInDays > $request->remaining_leaves) {

                                $fail($response->message);
                            }
                        }

                    },
                ],
                'to_date' => 'required',
                'remarks' => 'required',
                'from_date' => [
                    'required',
                    function ($attribute, $value, $fail) use ($from_date, $to_date, $request) {
                        $leaveExists = Leave::where('emp_id', $request->emp_id)
                            ->where(function ($query) use ($from_date, $to_date, $request) {
                                $query->where('from_date', '<=', $to_date)
                                    ->where('to_date', '>=', $from_date);
                            })

                            ->exists();
                        if ($leaveExists) {
                            $fail('Leave already exists for the specified date range.');
                        }
                    },

                ],
            ], [
                'from_date.unique' => 'Leave already exists for the specified date range.',
            ]);

            if ($validate->fails()) {
                return $this->sendResponse([], $validate->errors()->first(), 400);
            }

            if ($request->remaining_leaves <= 0 && $request->leave_type != '5') {
                return $this->sendResponse([], 'Remaining leaves are completed.', 400);
            }

            $empData = EmployeeDetail::find($request->emp_id);
            if (!$empData) {
                return response()->json([
                    'status' => 0,
                    'success' => false,
                    'message' => 'Employee Data not found'
                ]);
            }
            $leave_setting = Leave_setting::where('company_id', $empData->company_id)
                ->where('is_active', '1')
                ->where('is_deleted', '0')->first();
            if (!$leave_setting) {
                return response()->json([
                    'status' => 0,
                    'success' => false,
                    'message' => 'Company Setting not found'
                ]);
            }
            $today = Carbon::today();
            $startDate = Carbon::parse($request->from_date);
            $endDate = Carbon::parse($request->to_date);
            $days = $startDate->diffInDays($today);
            if ($request->leave_type == 2 && $days < $leave_setting->casual_before_days) {
                return response()->json([
                    'status' => 0,
                    'success' => false,
                    'message' => 'You cannot apply casual leave before ' . $leave_setting->casual_before_days . ' days',
                ]);
            }
            if ($request->leave_type == 1 && $days < $leave_setting->annual_before_days) {
                return response()->json([
                    'status' => 0,
                    'success' => false,
                    'message' => 'You cannot apply annual leave before ' . $leave_setting->annual_before_days . ' days',
                ]);
            }

            DB::beginTransaction();
            $difference = $startDate->diffInDays($endDate) + 1;
            $createLaveRequest = Leave::create([
                'company_id' => $request->company_id,
                'branch_id' => $request->branch_id,
                'emp_id' => $request->emp_id,
                'remaining' => $request->remaining_leaves,
                'leave_type' => $request->leave_type,
                'leave_status' => $request->leave_status,
                'requested_days' => $difference,
                'approved_days' => $difference,
                'is_approved' => '0',
                'from_date' => Carbon::parse($request->from_date)->format('Y-m-d'),
                'to_date' => Carbon::parse($request->to_date)->format('Y-m-d'),
                'remarks' => $request->remarks,
                'approved_by' => $request->approved_by,
            ]);
            $employee = EmployeeDetail::where('id', $request->emp_id)->first();
            $getLeaveName = Leave_Type::where('id', $request->leave_type)->first()['types'];
            $module_id = 12;
            $approval_setting = ApprovalSetting::where('module_id', $module_id)->get();
            if (!$approval_setting) {
                $response = SystemResponse::find(7);
                return response()->json([
                    'status' => 0,
                    'success' => false,
                    'click_able' => '1',
                    'message' => $response->message ?? 'No Response Found in Database'
                ]);
            }
            foreach ($approval_setting as $approval) {
                ApprovalStatus::create([
                    'requested_id' => $createLaveRequest->id,
                    'module_id' => $module_id,
                    'emp_id' => $request->emp_id,
                    'approval_level' => $approval->approval_level,
                    'status' => '0'
                ]);
            }
            $data = array();
            $type = "Leave";
            $branch = $request->branch_id;
            $data['emp_name'] = isset($employee->emp_name) ? $employee->emp_name : "N/A";
            $data['from_date'] = $from_date;
            $data['to_date'] = $to_date;
            $data['leave_type'] = $getLeaveName;
            $createNotification = new NotificationController();
            $createNotification->generateNotification($type, $data, $branch);

            $msg = '"' . $user->fullname . '" Added Leave For "' . ucwords(isset($employee->emp_name) ? $employee->emp_name : "N/A") . '" Manually';
            createLog('leave_action', $msg);

            if ($createLaveRequest) {
                DB::commit();
                return $this->sendResponse($createLaveRequest, 'Leave request create successfully!', 200);
            } else {
                return $this->sendResponse($createLaveRequest, 'Leave request not saved!', 500);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendResponse(null, $e->getMessage(), 500);
        }
    }

    public function editLeaveRequest(Request $request)
    {
        $id = $request->leave_id;
        $leave_detail = Leave::where('id', $id)->first();

        if (!$leave_detail) {
            return $this->sendResponse(null, 'Leave request not found!', 404);
        }

        $company = Company::where('id', $leave_detail->company_id)->first();
        $location = Location::where('id', $leave_detail->branch_id)->first();
        $employee = EmployeeDetail::where('id', $leave_detail->emp_id)->first();

        $company_name = $company ? $company->company_name : null;
        $branch_name = $location ? $location->branch_name : null;
        $employee_name = $employee ? $employee->emp_name : null;

        $leave_detail->company_name = $company_name;
        $leave_detail->branch_name = $branch_name;
        $leave_detail->employee_name = $employee_name;

        if ($leave_detail) {
            return $this->sendResponse($leave_detail, 'Leave request fetched successfully!', 200);
        } else {
            return $this->sendResponse($leave_detail, 'Data not found!', 200);
        }
    }

    public function updateLeaveRequest(Request $request)
    {

        $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
        $to_date = Carbon::parse($request->to_date)->format('Y-m-d');
        $startDate = Carbon::parse($request->from_date);
        $endDate = Carbon::parse($request->to_date);
        $newLeaveDays = $startDate->diffInDays($endDate) + 1;
        $validate = Validator::make($request->all(), [
            'id' => 'required',
            'company_id' => 'required',
            'branch_id' => 'required',
            'leave_type' => 'required',
            'leave_status' => 'required',
            'from_date' => 'required|date',
            'to_date' => 'required|date',
            'remarks' => 'required',
            'remaining_leaves' => [
                'required',
                function ($attribute, $value, $fail) use ($newLeaveDays, $request) {
                    if ($newLeaveDays > $request->remaining_leaves) {

                        $fail('You cannot apply more then remaining leaves.');
                    }
                },
            ],
        ]);

        if ($validate->fails()) {
            return $this->sendError([], $validate->errors(), 400);
        }

        $data = Leave::find($request->id);
        if (!$data) {
            return $this->sendError([], 'Data not found!', 404);
        }

        // Calculate the original leave days
        $originalStartDate = Carbon::parse($data->from_date);
        $originalEndDate = Carbon::parse($data->to_date);
        $originalLeaveDays = $originalStartDate->diffInDays($originalEndDate) + 1;

        $from_date_daily = new DateTime($data->from_date);
        $to_date_daily = new DateTime($data->to_date);



        if ($data->is_approved == '1') {
            while ($from_date_daily <= $to_date_daily) {
                $user_daily_reco = UserDailyRecord::where('emp_id', $data->emp_id)->where('dated', $from_date_daily)->first();
                if ($user_daily_reco) {
                    $user_daily_reco->delete();
                }
                $from_date_daily->modify('+1 day');
            }
            if ($newLeaveDays > $originalLeaveDays) {
                $differenceInDays = $newLeaveDays - $originalLeaveDays;
                $remaining_leaves_in_table = $request->remaining_leaves - $differenceInDays;
            } else {
                $differenceInDays = $originalLeaveDays - $newLeaveDays;
                $remaining_leaves_in_table = $request->remaining_leaves + $differenceInDays;
            }
        } else {
            $remaining_leaves_in_table = $request->remaining_leaves;
        }

        $f_date = new DateTime($from_date);
        $t_date = new DateTime($to_date);
        while ($f_date <= $t_date) {
            UserDailyRecord::updateOrCreate([
                'emp_id' => $data->emp_id,
                'dated' => $f_date->format('Y-m-d'),
                'check_in' => null,
                'check_out' => null,
            ], [
                'emp_id' => $data->emp_id,
                'dated' => $f_date->format('Y-m-d'),
                'check_in' => null,
                'check_out' => null,
                'present' => '0',
                'leave' => $request->leave_status,
                'pull_time' => null,
                'leave_type' => $data->leave_type,
                'working_hours' => 0,
                'device_serial_no' => null,
                'check_in_type' => null,
                'check_out_type' => null,
                'check_in_ip' => null,
                'check_out_ip' => null,
            ]);
            $f_date->modify('+1 day');
        }

        $data->update([
            'company_id' => $request->company_id,
            'branch_id' => $request->branch_id,
            'remaining' => $remaining_leaves_in_table,
            'requested_days' => $newLeaveDays,
            'approved_days' => $newLeaveDays,
            'leave_type' => $request->leave_type,
            'leave_status' => $request->leave_status,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'remarks' => $request->remarks,
        ]);

        return $this->sendResponse($data, 'Leave Request updated successfully!', 200);
    }

    public function getRemainingLeaves(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'emp_id' => 'required',
            'company_id' => 'required',
            'leave_type' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError([], $validator->errors(), 400);
        }
        $emp_id = $request->emp_id;
        $company_id = $request->company_id;
        $leave_type = $request->leave_type;
        $leave_settings = Leave_setting::where('company_id', $company_id)
            ->where('is_active', '1')
            ->where('is_deleted', '0')
            ->first();

        if ($leave_type == '1') {
            $numberOfDays = isset($leave_settings->annual_days) ? $leave_settings->annual_days : null;
        } elseif ($leave_type == '2') {
            $numberOfDays = isset($leave_settings->casual_days) ? $leave_settings->casual_days : null;
        } elseif ($leave_type == '3') {
            $numberOfDays = isset($leave_settings->sick_days) ? $leave_settings->sick_days : null;
        } elseif ($leave_type == '4') {
            $numberOfDays = isset($leave_settings->maternity_days) ? $leave_settings->maternity_days : null;
        } else {
            $numberOfDays = 0;
        }
        $total_leaves = Leave::where('is_approved', '1')
            ->where('leave_type', $leave_type)
            ->where('is_deleted', '0')
            ->where('company_id', $leave_settings->company_id)
            ->where('emp_id', $emp_id)
            ->whereYear('created_at', Carbon::now()->year)
            ->get();
        $leaveSum = 0;
        foreach ($total_leaves as $leave) {
            $leaveSum += $leave->approved_days;
        }
        if ($leaveSum == 0 || $leave_type == 5) {
            $remainingLeaves = $numberOfDays;
        } else {
            $remainingLeaves = $numberOfDays - $leaveSum;
        }

        $leave = Leave_Type::where('id', $leave_type)->first();
        $leave_title = $leave->types;
        $data['leaveTitle'] = $leave_title;
        $data['remainingLeaves'] = $remainingLeaves;
        if ($data) {
            return $this->sendResponse($data, 'All remaining leaves fetched successfully!', 200);
        } else {
            return $this->sendResponse($data, 'Remaining leaves are completed!', 200);
        }
    }

    public function getAllRemainingLeaves(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'emp_id' => 'required',
            'company_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError([], $validator->errors(), 400);
        }
        $emp_id = $request->emp_id;
        $company_id = $request->company_id;
        $leave_settings = Leave_setting::where('company_id', $company_id)
            ->where('is_active', '1')
            ->where('is_deleted', '0')
            ->first();

        $annualDays = isset($leave_settings->annual_days) ? $leave_settings->annual_days : null;

        $totalleaveSum = Leave::where('is_approved', '1')
            ->where('company_id', isset($leave_settings->company_id))
            ->where('emp_id', $emp_id)
            ->sum('approved_days');

        $totalremainingLeaves = $annualDays - $totalleaveSum;

        if ($totalremainingLeaves) {
            return $this->sendResponse($totalremainingLeaves, 'Total remaining leaves', 200);
        } else {
            return $this->sendResponse($totalremainingLeaves, 'Remaining leaves are completed!', 200);
        }
    }

    public function updateLeaveStatus(Request $request)
    {
        try {
            DB::beginTransaction();
            $module_id = 12;
            $id = $request->id;
            $status = $request->status;
            $user = Auth::user();
            $leave = Leave::where('id', $id)->first();

            if ($leave) {
                $from_date = new DateTime($leave->from_date);
                $to_date = new DateTime($leave->to_date);

                $startDate = Carbon::parse($leave->from_date);
                $endDate = Carbon::parse($leave->to_date);
                $difference = $startDate->diffInDays($endDate) + 1;
                $employee = EmployeeDetail::where('id', $leave->emp_id)->first();
                $emp_name = ucwords($employee->emp_name);
                $approval_status = $this->getApprovalStatusId($module_id, $id);

                if ($status == '1') {
                    if ($approval_status) {
                        $status_data = ApprovalStatus::find($approval_status->id);
                        $status_data->status = '1';
                        $status_data->save();
                    }
                    $check_all_status = ApprovalStatus::where('requested_id', $id)->where('module_id', $module_id)->get();
                    if ($check_all_status->isNotEmpty()) {
                        $is_all_approved =  ApprovalStatus::where('requested_id', $id)->where('status', '!=', '0')->where('module_id', $module_id)->count();
                       if($is_all_approved > 0){
                        $this->enterLeaveInDailyRecord($user,$leave, $from_date, $to_date, $difference, $status);
                       }
                    }else{
                        $this->enterLeaveInDailyRecord($user,$leave, $from_date, $to_date, $difference, $status);
                    }
                    $msg = '"' . $emp_name . '" Leave Approved From "' . $user->fullname . '"';
                    createLog('leave_action', $msg);
                    DB::commit();
                    return $this->sendResponse($leave, 'Leave approved successfully!', 200);

                } elseif ($status == '2') {
                    if ($approval_status) {
                        $status_data = ApprovalStatus::find($approval_status->id);
                        $status_data->status = '2';
                        $status_data->save();
                    }
                    $check_all_status = ApprovalStatus::where('requested_id', $id)->where('module_id', $module_id)->get();
                    if ($check_all_status->isNotEmpty()) {
                        $is_all_rejected =  ApprovalStatus::where('requested_id', $id)->where('status', '!=', '0')->where('module_id', $module_id)->count();
                       if($is_all_rejected > 0){
                    $this->enterLeaveInDailyRecord($user,$leave, $from_date, $to_date, $difference, $status);
                       }
                    }else{
                        $this->enterLeaveInDailyRecord($user,$leave, $from_date, $to_date, $difference, $status);
                    }
                    $msg = '"' . $emp_name . '" Leave Rejected From "' . $user->fullname . '"';
                    createLog('leave_action', $msg);
                    DB::commit();
                    return $this->sendResponse($leave, 'Leave Rejected successfully!', 200);
                

                } elseif ($status == '3') {
                    if ($approval_status) {
                     $check = $this->bypassAllApprovals($module_id, $id, '1', $approval_status->approval_level);
                       if($check){
                        $leave->is_approved = '1';
                        $leave->update();
                       }
                    }
                   $msg = '"' . $emp_name . '" Leave Approved (ByPass All) From "' . $user->fullname . '"';
                    createLog('leave_action', $msg);
                    DB::commit();
                    return $this->sendResponse($leave, 'Leave Approved successfully!', 200);
                
                } elseif ($status == '4') {
                    if ($approval_status) {
                        $check = $this->bypassAllApprovals($module_id, $id, '2', $approval_status->approval_level);
                        if($check){
                            $leave->is_approved = '2';
                        $leave->update();
                        }
                    }
                    $msg = '"' . $emp_name . '" Leave Rejected (ByPass All) From "' . $user->fullname . '"';
                    createLog('leave_action', $msg);
                    DB::commit();
                    return $this->sendResponse($leave, 'Leave Rejected successfully!', 200);
                }    
            } else {
                return $this->sendError([], 'Leave status not updated!', 400);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendResponse($e->getMessage(), 500);
        }
    }


    // enter leave in daily record 
    private function enterLeaveInDailyRecord($user,$leave, $from_date, $to_date, $difference, $status){
        while ($from_date <= $to_date) {
            UserDailyRecord::updateOrCreate(
                [
                    'emp_id' => $leave->emp_id,
                    'dated' => $from_date->format('Y-m-d')
                ],
                [
                    'check_in' => null,
                    'check_out' => null,
                    'present' => '0',
                    'leave' => $leave->leave_status,
                    'pull_time' => null,
                    'leave_type' => $leave->leave_type,
                    'working_hours' => 0,
                    'device_serial_no' => null,
                    'check_in_type' => null,
                    'check_out_type' => null,
                    'check_in_ip' => null,
                    'check_out_ip' => null,
                ]
            );
            $from_date->modify('+1 day');
        }
        if ($leave->leave_type == '5') {
            $leave->remaining = 0;
        } else {
            $leave->remaining -= $difference;
        }
        $leave->is_approved = $status;
        $leave->approved_by = $user->role_id;
        $leave->approved_days = $difference;
        $leave->update();
    }

    public function destroyLeaveRequest(Request $request)
    {
        try {
            DB::beginTransaction();
            $id = $request->id;
            $leave = Leave::findOrFail($id);
            if ($leave) {
                $from_date = new DateTime($leave->from_date);
                $to_date = new DateTime($leave->to_date);
                while ($from_date <= $to_date) {
                    $user_data = UserDailyRecord::where('emp_id', $leave->emp_id)->where('dated', $from_date->format('Y-m-d'))->where('leave_type', $leave->leave_type)->first();
                    if ($user_data) {
                        $user_data->delete();
                    }
                    $from_date->modify('+1 day');
                }
                // ApprovalStatus::where('requested_id',$leave->id)->delete();
                $leave->delete();
            }
            DB::commit();
            $employee = EmployeeDetail::where('id', $leave->emp_id)->first();
            $msg = 'for "' . ucwords($employee->emp_name) . '" Deleted Successfully';
            createLog('leave_action', $msg);
            if ($leave) {
                return $this->sendResponse([], 'Leave deleted successfully!', 200);
            } else {
                return $this->sendResponse([], 'Data not found!', 200);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendResponse([], $e->getMessage(), 500);
        }
    }

    // public function leavesearch(Request $request)
    // {
    //     // return $request->all();
    //     $validator = Validator::make($request->all(), [
    //         'searchValue' => 'required',
    //         'selectBranch' => 'required',
    //     ]);
    //     if ($validator) {
    //         return $this->sendError([], $validator->errors(), 400);
    //     }
    //     $searchValue = strtolower($request->input('searchValue'));
    //     $selectBranch = $request->input('selectBranch');
    //     if (isset($selectBranch) && $selectBranch != null) {
    //         $fetchData = Leave::where('emp_leaves.branch_id', $selectBranch)
    //             ->orderBy('emp_leaves.id', 'asc')
    //             ->leftJoin('locations', 'emp_leaves.branch_id', '=', 'locations.id')
    //             ->leftJoin('employee_details', 'emp_leaves.emp_id', '=', 'employee_details.id')
    //             ->select('employee_details.emp_id', 'employee_details.emp_name', 'emp_leaves.leave_type', 'emp_leaves.id', 'emp_leaves.from_date', 'emp_leaves.to_date')
    //             ->get();
    //     } else {
    //         $fetchData = Leave::where(function ($query) use ($searchValue, $selectBranch) {
    //             $query->where('emp_leaves.is_deleted', '0')
    //                 ->where(function ($query) use ($searchValue, $selectBranch) {
    //                     $query->whereRaw('LOWER(leave_types.types) LIKE ?', ['%' . $searchValue . '%'])
    //                         ->orWhereHas('employee', function ($query) use ($searchValue) {
    //                             $query->whereRaw('LOWER(employee_details.emp_name) LIKE ?', ['%' . $searchValue . '%'])
    //                                 ->orWhereRaw('LOWER(employee_details.emp_id) LIKE ?', ['%' . $searchValue . '%']);
    //                         })
    //                         ->orWhereHas('branch', function ($query) use ($searchValue, $selectBranch) {
    //                             $query->whereRaw('LOWER(locations.branch_name) LIKE ?', ['%' . $searchValue . '%']);
    //                         });
    //                 });
    //         })
    //             ->orderBy('emp_leaves.id', 'asc')
    //             ->leftJoin('locations', 'emp_leaves.branch_id', '=', 'locations.id')
    //             ->leftJoin('employee_details', 'emp_leaves.emp_id', '=', 'employee_details.id')
    //             ->select('employee_details.emp_id', 'employee_details.emp_name', 'emp_leaves.leave_type', 'emp_leaves.id', 'emp_leaves.from_date', 'emp_leaves.to_date')
    //             ->get();
    //     }
    //     if ($fetchData->count() > 0) {
    //         return $this->sendResponse($fetchData, 'Leave searched successfully!', 200);
    //     } else {
    //         return $this->sendResponse($fetchData, 'Data not found!', 200);
    //     }
    // }

    public function leaveTypes(Request $request)
    {
        $empData = EmployeeDetail::find($request->emp_id);
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

        $leaveTypes = $query->select('id', 'types', 'type_index')->get();

        if ($leaveTypes->isNotEmpty()) {
            return $this->sendResponse($leaveTypes, 'Leave types fetched successfully!', 200);
        } else {
            return $this->sendError([], 'Data not found!', 200);
        }
    }





    public function saveLeaveTypes(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'types' => 'required|unique:leave_types,types',
            'type_index' => 'required'
        ]);
        if ($validate->fails()) {
            return $this->sendError([], $validate->errors(), 400);
        } else {
            $saveLeaveType = new Leave_Type();
            $saveLeaveType->types = $request->types;
            $saveLeaveType->type_index = $request->type_index;
            $saveLeaveType->save();
            if ($saveLeaveType) {
                return $this->sendResponse($saveLeaveType, 'Leave type save successfully!', 200);
            } else {
                return $this->sendError([], 'Leave type not save successfully!', 409);
            }
        }
    }
    //
    public function leaveStatus(Request $request)
    {
        $user = Auth::user();
        $per_page = $request->per_page ?? 10;
        $leave_types = Leave_Type::all();

        if ($user->role_id == 1) {
            // For role_id 1, only filter by branch_id if provided
            $employees = EmployeeDetail::leftJoin('companies', 'companies.id', '=', 'employee_details.company_id')
                ->leftJoin('locations', 'locations.id', '=', 'employee_details.branch_id')
                ->select('employee_details.*', 'locations.branch_name', 'companies.company_name')
                ->where('employee_details.is_deleted', '0')
                ->where('employee_details.is_active', '1')
                ->where('employee_details.status', '1');

            if ($request->branch_id) {
                $employees->where('branch_id', $request->branch_id);
            }

        } else {

            $user_company_ids = explode(',', $user->company_id);
            $user_branch_ids = explode(',', $user->branch_id);

            $employees = EmployeeDetail::leftJoin('companies', 'companies.id', '=', 'employee_details.company_id')
                ->leftJoin('locations', 'locations.id', '=', 'employee_details.branch_id')
                ->select('employee_details.*', 'locations.branch_name', 'companies.company_name')
                ->where('employee_details.is_deleted', '0')
                ->where('employee_details.is_active', '1')
                ->where('employee_details.status', '1')
                ->whereIn('employee_details.company_id', $user_company_ids)
                ->whereIn('employee_details.branch_id', $user_branch_ids);

            if ($request->branch_id) {
                $employees->where('branch_id', $request->branch_id);
            }
        }

        if ($request->search_by) {
            $employees->where(function ($query) use ($request) {
                $query->where('emp_name', 'like', '%' . $request->search_by . '%')
                    ->orWhere('emp_id', 'like', '%' . $request->search_by . '%');
            });
        }

        $employees = $employees->get();

        if ($request->location_id) {
            $employees = $employees->filter(function ($employee) use ($request) {
                return $employee->branch_id == $request->location_id;
            });
        }

        if ($employees->isEmpty()) {
            return response()->json([
                'status' => 0,
                'success' => false,
                'message' => 'No employees found with the specified filters.',
                'data' => []
            ]);
        }

        $results = [];
        foreach ($employees as $employee) {
            $leaveData = [
                'id' => $employee->id,
                'emp_id' => $employee->emp_id,
                'emp_name' => $employee->emp_name,
                'emp_image' => $this->imgFunc($employee->emp_image, $employee->emp_gender),
                'company_name' => $employee->company_name,
                'location_name' => $employee->branch_name,
                'leaves' => []
            ];
            $total_leaves = 0;
            foreach ($leave_types as $type) {
                $leaveQuery = Leave::where('emp_id', $employee->id)
                    ->where('leave_type', $type->id)
                    ->where('is_approved', '1');

                if ($request->date) {
                    $formattedDate = Carbon::parse($request->date)->format('Y-m-d');
                    $leaveQuery->whereDate('from_date', '<=', $formattedDate)
                        ->whereDate('to_date', '>=', $formattedDate);
                } else {
                    $currentYear = Carbon::now()->year;
                    $leaveQuery->whereYear('from_date', '=', $currentYear);
                }

                $totalApprovedDays = $leaveQuery->sum('approved_days');

                $leaveData['leaves'][] = [
                    'leave_id' => $type->id ?: null,
                    'leave_name' => $type->types ?: null,
                    'count' => $totalApprovedDays ?: '0'
                ];

                if ($type->id != 5) {
                    $total_leaves += $totalApprovedDays;
                }
            }
            $leaveData['total_leaves'] = $total_leaves;
            $results[] = $leaveData;
        }

        if (empty($results)) {
            return response()->json([
                'status' => 0,
                'success' => false,
                'message' => 'No leave data found for the specified filters.',
                'data' => []
            ]);
        }

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect($results);
        $currentPageResults = $collection->slice(($currentPage - 1) * $per_page, $per_page)->values()->all();
        $paginatedResults = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);
        $paginatedResults->setPath($request->url());

        return response()->json([
            'status' => 1,
            'success' => true,
            'message' => 'Leaves fetched successfully',
            'data' => array_values($paginatedResults->items()),
            'current_page' => $paginatedResults->currentPage(),
            'next_page_url' => $paginatedResults->nextPageUrl(),
            'path' => $paginatedResults->path(),
            'per_page' => $paginatedResults->perPage(),
            'prev_page_url' => $paginatedResults->previousPageUrl(),
            'to' => $paginatedResults->count() > 0 ? $paginatedResults->lastItem() : null,
            'total' => $paginatedResults->total(),
            'total_pages' => $paginatedResults->lastPage(),
        ]);
    }

}

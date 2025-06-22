<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApprovalSetting;
use App\Models\ApprovalStatus;
use App\Models\Designation;
use App\Models\EmployeeDetail;
use App\Models\SystemResponse;
use App\Models\user_approval;
use DB;
use Exception;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{

    // fetch employees for approval level

    public function getEmpForApproval(Request $request)
    {
        $user = auth()->user();
        $company_ids = explode(',', $user->company_id);
        $branch_ids = explode(',', $user->branch_id);
        $query = EmployeeDetail::query();
        $query->select('id', 'emp_name as name')
        ->where('status', '1')->where('is_active', '1')->where('is_deleted', '0');
       if($user->role_id != '1'){
        $query->whereIn('company_id', $company_ids)->whereIn('branch_id', $branch_ids);
    }
        if ($request->search) {
            $query->where('emp_name', 'like', '%' . $request->search . '%');
        }
        $employees = $query->orderBy('emp_name')->get()->toArray();
        $employees = array_map(function ($employee) {
            $employee['type'] = 'employee';
            return $employee;
        }, $employees);
        $desig = Designation::query();
        $desig->select('id', 'name');
        if ($request->search) {
            $desig->where('name', 'like', '%' . $request->search . '%');
        }
        $designations = $desig->orderBy('name')->get()->toArray();
        $designations = array_map(function ($designation) {
            $designation['type'] = 'designation';
            return $designation;
        }, $designations);
        $line_manager = [
            'id' => 0,
            'name' => 'Line Manager',
            'type' => 'line_manager'
        ];
        $data = array_merge($employees, $designations);
        array_push($data, $line_manager);
        return response()->json([
            'status' => 1,
            'success' => true,
            'data' => $data
        ]);
    }


    // get approval setting
    public function getApprovalSetting(Request $request)
    {
        $approval_setting = ApprovalSetting::where('module_id', $request->module_id)->get();
        if (!$approval_setting) {
            $response = SystemResponse::find(9);
            return response()->json([
                'status' => 0,
                'success' => false,
                'message' => $response->message ?? 'No Response Found in Database'
            ]);
        }
        $bypass_check = ApprovalSetting::where('module_id', $request->module_id)->where('bypass_approval', '1')->count();
        $records = [];
        if ($bypass_check) {
            $records['bypass_approval'] = '1';
        } else {
            $records['bypass_approval'] = '0';
        }
        $data = [];
        $approval_setting = $approval_setting->map(function ($setting) use (&$data) {
            if ($setting->selected_type == "employee") {
                $record = [
                    'id' => $setting->selected_id,
                    'name' => $setting->approvalSettingToEmp->emp_name,
                    'type' => "employee"
                ];
                array_push($data, $record);
            } elseif ($setting->selected_type == "designation") {
                $record = [
                    'id' => $setting->selected_id,
                    'name' => $setting->approvalSettingToDesignation->name,
                    'type' => "designation"
                ];
                array_push($data, $record);
            } elseif ($setting->selected_type == "line_manager") {
                $record = [
                    'id' => 0,
                    'name' => "Line Manager",
                    'type' => "line_manager"
                ];
                array_push($data, $record);
            }
        });
        $records['data'] = $data;
        return response()->json([
            'status' => 1,
            'success' => true,
            'data' => $records
        ]);
    }
    // save approval levels
    public function saveApprovalLevels(Request $request)
    {
        try {
            DB::beginTransaction();

            $check_approval_status = ApprovalStatus::where('module_id', $request->module_id)
                ->where('approval_level', '1')->where('status', '0')->count();
            if ($check_approval_status > 0) {
                $response = SystemResponse::find(6);
                return response()->json([
                    'status' => 0,
                    'success' => false,
                    'message' => $response->message ?? 'No Response Found in Database'
                ]);
            }
            ApprovalSetting::where('module_id', $request->module_id)->delete();

            foreach ($request->levels[0] as $key => $level) {
                $lastLevel = ($key == array_key_last($request->levels[0]));
                $data = [
                    'module_id' => $request->module_id,
                    'selected_id' => $level['id'],
                    'selected_type' => $level['type'],
                    'approval_level' => intval($key),
                    'bypass_approval' => $request->bypass_approval,
                ];
                if ($lastLevel) {
                    $data['bypass_approval'] = '0';
                }
                ApprovalSetting::create($data);
            }

            DB::commit();
            $response = SystemResponse::find(5);
            return response()->json([
                'status' => 1,
                'success' => true,
                'message' => $response->message ?? 'No Response Found in Database'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 0,
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // get approval status
    public function getApprovalStatus(Request $request)
    {
        $user = auth()->user();
        $my_detail = EmployeeDetail::find($user->emp_id);
        $data = [];
        $emp_level = ApprovalSetting::where('module_id', $request->module_id)->where('selected_id', $user->emp_id)->where('selected_type', 'employee')->first();
        if (!$emp_level) {
            $designation_id = $user->userToEmp->approval->designation->id ?? null;
            if ($designation_id) {
                $designation_level = ApprovalSetting::where('module_id', $request->module_id)->where('selected_id', $designation_id)->where('selected_type', 'designation')->first();
                if (!$designation_level) {
                    $line_manager = user_approval::where('report_to', $user->emp_id)->where('emp_id', $request->emp_id)->where('is_active', '1')->where('is_deleted', '0')->first();
                    if ($line_manager) {
                        $line_manager_level = ApprovalSetting::where('module_id', $request->module_id)->where('selected_id', 0)->where('selected_type', 'line_manager')->first();
                        if ($line_manager_level) {
                            $approval_status = ApprovalStatus::where('requested_id', $request->id)->where('approval_level', $line_manager_level->approval_level)->first();
                            if ($approval_status) {
                                $shift_level = $approval_status->approval_level + 1;
                                $below_level = ApprovalStatus::where('approval_level', $shift_level)->where('requested_id', $request->id)->first();
                                if ($below_level) {
                                    $emp_data = ApprovalSetting::where('module_id', $request->module_id)->where('approval_level', $below_level->approval_level)->first();
                                    if ($emp_data->selected_type == 'designation') {
                                        $emp_approval = user_approval::where('designation_id', $emp_data->selected_id)
                                            ->pluck('emp_id')
                                            ->toArray();
                                        $employeeData = EmployeeDetail::whereIn('id', $emp_approval)
                                            ->where('is_active', '1')->where('is_deleted', '0')
                                            ->where('status', '1')->where('branch_id', $my_detail->branch_id)->first();
                                    } elseif ($emp_data->selected_type == 'employee') {
                                        $employeeData = EmployeeDetail::find($emp_data->selected_id);
                                    } elseif ($emp_data->selected_type == 'line_manager') {
                                        $manager_id = user_approval::select('report_to')->where('emp_id', $request->emp_id)->first();
                                        if ($manager_id) {
                                            $employeeData = EmployeeDetail::find($manager_id->report_to);
                                        }
                                    }

                                    $data['below_level'] = $below_level->approval_level;
                                    $data['below_level_name'] = $employeeData->emp_name ?? null;
                                    $data['below_level_designation'] = $employeeData->approval->designation->name ?? null;
                                    $data['below_level_status'] = $below_level->status;
                                }
                                $data['status'] = $approval_status->status;
                                $data['bypass_approval'] = $line_manager_level->bypass_approval;
                                $data['click_able'] = '1';
                                return response()->json([
                                    'status' => 1,
                                    'success' => true,
                                    'data' => $data
                                ]);
                            } else {
                                $response = SystemResponse::find(7);
                                $data['click_able'] = '1';
                                return response()->json([
                                    'status' => 1,
                                    'success' => true,
                                    'data' => $data,
                                    'message' => $response->message ?? 'No Response Found in Database'
                                ]);
                            }
                        } else {
                            $data['click_able'] = '0';
                            return response()->json([
                                'data' => $data
                            ]);
                        }
                    } else {
                        $data['click_able'] = '0';
                        return response()->json([
                            'data' => $data
                        ]);
                    }
                } else {
                    $approval_status = ApprovalStatus::where('requested_id', $request->id)->where('approval_level', $designation_level->approval_level)->first();
                    if ($approval_status) {
                        $shift_level = $approval_status->approval_level + 1;
                        $below_level = ApprovalStatus::where('approval_level', $shift_level)->where('requested_id', $request->id)->first();
                        if ($below_level) {
                            $emp_data = ApprovalSetting::where('module_id', $request->module_id)->where('approval_level', $below_level->approval_level)->first();
                            if ($emp_data->selected_type == 'designation') {
                                $emp_approval = user_approval::where('designation_id', $emp_data->selected_id)
                                    ->pluck('emp_id')
                                    ->toArray();
                                $employeeData = EmployeeDetail::whereIn('id', $emp_approval)
                                    ->where('is_active', '1')->where('is_deleted', '0')
                                    ->where('status', '1')->where('branch_id', $my_detail->branch_id)->first();
                            } elseif ($emp_data->selected_type == 'employee') {
                                $employeeData = EmployeeDetail::find($emp_data->selected_id);
                            } elseif ($emp_data->selected_type == 'line_manager') {


                                $manager_id = user_approval::select('report_to')->where('emp_id', $request->emp_id)->first();
                                if ($manager_id) {
                                    $employeeData = EmployeeDetail::find($manager_id->report_to);
                                }
                            }

                            $data['below_level'] = $below_level->approval_level;
                            $data['below_level_name'] = $employeeData->emp_name ?? null;
                            $data['below_level_designation'] = $employeeData->approval->designation->name ?? null;
                            $data['below_level_status'] = $below_level->status;
                        }
                        $data['status'] = $approval_status->status;
                        $data['bypass_approval'] = $designation_level->bypass_approval;
                        $data['click_able'] = '1';
                        return response()->json([
                            'status' => 1,
                            'success' => true,
                            'data' => $data
                        ]);
                    } else {
                        $response = SystemResponse::find(7);
                        $data['click_able'] = '1';
                        return response()->json([
                            'status' => 1,
                            'success' => true,
                            'data' => $data,
                            'message' => $response->message ?? 'No Response Found in Database'
                        ]);
                    }
                }
            } else {
                $response = SystemResponse::find(8);
                return response()->json([
                    'status' => '0',
                    'success' => false,
                    'message' => $response->message ?? 'No Response Found in Database'
                ]);
            }
        } else {
            $approval_status = ApprovalStatus::where('requested_id', $request->id)->where('approval_level', $emp_level->approval_level)->first();
            if ($approval_status) {
                $shift_level = $approval_status->approval_level + 1;
                $below_level = ApprovalStatus::where('approval_level', $shift_level)->where('requested_id', $request->id)->first();
                if ($below_level) {
                    $emp_data = ApprovalSetting::where('module_id', $request->module_id)->where('approval_level', $below_level->approval_level)->first();

                    if ($emp_data->selected_type == 'designation') {
                        $emp_approval = user_approval::where('designation_id', $emp_data->selected_id)
                            ->pluck('emp_id')
                            ->toArray();
                        $employeeData = EmployeeDetail::whereIn('id', $emp_approval)
                            ->where('is_active', '1')->where('is_deleted', '0')
                            ->where('status', '1')->where('branch_id', $my_detail->branch_id)->first();
                    } elseif ($emp_data->selected_type == 'employee') {
                        $employeeData = EmployeeDetail::find($emp_data->selected_id);
                    } elseif ($emp_data->selected_type == 'line_manager') {


                        $manager_id = user_approval::select('report_to')->where('emp_id', $request->emp_id)->first();
                        if ($manager_id) {
                            $employeeData = EmployeeDetail::find($manager_id->report_to);
                        }
                    }
                    $data['below_level'] = $below_level->approval_level;
                    $data['below_level_name'] = $employeeData->emp_name ?? null;
                    $data['below_level_designation'] = $employeeData->approval->designation->name ?? null;
                    $data['below_level_status'] = $below_level->status;
                }
                $data['status'] = $approval_status->status;
                $data['bypass_approval'] = $emp_level->bypass_approval;
                $data['click_able'] = '1';
                return response()->json([
                    'status' => 1,
                    'success' => true,
                    'data' => $data
                ]);
            } else {
                $response = SystemResponse::find(7);
                        $data['click_able'] = '1';
                        return response()->json([
                            'status' => 1,
                            'success' => true,
                            'data' => $data,
                            'message' => $response->message ?? 'No Response Found in Database'
                        ]);
            }
        }

    }
}

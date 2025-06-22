<?php
namespace App\Traits;

use App\Models\ApprovalSetting;
use App\Models\ApprovalStatus;
use App\Models\EmployeeDetail;
use App\Models\SystemResponse;
use App\Models\user_approval;
use DB;
use Exception;
use Request;
trait CheckApprovalStatus
{
    public function returnApprovalStatus($module_id, $id)
    { //pass module id and record id

        $user = auth()->user();
        $my_detail = EmployeeDetail::find($user->emp_id);
        $data = [];
        $emp_level = ApprovalSetting::where('module_id', $module_id)->where('selected_id', $user->emp_id)->where('selected_type', 'employee')->first();
        if (!$emp_level) {
            $designation_id = $user->userToEmp->approval->designation->id ?? null;
            if ($designation_id) {
                $designation_level = ApprovalSetting::where('module_id', $module_id)->where('selected_id', $designation_id)->where('selected_type', 'designation')->first();
                if (!$designation_level) {
                    $line_manager = user_approval::where('report_to', $user->emp_id)->where('is_active', '1')->where('is_deleted', '0')->get();
                    if ($line_manager) {
                        $line_manager_level = ApprovalSetting::where('module_id', $module_id)->where('selected_id', 0)->where('selected_type', 'line_manager')->first();
                        if ($line_manager_level) {
                            $approval_status = ApprovalStatus::where('requested_id', $id)->where('approval_level', $line_manager_level->approval_level)->first();
                            if ($approval_status) {
                                $shift_level = $approval_status->approval_level + 1;
                                $below_level = ApprovalStatus::where('approval_level', $shift_level)->where('requested_id', $id)->first();
                                if ($below_level) {
                                    $emp_data = ApprovalSetting::where('module_id', $module_id)->where('approval_level', $below_level->approval_level)->first();
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


                                        $manager_id = user_approval::select('report_to')->where('emp_id', $below_level->emp_id)->first();
                                        if ($manager_id) {
                                            $employeeData = EmployeeDetail::find($manager_id->report_to);
                                        }
                                    }

                                    if ($approval_status->status == '0') {
                                        if ($below_level->status == '0') {
                                            return "Level 0" . $below_level->approval_level . " Pending";
                                        } elseif ($below_level->status == '1') {
                                            return "Level 0" . $below_level->approval_level . " Approved";
                                        } elseif ($below_level->status == '2') {
                                            return "Level 0" . $below_level->approval_level . " Rejected";
                                        }
                                    } else {
                                        if ($approval_status->status == '1') {
                                            return 'Approved';
                                        } elseif ($approval_status->status == '2') {
                                            return 'Rejected';
                                        }
                                    }
                                } else {
                                    if ($approval_status->status == '0') {
                                        return 'Pending';
                                    } elseif ($approval_status->status == '1') {
                                        return 'Approved';
                                    } elseif ($approval_status->status == '2') {
                                        return 'Rejected';
                                    }
                                }
                            } else {
                                return null;
                            }
                        } else {
                            return 'LM Not Found';
                        }
                    } else {
                        return 'LM Not Found';
                    }
                } else {
                    $approval_status = ApprovalStatus::where('requested_id', $id)->where('approval_level', $designation_level->approval_level)->first();
                    if ($approval_status) {
                        $shift_level = $approval_status->approval_level + 1;
                        $below_level = ApprovalStatus::where('approval_level', $shift_level)->where('requested_id', $id)->first();
                        if ($below_level) {
                            $emp_data = ApprovalSetting::where('module_id', $module_id)->where('approval_level', $below_level->approval_level)->first();

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


                                $manager_id = user_approval::select('report_to')->where('emp_id', $below_level->emp_id)->first();
                                if ($manager_id) {
                                    $employeeData = EmployeeDetail::find($manager_id->report_to);
                                }
                            }
                            if ($approval_status->status == '0') {
                                if ($below_level->status == '0') {
                                    return "Level 0" . $below_level->approval_level . " Pending";
                                } elseif ($below_level->status == '1') {
                                    return "Level 0" . $below_level->approval_level . " Approved";
                                } elseif ($below_level->status == '2') {
                                    return "Level 0" . $below_level->approval_level . " Rejected";
                                }
                            } else {
                                if ($approval_status->status == '1') {
                                    return 'Approved';
                                } elseif ($approval_status->status == '2') {
                                    return 'Rejected';
                                }
                            }
                        } else {
                            if ($approval_status->status == '0') {
                                return 'Pending';
                            } elseif ($approval_status->status == '1') {
                                return 'Approved';
                            } elseif ($approval_status->status == '2') {
                                return 'Rejected';
                            }
                        }
                    } else {
                        return null;
                    }
                }
            } 
            // else {
            //     $response = SystemResponse::find(8);
            //     return response()->json([
            //         'status' => '0',
            //         'success' => false,
            //         'message' => $response->message ?? 'No Response Found in Database'
            //     ]);
            // }
        } else {
            $approval_status = ApprovalStatus::where('requested_id', $id)->where('approval_level', $emp_level->approval_level)->first();
            if ($approval_status) {
                $shift_level = $approval_status->approval_level + 1;
                $below_level = ApprovalStatus::where('approval_level', $shift_level)->where('requested_id', $id)->first();
                if ($below_level) {
                    $emp_data = ApprovalSetting::where('module_id', $module_id)->where('approval_level', $below_level->approval_level)->first();

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


                        $manager_id = user_approval::select('report_to')->where('emp_id', $below_level->emp_id)->first();
                        if ($manager_id) {
                            $employeeData = EmployeeDetail::find($manager_id->report_to);
                        }
                    }
                    if ($approval_status->status == '0') {
                        if ($below_level->status == '0') {
                            return "Level 0" . $below_level->approval_level . " Pending";
                        } elseif ($below_level->status == '1') {
                            return "Level 0" . $below_level->approval_level . " Approved";
                        } elseif ($below_level->status == '2') {
                            return "Level 0" . $below_level->approval_level . " Rejected";
                        }
                    } else {
                        if ($approval_status->status == '1') {
                            return 'Approved';
                        } elseif ($approval_status->status == '2') {
                            return 'Rejected';
                        }
                    }
                } else {
                    if ($approval_status->status == '0') {
                        return 'Pending';
                    } elseif ($approval_status->status == '1') {
                        return 'Approved';
                    } elseif ($approval_status->status == '2') {
                        return 'Rejected';
                    }
                }
            } else {
                return null;
            }
        }

    }


    // check approval status exist or not mean get its id if exist
    public function getApprovalStatusId($module_id, $id)
    {

        $user = auth()->user();
        $emp_level = ApprovalSetting::where('module_id', $module_id)->where('selected_id', $user->emp_id)->where('selected_type', 'employee')->first();
        if (!$emp_level) {
            $designation_id = $user->userToEmp->approval->designation->id ?? null;
            if ($designation_id) {
                $designation_level = ApprovalSetting::where('module_id', $module_id)->where('selected_id', $designation_id)->where('selected_type', 'designation')->first();
                if (!$designation_level) {
                    $line_manager_level = ApprovalSetting::where('module_id', $module_id)->where('selected_id', 0)->where('selected_type', 'line_manager')->first();
                    if ($line_manager_level) {
                        $approval_status = ApprovalStatus::where('requested_id', $id)->where('approval_level', $line_manager_level->approval_level)->first();
                        return $approval_status ? $approval_status : null;
                    } else {
                        return null;
                    }
                } else {
                    $approval_status = ApprovalStatus::where('requested_id', $id)->where('approval_level', $designation_level->approval_level)->first();
                    return $approval_status ? $approval_status : null;
                }
            } 
            // else {
            //     $response = SystemResponse::find(8);
            //     return response()->json([
            //         'status' => '0',
            //         'success' => false,
            //         'message' => $response->message ?? 'No Response Found in Database'
            //     ]);
            // }
        } else {
            $approval_status = ApprovalStatus::where('requested_id', $id)->where('approval_level', $emp_level->approval_level)->first();
            return $approval_status ? $approval_status : null;
        }
    }


    // Bypass below approvals, mean upper level can update lower level approval
    public function bypassAllApprovals($module_id, $id, $status, $level)
    {
       try{
        DB::beginTransaction();
        while (true) {
            $approvals = ApprovalStatus::where('module_id', $module_id)
                ->where('requested_id', $id)
                ->where('approval_level', $level)
                ->first();
            if (!$approvals) {
                break;
            }
            $approvals->update([
                'status' => $status,
            ]);
            $level += 1;
        }
        DB::commit();
        return true;
       }catch(Exception $e){
        DB::rollBack();
        return false;
       }
    }

}

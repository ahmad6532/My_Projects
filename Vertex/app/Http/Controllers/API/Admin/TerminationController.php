<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Models\ZkRoledEmployee;
use App\Traits\ProfileImage;
use Illuminate\Http\Request;
use App\Models\Emp_termination;
use App\Models\Location;
use App\Models\Designation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\EmployeeDetail;
use App\Models\user_approval;
use App\Http\Controllers\API\Admin\NotificationController;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class TerminationController extends BaseController
{
    use ProfileImage;
    public function termination(Request $request)
    {
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);

        $selectBranch = isset($request->selectBranch) ? $request->selectBranch : 'all';

        $searchBy = isset($request->search_by) ? $request->search_by : '';

        $query = Emp_termination::select('company_id','branch_id','emp_id','termination_type','termination_date','notice_date','reason','id','is_approved')->with(['employee_detail' => function ($query) {
            $query->select('id', 'emp_id', 'emp_name', 'emp_gender', 'emp_image');
        }, 'employee_approval', 'branch', 'company']);

        if ($user_role == '1') {
            if ($selectBranch != 'all') {
                $employees = $query->where('emp_terminations.branch_id', $selectBranch);
            }
        } else {
            if ($selectBranch == 'all') {
                $employees = $query->whereIn('emp_terminations.company_id', $user_company_id)
                    ->whereIn('emp_terminations.branch_id', $user_branch_id);
            } else {
                $employees = $query->whereIn('emp_terminations.company_id', $user_company_id)
                    ->where('emp_terminations.branch_id', $selectBranch);
            }
        }
        if(isset($searchBy) && $searchBy != ''){
            $employees = $query->where(function($query) use($searchBy){
                $query->whereHas('employee_detail', function($query) use ($searchBy) {
                    $query->where('emp_name', 'LIKE', '%' . $searchBy . '%')
                    ->orWhere('emp_id','LIKE','%'.$searchBy.'%');
                });
            });
        }

        $employees = $query->orderBy('emp_terminations.id', 'desc')
        ->paginate(20);

        foreach ($employees as $key => $item) {
            if ($item->employee_approval && $item->employee_approval->designation_id) {
                $designation = Designation::find($item->employee_approval->designation_id);

                if ($designation) {
                    $item->designation_name = $designation->name;
                    $department =  Department::where('id',$designation->department_id)->first();
                    if($department){
                        $item->department_name = isset($department->name) ? $department->name : "N/A";
                    }
                    $locations = Location::get();
                    foreach ($locations as $index => $value) {
                        $item->branch_name = $value->branch_name;
                    }
                } else {
                    $item->designation = "N/A";
                }
            } else {
                $item->designation = "N/A";
            }
            $item->imagePath = $this->imgFunc($item->employee_detail->emp_image, $item->employee_detail->emp_gender);
        }

        $data['employee'] =  $employees;

        if (!empty($data)) {
            return $this->sendResponse($data, 'Employees terminations list!', 200);
        } else {
            return $this->sendResponse($data, 'Data not found!', 200);
        }
    }


    public function saveTermination(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
            'emp_id' => 'required',
            'termination_type' => 'required',
            'termination_date' => 'required',
            'notice_date' => 'required',
            'reason' => 'required'
        ]);
        if ($validate->fails()) {
            return $this->sendError([],$validate->errors(),400);
        }
        $record = Emp_termination::where('emp_id', $request->emp_id)->first();
       // dd($record);
        DB::beginTransaction();
        try{
            if (!$record) {
                $create = new Emp_termination;
                $create->company_id = $request->company_id;
                $create->branch_id = $request->branch_id;
                $create->emp_id = $request->emp_id;
                $create->termination_type = $request->termination_type;
                $create->termination_date = Carbon::parse($request->termination_date)->format('Y-m-d');
                $create->notice_date = Carbon::parse($request->notice_date)->format('Y-m-d');
                $create->reason = $request->reason;
                $create->save();
                DB::commit();

                if($create){
                    $getEmployee = EmployeeDetail::where('id', $request->emp_id)->first();
                    $getDesignation = user_approval::where('emp_id', $request->emp_id)->first()['designation_id'];
                    if($getEmployee && $getDesignation){
                        $getDesignationName = Designation::where('id', $getDesignation)->first()['name'];
                        if($getDesignationName){
                            $user_id = User::where('email', $getEmployee->emp_email)->select('id')->first();
                            //generate notification
                            if ($user_id) {
                                $type = "Employee Termination";
                                $branch = $request->branch_id;
                                $data = [];
                                $data['emp_name'] = isset($getEmployee->emp_name) ?? 'N/A';
                                $data['user_id'] = $user_id->id;
                                $data['emp_position'] = isset($getDesignationName) ?? "N/A";
                                $data['termination_type'] = $request->termination;
                                $data['employee_personal_email'] = isset($getEmployee->personal_email) ?? "N/A";
                                $data['last_date'] = Carbon::parse($request->resignation_date)->format('Y-m-d');

                                $createNotification = new NotificationController();
                                $createNotification->generateNotification($type, $data, $branch);
                            }

                        }

                        $msg = '"' . ucwords(isset($getEmployee->emp_name) ?? "N/A") . ' ' . ucwords($request->termination) . '" added successfully';
                        createLog('termination_action', $msg);
                    }else{
                        return $this->sendError([], 'Something went wrong, Please try again!', 400);
                    }

                    return $this->sendResponse($create, 'Employee termination saved successfully!', 200);
                }else{
                    return $this->sendError([], 'Something went wrong, Please try again!', 400);
                }

            } else {
                return $this->sendError([], 'Termination request already exists', 208);
            }

        }catch(\Exception $e){
            DB::rollBack();
            return $this->sendError([],$e->getMessage(),500);
        }
    }

    public function editTermination(Request $request)
    {
        $employeeTermination = Emp_termination::where('id',$request->termination_id)->first();

        if($employeeTermination){
            return $this->sendResponse($employeeTermination,'Employee termination fetched successfully!',200);
        }else{
            return $this->sendResponse([],'Data not found!',200);
        }
    }

    public function updateTermination(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'termination_id' => 'required',
            'company_id' => 'required',
            'branch_id' => 'required',
            'emp_id' => 'required',
            'termination_type' => 'required',
            'termination_date' => 'required',
            'notice_date' => 'required',
            'reason' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors(),
            ], 400);
        }

            $data = Emp_termination::where('id', $request->termination_id)->first();
            if ($data) {
                if ($data->emp_id != $request->emp_id) {
                    return response()->json([
                        'success' => false,
                        'message' => ['exist' => ['Employee already exists']
                    ],
                ], 400);
            }

            $data->company_id = $request->company_id;
            $data->branch_id = $request->branch_id;
            $data->termination_type = $request->termination_type;
            $data->termination_date = Carbon::parse($request->termination_date)->format('Y-m-d');
            $data->notice_date = Carbon::parse($request->notice_date)->format('Y-m-d');
            $data->reason = $request->reason;
            $data->update();

            return $this->sendResponse($data, 'Employee termination updated successfully!', 200);
        } else {
            return $this->sendError([], 'Employee termination not found!', 404);
        }
    }

    public function changeTerminationStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'termination_id' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError([], $validator->errors(), 400);
        } else {
            $id = $request->termination_id;
            $status = $request->status;
            if ($status == "0") {
                $statusValue = "0";
            }
            if ($status == "1") {
                $statusValue = "1";
            }
            $data = Emp_termination::where('id', $id)->first();
            $data->is_approved = $statusValue;
            $data->update();

            $user_detail = EmployeeDetail::where('id', $data->emp_id)->first();
            if ($user_detail->status == "1")
            {
                $user_detail->status = "4";
            }
            $user_detail->update();
            if($status == "1"){
                $empRole = ZkRoledEmployee::where('emp_id',$user_detail->id)->first();
            if($empRole){
                $empRole->update([
                    'synced' => '0',
                    'action' => 'delete'
                ]);
            }
            }

            // } else if ($data && $status == "0") {
            //     $user_detail = EmployeeDetail::where('id', $data->emp_id)->first();
            //     $user_detail->status = "0";
            //     $user_detail->update();
            // }
            if (!empty($data)) {
                return $this->sendResponse($data, 'Ternimation status changed successfully!', 200);
            } else {
                return $this->sendResponse($data, 'Data not found!', 200);
            }
        }
    }

    public function downloadEmployeeTerminationList(Request $request)
    {
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);

        $availableFields = [
            'emp_id' => 'employee_details.emp_id',
            'emp_name' => 'employee_details.emp_name',
            'branch_name' => 'locations.branch_name',
            'company_name' => 'companies.company_name',
            'termination_date' => 'emp_terminations.termination_date',
            'notice_date' => 'emp_terminations.notice_date',
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
                                        ->leftJoin('emp_terminations', 'emp_terminations.emp_id', '=', 'employee_details.id')
                                        ->where('employee_details.is_deleted', '0')
                                        ->where('employee_details.status', '3')
                                        // ->select($selectedFields)
                                        ->orderBy('employee_details.emp_id', 'asc');

        dd($employeeQuery->get());
        if ($user_role != 1) {
            $employeeQuery->whereIn('employee_details.company_id', $user_company_id)
                ->whereIn('employee_details.branch_id', $user_branch_id);
        }

        $employees = $employeeQuery->get();

        $csvFileName = 'user_termination_details_' . time() . '.csv';
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
        return $this->sendResponse($downloadLink, 'User Termination details CSV file generated successfully!', 200);
    }

    public function deleteTermination(Request $request)
    {
        $EmpDetails = '';
        $validator = Validator::make($request->all(),[
            'termination_id' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError([],$validator->errors(),400);
        }else{
            $id = $request->termination_id;
            $termination = Emp_termination::where('id', $id)->first();
            if ($termination) {
                $EmpDetails = EmployeeDetail::where('id', $termination->emp_id)->first();
                if ($EmpDetails) {
                    $msg = '"' . ucwords(isset($EmpDetails->emp_name) ? $EmpDetails->emp_name : 'N/A') . ' ' . ucwords($termination->termination_type) . '" Deleted Successfully';
                    createLog('termination_action', $msg);
                }
            }
            $terminationDeleted = Emp_termination::where('id', $id)->delete();

            if($terminationDeleted){
                return $this->sendResponse($EmpDetails,'Employee termination deleted successfully!',200);
            }else{
                return $this->sendError($EmpDetails,'Ternimation not found!',200);
            }
        }
    }

    // public function terminationSearch(Request $request)
    // {
    //     $searchValue = strtolower($request->input('searchValue'));
    //     $selectBranch = $request->input('selectBranch');
    //         if($selectBranch == 'all'){
    //         $fetchData = Emp_termination::where(function ($query) use ($searchValue, $selectBranch) {
    //             $query->where(function ($query) use ($searchValue, $selectBranch) {
    //                 $query->WhereHas('employee_detail', function ($query) use ($searchValue) {
    //                     $query->whereRaw('LOWER(employee_details.emp_name) LIKE ?', ['%' . $searchValue . '%'])
    //                         ->orWhereRaw('LOWER(employee_details.emp_id) LIKE ?', ['%' . $searchValue . '%']);
    //                 })
    //                     ->orWhereHas('branch', function ($query) use ($searchValue, $selectBranch) {
    //                         $query->whereRaw('LOWER(locations.branch_name) LIKE ?', ['%' . $searchValue . '%']);
    //                     });
    //             });
    //         })
    //             ->orderBy('emp_terminations.id', 'asc')
    //             ->leftJoin('locations', 'emp_terminations.branch_id', '=', 'locations.id')
    //             ->leftJoin('employee_details', 'emp_terminations.emp_id', '=', 'employee_details.id')
    //             ->leftJoin('emp_approvals', 'emp_terminations.emp_id', 'emp_approvals.emp_id')
    //             ->select('employee_details.emp_id', 'locations.branch_name', 'emp_approvals.designation_id', 'employee_details.emp_name', 'emp_terminations.id', 'emp_terminations.termination_date', 'emp_terminations.notice_date', 'emp_terminations.is_approved')
    //             // ->where('emp_resignations.branch_id', $selectBranch)
    //             ->get();
    //     }else{
    //         $fetchData = Emp_termination::where(function ($query) use ($searchValue, $selectBranch) {
    //             $query->where(function ($query) use ($searchValue, $selectBranch) {
    //                 $query->WhereHas('employee_detail', function ($query) use ($searchValue) {
    //                     $query->whereRaw('LOWER(employee_details.emp_name) LIKE ?', ['%' . $searchValue . '%'])
    //                         ->orWhereRaw('LOWER(employee_details.emp_id) LIKE ?', ['%' . $searchValue . '%']);
    //                 })
    //                     ->orWhereHas('branch', function ($query) use ($searchValue, $selectBranch) {
    //                         $query->whereRaw('LOWER(locations.branch_name) LIKE ?', ['%' . $searchValue . '%']);
    //                     });
    //             });
    //         })
    //             ->orderBy('emp_terminations.id', 'asc')
    //             ->leftJoin('locations', 'emp_terminations.branch_id', '=', 'locations.id')
    //             ->leftJoin('employee_details', 'emp_terminations.emp_id', '=', 'employee_details.id')
    //             ->leftJoin('emp_approvals', 'emp_terminations.emp_id', 'emp_approvals.emp_id')
    //             ->select('employee_details.emp_id', 'locations.branch_name', 'emp_approvals.designation_id', 'employee_details.emp_name', 'emp_terminations.id', 'emp_terminations.termination_date', 'emp_terminations.notice_date', 'emp_terminations.is_approved')
    //             ->where('emp_terminations.branch_id', $selectBranch)
    //             ->get();
    //     }
    //     foreach($fetchData as $designation){
    //         if($designation->designation_id){
    //             $designation->desgn = Designation::where('id',$designation->designation_id)->first();
    //         }
    //         else{
    //             $designation->desgn = "N/A";
    //         }
    //     }
    //     if ($fetchData->count() > 0) {
    //         return $this->sendResponse($fetchData, 'Ternimation fetched successfully!',200);
    //     } else {
    //         return $this->sendResponse($fetchData, 'Ternimation not found!',200);
    //     }
    // }
}

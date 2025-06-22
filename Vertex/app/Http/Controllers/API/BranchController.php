<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\EmployeeDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class BranchController extends BaseController
{
    // use HasFactory;
    public function getBranchEmployees(Request $request)
    {
        $user = Auth::user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        if($user_role == '1'){
            $result = DB::table('employee_details')
                ->leftjoin('companies', 'employee_details.company_id', '=', 'companies.id')
                ->select('companies.company_name','employee_details.*',DB::raw("IFNULL(employee_details.dob, CURRENT_TIMESTAMP) as dob"))
                ->where('employee_details.is_deleted','0')
                ->where('employee_details.status','1')
                ->get();
        }else{
            $result = DB::table('employee_details')
                ->leftjoin('companies', 'employee_details.company_id', '=', 'companies.id')
                ->select('companies.company_name','employee_details.*',DB::raw("IFNULL(employee_details.dob, CURRENT_TIMESTAMP) as dob"))
                ->where('employee_details.is_deleted','0')
                ->where('employee_details.status','1')
                ->whereIn('employee_details.company_id',$user_company_id)
                ->whereIn('employee_details.branch_id',$user_branch_id)
                ->get();
        }
        if(isset($result) && count($result)>0 ){
            $response['error'] = false;
            $response['message'] = 'Fetched Location Employees Successfully';
            $response['data'] = $result;
            return $response;
        }else{
            $response['error'] = true;
            $response['message'] = 'No Record found';
            $response['data'] = [];
            return $response;
        }
    }

    public function updateUserFinger(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fingerprint' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()]);
        }
        $input = $request->all();
        $checkid = EmployeeDetail::where('id',$request->emp_id)->first();
        if($checkid){
            if(EmployeeDetail::where('fingerprint',$input['fingerprint'])->first()){
                return $this->sendResponse([],"User already registered!");
            }
            $checkid->attend_pin= isset($input['pin']) ? $input['pin']: null;
            $checkid->fingerprint= $input['fingerprint'];
            $checkid->save();
            $message = "FingerPrint are updated successfully!";
            return $this->sendResponse($checkid,$message);
            $response['error'] = false;
            $response['message'] = 'FingerPrint are updated successfully';
            $response['data'] = $checkid;
            return $response;
        }
        else{
            $response['error'] = true;
            $response['message'] = 'Employee record does not exist.';
            $response['data'] = [];
            return $response;
        }
    }
}

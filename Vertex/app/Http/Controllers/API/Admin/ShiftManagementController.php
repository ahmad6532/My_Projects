<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Models\AssignShift;
use Illuminate\Http\Request;
use App\Models\ShiftManagement;
use App\Models\Company;
use App\Models\Department;
use Illuminate\Support\Facades\Validator;

class ShiftManagementController extends BaseController
{
    public function shiftManagement(Request $request)
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        // $user_company_id = explode(',', $user->company_id);
        // $user_branch_id = explode(',', $user->branch_id);

        $searchBy = isset($request->search_by) ? $request->search_by : '';
        $query = ShiftManagement::query();
        if($searchBy){
            $shiftListing = $query->orWhere(function($query) use($searchBy){
                $query->orWhere('shift_name','LIKE','%'.$searchBy.'%');
            });
            if(strtolower($searchBy) == 'yes'){
                $query->orWhere('is_recurring','1');
            }
            if(strtolower($searchBy) == 'no'){
                $query->orWhere('is_recurring','0');
            }
        }
        $shiftListing = $query->where('is_active', '1')->where('is_deleted', '0')->paginate(20);
        if (!$shiftListing->isEmpty()) {
            return $this->sendResponse($shiftListing, 'Shift data fetched successfully!', 200);
        } else {
            return $this->sendResponse($shiftListing, 'Data not found!', 200);
        }
    }

    public function saveShift(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'shift_name' => 'required|unique:shift_management,shift_name',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);
        if ($validate->fails()) {
            return $this->sendResponse(['errors' => $validate->errors()], 400);
        } else {
            $create_shift = new ShiftManagement();
            $create_shift->shift_name = $request->shift_name;
            $create_shift->start_time = $request->start_time;
            $create_shift->end_time = $request->end_time;
            $create_shift->break_start_time = $request->break_start_time;
            $create_shift->break_end_time = $request->break_end_time;
            $create_shift->late_time = $request->late_time;
            $create_shift->half_day = $request->half_day;
            $create_shift->is_recurring = $request->is_recurring;
            $create_shift->note = $request->note;
            $selectedDays = $request->input('selectedDays');
            $daysString = implode(',', $selectedDays);
            $create_shift->working_days = $daysString;
            $start_time = strtotime($request->start_time);
            $end_time = strtotime($request->end_time);
            $duration = $end_time - $start_time;
            $hours = floor($duration / 3600);
            $minutes = floor(($duration % 3600) / 60);
            $create_shift->working_hours = sprintf('%02d:%02d', $hours, $minutes);
            $create_shift->save();
            $msg = '"' . $request->shift_name . '" Added Successfully';
            createLog('shift_management_action', $msg);
            if ($create_shift) {
                return $this->sendResponse($create_shift,'Shift create successfully!',200);
            } else {
                return $this->sendError($create_shift,'Something went wrong!',500);
            }
        }
    }

    public function deleteShift(Request $request)
    {
        $shift = ShiftManagement::where('id', $request->shift_id)->first();
        if ($shift) {
            $shift->delete();
            return $this->sendResponse([], 'Shift delete successfully!', 200);
        } else {
            return $this->sendResponse([], 'Data not found!', 200);
        }
    }

    public function editShift(Request $request)
    {
        $shift = ShiftManagement::where('id',$request->shift_id)->first();
        if($shift){
            return $this->sendResponse($shift,'Shift fetched successfully!',200);
        }else{
            return $this->sendResponse($shift,'Data not found!',200);
        }
    }

    public function updateShift(Request $request)
    {
        $shift = ShiftManagement::where('id', $request->shift_id)->first();
        if ($shift) {
            $updatedShift = $shift->update($request->all());
            if ($updatedShift) {
                return $this->sendResponse($shift, 'Shift update successfully!', 200);
            } else {
                return $this->sendError([], 'Shift not updated!', 500);
            }
        } else {
            return $this->sendResponse([], 'Data not found!', 200);
        }
    }

    public function assignShiftList(Request $request)
    {
        $searchBy = isset($request->search_by) ? $request->search_by : "";

       $query = AssignShift::select(
            'assign_shifts.id',
            'employee_details.emp_id',
            'employee_details.emp_name',
            'departments.name',
            'assign_shifts.shift_type',
            'assign_shifts.date')
        ->join('departments', 'assign_shifts.department_id', 'departments.id')
        ->join('employee_details', 'employee_details.id', 'assign_shifts.emp_id');

        if($searchBy){
          $query->where(function($query) use ($searchBy){
                $query->where('employee_details.emp_name','LIKE','%'.$searchBy.'%')
                ->orWhere('employee_details.emp_id','LIKE','%'.$searchBy.'%')
                ->orWhere('assign_shifts.shift_type','LIKE','%'.$searchBy.'%')
                ->orWhere('departments.name','LIKE','%'.$searchBy.'%');
            });
        }

        $assignShift = $query->orderBy('assign_shifts.id','desc')->paginate(20);

        foreach ($assignShift as $index => $shift) {
            $shift->department_name = $shift->name;
            unset($shift->name);
        }
        if(count($assignShift) > 0){
            return $this->sendResponse($assignShift,'Assign shift fetched successfully!',200);
        }else{
            return $this->sendResponse($assignShift,'Data not found!',200);
        }
    }

    public function saveAssignShift(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'emp_id' => 'required',
            'company_id' => 'required',
            'branch_id' => 'required',
            'department_id' => 'required',
            'shifit_id' => 'required',
            'shift_type' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError([],$validator->errors(),400);
        }
        $employee_ids = $request->emp_id;
        for ($index = 0; $index < count($employee_ids) ; $index++) { 
            $assignShift = new AssignShift();
            $assignShift->emp_id = $employee_ids[$index];
            $assignShift->company_id = $request->company_id;
            $assignShift->branch_id = $request->branch_id;
            $assignShift->department_id = $request->department_id;
            $assignShift->shifit_id = $request->shifit_id;
            $assignShift->shift_type = $request->shift_type;
            $assignShift->date = $request->date;
            $assignShift->extra_hours = $request->extra_hours;
            $assignShift->save();
        }
        if($assignShift){
            return $this->sendResponse([],'Shift assign succesfully!',200);
        }else{
            return $this->sendError([],'Shift not assign!',500);
        }
    }

    public function deleteAssignShift(Request $request)
    {
        $assignShift = AssignShift::where('id',$request->shift_id)->first();
        if($assignShift){
            $assignShift->delete();
            return $this->sendResponse([],'Assign shift delete successfully!',200);
        }else{
            return $this->sendResponse([],'Data not found!',200);
        }
    }

    public function editAssignShift(Request $request)
    {
        $assignShift = AssignShift::where('id',$request->shift_id)->first();
        if($assignShift){
            return $this->sendResponse($assignShift,'Assign shift fetched successfully!',200);
        }else{
            return $this->sendResponse($assignShift,'Data not found!',200);
        }
    }

    public function updateAssignShift(Request $request)
    {
        $assignShift = AssignShift::where('id', $request->assign_shift_id)->first();
        if ($assignShift) {
            $updateAssignShift = $assignShift->update([
                'emp_id' => $request->emp_id,
                'company_id' => $request->company_id,
                'branch_id' => $request->branch_id,
                'department_id' => $request->department_id,
                'shift_id' => $request->shift_id,
                'shift_type' => $request->shift_type,
                'date' => $request->date,
                'extra_hours' => $request->extra_hours
            ]);
            if ($updateAssignShift) {
                return $this->sendResponse($assignShift, 'Assign shift update successfully!', 200);
            } else {
                return $this->sendError([], 'Assign shift not updated successfully!', 500);
            }
        } else {
            return $this->sendResponse([], 'Data not found!', 200);
        }
    }
}

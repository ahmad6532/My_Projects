<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Holiday;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Location;

class HolidayController extends BaseController
{
    public function holidaysList(Request $request)
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);
        $searchBy = isset($request->search_by) ? $request->search_by : '';
        $perPage = isset($request->per_page) ? $request->per_page : '20';

        $query = Holiday::leftjoin('locations', 'locations.id', '=', 'holidays.branch_id')
            ->select('holidays.*', 'locations.branch_name');

        if ($user_role != '1') {
            $query->whereIn('holidays.company_id', $user_company_id)
                ->whereIn('holidays.branch_id', $user_branch_id);
        }
        if ($searchBy) {
            $query->where(function ($query) use ($searchBy) {
                $query->where('locations.branch_name', 'LIKE', '%' . $searchBy . '%')
                    ->orWhere('holidays.event_name', 'LIKE', '%' . $searchBy . '%')
                    ->orWhere('holidays.start_date', 'LIKE', '%' . $searchBy . '%');
            });
        }

        $holidays = $query->where('holidays.is_deleted', '0')->orderBy('start_date', 'desc')->paginate($perPage);

        if (count($holidays) > 0) {
            return $this->sendResponse($holidays, 'Holidays fetched successfully!', 200);
        } else {
            return $this->sendResponse($holidays, 'Data not found!', 200);
        }
    }

    public function saveHolidays(Request $request)
    {
        $start_date = date('Y-m-d', strtotime($request->start_date));
        $end_date = $request->end_date != null ? date('Y-m-d', strtotime($request->end_date)) : null;
        $companyId = $request->company_id;
        $branchId = $request->branch_id;
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
            'event_name' => 'required',
            'start_date' => [
                'required',
                function ($attribute, $value, $fail) use ($start_date, $end_date, $companyId, $branchId) {
                    $count = DB::table('holidays')
                        ->where('start_date', '<=', $end_date)
                        ->where('end_date', '>=', $start_date)
                        ->where('company_id', $companyId)
                        ->where('branch_id', $branchId)
                        ->count();

                    if ($count > 0) {
                        $fail("The selected date range overlaps with an existing holiday.");
                    }
                }
            ],
            'end_date' => 'required',
        ], [
            'start_date.required' => 'The start date is required.',
            'start_date.unique' => 'The selected date range overlaps with an existing holiday.',
            'end_date.required' => 'The end date is required.',
        ]);

        if ($validator->fails()) {
            return $this->sendError([], $validator->errors(), 400);
        }

        // change array of company ids in string for save in database
        // $companyString = implode(',',$request->company_id);
        // $branchString = implode(',', $request->branch_id);

        $companyString = $request->company_id;
        $branchString = $request->branch_id;

        $createHolidays = Holiday::create([
            'branch_id' => $branchString,
            'company_id' => $companyString,
            'event_name' => $request->event_name,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'is_repeated' => $request->is_repeated,
            'is_active' => $request->is_active,
        ]);

        $msg = '"' . $request->event_name . '" Added Successfully';
        createLog('holidays_action', $msg);
        if ($createHolidays) {
            return $this->sendResponse($createHolidays, 'Holiday save successfully!', 200);
        } else {
            return $this->sendResponse($createHolidays, 'Holiday not saved successfully!', 500);
        }
    }

    public function editHoliday(Request $request)
    {
        $holiday = Holiday::where('id', $request->holiday_id)->first();
        if ($holiday) {
            $holiday->start_date = date('d-m-Y', strtotime($holiday->start_date));
            $holiday->end_date = date('d-m-Y', strtotime($holiday->end_date));
            return $this->sendResponse($holiday, 'Holiday fetched successfully!', 200);
        } else {
            return $this->sendResponse($holiday, 'Data not found!', 200);
        }
    }

    public function updateHoliday(Request $request)
    {
        $id = $request->id;
        $start_date = date('Y-m-d', strtotime($request->start_date));
        $end_date = $request->end_date != null ? date('Y-m-d', strtotime($request->end_date)) : null;

        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
            'event_name' => 'required',
            'start_date' => 'required',   //[

            //     function ($attribute, $value, $fail) use ($start_date, $end_date, $id) {
            //         $count = DB::table('holidays')
            //         ->where('id', '!=', $id)
            //             ->where('start_date', '<=', $end_date)
            //             ->where('end_date', '>=', $start_date)
            //             ->count();

            //         if ($count > 0) {
            //             $fail("The selected date range overlaps with an existing holiday.");
            //         }
            //     }
            // ],
            'end_date' => 'required',
            // ], [
            //     'holiday_start_date.required' => 'The start date is required.',
            //     'holiday_start_date.unique' => 'The selected date range overlaps with an existing holiday.',
            //     'holiday_end_date.required' => 'The end date is required.',
        ]);

        if ($validator->fails()) {
            return $this->sendError([], $validator->errors(), 400);
        }

        // company and branch array converted in string as 1,2,3,4
        // $companyString = implode(',',$request->company_id);
        // $branchString = implode(',',$request->branch_id);

        $companyString = $request->company_id;
        $branchString = $request->branch_id;

        $updateHoliday = Holiday::where('id', $request->id)->update([
            'branch_id' => $branchString,
            'company_id' => $companyString,
            'event_name' => $request->event_name,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'is_repeated' => $request->is_repeated,
            'is_active' => $request->is_active,
        ]);

        $holiday = Holiday::find($request->id);

        if ($updateHoliday) {
            $msg = '"' . $request->event_name . '" Updated Successfully';
            createLog('holidays_action', $msg);
            return $this->sendResponse($holiday, 'Holiday updated successfully!', 200);
        } else {
            return $this->sendError([], 'Data not found!', 404);
        }
    }

    public function destroyHoliday(Request $request)
    {
        $id = $request->holiday_id;
        $delHoliday = Holiday::where('id', $id)->first();
        if ($delHoliday) {
            $delHoliday->delete();
            $msg = '"' . $delHoliday->event_name . '" Deleted';
            createLog('holidays_action', $msg);
            return $this->sendResponse([], 'Holiday delete successfully!', 200);

        } else {
            return $this->sendResponse($delHoliday, 'Data not found!', 200);
        }
    }

    // public function getsearchedHoliday(Request $request)
    // {
    //     $searchValue = strtolower($request->event_name);
    //     $fetchData = Holiday::where(function ($query) use ($searchValue) {
    //         $query->where('holidays.is_deleted', '0')
    //             ->where(function ($query) use ($searchValue) {
    //                 $query->whereRaw('LOWER(event_name) LIKE ?', ['%' . $searchValue . '%'])
    //                     ->orWhereRaw('LOWER(start_date) LIKE ?', ['%' . $searchValue . '%']);
    //             });
    //     })
    //         ->leftjoin('locations', 'locations.id', '=', 'holidays.branch_id')
    //         ->orderBy('holidays.id', 'asc')
    //         ->select('holidays.id', 'holidays.event_name', 'holidays.start_date', 'holidays.is_active', 'holidays.end_date', 'holidays.updated_at', 'locations.branch_name')
    //         ->get();

    //     $fetchData->each(function ($item) {
    //         $item->start_date = date('d-m-Y', strtotime($item->start_date));
    //         $item->end_date = date('d-m-Y', strtotime($item->end_date));
    //     });

    //     if (count($fetchData) > 0) {
    //         return $this->sendResponse($fetchData,'Holidays fetched successfully!',200);
    //     } else {
    //         return $this->sendResponse($fetchData,'Data not found!',200);
    //     }
    // }

    // public function getHolidayBranche(Request $request)
    // {
    //     $validator = Validator::make($request->all(),[
    //         'holiday_branch_id' => 'required',
    //     ]);
    //     if($validator->fails()){
    //         return $this->sendError([],$validator->errors()->first(),400);
    //     }else{
    //         $idString = $request->holiday_branch_id;
    //         $idArray = explode(',', $idString);
    //         $branches = Location::whereIn('id', $idArray)->get();
    //         if ($branches->count() > 0) {
    //             return response()->json(["success" => true, "data" => $branches]);
    //         } else {
    //             return response()->json(["success" => false, "message" => "No locations found for the given IDs."], 404);
    //         }
    //     }

    // }
}

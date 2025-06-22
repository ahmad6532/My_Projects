<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Traits\ProfileImage;
use Exception;
use Illuminate\Http\Request;
use App\Models\EmployeePromotion;
use App\Models\Location;
use Illuminate\Support\Facades\Validator;
use App\Models\user_approval;
use App\Models\EmpCompensation;
use App\Models\EmpCompensationDetails;
use Illuminate\Support\Carbon;
use App\Models\EmployeeDetail;
use App\Models\Emp_salary;
use App\Models\SalaryComponent;
use App\Models\SalaryComponentType;
use App\Models\Designation;
use Illuminate\Support\Facades\DB;

class PromotionController extends BaseController
{
    use ProfileImage;
    public function promotion(Request $request)
    {
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);
        $selectBranch = isset($request->selectBranch) ? $request->selectBranch : 'all';
        $searchBy = isset($request->search_by) ? $request->search_by : '';

        $query = EmployeePromotion::with('employee_detail', 'company', 'employee_approval', 'branch', 'designation', 'preDesignation');

        if ($user_role == '1') {
            if ($selectBranch != 'all') {
                $query->where('emp_promotions.branch_id', $selectBranch);
            }
        } else {
            if ($selectBranch == 'all') {
                $query->whereIn('emp_promotions.company_id', $user_company_id)
                    ->whereIn('emp_promotions.branch_id', $user_branch_id);
            } else {
                $query->whereIn('emp_promotions.company_id', $user_company_id)
                    ->where('emp_promotions.branch_id', $selectBranch);
            }
        }

        if ($searchBy && $searchBy != '') {
            $query->where(function ($query) use ($searchBy) {
                $query->whereHas('employee_detail', function ($query) use ($searchBy) {
                    $query->where('emp_name', 'LIKE', '%' . $searchBy . '%')
                        ->orWhere('emp_id', 'LIKE', '%' . $searchBy . '%');
                });
            });
        }

        $employees = $query->orderBy('emp_promotions.id', 'desc')->paginate(20);

        foreach ($employees as $key => $employee) {

            $employee->emp_name = isset($employee->employee_detail) ? $employee->employee_detail->emp_name : "N/A";
            $employee->employee_id = isset($employee->employee_detail) ? $employee->employee_detail->emp_id : "N/A";

            if ($employee) {
                $imagePath = $this->imgFunc($employee->employee_detail->emp_image, $employee->employee_detail->emp_gender);
                $employee->imagePath = $imagePath;
            } else {
                $employee->imagePath = '';
            }

            $designation_from = Designation::where('id', $employee->designation_from)->first();
            $employee->designation_from = $designation_from ? $designation_from->name : "N/A";

            // Designation to
            $designation_to = Designation::where('id', $employee->designation_to)->first();
            $employee->designation_to = $designation_to ? $designation_to->name : "N/A";

            // Branch name
            $branch = Location::where('id', $employee->branch_id)->first();
            if ($branch) {
                $employee->branch_name = isset($branch->branch_name) ? $branch->branch_name : "";
            }

            // Remove unnecessary fields
            unset($employee->employee_detail, $employee->designation_id, $employee->emp_desig, $employee->branch_id);
        }

        if (count($employees) > 0) {
            return $this->sendResponse($employees, 'Promotion list fetched successfully!', 200);
        } else {
            return $this->sendResponse($employees, 'Data not found!', 200);
        }
    }


    public function savePromotion(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
            'emp_id' => 'required',
            'from_date' => 'required',
            'designation_from' => 'required',
            'designation_to' => 'required',
        ]);
        if ($validator->fails()) {

            return response()->json([
                'status' => 1,
                'success' => 0,
                'errors' => $validator->errors()->all(),
            ], 400);
        } else {
            $employeeDetail = EmployeeDetail::find($request->emp_id);
            $salary = Emp_salary::where('employee_details_id', $request->emp_id)->first();
            $from = Designation::find($request->designation_from);
            $to = Designation::find($request->designation_to);
            if ($request->promotion_id) {
                $promotion = EmployeePromotion::where('id', $request->promotion_id)->where('is_approved', '!=', '1')->first();
                if ($promotion) {
                    $promotion->company_id = $request->company_id;
                    $promotion->branch_id = $request->branch_id;
                    $promotion->emp_id = $request->emp_id;
                    $promotion->from_date = Carbon::parse($request->from_date)->format('Y-m-d');
                    $promotion->designation_from = $request->designation_from;
                    $promotion->designation_to = $request->designation_to;
                    $promotion->increment = $request->increment;
                    $promotion->current_salary = $salary->basic_salary;
                    $promotion->incremented_salary = $salary->basic_salary + $request->increment;
                    $promotion->is_approved = '0';
                    $promotion->save();
                    $msg = '"'.$user->fullname.'"' . ' edit ' .'"'. $employeeDetail->emp_name .'"'. ' designation from ' .'"'. $from->name . '"'.' to ' .'"'. $to->name .'"';
                    createLog('promotion_action', $msg);
                } else {
                    return response()->json([
                        'status' => 0,
                        'success' => false,
                        'message' => 'Approved Promotion cannot be edit'
                    ]);
                }
            } else {
                $checkProm = EmployeePromotion::where('emp_id', $request->emp_id)->where('is_approved', '0')->first();
                if ($checkProm) {
                    return response()->json([
                        'status' => 0,
                        'success' => false,
                        'message' => 'Promotion already in pending'
                    ]);
                }
                $promotion = new EmployeePromotion;
                $promotion->company_id = $request->company_id;
                $promotion->branch_id = $request->branch_id;
                $promotion->emp_id = $request->emp_id;
                $promotion->from_date = Carbon::parse($request->from_date)->format('Y-m-d');
                $promotion->designation_from = $request->designation_from;
                $promotion->designation_to = $request->designation_to;
                $promotion->increment = $request->increment;
                $promotion->current_salary = $salary->basic_salary;
                $promotion->incremented_salary = $salary->basic_salary + $request->increment;
                $promotion->is_approved = '0';
                $promotion->save();
                $msg = '"'.$user->fullname.'"' . ' add ' .'"'. $employeeDetail->emp_name .'"'. ' designation from ' .'"'. $from->name . '"'.' to ' .'"'. $to->name .'"';
                createLog('promotion_action', $msg);
            }

            $componentsData = [];

            $incomingComponentIds = [];

            if ($request->has('components')) {
                foreach ($request->components as $component) {
                    $amount = isset($component['percentage']) ? $component['amount'] : 0;

                    // Check if the component already exists
                    $salaryComponent = SalaryComponent::where('employee_details_id', $request->emp_id)
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
                            'employee_details_id' => $request->emp_id,
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
                SalaryComponent::where('employee_details_id', $request->emp_id)
                    ->whereNotIn('id', $incomingComponentIds)
                    ->delete();
            }

            // $componentTypeIds = SalaryComponentType::whereIn('type', ['Allowance'])->pluck('id');
            // $grossSalary = $salary->basic_salary + $request->increment + SalaryComponent::where('employee_details_id', $request->emp_id)
            //     ->whereIn('component_type_id', $componentTypeIds)
            //     ->sum('amount');
            //     $detuction_contributions_ids = SalaryComponentType::whereIn('type', ['Deduction','Contribution'])->pluck('id');
            //     if($detuction_contributions_ids){
            //         $deductions_contributions = SalaryComponent::where('employee_details_id', $request->emp_id)
            //         ->whereIn('component_type_id', $detuction_contributions_ids)
            //         ->sum('amount');
            //     }
            // // Calculate taxable salary (excluding non-taxable components)
            // $taxableComponentTypeIds = SalaryComponentType::whereIn('type', ['Allowance', 'Contribution', 'Deduction'])->pluck('id');
            // $taxableSalary = $grossSalary - SalaryComponent::where('employee_details_id', $request->emp_id)
            //     ->where('tax_applicable', false)
            //     ->sum('amount');
            // $approvalDesignation = user_approval::where('emp_id', $request->emp_id)->first();
            // $approvalDesignation->designation_id = $request->designation_to;
            // $approvalDesignation->save();
            // Calculate tax and net salary

            // $tax = 0;
            // $netSalary = $grossSalary - $tax - $deductions_contributions;
            // $salary->taxable_salary = $taxableSalary;
            // $salary->net_salary = $netSalary ;
            // $salary->save();

            return response()->json([
                'status' => 1,
                'success' => true,
                'message' => "Promotion Details Added Successfully",
                // 'grossSalary' => $grossSalary,
                // 'taxableSalary' => $taxableSalary,
                // 'net_salary' => $netSalary,
                // 'tax' => $tax,
                'components' => $componentsData
            ], 200);
        }

    }
    // $validate = Validator::make($request->all(), [
    //     'company_id' => 'required',
    //     'branch_id' => 'required',
    //     'emp_id' => 'required',
    //     'from_date' => 'required',
    //     'designation_id' => 'required',
    //     'designation_to' => 'required',
    //     'current_salary' => 'required|numeric',
    //     'increment' => 'nullable|numeric',
    //     'compensations' => 'required|array',
    //     'compensations.*.amount' => 'required|integer',
    //     'compensations.*.type_id' => 'required|integer',
    //     'compensations.*.is_taxable' => 'required|integer',
    // ]);

    // if ($validate->fails()) {
    //     return $this->sendError([], $validate->errors(), 400);
    // }

    // DB::beginTransaction();

    // try {
    //     $record = EmployeePromotion::where('emp_id', $request->emp_id)->first();
    //     if ($record) {
    //         return $this->sendError([], 'Promotion request already exists', 208);
    //     }

    //     $promotion = new EmployeePromotion;
    //     $promotion->company_id = $request->company_id;
    //     $promotion->branch_id = $request->branch_id;
    //     $promotion->emp_id = $request->emp_id;
    //     $promotion->from_date = Carbon::parse($request->from_date)->format('Y-m-d');
    //     $promotion->emp_desig = $request->emp_desig_id;
    //     $promotion->designation_id = $request->designation_id;
    //     $promotion->designation_to = $request->designation_to;
    //     $promotion->current_salary = $request->current_salary;
    //     $promotion->increment = $request->increment ?? 0;
    //     $promotion->save();

    //     $currentSalary = $request->current_salary + $promotion->increment;

    //     $approval = user_approval::where('emp_id', $request->emp_id)->first();
    //     if ($approval) {
    //         $approval->designation_id = $request->designation_to;
    //         $approval->starting_sal = $currentSalary;
    //         $approval->update();
    //     }

    //     $allCompensations = collect();

    //     foreach ($request->compensations as $compensation) {

    //         $typeOf = EmpCompensationDetails::where('id', $compensation['type_id'])->value('type_of');

    //         if (!$typeOf) {
    //             return $this->sendError([], 'Invalid compensation type provided', 400);
    //         }

    //         $existingCompensation = EmpCompensation::where('emp_id', $request->emp_id)
    //             ->where('type_id', $compensation['type_id'])
    //             ->where('is_deleted', 0)
    //             ->latest()
    //             ->first();

    //         if ($existingCompensation) {
    //             $existingCompensation->is_deleted = 1;
    //             $existingCompensation->save();
    //         }

    //         $newCompensation = EmpCompensation::create([
    //             'emp_id' => $request->emp_id,
    //             'amount' => $compensation['amount'],
    //             'type_id' => $compensation['type_id'],
    //             'type_of' => $typeOf,
    //             'is_taxable' => $compensation['is_taxable'],
    //         ]);

    //         $allCompensations->push($newCompensation);
    //     }

    //     $empDetails = EmployeeDetail::find($request->emp_id);
    //     if ($empDetails) {
    //         $msg = '"' . ucwords($empDetails->emp_name) . '" Promotion Changes Added.';
    //         createLog('promotion_action', $msg);
    //     }

    //     DB::commit();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Promotion and compensation records change successfully!',
    //         'promotion' => $promotion,
    //         'compensations' => $allCompensations
    //     ], 200);

    // } catch (\Exception $e) {
    //     DB::rollBack();
    //     return $this->sendError([], $e->getMessage(), 500);
    // }

    public function changePromotionStatus(Request $request)
    {
       try{
DB::beginTransaction();

$user = auth()->user();
$validator = Validator::make($request->all(), [
    'promotion_id' => 'required',
    'status' => 'required',
]);
if ($validator->fails()) {
    return $this->sendError([], $validator->errors(), 400);
} else {
    $id = $request->promotion_id;
    $status = $request->status;
    $data = EmployeePromotion::where('id', $id)->first();
   $empDetail = EmployeeDetail::find($data->emp_id);
    if ($data && $status == "1") {

      $emp_sal = Emp_salary::where('employee_details_id',$data->emp_id)->first();
      if(!$emp_sal){
        return response()->json([
            'status' => 0,
            'success' => false,
            'message' => 'Employee Salary not Found'
        ]);
      }
      $emp_sal->update([
        'basic_salary' => $data->incremented_salary,
        'gross_salary' => $data->incremented_salary,
        'net_salary' => $data->incremented_salary,
      ]);
      $data->is_approved = $status;
      $data->update();
      $user_approval = user_approval::where('emp_id',$data->emp_id)->first();
      if(!$user_approval){
        return response()->json([
            'status' => 0,
            'success' => false,
            'message' => 'First Approve this Employee'
        ]);
      }

      $user_approval->update([
        'designation_id'=> $data->designation_to
      ]);
      $msg  = '"'. $user->fullname . '" approved '. '"' . $empDetail->emp_name . '" promotion';
      createLog('promotion-action',$msg);
      DB::commit();

      return response()->json([
        'status' => 1,
        'success' => true,
        'message' => 'Promotion Approved Successfully...'
    ]);
    } else if ($data && $status == "2") {

        if($data->is_approved == '1'){
            return response()->json([
                'status' => 0,
                'success' => false,
                'message' => 'Approved Promotion Cannot be Declined'
            ]);
        }
        $data->is_approved = $status;
        $data->update();
        $msg  = '"'. $user->fullname . '" declined '. '"' . $empDetail->emp_name . '" promotion';
      createLog('promotion-action',$msg);
      DB::commit();
      return response()->json([
        'status' => 1,
        'success' => true,
        'message' => 'Promotion Declined Successfully...'
    ]);
    }else{
        if($data->is_approved == '1'){
            return response()->json([
                'status' => 0,
                'success' => false,
                'message' => 'Approved Promotion Cannot be Pending'
            ]);
        }
        $data->is_approved = $status;
        $data->update();
        $msg  = '"'. $user->fullname . '" pending '. '"' . $empDetail->emp_name . '" promotion';
      createLog('promotion-action',$msg);
      DB::commit();
      return response()->json([
        'status' => 1,
        'success' => true,
        'message' => 'Promotion Pending Successfully...'
    ]);
    }
  
}

       }catch(Exception $e){
        DB::rollBack();
        return response()->json([
            'status'=> 0,
            'success' => false,
            'message' => $e->getMessage()
        ]);
       }

    }

    public function deletePromotion(Request $request)
    {
        $user = auth()->user();
        $id = $request->promotion_id;
        $validator = Validator::make($request->all(), [
            'promotion_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError([], $validator->errors(), 400);
        } else {
            $promotion = EmployeePromotion::where('id', $id)->where('is_approved','!=' , '1')->first();
            if(!$promotion){
                
                return response()->json([
                    'status' => 0,
                    'success' => false,
                    'message' => 'Approved Promotion Cannot be Delete'
                ]);
                
            }
            $EmpDetails = EmployeeDetail::where('id', $promotion->emp_id)->first();
            $promotionDeleted = EmployeePromotion::where('id', $id)->delete();
            if ($EmpDetails) {
                $msg = '"'.$user->fullname.'"' . ' delete  ' .'"'. $EmpDetails->emp_name .'"'. ' promotion ';
                createLog('promotion_action', $msg);
            }
            if ($promotionDeleted) {
                return response()->json([
                    'status' => 1,
                    'success' => true,
                    'message' => 'Promotion Deleted Successfully...'
                ]);
            } else {
                return response()->json([
                    'status' => 0,
                    'success' => false,
                    'message' => 'Data not found'
                ]);
            }
        }
    }

    public function promotionSearch(Request $request)
    {
        $searchValue = strtolower($request->input('searchValue'));
        $selectBranch = $request->input('selectBranch');
        if ($selectBranch == 'all') {
            $fetchData = EmployeePromotion::where(function ($query) use ($searchValue, $selectBranch) {
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
                ->orderBy('emp_promotions.id', 'asc')
                ->leftJoin('locations', 'emp_promotions.branch_id', '=', 'locations.id')
                ->leftJoin('employee_details', 'emp_promotions.emp_id', '=', 'employee_details.id')
                ->leftJoin('emp_approvals', 'emp_promotions.emp_id', 'emp_approvals.emp_id')
                ->select('employee_details.emp_id', 'locations.branch_name', 'emp_approvals.designation_id', 'employee_details.emp_name', 'emp_promotions.id', 'emp_promotions.from_date', 'emp_promotions.emp_desig', 'emp_promotions.is_approved')
                ->get();
        } else {
            $fetchData = EmployeePromotion::where(function ($query) use ($searchValue, $selectBranch) {
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
                ->orderBy('emp_promotions.id', 'asc')
                ->leftJoin('locations', 'emp_promotions.branch_id', '=', 'locations.id')
                ->leftJoin('employee_details', 'emp_promotions.emp_id', '=', 'employee_details.id')
                ->leftJoin('emp_approvals', 'emp_promotions.emp_id', 'emp_approvals.emp_id')
                ->select('employee_details.emp_id', 'locations.branch_name', 'emp_approvals.designation_id', 'employee_details.emp_name', 'emp_promotions.id', 'emp_promotions.from_date', 'emp_promotions.emp_desig', 'emp_promotions.is_approved')
                ->where('emp_promotions.branch_id', $selectBranch)
                ->get();
        }
        foreach ($fetchData as $designation) {
            if ($designation->designation_id) {
                $designation->new_designation = Designation::where('id', $designation->designation_id)->first();
                $designation->previous_desgnation = Designation::where('id', $designation->emp_desig)->first();
            } else {
                $designation->desgn = "N/A";
            }
        }
        if ($fetchData->count() > 0) {
            return $this->sendResponse($fetchData, 'Promotion fetched successfully!', 200);
        } else {
            return $this->sendResponse($fetchData, 'Data Not found!', 200);
        }
    }

    public function selectEmployeeDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError([], $validator->error(), 400);
        } else {
            $employee_details = EmployeeDetail::leftJoin('emp_promotions', 'emp_promotions.emp_id', '=', 'employee_details.id')
                ->leftJoin('designations', 'designations.id', '=', 'emp_promotions.designation_id')
                ->leftJoin('emp_approvals', 'emp_approvals.emp_id', '=', 'employee_details.id')
                ->select('employee_details.branch_id', 'employee_details.company_id', 'employee_details.id', 'emp_approvals.starting_sal', 'designations.name')
                ->findOrFail($request->id);

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

                ->where('employee_compensation.emp_id', $request->id)
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
                ->where('employee_salary.emp_id', $request->id)
                ->get();


            return response()->json([
                'status' => true,
                'message' => "Employee fetch Successfully",
                'employee_details' => $employee_details,
                'empCompensations' => $empCompensations,
                'empSalary' => $empSalary
            ], 200);
        }
    }
    public function getDetailsForPromotion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'emp_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 1,
                'success' => 0,
                'errors' => $validator->errors()->all()
            ], 400);
        } else {
            $employeeId = $request->emp_id;
            // Fetch the salary record for the given employee ID
            $approval = user_approval::where('emp_id', $employeeId)->first();
            $designations = Designation::select('id', 'name')->get();
            $designation_id = $approval->designation_id;
            foreach ($designations as $designation) {
                if ($designation->id == $designation_id) {
                    $designation_name = $designation->name;
                }
            }
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
                            'current_designation_id' => $designation_id,
                            'current_designation_name' => $designation_name,
                            'componentTypes' => $componentTypes,
                            'designations' => $designations
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

    }

    public function editPromotion(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'promotion_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 1,
                'success' => 0,
                'errors' => $validator->errors()->all()
            ], 400);
        } else {




            $promotionData = EmployeePromotion::with(['employee_detail:id,emp_name', 'branch:id,branch_name', 'company:id,company_name'])->where('id', $request->promotion_id)->select('emp_promotions.*')->first();
            $employeeId = $promotionData->emp_id;
            $pervious = $promotionData->designation_from;
            $promotionData->from_name = $promotionData->preDesignation->name;
            $promotionData->to_name = $promotionData->designation->name;
            unset($promotionData->preDesignation);
            unset($promotionData->designation);
            // Fetch the salary record for the given employee ID
            $approval = user_approval::where('emp_id', $employeeId)->first();
            $designations = Designation::select('id', 'name')->get();
            $designation_id = $approval->designation_id;
            $designation_name = "";
            foreach ($designations as $designation) {
                if ($designation->id == $pervious) {
                    $designation_name = $designation->name;
                }
            }
            foreach ($designations as $designation) {
                if ($designation->id == $designation_id) {
                    $designation_pervious = $designation->name;
                }
            }

            $salary = Emp_salary::with(['components.componentType'])->where('employee_details_id', $employeeId)->first();

            if ($salary) {

                $salary->components->transform(function ($component) {
                    $component->type = $component->componentType->type;
                    unset($component->componentType);
                    return $component;
                });

                $componentTypes = SalaryComponentType::all();

                // Build the response
                $response = [
                    'status' => 1,
                    'success' => 1,
                    'message' => 'Data Fetched Successfully',
                    'data' => [
                            'promotion_data' => $promotionData,
                            'salary' => $salary,
                            'current_designation_id' => $designation_id,
                            'designation_pervious' => $designation_pervious,
                            'current_designation_name' => $designation_name,
                            'componentTypes' => $componentTypes,
                            'designations' => $designations
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

    }
}

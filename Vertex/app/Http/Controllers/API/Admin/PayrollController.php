<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\Holiday;
use App\Models\PayPeriod;
use App\Models\PayRollApprovals;
use App\Models\SalaryComponent;
use App\Models\SalaryComponentType;
use App\Models\SystemResponse;
use App\Models\TaxSlab;
use App\Models\TaxYear;
use App\Models\UserDailyRecord;
use App\Traits\ProfileImage;
use DateTime;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Emp_salary;
use App\Models\user_approval;
use App\Models\EmployeeDetail;
use App\Models\Designation;
use App\Models\Department;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use App\Models\Company;
use Illuminate\Support\Carbon;
use App\Models\Monthly_payroll;

class PayrollController extends BaseController
{
    use ProfileImage;
    // save a pay roll period
    public function savePayRollPeriod(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'payroll_type' => 'required',
                'company_id' => 'required',
                'branch_id' => 'required',
                // 'department_id' => 'required',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
            ]);
            if ($validation->fails()) {
                return $this->sendError([], $validation->errors(), 400);
            }
            DB::beginTransaction();
            $empCount = EmployeeDetail::where('branch_id', $request->branch_id)
                ->where('company_id', $request->company_id)
                // ->where('status', '1')
                ->where('is_deleted', '0')
                // ->where('is_active', '1')
                ->count();
            if (!$empCount) {
                return response()->json([
                    'stauts' => '0',
                    'success' => false,
                    'message' => 'No data found against this company and branch',
                ]);
            }
            $prePayRoll = PayPeriod::where('company_id', $request->company_id)
                ->where('branch_id', $request->branch_id)
                ->whereMonth('start_date', date('m', strtotime($request->start_date)))
                ->whereYear('start_date', date('Y', strtotime($request->start_date)))
                ->whereMonth('end_date', date('m', strtotime($request->end_date)))
                ->whereYear('end_date', date('Y', strtotime($request->end_date)))
                ->first();

            if ($prePayRoll) {
                return response()->json([
                    'stauts' => '0',
                    'success' => false,
                    'message' => 'You Already have Pay Roll on This Period',
                ]);
            }
            $total_emp = 0;
            $payRollData = PayPeriod::create([
                'payroll_type' => $request->payroll_type,
                'company_id' => $request->company_id,
                'branch_id' => $request->branch_id,
                'department_id' => $request->department_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'total_emp' => $total_emp,
                'closed' => '0',
            ]);
            if ($payRollData) {
                $query = EmployeeDetail::query();
                $query->where('branch_id', $request->branch_id)
                    ->where('company_id', $request->company_id)
                // ->where('status', '1')
                ->where('is_deleted', '0');
                // ->where('is_active', '1');
                if ($payRollData->department_id) {
                    $query->whereHas('approval', function ($query) use ($payRollData) {
                        $query->where('department_id', $payRollData->department_id);
                    });
                }
                $employees = $query->get();
                if (!$employees) {
                    DB::rollBack();
                    return response()->json([
                        'stauts' => '0',
                        'success' => false,
                        'message' => 'No data found against this company and branch',
                    ]);
                } else {
                    foreach ($employees as $employee) {
                        if($employee->terminations){
                            $termination_date = $employee->terminations->notice_date;
                            if ($request->start_date > $termination_date) {
                                continue;
                            }
                        }elseif($employee->resignations){
                            $resignation_date = $employee->resignations->notice_date;
                            if ($request->start_date > $resignation_date) {
                                continue;
                            }
                        }



                        $totalCount = 0;
                        $lateCount = 0;
                        $leaveCount = 0;
                        $holidayCount = 0;
                        $weekCount = 0;
                        $presentCount = 0;
                        $absentCount = 0;
                        $monthlyTax = 0;
                        $lateDeduc = 0;
                        $salData = Emp_salary::where('employee_details_id', $employee->id)
                            ->where('first_working_date', '<', $request->end_date)->first();
                        if ($salData) {
                            $total_emp ++;
                            $basicSal = (int) $salData->taxable_salary;
                            $empSal = $basicSal;
                            $remainingSal = $basicSal;
                            $start_date = Carbon::parse($request->start_date);
                            $end_date = Carbon::parse($request->end_date);
                            $empDays = $start_date->diffInDays($end_date) + 1;
                            if ($salData->first_working_date > $request->start_date) {
                                $start_date = Carbon::parse($salData->first_working_date);
                                $empDays = $start_date->diffInDays($end_date) + 1;
                                $perDaySal = $basicSal / 30;
                                $empSal = $empDays * $perDaySal;

                            }
                            // check resignations
                            if ($employee->resignations) {
                                $resignationDate = $employee->resignations->notice_date;
                                if ($resignationDate < $request->end_date) {
                                    $end_date = Carbon::parse($resignationDate);
                                    $empDays = $start_date->diffInDays($end_date) + 1;
                                    $perDaySal = $basicSal / 30;
                                    $empSal = $empDays * $perDaySal;
                                }
                            }
                            // check terminations
                            if ($employee->terminations) {
                                $terminationDate = $employee->terminations->notice_date;
                                if ($terminationDate < $request->end_date) {
                                    $end_date = Carbon::parse($terminationDate);
                                    $empDays = $start_date->diffInDays($end_date) + 1;
                                    $perDaySal = $basicSal / 30;
                                    $empSal = $empDays * $perDaySal;
                                }
                            }
                            $company_detail = CompanySetting::where('branch_id', $employee->branch_id)->where('company_id', $employee->company_id)
                                ->where('is_deleted', '0')
                                ->first();
                            $late_time = new DateTime($company_detail->late_time);
                            $workingDays = ($company_detail ? explode(',', strtolower($company_detail->days)) : []);

                            $attendanceData = UserDailyRecord::where('emp_id', $employee->id)
                                ->whereBetween('dated', [$start_date->format('Y-m-d'), $end_date->format('Y-m-d')])->get();
                            if ($attendanceData) {
                                foreach ($attendanceData as $attendance) {
                                    if ($attendance->check_in != null) {
                                        $presentCount++;
                                        $emp_check_in = new DateTime($attendance->check_in);
                                        if ($late_time < $emp_check_in) {
                                            $lateCount++;
                                        }
                                    } elseif ($attendance->leave && $attendance->leave_type != null && $attendance->leave_type != 5) {
                                        $leaveCount++;
                                    }

                                }
                            }

                            $query = Holiday::where('is_deleted', '0')
                                ->where('is_active', '1')
                                ->where(function ($query) use ($start_date, $end_date) {
                                    $query->whereBetween('start_date', [$start_date, $end_date])
                                        ->orWhereBetween('end_date', [$start_date, $end_date]);
                                })
                                ->whereRaw("FIND_IN_SET(?, branch_id)", [$employee->branch_id])
                                ->where('company_id', $employee->company_id);
                            $holidayCount = $query->count();
                            $s_date = $start_date->copy();
                            for ($date = $s_date; $date->lte($end_date); $date->addDay()) {
                                $totalCount++;
                                if (!in_array(strtolower($date->format('l')), $workingDays)) {
                                    $weekCount++;
                                }
                            }

                            $absentCount = $totalCount - ($holidayCount + $weekCount + $presentCount + $leaveCount);
                            if ($company_detail->late_limit > 0) {
                                $lateDeduc = intdiv($lateCount, $company_detail->late_limit);
                            } else {
                                $lateDeduc = 0;
                            }
                            $componentType = SalaryComponentType::all();
                            $deduction = 0;
                            $allowance = 0;
                            $contribution = 0;
                            foreach ($componentType as $type) {
                                if ($type->type == 'Deduction') {
                                    $deductionData = SalaryComponent::where('employee_details_id', $employee->id)->where('component_type_id', $type->id)->first();
                                    if ($deductionData) {
                                        $deduction = $deductionData->amount;
                                    }
                                } elseif ($type->type == 'Allowance') {
                                    $allowanceData = SalaryComponent::where('employee_details_id', $employee->id)->where('component_type_id', $type->id)->first();
                                    if ($allowanceData) {
                                        $allowance = $allowanceData->amount;
                                    }
                                } else {
                                    $contributionData = SalaryComponent::where('employee_details_id', $employee->id)->where('component_type_id', $type->id)->first();
                                    if ($contributionData) {
                                        $contribution = $contributionData->amount;
                                    }
                                }
                            }
                            if ($empSal > 0) {

                                $countryId = $employee->branch->country_id;
                                $taxYear = TaxYear::where('country_id', $countryId)
                                    ->where(function ($query) use ($start_date, $end_date) {
                                        $query->where('from_year', '<=', $start_date)
                                            ->where('to_year', '>=', $end_date);
                                    })->first();

                                if ($taxYear) {
                                    $from_year = Carbon::parse($taxYear->from_year)->year;
                                    $to_year = Carbon::parse($taxYear->to_year)->year;
                                    $joining_date = Carbon::parse($salData->first_working_date)->startOfMonth();
                                    $financial_from_date = Carbon::createFromDate($from_year, $company_detail->financial_month_from, 1);
                                    $financial_to_date = Carbon::createFromDate($to_year, $company_detail->financial_month_to, 1);

                                    $multiplier = 12;
                                    if ($joining_date > $financial_from_date && $joining_date < $financial_to_date) {
                                        $multiplier = $joining_date->diffInMonths($financial_to_date) + 1;

                                    } elseif ($joining_date == $financial_to_date) {
                                        $multiplier = 1;
                                    }

                                    $annualSal = (int) $basicSal * $multiplier;
                                    $slab = TaxSlab::where('year_id', $taxYear->id)
                                        ->where(function ($query) use ($annualSal) {
                                            $query->where(function ($q) use ($annualSal) {
                                                $q->where('start_range', '<', $annualSal)
                                                    ->where('end_range', '>=', $annualSal);
                                            })
                                                ->orWhere(function ($q) use ($annualSal) {
                                                    $q->where('start_range', '<=', $annualSal)
                                                        ->where('end_range', 0);
                                                });
                                        })
                                        ->first();

                                    if ($slab) {
                                        $exceedingAmount = (int) $annualSal - $slab->amount_exceed;
                                        $taxAmount = ((int) (($slab->tax_percent / 100) * $exceedingAmount) + $slab->fixed_amount);
                                        $monthlyTax = (int) $taxAmount / 12;
                                        if ($empDays < 28) {
                                            $dayTax = $monthlyTax / 30;
                                            $monthlyTax = $dayTax * $empDays;
                                            $remainingSal = $empSal - $monthlyTax;
                                        } else {
                                            $remainingSal = $empSal - $monthlyTax;
                                        }
                                    }
                                }
                            }
                            $perDaySal = $basicSal / 30;
                            $absentDeduc = $absentCount * $perDaySal;
                            $lateDe = $lateDeduc * $perDaySal;
                            $absent_deduction = $absentDeduc + $lateDe;
                            $remainingSal -= $absent_deduction;
                            $remainingSal -= (int) $deduction;
                            $monthlyIncome = $empSal + (int) $allowance;

                            // For api testing
                            // return [
                            //     'pay_period_id' => $payRollData->id,
                            //     'emp_id' => $employee->id,
                            //     'emp_type' => $employee->approval->approvalToJobStatus->job_status ?? '',
                            //     'department' => $employee->approval->approvalToDept->id ?? null,
                            //     'designation' => $employee->approval->designation->id ?? null,
                            //     'basic_salary' => $salData->taxable_salary,
                            //     'late' => $lateCount,
                            //     'leave' => $leaveCount,
                            //     'absent' => $absentCount,
                            //     'absent_deduction' => floor($absent_deduction),
                            //     'sales_incentive' => null,
                            //     'allowances' => $allowance,
                            //     'loan' => null,
                            //     'deduction' => $deduction,
                            //     'monthly_incom' => $monthlyIncome,
                            //     'monthly_tax' => $monthlyTax,
                            //     'net_salary' => floor($remainingSal),
                            //     'status' => 1,

                            // ];
                            PayRollApprovals::create([
                                'pay_period_id' => $payRollData->id,
                                'emp_id' => $employee->id,
                                'emp_type' => $employee->approval->approvalToJobStatus->job_status ?? '',
                                'department' => $employee->approval->approvalToDept->id ?? null,
                                'designation' => $employee->approval->designation->id ?? null,
                                'basic_salary' => $salData->taxable_salary,
                                'late' => $lateCount,
                                'leave' => $leaveCount,
                                'absent' => $absentCount,
                                'sales_incentive' => null,
                                'allowances' => $allowance,
                                'loan' => null,
                                'absent_deduction' => $absent_deduction,
                                'deduction' => $deduction,
                                'monthly_incom' => $monthlyIncome,
                                'monthly_tax' => $monthlyTax,
                                'net_salary' => $remainingSal,
                                'paid_date' => null,
                                'status' => 1,

                            ]);
                        }

                    }
                }
            }

            $payRollData->update([
                'total_emp' => $total_emp,
            ]);

            $user = auth()->user();
            $msg = '"' . $user->fullname . '" ' . "Created " . '"' . $payRollData->payRollToBranch->branch_name . '"' . " Pay Period";
            createLog('payperiod-action', $msg);
            DB::commit();
            return response()->json([
                'status' => 1,
                'success' => true,
                'message' => 'Pay Roll created successfully...',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 0,
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // list pay pay period
    public function listPayPeriod(Request $request)
    {

        $per_page = $request->per_page ?? 8;
        $results = [];

        $query = PayPeriod::query();
        if ($request->search_by) {
            $query->where(function ($query) use ($request) {
                $query->whereHas('payRollToBranch', function ($query) use ($request) {
                    $query->where('branch_name', 'like', '%' . $request->search_by . '%');
                })
                    ->orWhereHas('payRollToCompany', function ($query) use ($request) {
                        $query->where('company_name', 'like', '%' . $request->search_by . '%');
                    });
            });
        }
        $query->orderByDesc('created_at');
        $data = $query->get();
        foreach ($data as $record) {
            $each = [
                'id' => $record->id,
                'payroll_type' => $record->payroll_type,
                'company' => $record->payRollToCompany->company_name,
                'branch' => $record->payRollToBranch->branch_name,
                'total_emp' => $record->total_emp,
                'start_date' => $record->start_date,
                'end_date' => $record->end_date,
                'net_salary' => $record->net_salary,
                'remarks' => $record->remarks,
                'closed' => $record->closed,
                'created_at' => Carbon::parse($record->created_at)->format('Y-m-d'),
            ];
            array_push($results, $each);
        }
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect($results);
        $currentPageResults = $collection->slice(($currentPage - 1) * $per_page, $per_page)->values()->all();
        $paginatedResults = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);

        $paginatedResults->setPath($request->url());

        return response()->json([
            'status' => 1,
            'success' => true,
            'message' => 'Pay periods fetched successfully',
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


    // update pay roll approval status
    public function updatePayrollApprovalStatus(Request $request)
    {
        $user = auth()->user();
        try {
            DB::beginTransaction();
            $paryRollData = PayRollApprovals::where('pay_period_id', $request->pay_period_id)
                ->where('status', $request->current_status)
                ->get();
            if ($request->current_status == ' 1') {
                foreach ($paryRollData as $payRoll) {
                    $payRoll->update(['status' => $request->status]);
                }
                $msg = '"' . $user->fullname . '" ' . 'Update Statu Pending of Employee Pay Roll';
                createLog('payperiod-action', $msg);
            } elseif ($request->current_status == '2') {
                foreach ($paryRollData as $payRoll) {
                    $payRoll->update(['status' => $request->status]);
                }
                $msg = '"' . $user->fullname . '" ' . 'Update Statu Pending of Employee Pay Roll';
                createLog('payperiod-action', $msg);
            } elseif ($request->current_status == '3') {
                foreach ($paryRollData as $payRoll) {
                    $payRoll->update(['status' => $request->status]);
                }
            }
            $checkApproval = PayRollApprovals::where('pay_period_id', $request->pay_period_id)
                ->where('status', '!=', '3')
                ->count();
            if ($checkApproval == 0) {
                $payPeriod = PayPeriod::where('id', $request->pay_period_id)->where('closed', '0')->first();
                if ($payPeriod) {
                    $netSum = PayRollApprovals::where('pay_period_id', $request->pay_period_id)
                        ->where('status', '3')
                        ->sum('net_salary');
                    $payPeriodUpdate = PayRollApprovals::where('pay_period_id', $request->pay_period_id)->where('status', '3')->get();
                    foreach ($payPeriodUpdate as $period) {
                        $period->update(['paid_date' => $request->paid_date]);
                    }
                    $payPeriod->update([
                        'closed' => '1',
                        'remarks' => $request->remarks ?? null,
                        'net_salary' => $netSum
                    ]);
                }
            }
            DB::commit();
            return response()->json([
                'status' => 1,
                'success' => true,
                'message' => 'Payroll approval status updated successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 0,
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    // list of pay roll approvals

    public function listPayRollApprval(Request $request)
    {
        $per_page = $request->per_page ?? 8;
        $user_data = [];
        $results = [];

        $query = PayRollApprovals::query();

        if ($request->search_by) {
            $query->where(function ($query) use ($request) {
                $query->whereHas('payRollApprovalToEmp', function ($query) use ($request) {
                    $query->where('emp_name', 'like', '%' . $request->search_by . '%')
                        ->orWhere('emp_id', 'like', '%' . $request->search_by . '%');
                });
            });
        }

        if ($request->department_id) {
            $query->whereHas('payRollApprovalToEmp.approval.approvalToDept', function ($query) use ($request) {
                $query->where('id', $request->department_id);
            });
        }

        if ($request->designation_id) {
            $query->whereHas('payRollApprovalToEmp.approval.designation', function ($query) use ($request) {
                $query->where('id', $request->designation_id);
            });
        }

        $query->where('status', $request->status);
        $query->where('pay_period_id', $request->pay_period_id);

        $draftCount = PayRollApprovals::where('status', '1')->where('pay_period_id', $request->pay_period_id)->count();
        $pendingCount = PayRollApprovals::where('status', '2')->where('pay_period_id', $request->pay_period_id)->count();
        $approvedCount = PayRollApprovals::where('status', '3')->where('pay_period_id', $request->pay_period_id)->count();

        $data = $query->get();

        foreach ($data as $record) {
            $each = [
                'id' => $record->id,
                'emp_id' => $record->payRollApprovalToEmp->emp_id,
                'emp_name' => $record->payRollApprovalToEmp->emp_name,
                'emp_image' => $this->imgFunc($record->payRollApprovalToEmp->emp_image, $record->payRollApprovalToEmp->emp_gender),
                'emp_type' => $record->emp_type ?? null,
                'department' => $record->payRollApprovalToEmp->approval->approvalToDept->name ?? null,
                'designation' => $record->payRollApprovalToEmp->approval->designation->name ?? null,
                'basic_salary' => $record->basic_salary,
                'late' => $record->late,
                'leave' => $record->leave,
                'absent' => $record->absent,
                'sales_incentive' => $record->sales_incentive,
                'allowances' => $record->allowances,
                'loan' => $record->loan,
                'deduction' => $record->deduction,
                'absent_deduction' => $record->absent_deduction,
                'monthly_incom' => $record->monthly_incom,
                'monthly_tax' => $record->monthly_tax,
                'net_salary' => $record->net_salary,
                'paid_date' => $record->paid_date,
            ];
            $user_data[] = $each;
        }

        $results = [
            'draft_count' => $draftCount,
            'pending_count' => $pendingCount,
            'approved_count' => $approvedCount
        ];

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect(['user_data' => $user_data, 'results' => $results]);
        $currentPageResults = $collection->slice(($currentPage - 1) * $per_page, $per_page)->values()->all();
        $paginatedResults = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);
        $paginatedResults->setPath($request->url());

        return response()->json([
            'status' => 1,
            'success' => true,
            'message' => 'Data fetched successfully',
            'data' => [
                'user_data' => $user_data,
                'results' => $results,
            ],
            'pagination' => [
                'current_page' => $paginatedResults->currentPage(),
                'next_page_url' => $paginatedResults->nextPageUrl(),
                'path' => $paginatedResults->path(),
                'per_page' => $paginatedResults->perPage(),
                'prev_page_url' => $paginatedResults->previousPageUrl(),
                'to' => $paginatedResults->count() > 0 ? $paginatedResults->lastItem() : null,
                'total' => $paginatedResults->total(),
                'total_pages' => $paginatedResults->lastPage(),
            ]
        ]);
    }



    // destroy pay period
    public function destroyPayPeriod(Request $request)
    {
        $user = auth()->user();
        try {
            $payPeriod = PayPeriod::where('id', $request->period_id)->where('closed', '0')->first();
            if ($payPeriod) {
                DB::beginTransaction();
                $payRolls = PayRollApprovals::where('pay_period_id', $payPeriod->id)->get();
                foreach ($payRolls as $payRoll) {
                    $payRoll->delete();
                }
                $payPeriod->delete();

                $msg = '"' . $user->fullname . '" ' . "Deleted " . '"' . $payPeriod->payRollToBranch->branch_name . '"' . " Pay Period";
                createLog('payperiod-action', $msg);
                DB::commit();
                return response()->json([
                    'status' => true,
                    'success' => 1,
                    'message' => 'Pay Period deleted successfully...'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'success' => 0,
                    'message' => 'Pay Period has been closed'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'success' => 0,
                'message' => $e->getMessage()
            ]);
        }
    }


    // download payroll csv

    public function downloadPayRollCSV(Request $request)
    {
        $payRoll_data = PayRollApprovals::select('employee_details.emp_id as emp_card', 'employee_details.emp_name', 'employee_details.branch_id', 'pay_roll_approvals.*')
            ->join('employee_details', 'pay_roll_approvals.emp_id', '=', 'employee_details.id')
            ->with('payRollApprovalToEmp')
            ->where('pay_roll_approvals.pay_period_id', $request->pay_period_id)
            ->where('pay_roll_approvals.status', $request->status)
            ->get();
        $fileName = 'payRoll_' . Carbon::now()->format('Y_m_d_H_i_s') . '.csv';
        $filePath = Storage::path('public/' . $fileName);
        $file = fopen($filePath, 'w');

        if (!$file) {
            $response = SystemResponse::find(2);
            return response()->json([
                'status' => 0,
                'success' => false,
                'message' => $response ? $response->message : 'Error creating CSV file'
            ]);
        }

        $header = [
            'Employee Id',
            'Employee Name',
            'Employee Type',
            'Location',
            'Department',
            'Designation',
            'Basic Salary',
            'Late',
            'Leave',
            'Absent',
            'Sales Incentive',
            'Allowances',
            'Loan',
            'Deduction',
            'Absent Deduction',
            'Monthly Income',
            'Monthly Tax',
            'Net Salary',
            'Paid Date',
            'Status'
        ];
        fputcsv($file, $header);

        foreach ($payRoll_data as $row) {
            fputcsv($file, [
                $row->emp_card,
                $row->emp_name,
                $row->emp_type ?? '',
                $row->payRollApprovalToEmp->branch->branch_name ?? '',
                $row->payRollApprovalToEmp->approval->approvalToDept->name ?? '',
                $row->payRollApprovalToEmp->approval->designation->name ?? '',
                $row->basic_salary ? number_format($row->basic_salary) : '',
                $row->late ? number_format($row->late) : '0',
                $row->leave ? number_format($row->leave) : '0',
                $row->absent ? number_format($row->absent) : '0',
                $row->sales_incentive ? number_format($row->sales_incentive) : '0',
                $row->allowances ? number_format($row->allowances) : '0',
                $row->loan ? number_format($row->loan) : '0',
                $row->deduction ? number_format($row->deduction) : '0',
                $row->absent_deduction ? number_format($row->absent_deduction) : '0',
                $row->monthly_incom ? number_format($row->monthly_incom) : '',
                $row->monthly_tax ? number_format($row->monthly_tax) : '',
                $row->net_salary ? number_format($row->net_salary) : '',
                $row->paid_date ?? '',
                $row->status == 1 ? 'Draft' : ($row->status == 2 ? 'Pending' : ($row->status == 3 ? 'Approved' : '')),
            ]);
        }


        fclose($file);
        $downloadLink = url('api/download-payroll-sheet?file_path=' . $fileName);
        $response = SystemResponse::find(3);
        return $this->sendResponse($downloadLink, $response->message, 200);
    }


    public function downloadPayRollSheet(Request $request)
    {
        $filePath = 'public/' . $request->file_path;
        return response()->download(Storage::path($filePath))->deleteFileAfterSend(true);
    }


}



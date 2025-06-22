<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CronJobHistory;
use App\Models\Department;
use App\Models\Designation;
use App\Models\EmployeeDetail;
use App\Models\EmployeeResignation;
use App\Models\Location;
use App\Models\User;
use App\Models\user_approval;
use App\Models\UserDailyRecord;
use App\Models\ZKSyncEmp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class HBLMFBController extends Controller
{
    // push attendance on HBL oracle database
    public function exportDataToOracle()
    {
        $cronJobStartTime = date('Y-m-d H:i:s');
        $cronJobTime = new CronJobHistory();
        $cronJobTime->start_time = $cronJobStartTime;
        $cronJobTime->type = 'sync-attendance-hbl';
        $cronJobTime->status = 'in-progress';
        $cronJobTime->save();
        $allEmpAttendence = UserDailyRecord::whereDate('dated', Date('Y-m-d'))->get();
        foreach ($allEmpAttendence as $empAttendance) {

            $employee = EmployeeDetail::where('id', $empAttendance->emp_id)->first();
            // return $employee->emp_id;
            $checkEmpAttendance = DB::connection('oracle')->table("EMP_ATTENDENCE")->where('EMP_ID', $employee->emp_id)->where('CHECK_IN', date('Y-M-d H:i:s', strtotime(Date("Y-m-d") . $empAttendance->check_in)))->first();
            // return $checkEmpAttendance;
            if ($checkEmpAttendance && $checkEmpAttendance->check_in != null) {
                $checkOut = null;
                if ($empAttendance->check_out != null) {
                    $checkOut = date('Y-M-d H:i:s', strtotime(Date('Y-M-d') . $empAttendance->check_out));
                }
                // return $checkOut;
                $empAttData = [
                    'EMP_ID' => $employee->emp_id,
                    'CHECK_IN' => date('Y-M-d H:i:s', strtotime(Date('Y-M-d') . $empAttendance->check_in)),
                    'CHECK_OUT' => $checkOut
                ];
                $connection = DB::connection('oracle')->table('EMP_ATTENDENCE')->where('EMP_ID', $employee->emp_id)->whereDate('CHECK_IN', date('Y-M-d', strtotime(Date("Y-m-d") . $empAttendance->check_in)))->update($empAttData);

            } else {

                // return [date('Y-M-d H:i:s', strtotime(Date('Y-M-d') . $empAttendance->check_in)),"in else"];

                $empAttData = [
                    'EMP_ID' => $employee->emp_id,
                    'CHECK_IN' => date('Y-M-d H:i:s', strtotime(Date('Y-M-d') . $empAttendance->check_in))
                ];
                $connection = DB::connection('oracle')->table('EMP_ATTENDENCE')->insert($empAttData);

            }
            // return $empAttendance;
        }
        $CronJobDetail = CronJobHistory::where('id', $cronJobTime->id)->first();
        $CronJobDetail->end_time = date('Y-m-d H:i:s');
        $CronJobDetail->status = 'completed';
        $CronJobDetail->save();
    }


    // fetch employees from HBL oracel database
    public function importEmployees()
    {
        set_time_limit(0);
        DB::beginTransaction();
        try {
            $cronJobStartTime = date('Y-m-d H:i:s');
            $cronJobTime = new CronJobHistory();
            $cronJobTime->start_time = $cronJobStartTime;
            $cronJobTime->type = 'import-hbl-user';
            $cronJobTime->status = 'in-progress';
            $cronJobTime->save();

            $employeeData = DB::connection('oracle')->table("CUST_EMPLOYEE_AMS_V")->get();

            if ($employeeData) {
                foreach ($employeeData as $key => $item) {

                    if ($item) {
                        $company = Company::updateOrCreate(
                            ['company_name' => "HBL Microfinance Bank"],
                            [
                                "phone" => "03341234567",
                                "company_name" => "HBL Microfinance Bank",
                                "email" => "hbl.pakistan@gmail.com",
                                "contact_person" => "HBL Admin",
                                "country_id" => 166,
                                "city_id" => 31439,
                                "address" => "HBL Microfinance Islamabad, Pakistan",
                                "website" => "https://www.hbl.com/",
                                "is_deleted" => '0'
                            ]
                        );
                    }
                    if ($company) {
                        $location = Location::updateOrCreate(
                            ['branch_id' => $item->location_code],
                            [
                                'company_id' => $company->id,
                                'branch_name' => $item->location,
                                'branch_id' => $item->location_code,
                                "country_id" => 166,
                                "city_id" => 31439,
                                "is_deleted" => '0'
                            ]
                        );
                    }
                    if ($location) {
                        $department = Department::updateOrCreate(
                            ['name' => $item->department],
                            ['name' => $item->department]
                        );

                        if ($item->designation != null && $item->designation != "") {
                            $designation = Designation::updateOrCreate(
                                [
                                    'name' => $item->designation
                                ],
                                [
                                    'name' => $item->designation
                                ]
                            );
                        }
                        $official_mail = $item->official_email_id != "" && $item->official_email_id != null ? $item->official_email_id : NULL;
                        $emp_status = ($item->employee_status == 'Terminate Assignment') ? '4' : '1';
                        $employeeRecord = EmployeeDetail::updateOrCreate([
                            'emp_id' => $item->employee_number
                        ], [
                            'company_id' => $company->id,
                            'branch_id' => $location->id,
                            "emp_id" => $item->employee_number,
                            "emp_name" => $item->employee_name,
                            "personal_email" => '',
                            "emp_phone" => 0,
                            "cnic" => 0,
                            "starting_sal" => 0,
                            "emp_gender" => "M",
                            "added_by" => '99999',
                            "is_active" => "1",
                            "is_deleted" => "0",
                            "emp_email" => $official_mail,
                            "attend_pin" => $item->employee_number,
                            "status" => $emp_status
                        ]);

                        if($emp_status == '4'){
                            EmployeeResignation::updateOrCreate(
                                [
                                    'company_id' => $company->id,
                                    'branch_id' => $location->id,
                                    "emp_id" => $item->employee_number,
                                ],
                                [
                                    'company_id' => $company->id,
                                    'branch_id' => $location->id,
                                    "emp_id" => $item->employee_number,
                                    'resignation_date' => $item->date_of_leaving,
                                    'notice_date'=> $item->date_of_leaving,
                                    'reason' => '',
                                    'is_approved' => '1'
                                ]);
                        }

                        $fetchUpdatedAt = EmployeeDetail::where('emp_id',$item->employee_number)->first();
                        if( $fetchUpdatedAt &&  $fetchUpdatedAt->updated_at !=  $employeeRecord->updated_at){
                            ZKSyncEmp::updateOrCreate([
                                'emp_id' => $employeeRecord->id,
                                'old_branch' => null
                            ], [
                                'emp_id' => $employeeRecord->id,
                                'synced' => '0',
                                'action' => $emp_status == '1' ? 'create' : 'delete',
                                'old_branch' => null
                            ]);
                        }elseif(!$fetchUpdatedAt ){
                            ZKSyncEmp::updateOrCreate([
                                'emp_id' => $employeeRecord->id,
                                'old_branch' => null
                            ], [
                                'emp_id' => $employeeRecord->id,
                                'synced' => '0',
                                'action' => $emp_status == '1' ? 'create' : 'delete',
                                'old_branch' => null
                            ]);
                        }

                        $getDate = date("Y-m-d", strtotime($item->appointment_date));

                        if ($employeeRecord) {
                            $user = User::updateOrCreate(
                                [
                                    'emp_id' => $employeeRecord->id
                                ],
                                [
                                    "company_id" => $company->id,
                                    "branch_id" => $location->id,
                                    "emp_id" => $employeeRecord->id,
                                    // "role_id" => 3,
                                    "fullname" => $item->employee_name,
                                    "gender" => "M",
                                    "email" => $item->official_email_id ?? 'hbl@gmail.com',
                                    // "password" => hash::make('12345'),
                                    "phone" => ($item->phone_number != "") ? $item->phone_number : NULL
                                ]
                            );


                            user_approval::updateOrCreate(
                                [
                                    'emp_id' => $employeeRecord->id
                                ],
                                [
                                    'emp_id' => $employeeRecord->id,
                                    'user_id' => $user->id,
                                    'starting_sal'=>0,
                                    'designation_id' => $designation->id,
                                    "joining_date" => $getDate,
                                    "is_active" => "1",
                                    "is_deleted" => "0",
                                ]
                            );
                        }

                    }

                }
            }
            $CronJobDetail = CronJobHistory::where('id', $cronJobTime->id)->first();
            $CronJobDetail->end_time = date('Y-m-d H:i:s');
            $CronJobDetail->status = 'completed';
            $CronJobDetail->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }
    }

}

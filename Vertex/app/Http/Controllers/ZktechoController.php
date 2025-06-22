<?php

namespace App\Http\Controllers;

use App\Models\CronJobHistory;
use App\Models\DeviceStatusHistory;
use App\Models\Emp_termination;
use App\Models\EmployeeResignation;
use App\Models\UserDailyRecord;
use App\Models\ZKSyncEmp;
use App\Traits\ZKConnection;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use App\Models\UserAttendence;
use App\Models\CronJobTime;
use App\Models\EmployeeDetail;
use App\Models\DeviceManagement;
use DB;
use Jmrashed\Zkteco\Lib\ZKTeco;

class ZktechoController extends Controller
{
    use ZKConnection;

    private $devices = [];
    public function __construct()
    {
        $this->getDeviceData();
    }
    // function to make connection and get device data
    public function getDeviceData()
    {
        $device_ip = DeviceManagement::where('is_deleted', '0')->get();
        if ($device_ip) {
            foreach ($device_ip as $ip) {
                $status = $this->pingDevice($ip->device_ip);
                if ($status) {
                    $device = new ZKTeco($ip->device_ip);
                    $connected = $device->connect();
                    $this->devices[] = [
                        'sr_no' => $ip->serial_number,
                        'device' => $device,
                        'connected' => $connected ? '1' : '0'
                    ];
                } else {
                    $this->devices[] = [
                        'sr_no' => $ip->serial_number,
                        'device' => null,
                        'connected' => '0'
                    ];
                }
            }
        }
    }
    public function conn()
    {
        // dd('hi');
        // $this->devices;

        // $status = $this->pingDevice('192.168.1.253');
        // dd($status);

        $device = new ZKTeco('192.168.1.253');
        $device->connect();



        // $device->setUser(111, 197, 'zohaib', 1, 0);

        // $device->removeUser(110);
        // dd('success');
        $fingerprintData = $device->getFingerprint(110);
        // dd($fingerprintData);
        foreach ($fingerprintData as $finger => $data) {
            $encodedData = base64_encode($data);
            EmployeeDetail::updateOrCreate(
                [
                    'id' => 110,
                ],
                [
                    'attend_pin' => $finger,
                    'fingerprint' => $encodedData,
                ]
            );
            return 'success';
        }



        $fingerprints = EmployeeDetail::where('id', 110)->first();


        // foreach ($fingerprints as $fingerprint) {

        // Decode from base64 to binary
        $binaryData = base64_decode($fingerprints->fingerprint);
        $a[$fingerprints->attend_pin] = $binaryData;
        // Send to the device
        // dd($fingerprints->fingerprint);
        $device->setFingerprint(111, $a);
        // dd($d);
        // }





        // $employee = EmployeeDetail::find(14);

        // if ($getEmployees) {
        //     foreach ($getEmployees as $employee) {

        // dd($this->devices);
        // foreach ($this->devices as $device) {
        //     $role = "14";
        //     $password = $employee->attend_pin;
        //     $device['device']->setUser($employee->id, $employee->emp_id, $employee->emp_name, $password, $role);

        // }
        // return 'success';
        //     }







        // $s = $device->serialNumber();
        // dd($s);
        // $device->testVoice();
        // foreach ($this->devices as $device) {
        //    dd($this->devices);
        // }

    }
    // count total user on a device
    public function countUserOnDevice()
    {
        try {
            DB::beginTransaction();
            set_time_limit(0);
            $cronJobStartTime = date('Y-m-d H:i:s');
            $cronJobTime = new CronJobHistory();
            $cronJobTime->start_time = $cronJobStartTime;
            $cronJobTime->type = 'users-of-ZK-are-counted';
            $cronJobTime->status = 'in-progress';
            $cronJobTime->save();
            foreach ($this->devices as $device) {
                if ($device['connected'] == '1') {
                    DB::beginTransaction();
                    $serial_data = $device['device']->serialNumber();
                    $extract_serial = strpos($serial_data, '=');
                    $serialNumber = substr($serial_data, $extract_serial + 1);
                    $serialNumber = rtrim($serialNumber, "\x00");
                    $count = $device['device']->getUser();
                    DeviceManagement::where('serial_number', $serialNumber)->update([
                        'enrolled_users' => count($count),
                    ]);
                    DB::commit();
                }
            }
            $CronJobDetail = CronJobHistory::where('id', $cronJobTime->id)->first();
            $CronJobDetail->end_time = date('Y-m-d H:i:s');
            $CronJobDetail->status = 'completed';
            $CronJobDetail->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }




    // count total user on single device
    public function countUserOnSingleDevice($ip)
    {
        try {
            DB::beginTransaction();
            set_time_limit(0);
            $cronJobStartTime = date('Y-m-d H:i:s');
            $cronJobTime = new CronJobHistory();
            $cronJobTime->start_time = $cronJobStartTime;
            $cronJobTime->type = 'users-of-single-ZK-are-counted';
            $cronJobTime->status = 'in-progress';
            $cronJobTime->save();
            $device = DeviceManagement::where('device_ip', $ip)->where('is_deleted', '0')->first();
            $status = $this->pingDevice($ip);
            if ($status) {
                DB::beginTransaction();
                $conn = new ZKTeco($ip);
                $conn->connect();
                $count = $conn->getUser();
                DeviceManagement::where('serial_number', $device->serial_number)->update([
                    'enrolled_users' => count($count),
                ]);
                DB::commit();
            }

            $CronJobDetail = CronJobHistory::where('id', $cronJobTime->id)->first();
            $CronJobDetail->end_time = date('Y-m-d H:i:s');
            $CronJobDetail->status = 'completed';
            $CronJobDetail->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }



    public function zkAttendance()
    {

        // $zk = new ZKTeco($this->getDeviceIp()->device_ip);
        // $zk->connect();
        // //  return $zk->restart();
        // $finger = $zk->getFingerprint(11);
        // return mb_convert_encoding($finger, 'UTF-8', 'UTF-8');
        // //  return $zk->setFingerprint("11",$finger);
        // $finger1 = $zk->getFingerprint(6);
        // return mb_convert_encoding($finger, 'UTF-8', 'UTF-8');

    }
    public function updateDeviceStatus(Request $request)
    {
        try {
            DB::beginTransaction();
            $id = $request->id;
            $device = DeviceManagement::where('id', $id)->first();
            $device->is_active = '0';
            $device->update();
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
    public function syncCounts(Request $request)
    {
        // $id = $request->id;
        // $device = DeviceManagement::where('id', $id)->first();
        // $device_ip = $device->device_ip;

        // $zk = new ZKTeco($device_ip);
        // $connected = $zk->connect();

        // if ($connected) {
        //     $device->is_active = '1';
        //     $heartbeat_time = $zk->getTime();
        //     $users = $zk->getUser();
        //     $records = $zk->getAttendance();

        //     $device->total_records = count($records);
        //     $device->total_users = count($users);
        //     $device->heartbeat_time = $heartbeat_time;
        // } else {
        //     $device->is_active = '0';
        // }

        // $device->update();

        // $data['users'] = $device->total_users;
        // $data['records'] = $device->total_records;
        // $data['is_active'] = $device->is_active;
        // $data['heartbeat_time'] = date('d-m-Y, h:i A', strtotime($device->heartbeat_time));

        // return response()->json(['success' => true, 'data' => $data]);
    }
    public function getAllUser()
    {
        foreach ($this->devices as $device) {
            if ($device['connected'] == '1') {
                dd($device['device']->getUser());
            }
        }
    }
    // add all user on  device
    public function createAllUser()
    {
        try {
            DB::beginTransaction();
            set_time_limit(0);
            $cronJobStartTime = date('Y-m-d H:i:s');
            $cronJobTime = new CronJobHistory();
            $cronJobTime->start_time = $cronJobStartTime;
            $cronJobTime->type = 'all-user-uploaded-on-ZK';
            $cronJobTime->status = 'in-progress';
            $cronJobTime->save();
            foreach ($this->devices as $device) {
                if ($device['connected'] == '1') {
                    $serial_data = $device['device']->serialNumber();
                    $extract_serial = strpos($serial_data, '=');
                    $serialNumber = substr($serial_data, $extract_serial + 1);
                    $serialNumber = rtrim($serialNumber, "\x00");
                    $device_data = DeviceManagement::where('serial_number', $serialNumber)->first();
                    $getEmployees = EmployeeDetail::where('status', '1')->where('is_deleted', '0')
                        ->where('is_active', '1')->where('branch_id', $device_data->branch_id)
                        ->where('company_id', $device_data->company_id)->get();
                    if ($getEmployees) {
                        foreach ($getEmployees as $employee) {
                            $role = "0";
                            $password = $employee->attend_pin;
                            $device['device']->setUser($employee->id, $employee->emp_id, $employee->emp_name, $password, $role);

                            ZKSyncEmp::updateOrCreate([
                                'emp_id' => $employee->id,
                                'old_branch' => null
                            ], [
                                'emp_id' => $employee->id,
                                'synced' => '1',
                                'action' => 'create',
                                'old_branch' => null
                            ]);
                        }
                    }
                } else {
                    return false;
                }
            }

            $CronJobDetail = CronJobHistory::where('id', $cronJobTime->id)->first();
            $CronJobDetail->end_time = date('Y-m-d H:i:s');
            $CronJobDetail->status = 'completed';
            $CronJobDetail->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
    // add or update daily user on device
    public function createOrUpdateUser()
    {
        try {
            DB::beginTransaction();
            set_time_limit(0);
            $cronJobStartTime = date('Y-m-d H:i:s');
            $cronJobTime = new CronJobHistory();
            $cronJobTime->start_time = $cronJobStartTime;
            $cronJobTime->type = 'upload-today-created/updated-user-on-ZK';
            $cronJobTime->status = 'in-progress';
            $cronJobTime->save();

            // Track users to be removed from old branches
            $usersToRemoveFromOldBranches = [];

            foreach ($this->devices as $device) {
                if ($device['connected'] == '1') {
                    $serial_data = (string) $device['device']->serialNumber();
                    $serialNumber = substr($serial_data, strpos($serial_data, '=') + 1);
                    $serialNumber = rtrim($serialNumber, "\x00");

                    $device_data = DeviceManagement::where('serial_number', $serialNumber)->first();

                    if (!$device_data) {
                        continue;
                    }

                    $deviceBranchId = $device_data->branch_id;
                    $deviceCompanyId = $device_data->company_id;

                    $unsyncedEmployees = ZKSyncEmp::where('synced', '0')->get();

                    foreach ($unsyncedEmployees as $employee) {
                        $getEmployees = EmployeeDetail::where('id', $employee->emp_id)
                            ->where('branch_id', $deviceBranchId)
                            ->where('company_id', $deviceCompanyId)
                            ->first();

                        if ($getEmployees) {
                            // Handle user deletion from old branch
                            if ($employee->action == 'delete' && $employee->old_branch != null) {
                                if ($employee->old_branch == $deviceBranchId) {
                                    $device['device']->removeUser($getEmployees->id);

                                    ZKSyncEmp::updateOrCreate([
                                        'emp_id' => $getEmployees->id,
                                        'old_branch' => $employee->old_branch,
                                        'action' => 'delete'
                                    ], [
                                        'emp_id' => $getEmployees->id,
                                        'synced' => '1'
                                    ]);
                                } else {
                                    // Track user for removal from old branch if not already done
                                    if (!isset($usersToRemoveFromOldBranches[$employee->old_branch])) {
                                        $usersToRemoveFromOldBranches[$employee->old_branch] = [];
                                    }
                                    $usersToRemoveFromOldBranches[$employee->old_branch][] = $getEmployees->id;
                                }
                            }

                            // Handle user creation or update
                            if (($employee->action == 'create' || $employee->action == 'delete') && $employee->old_branch == null) {
                                if ($getEmployees->status == '1' && $getEmployees->is_active == '1' && $getEmployees->is_deleted == '0') {
                                    $role = "0";
                                    $password = $getEmployees->attend_pin;
                                    $device['device']->setUser($getEmployees->id, $getEmployees->emp_id, $getEmployees->emp_name, $password, $role);
                                } else {
                                    // Check for termination or resignation
                                    $emp_termination = Emp_termination::where('emp_id', $getEmployees->id)->where('is_approved', '1')->first();
                                    $emp_resignation = EmployeeResignation::where('emp_id', $getEmployees->id)->where('is_approved', '1')->first();

                                    if (
                                        ($emp_termination && $emp_termination->termination_date < Carbon::today()) ||
                                        ($emp_resignation && $emp_resignation->resignation_date < Carbon::today())
                                    ) {
                                        $device['device']->removeUser($getEmployees->id);
                                    }
                                }

                                ZKSyncEmp::updateOrCreate([
                                    'emp_id' => $getEmployees->id,
                                    'old_branch' => null,
                                ], [
                                    'emp_id' => $getEmployees->id,
                                    'synced' => '1'
                                ]);
                            }
                        }
                    }
                } else {
                    return false;
                }
            }

            // After processing all devices, remove users from their old branches
            foreach ($usersToRemoveFromOldBranches as $branchId => $userIds) {
                foreach ($this->devices as $device) {
                    if ($device['connected'] == '1') {
                        $serial_data = (string) $device['device']->serialNumber();
                        $serialNumber = substr($serial_data, strpos($serial_data, '=') + 1);
                        $serialNumber = rtrim($serialNumber, "\x00");

                        $device_data = DeviceManagement::where('serial_number', $serialNumber)->first();

                        if ($device_data && $device_data->branch_id == $branchId) {
                            foreach ($userIds as $userId) {
                                $device['device']->removeUser($userId);

                                ZKSyncEmp::updateOrCreate([
                                    'emp_id' => $userId,
                                    'old_branch' => $branchId,
                                    'action' => 'delete'
                                ], [
                                    'emp_id' => $userId,
                                    'synced' => '1'
                                ]);
                            }
                            break;
                        }
                    }
                }
            }

            $cronJobTime->end_time = date('Y-m-d H:i:s');
            $cronJobTime->status = 'completed';
            $cronJobTime->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }





    // add  user on sigle device
    public function createUserSingleDevice($ip)
    {
        try {
            $device_ip = DeviceManagement::where('is_deleted', '0')
                ->where('device_ip', $ip)
                ->first();

            if ($device_ip) {
                $devices = [];
                $status = $this->pingDevice($ip);

                if ($status) {
                    $device = new ZKTeco($ip);
                    $connected = $device->connect();

                    $devices[] = [
                        'sr_no' => $device_ip->serial_number,
                        'device' => $device,
                        'connected' => $connected ? '1' : '0'
                    ];
                } else {
                    return false;
                }

                DB::beginTransaction();
                set_time_limit(0);
                $cronJobStartTime = date('Y-m-d H:i:s');
                $cronJobTime = new CronJobHistory();
                $cronJobTime->start_time = $cronJobStartTime;
                $cronJobTime->type = 'all-user-uploaded-on-ZK-single-device';
                $cronJobTime->status = 'in-progress';
                $cronJobTime->save();

                if (!empty($devices) && $devices[0]['connected'] == '1') {
                    $serial_data = $devices[0]['device']->serialNumber();
                    $extract_serial = strpos($serial_data, '=');
                    $serialNumber = substr($serial_data, $extract_serial + 1);
                    $serialNumber = rtrim($serialNumber, "\x00");

                    $device_data = DeviceManagement::where('serial_number', $serialNumber)->first();
                    $getEmployees = EmployeeDetail::where('status', '1')
                        ->where('is_deleted', '0')
                        ->where('is_active', '1')
                        ->where('branch_id', $device_data->branch_id)
                        ->where('company_id', $device_data->company_id)
                        ->get();

                    if (!$getEmployees->isEmpty()) {
                        foreach ($getEmployees as $employee) {
                            $role = "0";
                            $password = $employee->attend_pin;
                            $devices[0]['device']->setUser($employee->id, $employee->emp_id, $employee->emp_name, $password, $role);

                            ZKSyncEmp::updateOrCreate(
                                ['emp_id' => $employee->id, 'old_branch' => null],
                                ['emp_id' => $employee->id, 'synced' => '1', 'action' => 'create', 'old_branch' => null]
                            );
                        }
                    }
                }

                $CronJobDetail = CronJobHistory::where('id', $cronJobTime->id)->first();
                $CronJobDetail->end_time = date('Y-m-d H:i:s');
                $CronJobDetail->status = 'completed';
                $CronJobDetail->save();
                DB::commit();
                return response()->json([
                    'status' => true,
                    'success' => 1,
                    'message' => 'User synced successfully...'
                ]);
            } else {
                return false;
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'success' => 0,
                'message' => $e->getMessage(),
            ]);
        }
    }

    // add or update daily user on single device
    public function createOrUpdateUserSingleDevice($ip)
    {
        try {

            $status = $this->pingDevice($ip);
            if ($status) {
                DB::beginTransaction();
                set_time_limit(0);
                $cronJobStartTime = date('Y-m-d H:i:s');
                $cronJobTime = new CronJobHistory();
                $cronJobTime->start_time = $cronJobStartTime;
                $cronJobTime->type = 'upload-today-created/updated-user-on-ZK-single-device';
                $cronJobTime->status = 'in-progress';
                $cronJobTime->save();

                // Track users to be removed from old branches
                $usersToRemoveFromOldBranches = [];

                // Get the device from the request
                $device_ip = $ip;
                $device_data = DeviceManagement::where('device_ip', $device_ip)
                    ->where('is_deleted', '0')
                    ->first();

                if (!$device_data) {
                    return response()->json([
                        'status' => false,
                        'success' => 0,
                        'message' => 'No device found'
                    ]);
                }

                // Connect to the device
                $device = new ZKTeco($device_ip);
                if (!$device->connect()) {
                    return false;
                }

                $serial_data = (string) $device->serialNumber();
                $serialNumber = substr($serial_data, strpos($serial_data, '=') + 1);
                $serialNumber = rtrim($serialNumber, "\x00");

                if ($device_data->serial_number !== $serialNumber) {
                    return false;
                }

                $deviceBranchId = $device_data->branch_id;
                $deviceCompanyId = $device_data->company_id;

                // Fetch unsynced employees
                $unsyncedEmployees = ZKSyncEmp::where('synced', '0')->get();

                foreach ($unsyncedEmployees as $employee) {
                    $getEmployees = EmployeeDetail::where('id', $employee->emp_id)
                        ->where('branch_id', $deviceBranchId)
                        ->where('company_id', $deviceCompanyId)
                        ->first();

                    if ($getEmployees) {
                        // Handle user deletion from old branch
                        if ($employee->action == 'delete' && $employee->old_branch != null) {
                            if ($employee->old_branch == $deviceBranchId) {
                                $device->removeUser($getEmployees->id);

                                ZKSyncEmp::updateOrCreate([
                                    'emp_id' => $getEmployees->id,
                                    'old_branch' => $employee->old_branch,
                                    'action' => 'delete'
                                ], [
                                    'emp_id' => $getEmployees->id,
                                    'synced' => '1'
                                ]);
                            } else {
                                if (!isset($usersToRemoveFromOldBranches[$employee->old_branch])) {
                                    $usersToRemoveFromOldBranches[$employee->old_branch] = [];
                                }
                                $usersToRemoveFromOldBranches[$employee->old_branch][] = $getEmployees->id;
                            }
                        }

                        // Handle user creation or update
                        if (($employee->action == 'create' || $employee->action == 'delete') && $employee->old_branch == null) {
                            if ($getEmployees->status == '1' && $getEmployees->is_active == '1' && $getEmployees->is_deleted == '0') {
                                $role = "0";
                                $password = $getEmployees->attend_pin;
                                $device->setUser($getEmployees->id, $getEmployees->emp_id, $getEmployees->emp_name, $password, $role);
                            } else {
                                // Check for termination or resignation
                                $emp_termination = Emp_termination::where('emp_id', $getEmployees->id)->where('is_approved', '1')->first();
                                $emp_resignation = EmployeeResignation::where('emp_id', $getEmployees->id)->where('is_approved', '1')->first();

                                if (
                                    ($emp_termination && $emp_termination->termination_date < Carbon::today()) ||
                                    ($emp_resignation && $emp_resignation->resignation_date < Carbon::today())
                                ) {
                                    $device->removeUser($getEmployees->id);
                                }
                            }

                            ZKSyncEmp::updateOrCreate([
                                'emp_id' => $getEmployees->id,
                                'old_branch' => null,
                            ], [
                                'emp_id' => $getEmployees->id,
                                'synced' => '1'
                            ]);
                        }
                    }
                }

                // Remove users from their old branches
                foreach ($usersToRemoveFromOldBranches as $branchId => $userIds) {
                    if ($device_data->branch_id == $branchId) {
                        foreach ($userIds as $userId) {
                            $device->removeUser($userId);

                            ZKSyncEmp::updateOrCreate([
                                'emp_id' => $userId,
                                'old_branch' => $branchId,
                                'action' => 'delete'
                            ], [
                                'emp_id' => $userId,
                                'synced' => '1'
                            ]);
                        }
                    }
                }

                $cronJobTime->end_time = date('Y-m-d H:i:s');
                $cronJobTime->status = 'completed';
                $cronJobTime->save();

                DB::commit();

                return response()->json([
                    'status' => true,
                    'success' => 1,
                    'message' => 'Users synced successfully'
                ]);
            } else {
                return false;
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }





















    public function removeUser()
    {
        foreach ($this->devices as $device) {
            if ($device['connected'] == '1') {
                $users = $device['device']->getUser();
                foreach ($users as $user) {
                    $device['device']->removeUser($user['uid']);
                }
            }
        }
        return 'success';
    }
    public function clearUsers()
    {
        // $zk = new ZKTeco($this->getDeviceIp()->device_ip);
        // $zk->connect();
        // $zk->clearUsers();
        // return $zk->getUser();
    }
    public function fetchAttendance()
    {
        try {
            DB::beginTransaction();
            set_time_limit(0);
            $cronJobStartTime = date('Y-m-d H:i:s');
            $cronJobTime = new CronJobHistory();
            $cronJobTime->start_time = $cronJobStartTime;
            $cronJobTime->type = 'sync-attendence-from-ZK';
            $cronJobTime->status = 'in-progress';
            $cronJobTime->save();
            foreach ($this->devices as $device) {
                if ($device['connected'] == '1') {
                    DeviceManagement::where('serial_number', $device['sr_no'])->update([
                        'status' => 'ONLINE',
                    ]);
                    $attendanceArray = $device['device']->getAttendance();
                    $serial_data = $device['device']->serialNumber();
                    $extract_serial = strpos($serial_data, '=');
                    $serialNumber = substr($serial_data, $extract_serial + 1);

                    foreach ($attendanceArray as $attendance) {
                        if (Carbon::parse($attendance['timestamp'])->toDateString() == Carbon::today()->toDateString()) {
                            $id = $attendance['id'];
                            $state = $attendance['state'];
                            $remarks = null;
                            if ($state == 1) {
                                $remarks = '2';
                            } elseif ($state == 15) {
                                $remarks = '1';
                            } elseif ($state == 3) {
                                $remarks = '4';
                            }

                            $empDetail = EmployeeDetail::where('emp_id', $id)
                                ->where('status', '1')
                                ->first();

                            if ($empDetail) {
                                $attendDate = Carbon::parse($attendance['timestamp'])->toDateString();
                                $time = Carbon::parse($attendance['timestamp'])->format('H:i:s');

                                $empAttendence = UserDailyRecord::where('emp_id', $empDetail->id)
                                    ->whereDate('created_at', $attendDate)
                                    ->first();

                                if ($empAttendence) {
                                    $empAttendenceWithTime = UserDailyRecord::where('emp_id', $empDetail->id)
                                        ->whereDate('created_at', $attendDate)
                                        ->where('check_in', $time)
                                        ->first();

                                    if (!$empAttendenceWithTime) {
                                        $check_in_time = new DateTime($empAttendence->check_in);
                                        $out_time = new DateTime($time);
                                        if ($check_in_time < $out_time) {
                                            $check_out_time = $out_time;
                                        } else {
                                            $check_out_time = $out_time->modify('+12 hours');
                                        }
                                        $difference = $check_in_time->diff($check_out_time);
                                        $total_hours = $difference->h + $difference->i / 60;
                                        $working_hours = number_format($total_hours, 1);

                                        $empAttendence->update([
                                            'check_out' => $time,
                                            'working_hours' => $working_hours,
                                            'check_out_type' => $remarks,
                                            'check_out_ip' => $device['device']->_ip,
                                        ]);
                                    }

                                } else {
                                    $device_time = new DateTime($device['device']->getTime());
                                    UserDailyRecord::create([
                                        'emp_id' => $empDetail->id,
                                        'dated' => $device_time->format('Y-m-d'),
                                        'check_in' => $time,
                                        'check_out' => null,
                                        'present' => '1',
                                        'pull_time' => $device_time->format('H:i:s'),
                                        'leave' => null,
                                        'leave_type' => null,
                                        'working_hours' => 0,
                                        'device_serial_no' => $serialNumber,
                                        'check_in_type' => $remarks,
                                        'check_out_type' => null,
                                        'check_in_ip' => $device['device']->_ip,
                                        'check_out_ip' => null,

                                    ]);
                                }


                            }
                        }
                    }

                } else {
                    DeviceManagement::where('serial_number', $device['sr_no'])->update([
                        'status' => 'OFFLINE',
                    ]);
                }
            }
            $CronJobDetail = CronJobHistory::where('id', $cronJobTime->id)->first();
            $CronJobDetail->end_time = date('Y-m-d H:i:s');
            $CronJobDetail->status = 'completed';
            $CronJobDetail->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
    public function clearAttendance()
    {
        $zk = new ZKTeco($this->getDeviceIp()->device_ip);
        $zk->connect();
        $zk->clearAttendance();
    }
    public function restartDevice(Request $request)
    {
        $id = $request->id;
        $device = DeviceManagement::where('id', $id)->first();
        $device_ip = $device->device_ip;
        $zk = new ZKTeco($device_ip);
        $connected = $zk->connect();
        if ($connected) {
            $restart = $zk->restart();
            return response()->json(['success' => true, 'message' => 'Device Restart Successfully']);

        } else {
            return response()->json(['success' => false, 'message' => 'Device not connected']);
        }
    }
    public function shutdownDevice(Request $request)
    {
        $id = $request->id;
        $device = DeviceManagement::where('id', $id)->first();
        $device_ip = $device->device_ip;
        $zk = new ZKTeco($device_ip);
        $connected = $zk->connect();
        if ($connected) {
            $shutDown = $zk->shutdown();
            if ($shutDown) {
                return response()->json(['success' => true, 'message' => 'Device Shutdown Successfully']);
            } else {
                return response()->json(['success' => false, 'message' => 'Device not Shutdown']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Device not connected']);
        }
    }
    public function sleepDevice()
    {
        $zk = new ZKTeco($this->getDeviceIp()->device_ip);
        $zk->connect();
        $zk->sleep();
    }
    public function resumeDevice()
    {
        $zk = new ZKTeco($this->getDeviceIp()->device_ip);
        $zk->connect();
        $zk->resume();
    }
    public function testVoice()
    {
        $zk = new ZKTeco('192.168.1.253');
        $zk->connect();
        $zk->testVoice();
    }
    public function clearLCD()
    {
        $zk = new ZKTeco($this->getDeviceIp()->device_ip);
        $zk->connect();
        $zk->clearLCD();
    }
    public function writeLCD()
    {
        $zk = new ZKTeco($this->getDeviceIp()->device_ip);
        $zk->connect();
        $zk->writeLCD();
    }
    public function setTime()
    {
        $zk = new ZKTeco($this->getDeviceIp()->device_ip);
        $zk->connect();
        $t = "Y-m-d H:i:s";
        $zk->setTime($t);
    }
    public function getFingerprint()
    {
        try {
            $zk = new ZKTeco($this->getDeviceIp()->device_ip);
            $zk->connect();
            // $users = $zk->getUser();
            // foreach ($users as $user) {
            $finger = $zk->getFingerprint(14);
            $finger_print = mb_convert_encoding($finger, 'UTF-8', 'UTF-8');
            //     EmployeeDetail::where('id', $user['uid'])->update([
            //         'fingerprint' => $finger_print
            //     ]);
            // }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function setFingerprint()
    {
        $zk = new ZKTeco($this->getDeviceIp()->device_ip);
        $zk->connect();
        $encodedData = [
            "7" => "?\u0004\u000b\u0001\u0007\u0001J?SS21\u0000\u0000\u0003??\u0004\b\u0005\u0007\t??\u0000\u0000\u001b?i\u0001\u0000\u0000\u0000?q ??\u001b\u0000?\u000f5\u0000?\u0000\u0003æ\u0000%\u0000\n\u000f\u0000\u0000J̉\u000f?\u0000Q\u0000?\u000fp?[\u0000?\u000fh\u0000?\u0000z??\u0000t\u0000?\u000f?\u0000???\u000fp\u0000?\u0000?\u000f9̹\u0000i\u000f?\u0000\u001f\u0000x?i\u0000?\u0000?\u000ek\u0000??\u000f\u000f?\u0000?\u0000H\u000f>??\u0000Q\u000fz\u0000?\u0001\u0001??\u0000\u0007\u0001?\r?\u0000\u0013?R\u000f?\u0000\u0012\u0001P\u000e??\u0016\u0001?\rf\u0000?\u0001?Î\u0000'\u0001e\u000e?\u0000*?B\u000f?\u0000,\u0001?\u000f.?1\u0001D\u000f\u001d\u0000?\u0001?Ã\u0000A\u0001&\u000eJ\u0000B?\u001e\u000eo\u0000D\u0001\u000f\u000f??S\u0001?\u000e?\u0000?\u0001??\u000f|?~K\u0007Z?|???\u0006???\u0016\u000653??w??\u001f?P??zQ\n??\u001c??B\u0007?\u0007??b??F~{?V{?????z\u0013?3sC?\r?S{??N?r?vM??Ώu???X?w\u0013C\u0000o\u0014,??O?\u0018\u001d???? r?\u0018RG?f\bn\btٔ\n]+????q$?\"]K\u0005۴CSƨ\u0000?|??x???(\u0000?|5\tt??H??\\\u000b?]Du?7?p!???s??Ր?y?b??|?zw???;\u0002?+\u001dM\n\u00009?\u0010?\f<e\u0005\u0000?\u000b??/?\u00016\u0019?5\u0004?`\u0015???\t\u00009!8Tg3\r\u00009(\u0003???6?\u0010\u0000\u0014>?'?DO\f??\u000e\u0000\u0015E2WA?6\b\u0000?N\u0003;[B?\u0001?P???Ae\u0012?\u000fR??V/\u0005W?\f<\b\u0000?T\t?T\u000b?nW???`?\u0000t?\u0007?S?\u0007\u0000?]y?s\b\u0000do?Nx\u000f?mq\u0000FS??\b\u0003?tz?_h\u0010??uE?X??f?\u0011?)??C@??J??\u0012\u0000+????OP???;\t\u0000liw?HX\r\u0000t?\u0000;>?2j)\t\u0000m???{?\u0014\u0000\u000e???\u00056?2??Kk:\u0004?6??p\u0014\u0000\u0013???=?G[K\u0010\u0000?\u0006??Ok???I\u0005ō??7\r\u0000?̀\u0007?w\r???\u0014\u0000\u000e\u000b???F?????\u0005UE?\u0001k??&\u0004?dݧ?\u0007\u0000??\u001a?D\n̰?\u001a?T?\u0005?\u0015????~?????\u000f?ii?\u0007\u0000??T??\u0004\u0010?\u0000P?\u000f\u0013a\u0002?y???\u0007?ú\t\u0010?\b\u0013U;\\\u0011??\r????\u0004??\r????\u0003\u0010F\u000et\b\u0004\u0010\u0010\u000fC??\b\u0013?\u000e^?????\u0010d?R?\u0005\u0010\u000f\u0015\u001f\f9\u0014\u0010;%?\u0005*-\f??CJL\u0007Ռ+?ǲ?\u0004\u00105?Cp?\u0011;-F?\u0005??,???\u0003\u0010/4??\n܀=?\u0012X??\u0010????f\f\u0010n???6??????\u0004?\u0007ܒA\u001e?\u0004\u0010EE.[\u0004\u0010?E+?W\u0011\u0013\u0010G?m???d???C\u0000\u000bC\u0001\u0000?\u000bF?\u0000\u0000\u0000\u0000\u0000\u0000"
        ];

        // Define an array to store the decoded data
        $decodedData = [];

        // Iterate through each character in the encoded data
        foreach ($encodedData as $char) {
            // Convert the character to its UTF-8 representation
            $utf8Char = utf8_encode($char);

            // Add the UTF-8 character to the decoded data array
            $decodedData[] = $utf8Char;
        }

        $finger = $zk->setFingerprint(14, $decodedData);
        return $finger;
    }

    public function removeFingerprint()
    {
        $zk = new ZKTeco($this->getDeviceIp()->device_ip);
        $zk->connect();
        $data = ['7'];
        $result = $zk->removeFingerprint(267, $data);
        return $result;
    }

    public function getTime()
    {
        $zk = new ZKTeco($this->getDeviceIp()->device_ip);
        $zk->connect();
        $zk->getTime();
    }


    public function deviceStatusHistory()
{
    $device_id = 1;
    $status = 1;
    $historyData = DeviceStatusHistory::where('device_id', $device_id)->orderBy('id', 'desc')->first();

    if (!$historyData) {
        DeviceStatusHistory::create([
            'device_id' => $device_id,
            'from_date' => Carbon::now(),
            'to_date' => null,
            'sync_date' => $status == '0' ? Carbon::now() : null,
            'offline' => $status
        ]);
    } elseif ($historyData->offline != $status) {
        $historyData->update(['to_date' => Carbon::now()]);
        DeviceStatusHistory::create([
            'device_id' => $device_id,
            'from_date' => Carbon::now(),
            'to_date' => null,
            'sync_date' => $status == '0' ? Carbon::now() : null,
            'offline' => $status
        ]);
    }
    return 'success';
}

}

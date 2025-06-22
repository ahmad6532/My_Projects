<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ZktechoController;
use App\Models\Emp_termination;
use App\Models\EmployeeDetail;
use App\Models\EmployeeResignation;
use App\Models\ZkRoledEmployee;
use App\Traits\ZKConnection;
use Auth;
use DB;
use Exception;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\DeviceManagement;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use App\Models\Company;
use App\Models\DeviceType;
use Jmrashed\Zkteco\Lib\ZKTeco;

class DeviceManagementController extends BaseController
{
    use ZKConnection;
    public function deviceManagement(Request $request)
    {
        $user = Auth::user();
        $branchIds = explode(',', $user->branch_id);

        $per_page = $request->per_page ?? 10;
        $query = DeviceManagement::query();
        $query->where('is_deleted', '!=', '1');
        if ($request->branch_id && $request->branch_id !== 'all') {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->search_by) {
            $query->where(function ($query) use ($request) {
                $query->where('device_name', 'like', '%' . $request->search_by . '%')
                    ->orWhere('device_model', 'like', '%' . $request->search_by . '%')
                    ->orWhere('serial_number', 'like', '%' . $request->search_by . '%')
                    ->orWhere('device_ip', 'like', '%' . $request->search_by . '%');
            });
        }
        if ($user->role_id != 1) {
            $query->whereIn('branch_id', $branchIds);
        }

        $devices = $query->get();

        if ($devices->isEmpty()) {
            return response()->json([
                'status' => 0,
                'success' => false,
                'message' => 'No Devices found.',
                'data' => []
            ]);
        }

        $results = [];
        foreach ($devices as $device) {
            $deviceData = [
                'device_id' => $device->id,
                'company' => $device->deviceToCompany->company_name,
                'location' => $device->deviceToLocation->branch_name,
                'device_name' => $device->device_name,
                'device_type' => $device->deviceToType->name,
                'device_ip' => $device->device_ip,
                'serial_number' => $device->serial_number,
                'device_model' => $device->device_model,
                'enrolled_users' => $device->enrolled_users,
                'asset_no' => $device->asset_no,
                'status' => $device->status,
                'last_synced' => Carbon::parse($device->updated_at)->format('d-m-y, h:iA'),
                'enrolled_on' => Carbon::parse($device->created_at)->format('d-m-y'),
                'expiry_date' => Carbon::parse($device->expiry_date)->format('d-m-Y'),
            ];
            $results[] = $deviceData;
        }
        if (empty($results)) {
            return response()->json([
                'status' => 0,
                'success' => false,
                'message' => 'No leave data found for the specified filters.',
                'data' => []
            ]);
        }


        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect($results);
        $currentPageResults = $collection->slice(($currentPage - 1) * $per_page, $per_page)->values()->all();
        $paginatedResults = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);

        $paginatedResults->setPath($request->url());

        return response()->json([
            'status' => 1,
            'success' => true,
            'message' => 'Leaves fetched successfully',
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

    public function saveDevice(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'company_id' => 'required',
                'branch_id' => 'required',
                'device_name' => 'required',
                'device_type' => 'required',
                'device_ip' => 'required',
                'serial_number' => 'required',
                'device_model' => 'required',
                'expiryDate' => 'required|date',
                'asset_no' => 'required',
                'emp_id' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', "Some of the fields are missing or invalid");
            }
            DB::beginTransaction();
            $device = new DeviceManagement();
            $device->company_id = $request->company_id;
            $device->branch_id = $request->branch_id;
            $device->device_name = $request->device_name;
            $device->device_model = $request->device_model;
            $device->device_type_id = $request->device_type;
            $device->device_ip = $request->device_ip;
            $device->serial_number = $request->serial_number;
            $device->heartbeat = 0;
            $device->asset_no = $request->asset_no;
            $device->status = null;
            $device->is_deleted = '0';
            $device->expiry_date = Carbon::parse($request->expiryDate)->format('Y-m-d H:i:s');
            $device->save();

            $emp_ids = $request->emp_id;
            $ids = explode(',', $emp_ids);
            foreach ($ids as $id) {
                ZkRoledEmployee::create([
                    'device_id' => $device->id,
                    'emp_id' => $id,
                    'role_no' => 8,
                    'synced' => '0',
                    'action' => 'create'
                ]);
            }

            $msg = 'Device "' . ucwords($request->device_name) . '" Added Successfully';
            createLog('device_action', $msg);
            DB::commit();

            return response()->json([
                'status' => true,
                'sucess' => 1,
                'message' => 'Device added successfully...'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'sucess' => 0,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function editDevice(Request $request)
    {
        $id = $request->device_id;
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_branch_id = explode(',', $user->branch_id);
        $user_company_id = explode(',', $user->company_id);

        if ($user_role == 1) {
            $companies = Company::where('is_deleted', '0')->get();
        } else {
            $companies = Company::whereIn('id', $user_company_id)
                ->where('is_deleted', '0')->get();
        }

        $deviceTypes = DeviceType::orderBy('name')->get();

        $deviceQuery = DeviceManagement::query();
        if ($request->has('search_by')) {
            $searchBy = $request->search_by;
            $deviceQuery->where('device_name', 'LIKE', "%{$searchBy}%");
        }

        // Check if device_id is provided
        if ($request->has('device_id')) {
            $device = $deviceQuery->where('id', $id)->first();
            $device->company_name = $device->deviceToCompany->company_name;
            $device->location = $device->deviceToLocation->branch_name;
            $device->type_name = $device->deviceToType->name;
            $device->last_synced = Carbon::parse($device->updated_at)->format('d-m-y, h:iA');

            $empData = [];
            $roleEmp = ZkRoledEmployee::where('device_id', $device->id)->get();
            if ($roleEmp) {
                foreach ($roleEmp as $role) {
                    $record = EmployeeDetail::where('id', $role->emp_id)->first();
                    $push = [
                        'label' => $record->emp_name,
                        'value' => $record->id
                    ];
                    array_push($empData, $push);
                }
            }

            $data = [
                'device' => $device ?? null,
                'deviceTypes' => $deviceTypes,
                'deviceAdmin' => $empData
            ];

            return $this->sendResponse($data, 'Device fetched successfully!', 200);
        } else {
            // Add pagination
            $perPage = $request->get('per_page', 10);
            $devices = $deviceQuery->paginate($perPage);

            // if ($devices->isEmpty()) {
            //     return $this->sendError([], 'Data not found!', 404);
            // }

            $data = [
                'devices' => $devices->items(),
                'deviceTypes' => $deviceTypes,
                'pagination' => [
                    'current_page' => $devices->currentPage(),
                    'last_page' => $devices->lastPage(),
                    'per_page' => $devices->perPage(),
                    'total' => $devices->total(),
                    'next_page_url' => $devices->nextPageUrl(),
                    'prev_page_url' => $devices->previousPageUrl(),
                ]
            ];

            return response()->json([
                'status' => 1,
                'message' => 'Devices fetched successfully!',
                'details' => $data
            ], 200);
        }
    }

    public function updateDevice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
            'device_name' => 'required|unique:devices,device_name,' . $request->device_id,
            'device_type' => 'required',
            'device_ip' => 'required',
            'serial_number' => 'required',
            'device_model' => 'required',
            'expiryDate' => 'required',
            'asset_no' => 'required',
            'emp_id' => 'required'

        ]);

        if ($validator->fails()) {
            if ($validator->errors()->has('device_name')) {
                return $this->sendError([], 'Device name already exists!', 200);
            } else {
                return $this->sendError([], $validator->errors(), 200);
            }
        }
        $device = DeviceManagement::where('id', $request->device_id)->first();
        $device->company_id = $request->company_id;
        $device->branch_id = $request->branch_id;
        $device->device_name = $request->device_name;
        $device->device_model = $request->device_model;
        $device->device_type_id = $request->device_type;
        $device->device_ip = $request->device_ip;
        $device->serial_number = $request->serial_number;
        $device->heartbeat = 0;
        $device->asset_no = $request->asset_no;
        $device->expiry_date = Carbon::parse($request->expiryDate)->format('Y-m-d H:i:s');
        $device->save();


        ZkRoledEmployee::where('device_id', $device->id)->delete();

        $emp_ids = $request->emp_id;
        if (!is_array($emp_ids)) {
            $emp_ids = explode(',', $emp_ids);
        }

        foreach ($emp_ids as $id) {
            ZkRoledEmployee::create([
                'emp_id' => $id,
                'role_no' => 8,
                'device_id' => $device->id,
                'synced' => '0',
                'action' => 'create'
            ]);
        }

        $msg = 'Device "' . ucwords($request->device_name) . '" Updated Successfully';
        createLog('device_action', $msg);
        if ($device) {
            return $this->sendResponse($device, 'Device update successfully!', 200);
        } else {
            return $this->sendResponse($device, 'Data not found!', 200);
        }
    }
    public function addDeviceType(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:device_types,name|max:255',
            ]);

            $device = DeviceType::create([
                'name' => $request->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Device type add successfully',
                'data' => $device,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function deleteDevice(Request $request)
    {
        $id = $request->device_id;

        $user = auth()->user();
        $user_role = $user->role_id;
        $device = DeviceManagement::find($id);

        if (!$device) {
            return response()->json([
                'status' => false,
                'message' => 'Device not found',
                'data' => [],
            ], 404);
        }

        if ($user_role == 1) {
            if ($device->is_deleted == 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'Device already deleted',
                    'data' => $device,
                ], 200);
            }

            $device->is_deleted = "1";
            $device->save();

            $deviceChange = DeviceManagement::find($id);

            return response()->json([
                'status' => true,
                'message' => 'Device Deleted Successfully',
                'data' => $deviceChange,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized to delete device',
                'data' => [],
            ], 403);
        }
    }

    // Device dashboard starts here......................................................................................

    // show list of all devices

    public function deviceList(Request $request)
    {
        $per_page = $request->per_page ?? 8;

        $Access = Auth::user();
        $company_id = explode(',', $Access->company_id);
        $branch_id = explode(',', $Access->branch_id);

        $query = DeviceManagement::query();

        if ($Access->role_id != 1) {
            $query->whereIn('company_id', $company_id)
                ->whereIn('branch_id', $branch_id);
        }
        $query->where('is_deleted', '!=', '1');


        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->status) {
            $query->where('status', strtoupper($request->status));
        }

        if ($request->search_by) {
            $query->where(function ($subQuery) use ($request) {
                $subQuery->where('device_model', 'like', '%' . $request->search_by . '%')
                    ->orWhere('device_ip', 'like', '%' . $request->search_by . '%')
                    ->orWhere('serial_number', 'like', '%' . $request->search_by . '%');
            });
        }
        // $query->where('company_id', $company_id);
        $query->with('deviceToLocation');

        $data = $query->paginate($per_page);

        $data->getCollection()->transform(function ($item) {
            $item->branch_name = $item->deviceToLocation->branch_name ?? null;
            $item->last_synced = Carbon::parse($item->updated_at)->format('d-m-y, h:iA');
            $deviceRole = $item->deviceToZkRoledEmp;
            $names = [];
            foreach ($deviceRole as $role) {
                $empData = EmployeeDetail::where('id', $role->emp_id)->value('emp_name') ?? "";
                array_push($names, $empData);
            }
            $namesString = implode(', ', $names);
            $item->device_admin = $namesString;
            unset($item->deviceToLocation);
            return $item;
        });

        $response = [
            'status' => 1,
            'success' => true,
            'message' => 'Devices fetched successfully',
            'data' => $data->items(),
            'current_page' => $data->currentPage(),
            'next_page_url' => $data->nextPageUrl(),
            'path' => $data->path(),
            'per_page' => $data->perPage(),
            'prev_page_url' => $data->previousPageUrl(),
            'to' => $data->count() > 0 ? $data->lastItem() : null,
            'total' => $data->total(),
            'total_pages' => $data->lastPage(),
        ];

        return response()->json($response);
    }

    // count devices
    public function deviceCount()
    {
        $Access = Auth::user();
        $company_id = explode(',', $Access->company_id);
        $branch_id = explode(',', $Access->branch_id);

        $total_devices = DeviceManagement::where('is_deleted', '!=', '1')
            ->whereIn('company_id', $company_id)
            ->whereIn('branch_id', $branch_id)
            ->count();

        $offline_devices = DeviceManagement::where('is_deleted', '!=', '1')
            ->whereIn('company_id', $company_id)
            ->whereIn('branch_id', $branch_id)
            ->where(function ($query) {
                $query->where('status', 'OFFLINE')
                    ->orWhereNull('status');
            })
            ->count();

        $online_devices = DeviceManagement::where('is_deleted', '!=', '1')
            ->whereIn('company_id', $company_id)
            ->whereIn('branch_id', $branch_id)
            ->where('status', 'ONLINE')
            ->count();
        if ($Access->role_id == 1) {
            $total_devices = DeviceManagement::where('is_deleted', '!=', '1')
                ->count();

            $offline_devices = DeviceManagement::where('is_deleted', '!=', '1')
                ->where(function ($query) {
                    $query->where('status', 'OFFLINE')
                        ->orWhereNull('status');
                })
                ->count();

            $online_devices = DeviceManagement::where('is_deleted', '!=', '1')
                ->where('status', 'ONLINE')
                ->count();
        }
        $data = [
            'total_devices' => $total_devices ?? 0,
            'offline_devices' => $offline_devices ?? 0,
            'online_devices' => $online_devices ?? 0,
            'date' => Carbon::now()->format('d F Y'),
            'time' => Carbon::now()->format('h:i A'),
        ];

        return response()->json([
            'status' => 1,
            'success' => true,
            'message' => 'Devices count fetched successfully',
            'data' => $data
        ]);
    }


    // sync roled user on zk device
    public function syncRoledEmployee()
    {
        $access = Auth::user();
        $devices = DeviceManagement::where('is_deleted', '0')->where('company_id', $access->company_id)->get();
        if ($devices) {
            foreach ($devices as $device) {
                $status = $this->pingDevice($device->device_ip);
                if ($status) {
                    $employees = ZkRoledEmployee::where('device_id', $device->id)->where('synced', '0')->get();
                    if ($employees) {
                        $conn = new ZKTeco($device->device_ip);
                        $conn->connect();
                        foreach ($employees as $employee) {
                            $empData = EmployeeDetail::find($employee->emp_id);
                            if ($empData) {
                                $status = $empData->status;
                                if ($employee->action == 'delete') {
                                    $termination = Emp_termination::where('emp_id', $empData->id)->where('is_approved', '1')->whereDate('termination_date', '<', Carbon::now()->format('Y-m-d'))->first();
                                    $resignation = EmployeeResignation::where('emp_id', $empData->id)->where('is_approved', '1')->whereDate('resignation_date', '<', Carbon::now()->format('Y-m-d'))->first();
                                    if ($termination || $resignation) {
                                        $conn->removeUser($empData->id);
                                        $employee->update([
                                            'synced' => '1',
                                        ]);
                                    } elseif ($empData->is_active == 0 || $empData->is_deleted == 1) {
                                        $conn->removeUser($empData->id);
                                        $employee->update([
                                            'synced' => '1',
                                        ]);
                                    }

                                } else {
                                    $conn->setUser($empData->id, $empData->emp_id, $empData->emp_name, $empData->emp_id, $employee->role_no);
                                    $employee->update([
                                        'synced' => '1',
                                    ]);
                                }

                            }

                        }
                    }
                    $device->update([
                        'status' => 'ONLINE',
                    ]);
                } else {
                    $device->update([
                        'status' => 'OFFLINE',
                    ]);
                }
            }
        }
    }


    // sync single device roled employees
    public function syncSingleRoledEmployee($ip)
    {

        $device = DeviceManagement::where('is_deleted', '0')->where('device_ip', $ip)->first();
        if ($device) {
            $status = $this->pingDevice($device->device_ip);
            if ($status) {
                $employees = ZkRoledEmployee::where('device_id', $device->id)->where('synced', '0')->get();
                if ($employees) {

                    $conn = new ZKTeco($device->device_ip);
                    $conn->connect();
                    foreach ($employees as $employee) {
                        $empData = EmployeeDetail::find($employee->emp_id);
                        if ($empData) {
                            $status = $empData->status;
                            if ($employee->action == 'delete') {
                                $termination = Emp_termination::where('emp_id', $empData->id)->where('is_approved', '1')->whereDate('termination_date', '<', Carbon::now()->format('Y-m-d'))->first();
                                $resignation = EmployeeResignation::where('emp_id', $empData->id)->where('is_approved', '1')->whereDate('resignation_date', '<', Carbon::now()->format('Y-m-d'))->first();
                                if ($termination || $resignation) {
                                    $conn->removeUser($empData->id);
                                    $employee->update([
                                        'synced' => '1',
                                    ]);
                                } elseif ($empData->is_active == 0 || $empData->is_deleted == 1) {
                                    $conn->removeUser($empData->id);
                                    $employee->update([
                                        'synced' => '1',
                                    ]);
                                }

                            } else {
                                $conn->setUser($empData->id, $empData->emp_id, $empData->emp_name, $empData->emp_id, $employee->role_no);
                                $employee->update([
                                    'synced' => '1',
                                ]);
                            }

                        }

                    }
                }
                $device->update([
                    'status' => 'ONLINE',
                ]);
            } else {
                $device->update([
                    'status' => 'OFFLINE',
                ]);
            }

        }
    }

    // sync users on all devices manually
    public function syncAllDevices()
    {
        try {
            $device_ips = DeviceManagement::where('is_deleted', '0')->get();
            foreach ($device_ips as $ip) {
                $status = $this->pingDevice($ip->device_ip);
                if ($status) {
                    $ip->update([
                        'status' => 'ONLINE',
                    ]);
                } else {
                    $ip->update([
                        'status' => 'OFFLINE',
                    ]);
                }
            }
            $all = app(ZktechoController::class)->createAllUser();
            $single = app(ZktechoController::class)->createOrUpdateUser();
            $this->syncRoledEmployee();
            if ($all === null && $single === null) {
                app(ZktechoController::class)->countUserOnDevice();
                $msg = 'All devices synced Successfully';
                createLog('device_action', $msg);
                return response()->json([
                    'status' => true,
                    'success' => 1,
                    'message' => 'All Devices Synced Successfully...'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'success' => 0,
                    'message' => 'Device not connected'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'success' => 0,
                'message' => $e->getMessage()
            ]);
        }
    }

    // sync users on single device manuallly
    public function syncSingleDevice(Request $request)
    {
        try {

            $status = $this->pingDevice($request->device_ip);
            if ($status) {
                app(ZktechoController::class)->createUserSingleDevice($request->device_ip);
                app(ZktechoController::class)->createOrUpdateUserSingleDevice($request->device_ip);
                $this->syncSingleRoledEmployee($request->device_ip);
                app(ZktechoController::class)->countUserOnSingleDevice($request->device_ip);
                $msg = 'Device with IP "' . $request->device_ip . '" Synced Successfully';
                createLog('device_action', $msg);
                DeviceManagement::where('device_ip', $request->device_ip)->update([
                    'status' => 'ONLINE',
                ]);
                return response()->json([
                    'status' => true,
                    'success' => 1,
                    'message' => 'Device synced successfully...'
                ]);

            } else {
                DeviceManagement::where('device_ip', $request->device_ip)->update([
                    'status' => 'OFFLINE',
                ]);
                return response()->json([
                    'status' => false,
                    'success' => 0,
                    'message' => 'Device not connected'
                ]);
            }

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'success' => 0,
                'message' => $e->getMessage()
            ]);
        }
    }
}

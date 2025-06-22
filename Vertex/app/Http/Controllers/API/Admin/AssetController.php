<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\AssetTypes;
use App\Models\EmployeeDetail;
use App\Models\AssetHistory;
use App\Models\AssetImages;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DB;

class AssetController extends Controller
{
    public function saveAsset(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'branch_id' => 'required',
            'company_id' => 'required',
            'serial_no' => 'required|unique:assets,serial_no|max:50',
            'asset_name' => 'required|string|max:255',
            'status' => 'required|string|max:50',
            'asset_type_id' => 'required|numeric',
            'asset_id' => 'nullable|unique:assets,asset_id|max:50',
            'purchase_date' => 'required|date',
            'guarantee_date' => 'nullable|date',
            'asset_price' => 'required|numeric',
            'description' => 'required|string|max:1000',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validatedData->errors()
            ]);
        }

        $asset = Asset::create([
            'branch_id' => $request->branch_id,
            'company_id' => $request->company_id,
            'serial_no' => $request->serial_no,
            'asset_name' => $request->asset_name,
            'status' => '1',
            'asset_type_id' => $request->asset_type_id,
            'asset_id' => $request->asset_id,
            'purchase_date' => $request->purchase_date,
            'guarantee_date' => $request->guarantee_date,
            'asset_price' => $request->asset_price,
            'description' => $request->description,
        ]);

        if ($request->hasFile('images')) {
            $images = $request->file('images');

            if (is_array($images)) {
                foreach ($images as $file) {
                    if ($file->isValid()) {
                        $originalName = $file->getClientOriginalName();
                        $timestamp = round(microtime(true) * 1000);
                        $newFileName = $timestamp . '_' . $originalName;
                        $imagePath = $file->storeAs('/assets_images', $newFileName);
                        $imagePublicPath = $newFileName;

                        AssetImages::create([
                            'asset_id' => $asset->id,
                            'image_url' => $imagePublicPath,
                        ]);
                    } else {

                        return response()->json([
                            'status' => false,
                            'message' => 'Invalid image file.',
                        ], 400);
                    }
                }
            } else {

                if ($images->isValid()) {
                    $originalName = $images->getClientOriginalName();
                    $timestamp = round(microtime(true) * 1000);
                    $newFileName = $timestamp . '_' . $originalName;
                    $imagePath = $images->storeAs('public/assets/assets_images', $newFileName);
                    $imagePublicPath = 'assets/assets_images/' . $newFileName;

                    AssetImages::create([
                        'asset_id' => $asset->id,
                        'image_url' => $imagePublicPath,
                    ]);

                } else {

                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid image file.',
                    ], 400);
                }
            }
        }

        if ($asset) {
            $name = Auth::user()->fullname;
            $name = ucwords($name ?? 'N/A');
            $msg = $name . ' saved ' . ucwords($request->serial_no ?? 'N/A') . ' new asset in the available directory.';

            AssetHistory::create([
                'type' => 'save_assets',
                'user_id' => Auth::id(),
                'asset_id' => $asset->id,
                'msg' => $msg,
                'status' => 1,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Asset created successfully',
            'data' => $asset,
            'history' => $msg,
        ], 201);
    }

    public function getAvaiableAsset(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $branch_id = $request->input('branch_id');
        $company_id = $request->input('company_id');

        $branch = Auth::user()->branch_id;

        $query = DB::table('assets')
                    ->leftJoin('asset_types', 'asset_types.id', '=', 'assets.asset_type_id')
                    ->leftJoin('companies', 'companies.id', '=', 'assets.company_id')
                    ->leftJoin('locations', 'locations.id', '=', 'assets.branch_id')
                    ->select('assets.*', 'asset_types.name', 'companies.company_name', 'locations.branch_name')
                    ->where('assets.status', '1')
                    ->where('assets.is_deleted', '0')
                    ->where(function ($query) {
                        $query->whereNull('emp_id')
                            ->orWhere('emp_id', '');
                    });

        if (filled($branch_id)) {
            $query->where('locations.id', $branch_id);
        }

        if (filled($company_id)) {
            $query->where('companies.id', $company_id);
        }

        if (!empty($branch)) {
            $branchArray = (array) explode(',', $branch);
            $query->whereIn('assets.branch_id', $branchArray);
        }

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('assets.asset_name', 'like', '%' . $search . '%')
                    ->orWhere('assets.serial_no', 'like', '%' . $search . '%')
                    ->orWhere('asset_types.name', 'like', '%' . $search . '%');
            });
        }

        $AssetData = $query->paginate($perPage, ['*'], 'page', $page);

        if ($AssetData->isNotEmpty()) {
            return response()->json([
                'status' => true,
                'message' => "Assets fetched successfully",
                'data' => $AssetData
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => "No assets found",
                'data' => []
            ], 200);
        }
    }


    public function addAssetTypes(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|unique:asset_types,name|max:255',
            ]);

            $asset = AssetTypes::create([
                'name' => $request->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Asset type add successfully',
                'data' => $asset,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function getAvaiableAssetRecord(Request $request)
    {
        $id = $request->id;
        $imageURL = 'api/storage/app/assets_images/';
        $baseURL = url('/');

        $getAsset = DB::table('assets')
                        ->leftJoin('asset_types', 'asset_types.id', '=', 'assets.asset_type_id')
                        ->leftJoin('companies', 'companies.id', '=', 'assets.company_id')
                        ->leftJoin('locations', 'locations.id', '=', 'assets.branch_id')
                        ->where('assets.id', $id)
                        ->select('assets.*', 'asset_types.name', 'companies.company_name', 'locations.branch_name')
                        ->first();

        if ($getAsset) {
            $getImages = DB::table('asset_images')
                            ->where('asset_id', $id)
                            ->pluck('image_url')
                            ->map(function ($image) use ($baseURL, $imageURL) {
                                return $baseURL . '/' . $imageURL . $image;
                            });

            return response()->json([
                'status' => true,
                'message' => "Asset fetched successfully",
                'data' => $getAsset,
                'images' => $getImages
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Asset not found",
                'data' => []
            ], 404);
        }
    }


    public function employeeList(Request $request)
    {
        $EmployeeList = EmployeeDetail::where('is_deleted', '0')
                                    ->select('id', 'emp_name')
                                    ->get();
            if($EmployeeList){
                return response()->json([
                    'status' => true,
                    'message' => "Employee's Fetch Successfully",
                    'data' => $EmployeeList
                ], 200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => "Employee's not found",
                    'data' => []
                ], 404);
            }
    }

    public function updateAsset(Request $request)
{
    $id = $request->id;

    // Validation
    $validator = Validator::make($request->all(), [
        'branch_id' => 'required',
        'company_id' => 'required',
        'emp_id' => 'nullable',
        'serial_no' => 'required|unique:assets,serial_no,' . $id . '|max:50',
        'asset_id' => 'required|unique:assets,asset_id,' . $id . '|max:50',
        'asset_name' => 'required|string|max:255',
        'status' => 'required|string|max:50',
        'asset_type_id' => 'required|numeric',
        'purchase_date' => 'required|date',
        'guarantee_date' => 'nullable|date',
        'asset_price' => 'required|numeric',
        'description' => 'required|string|max:1000',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors(),
        ], 422);
    }

    // Find asset
    $asset = Asset::find($id);

    if (!$asset) {
        return response()->json([
            'success' => false,
            'message' => 'Record not found',
            'data' => [],
        ], 404);
    }

    // Check status and emp_id
    if (in_array($request->status, ['3', '4', '5']) && $request->emp_id) {
        return response()->json([
            'success' => false,
            'message' => 'Please unassign the employee first before changing the asset status to disposed, broken, or sold.',
        ], 400);
    }


    if ($request->hasFile('images')) {
        AssetImages::where('asset_id', $id)->delete();
        $images = $request->file('images');
        foreach ($images as $file) {
            if ($file->isValid()) {
                $timestamp = round(microtime(true) * 1000);
                $newFileName = $timestamp . '_' . $file->getClientOriginalName();
                $imagePublicPath = $file->storeAs('assets_images', $newFileName);
                AssetImages::create([
                    'asset_id' => $asset->id,
                    'image_url' => $newFileName,
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid image file.',
                ], 400);
            }
        }
    }

    if ($request->emp_id && $request->status == '1') {
        return response()->json([
            'success' => false,
            'message' => 'Change Available Status to Assigned.',
        ], 400);
    }

    $disposedDate = in_array($request->status, ['3', '4', '5']) ? now()->format('Y-n-j') : null;

    $asset->update([
        'branch_id' => $request->branch_id,
        'company_id' => $request->company_id,
        'emp_id' => $request->emp_id,
        'serial_no' => $request->serial_no,
        'asset_name' => $request->asset_name,
        'status' => $request->status,
        'asset_type_id' => $request->asset_type_id,
        'purchase_date' => $request->purchase_date,
        'guarantee_date' => $request->guarantee_date,
        'asset_price' => $request->asset_price,
        'description' => $request->description,
        'asset_id' => $request->asset_id,
        'disposed_date' => $disposedDate,
    ]);

    $employee = EmployeeDetail::leftJoin('assets', 'assets.emp_id', '=', 'employee_details.id')
        ->where('employee_details.id', $request->emp_id)
        ->select('employee_details.emp_name')
        ->first();

    $name = Auth::user()->fullname;
    $user_id = Auth::user()->id;
    $msg = '';

    if ($employee && $request->emp_id !== null) {
        $employeeName = $employee->emp_name;

        if ($request->status == '2') {
            $msg = ucwords($request->serial_no ?? 'N/A') . ' asset has been assigned to ' . ucwords($employeeName ?? 'N/A') . ' by ' . ucwords($name ?? 'N/A') . '.';
            AssetHistory::create([
                'type' => 'assign_assets',
                'user_id' => $user_id,
                'asset_id' => $id,
                'msg' => $msg,
                'status' => 1,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Asset updated successfully',
            'data' => $asset,
            'history' => $msg,
        ], 200);
    }

    if ($request->emp_id === null) {
        if ($request->status == '3') {
            $msg = ucwords($name ?? 'N/A') . ' changed ' . ucwords($request->serial_no ?? 'N/A') . ' asset status to disposed.';
            AssetHistory::create([
                'type' => 'disposed_assets',
                'user_id' => $user_id,
                'asset_id' => $id,
                'msg' => $msg,
                'status' => 1,
            ]);
        } elseif ($request->status == '4') {
            $msg = ucwords($name ?? 'N/A') . ' changed ' . ucwords($request->serial_no ?? 'N/A') . ' asset status to broken.';
            AssetHistory::create([
                'type' => 'broken_assets',
                'user_id' => $user_id,
                'asset_id' => $id,
                'msg' => $msg,
                'status' => 1,
            ]);
        } elseif ($request->status == '5') {
            $msg = ucwords($name ?? 'N/A') . ' changed ' . ucwords($request->serial_no ?? 'N/A') . ' asset status to sold.';
            AssetHistory::create([
                'type' => 'sold_assets',
                'user_id' => $user_id,
                'asset_id' => $id,
                'msg' => $msg,
                'status' => 1,
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'Asset updated successfully',
            'data' => $asset,
            'history' => $msg,
        ], 200);
    }

    return response()->json([
        'success' => true,
        'message' => 'Asset updated successfully',
        'data' => $asset,
    ], 200);
    }


    public function deleteAsset(Request $request)
    {
        $id = $request->id;
        $getAssets = Asset::find($id);
        $getSerialNo =  $getAssets->serial_no;
        if($getAssets){
            $getAssets->is_deleted = 1;
            $getAssets->save();

                $name = Auth::user()->fullname;
                $name = ucwords($name ?? 'N/A');
                $msg =  $name . ' deleted ' .ucwords($getSerialNo ?? 'N/A'). ' asset successfully.';

                AssetHistory::create([
                'type' => 'delete_assets',
                'user_id' => Auth::id(),
                'asset_id' => $id,
                'msg' => $msg,
                'status' => 1
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Asset deleted successfully',
                'data' => $getAssets,
                'history' => $msg
            ], 200);

        }else{
            return response()->json([
                'success' => false,
                'message' => 'Asset not found',
                'data' => [],
            ], 404);
        }
    }

    public function getAssignedAsset(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 15);
        $page = $request->input('page', 1);
        $branch_id = $request->input('branch_id');
        $company_id = $request->input('company_id');

        $branch = Auth::user()->branch_id;

        $query = Asset::leftJoin('employee_details', 'employee_details.id', '=', 'assets.emp_id')
                    ->leftJoin('locations', 'locations.id', '=', 'assets.branch_id')
                    ->leftJoin('companies', 'companies.id', '=', 'assets.company_id')
                    ->where('assets.is_deleted', 0)
                    ->where('assets.status', 2)
                    ->whereNotNull('assets.emp_id')
                    ->select('assets.*', 'employee_details.emp_name', 'locations.branch_name', 'companies.company_name');

        if (filled($branch_id)) {
            $query->where('locations.id', $branch_id);
        }

        if (filled($company_id)) {
            $query->where('companies.id', $company_id);
        }

        if ($branch !== null && $branch !== '') {
            $branchArray = explode(',', $branch);
            $query->whereIn('assets.branch_id', $branchArray);
        }

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('assets.asset_name', 'like', '%' . $search . '%')
                    ->orWhere('employee_details.emp_name', 'like', '%' . $search . '%')
                    ->orWhere('locations.branch_name', 'like', '%' . $search . '%')
                    ->orWhere('companies.company_name', 'like', '%' . $search . '%')
                    ->orWhere('assets.serial_no', 'like', '%' . $search . '%');
            });
        }

        $getAsset = $query->paginate($perPage, ['*'], 'page', $page);

        if ($getAsset->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Assigned assets fetched successfully',
                'data' => $getAsset,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Assets not found',
                'data' => [
                    'data' => []
                ],
            ], 200);
        }
    }
    //
    public function getDeposedAssets(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 15);
        $page = $request->input('page', 1);
        $branch_id = $request->input('branch_id');
        $company_id = $request->input('company_id');
        $branch = Auth::user()->branch_id;

        $perPage = is_numeric($perPage) && $perPage > 0 ? $perPage : 15;
        $page = is_numeric($page) && $page > 0 ? $page : 1;

        $query = Asset::leftJoin('companies', 'companies.id', '=', 'assets.company_id')
        ->leftJoin('locations', 'locations.id', '=', 'assets.branch_id')
        ->select('assets.*', 'locations.branch_name', 'companies.company_name')
        ->where('assets.is_deleted', '0')
        ->whereIn('status', ['3', '4', '5']);

        if (filled($branch_id)) {
            $query->where('locations.id', $branch_id);
        }

        if (filled($company_id)) {
            $query->where('companies.id', $company_id);
        }

        if ($branch !== null && $branch !== '') {
            $branchArray = explode(',', $branch);
            $query->whereIn('assets.branch_id', $branchArray);
        }
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('asset_name', 'like', '%' . $search . '%')
                    ->orWhere('serial_no', 'like', '%' . $search . '%');
            });
        }

        $getDeposed = $query->paginate($perPage, ['*'], 'page', $page);

        if ($getDeposed->isNotEmpty()) {
            return response()->json([
                'status' => true,
                'message' => "Deposed records fetched successfully",
                'data' => $getDeposed
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Deposed records not found",
                'data' => [
                    'data' => []
                ],
            ], 200);
        }
    }

    public function assetHistory(Request $request)
    {
        $assetHistory = AssetHistory::leftJoin('users', 'users.id', '=', 'asset_history.user_id')
                                    ->where('asset_history.status', '1')
                                    ->where('asset_history.asset_id', $request->id)
                                    ->get(['asset_history.msg', 'asset_history.id', 'asset_history.user_id', 'asset_history.created_at', 'users.email']);

        $assetHistoryFormatting = $assetHistory->map(function($item) {
            $createdAt = Carbon::parse($item->created_at);
            return [
                'id' => $item->id,
                'msg' => $item->msg,
                'user_email' => $item->email,
                'date' => $createdAt->format('Y/n/j'),
                'time' => $createdAt->format('g:i A'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $assetHistoryFormatting,
        ]);
    }

    public function deleteAssetHistory(Request $request)
    {
        $id = $request->id;
        $getAssets = AssetHistory::find($id);
        if($getAssets){
            $getAssets->delete();

            return response()->json([
                'success' => true,
                'message' => 'History deleted successfully',
                'data' => $getAssets
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'No record found',
                'data' => []
            ]);
        }
    }

    public function assetType(Request $request)
    {
        $getType = DB::table('asset_types')->select('id', 'name')->get();

        return response()->json([
            'status' => true,
            'message' => 'Assets types fetch successfully',
            'data' =>$getType
        ]);
    }

    public function assignedAsset(Request $request)
    {
        $emp_id = $request->emp_id;

        $validator = Validator::make($request->all(), [
            'emp_id' => 'required',
            'assigned_date' => 'required',
            'asset_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        $employee = EmployeeDetail::find($emp_id);
        $asset = Asset::find($request->asset_id);

        if (!$asset) {
            return response()->json([
                'success' => false,
                'message' => 'Asset not found',
            ], 404);
        }

        $asset->update([
            'emp_id' => $request->emp_id,
            'assigned_date' => $request->assigned_date,
            'status' => '2',
        ]);

        $name = Auth::user()->fullname;
        $user_id = Auth::user()->id;

        $msg = ucwords($asset->serial_no ?? 'N/A') . ' asset has been assigned to ' . ucwords($employee->emp_name ?? 'N/A') . ' by ' . ucwords($name ?? 'N/A') . '.';

        AssetHistory::create([
            'type' => 'assign_assets',
            'asset_id' => $request->asset_id,
            'user_id' => $user_id,
            'msg' => $msg,
            'status' => 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Asset updated successfully',
            'data' => $asset,
            'history' => $msg,
        ], 200);
    }

    public function allAsset(Request $request)
    {
        $searchByName = $request->input('searchByName', '');
        $current_page = $request->input('current_page', 1);
        $per_page = $request->input('per_page', 10);
        $company = $request->company_id;
        $branch = $request->branch_id;

        $offset = ($current_page - 1) * $per_page;

        $query = DB::table('assets')
            ->leftJoin('employee_details', 'employee_details.id', '=', 'assets.emp_id')
            ->leftJoin('asset_types', 'asset_types.id', '=', 'assets.asset_type_id')
            ->leftJoin('locations', 'locations.id', '=', 'assets.branch_id')
            ->where('assets.is_deleted', 0)
            ->select('assets.id', 'assets.emp_id', 'employee_details.emp_name','assets.branch_id', 'assets.company_id','assets.asset_name', 'assets.asset_id', 'asset_types.name', 'assets.asset_price', 'locations.branch_name', 'assets.assigned_date', 'assets.guarantee_date', 'assets.status');

        if ($searchByName) {
            $query->where('assets.asset_name', 'LIKE', '%' . $searchByName . '%');
        }

        if($company){
            $query->where('assets.company_id', $company);
        }

        if($branch){
            $query->where('assets.branch_id', $branch);
        }
        //
        $total = $query->count();

        $assets = $query->offset($offset)->limit($per_page)->get();

        $last_page = ceil($total / $per_page);
        $previous_page = $current_page > 1 ? $current_page - 1 : null;
        $next_page = $current_page < $last_page ? $current_page + 1 : null;

        $base_url = $request->url();
        $query_params = $request->query();
        $previous_page_url = $previous_page ? $base_url . '?' . http_build_query(array_merge($query_params, ['current_page' => $previous_page])) : null;
        $next_page_url = $next_page ? $base_url . '?' . http_build_query(array_merge($query_params, ['current_page' => $next_page])) : null;

        $pagination = [
            'total' => $total,
            'per_page' => $per_page,
            'current_page' => $current_page,
            'last_page' => $last_page,
            'previous_page' => $previous_page,
            'next_page' => $next_page,
            'previous_page_url' => $previous_page_url,
            'next_page_url' => $next_page_url
        ];

        return response()->json([
            'status' => 1,
            'message' => 'All asset records fetched successfully',
            'data' => $assets,
            'pagination' => $pagination
        ]);
    }

    }

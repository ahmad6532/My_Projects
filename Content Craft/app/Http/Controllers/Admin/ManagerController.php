<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ManagerDataTable;
use App\Enums\UserStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\ManagerCreateRequest;
use App\Http\Requests\Manager\ManagerUpdateRequest;
use App\Repositories\Repositories\ManagerRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function __construct(public ManagerRepository $managerRepository)
    {
    }

    // all Managers
    public function allManagers(ManagerDataTable $managersDataTable)
    {
        try {
            return $managersDataTable->render('admin.allManagers');
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // create manager

    public function create()
    {
        try {
            return view('manager.create');
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // store manager
    public function store(ManagerCreateRequest $managerCreateRequest)
    {
        try {
            $this->managerRepository->create($managerCreateRequest->all());
            return redirect()->route('admin.allManagers')->with('message', 'Manager Created Successfully...');
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // show single manager
    public function showManager($managerId)
    {
        try {
            $managerData = $this->managerRepository->find($managerId);
            return response()->json(['response' => ['status' => true, 'data' => $managerData]], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // edit single manager
    public function editManager($managerId)
    {
        try {
            $managerData = $this->managerRepository->find($managerId);
            return view('admin.editManager', compact('managerData'));
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    // update Manager
    public function updateManager(ManagerUpdateRequest $managerUpdateRequest, $userId)
    {
        try {
            $this->managerRepository->update($userId, $managerUpdateRequest->all());
            return redirect()->route('admin.allManagers')->with(['message' => 'Manager Updated Successfully...']);
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    // delete manager
    public function destroy($managerId)
    {
        try {
            $this->managerRepository->delete($managerId);
            return redirect()->route('admin.allManagers')->with('message', 'Manager Deleted Successfully...');
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // Block or Unblock Manager
    function blockUnblockManager(Request $request, $ManagerId)
    {
        try {
            $status = $this->managerRepository->changeManagerStatus($ManagerId, $request->all());
            if ($status === UserStatusEnum::ACTIVE) {
                return response(['success' => true, 'message' => "Manager Activated Successfully..."]);
            }
            return response(['success' => true, 'message' => "Manager InActivated Successfully..."]);
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}

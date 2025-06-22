<?php

namespace App\Http\Controllers\Manager;

use App\DataTables\UserDataTable;
use App\Enums\UserStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Repositories\Repositories\ManagerRepository;
use App\Repositories\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(public UserRepository $userRepository, public ManagerRepository $managerRepository)
    {
    }
    // get all usesrs
    public function allUsers(UserDataTable $userDataTable)
    {
        try {
            return $userDataTable->render('manager.allUsers');
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    // show create user page
    public function create()
    {
        try {
            $managers = $this->managerRepository->all();
            return view('user.create', compact('managers'));
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // store new user
    public function store(UserCreateRequest $userCreateRequest)
    {
        try {
            $this->userRepository->create($userCreateRequest->all());
            return redirect()->route('manager.allUsers')->with('message', 'User Added Successfully...');
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    // show single user
    public function showUser($userId)
    {
        try {
            $userData = $this->userRepository->find($userId);
            return response(['success' => true, 'data' => $userData]);
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // edit single user
    public function editUser($userId)
    {
        try {
            $userData = $this->userRepository->find($userId);
            $managers = $this->managerRepository->all();
            return view('manager.editUser', compact('userData', 'managers'));
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    // update user
    public function updateUser(UserUpdateRequest $userUpdateRequest, $userId)
    {
        try {
            $this->userRepository->update($userId, $userUpdateRequest->all());
            return redirect()->route('manager.allUsers')->with(['message' => 'User Updated Successfully...']);
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    // Block or Unblock user
    function blockUnblockUser(Request $request, $userId)
    {
        try {
            $status = $this->userRepository->changeUserStatus($userId, $request->all());
            if ($status === UserStatusEnum::ACTIVE) {
                return response(['success' => true, 'message' => "User Activated Successfully..."]);
            }
            return response(['success' => true, 'message' => "User InActivated Successfully..."]);
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // delete user
    public function destroy($userId)
    {
        try {
            $this->userRepository->delete($userId);
            return redirect()->route('manager.allUsers')->with('message', 'User Deleted Successfully...');
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}

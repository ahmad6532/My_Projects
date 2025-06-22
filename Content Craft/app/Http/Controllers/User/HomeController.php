<?php

namespace App\Http\Controllers\User;

use App\DataTables\ArticlesDataTable;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserResource;
use App\Repositories\Repositories\ManagerRepository;
use App\Repositories\Repositories\UserRepository;


class HomeController extends Controller
{
    public function __construct(public UserRepository $userRepository, public ManagerRepository $managerRepository)
    {
    }
    public function index(ArticlesDataTable $articlesDataTable)
    {
        try {
                return $articlesDataTable->render('article.index');
        } catch (\Exception $e) {
        return response(['success' => false, 'message' => $e->getMessage()]);
     }
    }


    // show single user
    public function show($userId)
    {
        try {
            $userData = $this->userRepository->find($userId);
            return response(['success' => true, 'data' => new UserResource($userData)]);
        } catch (\Exception $e) {
        return response(['success' => false, 'message' => $e->getMessage()]);
     }
    }

    // edit single user
    public function edit($userId)
    {
        try {
            $userData = $this->userRepository->find($userId);
            $managerData = $userData->userToManager;
            $managers = $this->managerRepository->all();
            return view('user.edit', compact('userData', 'managers','managerData'));
        } catch (\Exception $e) {
        return response(['success' => false, 'message' => $e->getMessage()]);
     }
    }


    // update user
    public function update(UserUpdateRequest $userUpdateRequest, $userId)
    {
        try {
            $this->userRepository->update($userId, $userUpdateRequest->all());
            return redirect()->route('article.index')->with(['message' => 'Record Updated Successfully...']);
        } catch (\Exception $e) {
        return response(['success' => false, 'message' => $e->getMessage()]);
     }
    }
}

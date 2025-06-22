<?php

namespace App\Http\Controllers\Manager;

use App\Helpers\Dashboard\Manager\AllUsersByManager;
use App\Helpers\Dashboard\Manager\MonthlyAmount;
use App\Helpers\Dashboard\Manager\TotalNotificationsHelper;
use App\Helpers\Dashboard\Manager\TotalTransactionsHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\ManagerUpdateRequest;
use App\Repositories\Repositories\ManagerRepository;


class HomeController extends Controller
{

    public function __construct(public ManagerRepository $managerRepository)
    {
    }

    // manager dashboard
    public function index()
    {
        try {
            // all users
            $users = AllUsersByManager::usersByManager();

            // total users of a manager
            $totalUsers = $users->count();

            // number of transactions
            $transactions = TotalTransactionsHelper::totalTransactions($users);

            // manager earning on month base
            $mothlyAmount = MonthlyAmount::monthBaseAmount($users);

            // number of notifications
            $notifications = TotalNotificationsHelper::totalNotifications($users);

            return view('manager.index', compact('totalUsers', 'transactions', 'notifications', 'mothlyAmount'));
        
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    // show single manager
    public function show($managerId)
    {
        try {
            $managerData = $this->managerRepository->find($managerId);
            return response(['success' => true, 'data' => $managerData]);
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // edit single manager
    public function edit($managerId)
    {
        try {
            $managerData = $this->managerRepository->find($managerId);
            return view('manager.edit', compact('managerData'));
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    // update Manager
    public function update(ManagerUpdateRequest $managerUpdateRequest, $userId)
    {
        try {
            $this->managerRepository->update($userId, $managerUpdateRequest->all());
            return redirect()->route('manager.index')->with(['message' => 'Profile Updated Successfully...']);
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}

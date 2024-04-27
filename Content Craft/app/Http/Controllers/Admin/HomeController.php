<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ArticlesDataTable;
use App\Enums\UserRoleEnum;
use App\Helpers\Dashboard\Admin\AllManagersWithAmountHelper;
use App\Helpers\Dashboard\Admin\TotalManagersHelper;
use App\Helpers\Dashboard\Admin\TotalNotificationsHelper;
use App\Helpers\Dashboard\Admin\TotalTransactionsHelper;
use App\Helpers\Dashboard\Admin\TotalUsersHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUpdateRequest;
use App\Http\Resources\User\UserResource;
use App\Repositories\Repositories\AdminRepository;
use App\Repositories\Repositories\ManagerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct(public AdminRepository $adminRepository, public ManagerRepository $managerRepository)
    {
    }
    // index page of admin
    public function index()
    {
        try {
            /** @var App\Models\User */
            $user  = Auth::user();
            if ($user->hasRole(UserRoleEnum::ADMIN)) {
                // all managers
                $managers = TotalManagersHelper::totalManagers();
                // number of managers
                $totalManagers = $managers->count();
                // count amount against managers
                $amountAndManager = AllManagersWithAmountHelper::totalManagersWithAmount($managers);
                // all users
                $totalUsers = TotalUsersHelper::totalUsers();
                // number of transactions
                $transactions = TotalTransactionsHelper::totalTransactions();
                // number of notifications
                $notifications = TotalNotificationsHelper::totalNotifications();

                return view('admin.index', compact('totalManagers', 'totalUsers', 'transactions', 'notifications', 'amountAndManager'));
            } elseif ($user->hasRole(UserRoleEnum::MANAGER)) {
                return redirect()->route('manager.index');
            } elseif ($user->hasRole(UserRoleEnum::USER)) {
                return redirect()->route('article.index');
            }
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // get chart data according to date
    public function barChart(Request $request)
    {
        try {
            if (strtoupper($request->date) == 'ALL') {
                $managers =  $this->managerRepository->all();
                $amountAndManager = AllManagersWithAmountHelper::totalManagersWithAmount($managers);
                return $amountAndManager;
            }
            $managers = $this->managerRepository->findByDate($request->date);
            $amountAndManager = AllManagersWithAmountHelper::totalManagersWithAmount($managers);
            return $amountAndManager;
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // find admin profile
    public function show($adminId)
    {
        try {
            $userData = $this->adminRepository->find($adminId);
            return response(['success' => true, 'data' => new UserResource($userData)]);
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    // edit admin profile
    public function edit($adminId)
    {
        try {
            $adminData = $this->adminRepository->find($adminId);
            return view('admin.edit', compact('adminData'));
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // update profile
    public function update(AdminUpdateRequest $adminUpdateRequest, $adminId)
    {
        try {
            $this->adminRepository->update($adminId, $adminUpdateRequest->all());
            return redirect()->route('admin.index')->with('message', 'Profile Updated Successfully...');
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}

<?php

namespace App\Repositories\Repositories;

use App\Enums\UserRoleEnum;
use App\Enums\UserStatusEnum;
use App\Models\User;
use App\Repositories\Interfaces\ManagerInterface;
use App\Traits\User\ImageTrait;
use App\Traits\User\UserAvatar;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ManagerRepository implements ManagerInterface
{
    use ImageTrait;

    // find sigle manager
    public function find($managerId)
    {
        try {
            $managerData = User::where('id', $managerId)->first();
            return $managerData;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    // all manager
    public function all()
    {
        try {
            $managers = User::role(UserRoleEnum::MANAGER)->get();
            return $managers;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // find manager with date
    public function findByDate($date)
    {
        try {
            $managers =  User::role(UserRoleEnum::MANAGER)->whereMonth('updatedAt',$date)->get();
            return $managers;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // update manager
    public function update($managerId, $data)
    {
        try {
            DB::beginTransaction();
            $managerData = User::where('id', $managerId)->update([
                'firstName' =>  $data['firstName'],
                'lastName' =>  $data['lastName'],
                'phone' =>  $data['phone'],
                'gender' =>  $data['gender'],
                'address' =>  $data['address'],
                'country' =>  $data['country'],
                'postalCode' =>  $data['postalCode'],
            ]);
            DB::commit();
            return $managerData;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }




    // create new manager
    public function create($data)
    {
        try {
            DB::beginTransaction();
            $data['avatar'] = $this->uploadImage($data['avatar']);
            $manager = User::create([
                'firstName' => $data['firstName'],
                'lastName' => $data['lastName'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'gender' => $data['gender'],
                'address' => $data['address'],
                'phone' => $data['phone'],
                'country' => $data['country'],
                'postalCode' => $data['postalCode'],
                'avatar' => $data['avatar'],
            ]);
            $manager->syncRoles([UserRoleEnum::MANAGER]);
            DB::commit();
            return $manager;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
    public function delete($managerId)
    {
        try {
            DB::beginTransaction();
            User::where('id', $managerId)->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }


    // block and unblock manager
    public function changeManagerStatus($managerId, $data)
    {
        try {
            DB::beginTransaction();
            $ACTIVE = UserStatusEnum::ACTIVE;
            $INACTIVE = UserStatusEnum::INACTIVE;
            if ($data['status'] == $ACTIVE) {
                User::where('id', $managerId)->update([
                    'status' => $INACTIVE,
                ]);
                DB::commit();
                return 'INACTIVE';
            } else {
                User::where('id', $managerId)->update([
                    'status' => $ACTIVE,
                ]);
                DB::commit();
                return 'ACTIVE';
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}

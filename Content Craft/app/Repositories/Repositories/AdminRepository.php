<?php

namespace App\Repositories\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\AdminInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class AdminRepository implements AdminInterface
{

    // find sigle user
    public function find($userId)
    {
        try {
            $userData = User::where('id', $userId)->first();
            return $userData;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }


    // update user
    public function update($userId, $data)
    {
        try {
            DB::beginTransaction();
            $userData = User::where('id', $userId)->update([
                'firstName' =>  $data['firstName'],
                'lastName' =>  $data['lastName'],
                'phone' =>  $data['phone'],
                'gender' =>  $data['gender'],
                'address' =>  $data['address'],
                'country' =>  $data['country'],
                'postalCode' =>  $data['postalCode'],
            ]);
            DB::commit();
            return $userData;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }


}

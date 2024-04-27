<?php

namespace App\Repositories\Repositories\Customer;

use App\Enums\User\UserRole;
use App\Enums\User\UserRoleEnum;
use App\Models\Feedback;
use App\Models\User;
use App\Repositories\Interfaces\Customer\CustomerInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerRepository implements CustomerInterface
{
    // find Customer
    public function all()
    {
        try {
            $userData = User::where('role', UserRoleEnum::Customer->value)->get();
            return $userData;
        } catch (\Exception $e) {
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
    // add Customer
    public function create($data, $requestType)
    {
        try {
            DB::beginTransaction();
            $checkMail = User::where('email', $data['email'])->first();
            if ($checkMail) {
                return null;
            }
            $customer = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'riderId' => $data['rider'],
                'password' => Hash::make($data['password']),
                'role' => 'Customer',
            ]);
            $token = $customer->createToken('customerSignUpToken')->plainTextToken;
            $customer->token = $token;
            DB::commit();
            return $customer;
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
    // update Customer
    public function update($customerId, $data)
    {
        try {
            DB::beginTransaction();
            User::find($customerId)->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    // show a customer detail
    public function find($customerId)
    {
        try {
            $customer =  User::find($customerId);
            return $customer;
        } catch (\Exception $e) {
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

   
    //    delete a customer
    public function delete($customerId)
    {
        try {
            DB::beginTransaction();
            $customer =  User::find($customerId);
            $customer->order()->delete();
            $customer->feedback()->delete();
            $customer->delete();
            DB::commit();
            return $customer;
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}

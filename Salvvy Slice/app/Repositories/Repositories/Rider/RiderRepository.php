<?php

namespace App\Repositories\Repositories\Rider;

use App\Enums\User\UserRole;
use App\Enums\User\UserRoleEnum;
use App\Http\Requests\Rider\RiderUpdateRequest;
use App\Models\User;
use App\Repositories\Interfaces\Rider\RiderInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RiderRepository implements RiderInterface
{
    // get all rider
    public function all()
    {
        try {
            return User::where('role', UserRoleEnum::Rider->value)->get();
        } catch (\Exception $e) {
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }


    // create or signup new rider
    public function create($data, $requestType)
    {
        try {
            DB::beginTransaction();
            $checkMail = User::where('email', $data['email'])->first();
            if ($checkMail) {
                return null;
            }
            $rider = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
                'role' => 'Rider',
            ]);
            $token = $rider->createToken('RiderSignUpToken')->plainTextToken;
            $rider->token = $token;
            DB::commit();
            return $rider;
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    // show a rider detail
    public function find($riderId)
    {
        try {
            $rider =  User::find($riderId);
            return $rider;
        } catch (\Exception $e) {
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    public function update($riderId, $data)
    {
        try {
            DB::beginTransaction();
            $rider =  User::find($riderId)->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
            ]);
            DB::commit();
            return $rider;
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    //    delete a rider
    public function delete($riderId)
    {
        try {
            DB::beginTransaction();
            $rider =  User::find($riderId);
            User::where('riderId', $rider->id)->update([
                'riderId' => null,
            ]);
            $rider->orders()->delete();
            $rider->delete();
            DB::commit();
            return $rider;
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}

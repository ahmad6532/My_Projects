<?php

namespace App\Repositories\Repositories;

use App\Enums\PlanStatusEnum;
use App\Enums\UserRoleEnum;
use App\Enums\UserStatusEnum;
use App\Models\History;
use App\Models\Subscriptions;
use App\Models\User;
use App\Repositories\Interfaces\UserInterface;
use App\Traits\User\ImageTrait;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Kreait\Laravel\Firebase\Facades\Firebase;

class UserRepository implements UserInterface
{
    use ImageTrait;

    // signIn a User
    public function signIn($credentiols)
    {
        try {
            $firebase = Firebase::auth()->signInWithEmailAndPassword($credentiols['email'], $credentiols['password']);
            if ($firebase) {
                $user = User::where('email', $credentiols['email'])->first();
                if ($user &&  Hash::check($credentiols['password'], $user->password)) {
                    $user->update([
                        'uuid' => Str::random(65),
                    ]);
                    $token = $user->createToken('Login Token')->plainTextToken;
                    $user->token = $token;
                    return $user;
                }
                return null;
            }
            return null;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
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
                'managerId' =>  $data['managerId'],
            ]);
            DB::commit();
            return $userData;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    // create or signup new user
    public function create($data)
    {
        try {
            DB::beginTransaction();
            $data['avatar'] = $this->uploadImage($data['avatar']);
            $user = User::create([
                'firstName' => $data['firstName'],
                'lastName' => $data['lastName'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'gender' => $data['gender'],
                'address' => $data['address'],
                'uuid' => Str::random(65),
                'phone' => $data['phone'],
                'country' => $data['country'],
                'status' => UserStatusEnum::ACTIVE,
                'postalCode' => $data['postalCode'],
                'managerId' => auth()->id(),
                'avatar' => $data['avatar'],
            ]);
            $user->syncRoles([UserRoleEnum::USER]);
            $token = $user->createToken('Login Token')->plainTextToken;
            $user->token = $token;
            Subscriptions::create([
                'userId' => $user->id,
                'planId' => 1,
                'status' => PlanStatusEnum::PAID,
                'articles' => 5,
            ]);
            History::create([
                'userId' => $user->id,
                'planId' => 1
            ]);
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    // destroy uuid 
    public function destroy()
    {
        try {
            DB::beginTransaction();
            $user = User::where('id', auth()->id())->first();
            $user->update([
                'uuid' => '',
            ]);
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }


    // block and unblock user
    public function changeUserStatus($userId, $data)
    {
        try {
            DB::beginTransaction();
            $ACTIVE = UserStatusEnum::ACTIVE;
            $INACTIVE = UserStatusEnum::INACTIVE;
            if ($data['status'] == $ACTIVE) {
                User::where('id', $userId)->update([
                    'status' => $INACTIVE,
                ]);
                DB::commit();
                return $INACTIVE;
            } else {
                User::where('id', $userId)->update([
                    'status' => $ACTIVE,
                ]);
                DB::commit();
                return $ACTIVE;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

// delete user
    public function delete($userId)
    {
        try {
            DB::beginTransaction();
            User::where('id', $userId)->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}

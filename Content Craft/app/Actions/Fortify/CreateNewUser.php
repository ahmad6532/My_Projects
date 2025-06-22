<?php

namespace App\Actions\Fortify;

use App\Enums\PlanStatusEnum;
use App\Enums\UserRoleEnum;
use App\Enums\UserStatusEnum;
use App\Models\History;
use App\Models\Subscriptions;
use App\Models\User;
use App\Traits\User\ImageTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;
    use ImageTrait;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'firstName' =>  'required|string|max:255',
            'lastName' =>  'required|string|max:255',
            'gender' =>  'required|string',
            'address' =>  'required|string|min:3',
            'phone' =>  'required|string',
            'country' =>  'required|string',
            'postalCode' =>  'required|string',
            'managerId' =>  'required',
            'avatar' => 'required|file|image|mimes:jpeg,png,jpg,gif|max:4048', // Example extensions: jpeg, png, jpg, gif

        ])->validate();
        DB::beginTransaction();
        $avatar = $this->uploadImage($input['avatar']);
        $user = User::create([
            'firstName' => $input['firstName'],
            'lastName' => $input['lastName'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'gender' => $input['gender'],
            'address' => $input['address'],
            'phone' => $input['phone'],
            'country' => $input['country'],
            'status' => UserStatusEnum::ACTIVE,
            'postalCode' => $input['postalCode'],
            'managerId' => $input['managerId'],
            'avatar' => $avatar,
        ]);
        $user->syncRoles([UserRoleEnum::USER]);
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
    }
}

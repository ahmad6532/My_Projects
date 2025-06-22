<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'avatar' => $this->avatar,
            'country' => $this->country,
            'address' => $this->address,
            'postalCode' => $this->postalCode,
            'manager' => $this->managerId,
            'token' => $this->token,
        ];
    }
}

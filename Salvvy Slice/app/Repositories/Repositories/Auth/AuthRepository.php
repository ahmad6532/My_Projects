<?php

namespace App\Repositories\Repositories\Auth;

use App\Http\Resources\Auth\AuthResponse;
use App\Models\User;
use App\Repositories\Interfaces\Auth\AuthInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthInterface
{
    // SignIn
    public function signIn($data)
    {
        try {
            $userData = User::where('email', $data['email'])->first();
            if ($userData && Hash::check($data['password'], $userData->password)) {
                $token = $userData->createToken($userData->name . '-AuthToken')->plainTextToken;
                $userData->token = $token;
                return $userData;
            }
        } catch (\Exception $e) {
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}

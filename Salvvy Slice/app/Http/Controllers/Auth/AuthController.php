<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use App\Http\Resources\Auth\AuthResponse;
use App\Http\Resources\Auth\SignInResource;
use App\Repositories\Repositories\Auth\AuthRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function __construct(public AuthRepository $authRepository)
    {
    }
    //signIn
    public function login(AuthRequest $authRequest)
    {
        try {
            $authResponse = $this->authRepository->signIn($authRequest->all());
            if ($authResponse) {
                return response()->json(['response' => ['status' => true, 'data' => new SignInResource($authResponse)]], JsonResponse::HTTP_OK);
            }
            return response()->json(['response' => ['status' => false, 'data' => 'Invalid Email or Password']], JsonResponse::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
    // SignOut
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['response' => ['status' => true, 'data' => null]], JsonResponse::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}

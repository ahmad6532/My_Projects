<?php

namespace App\Http\Controllers\Api\User;


use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UserSignInRequest;
use App\Http\Requests\Api\User\UserSignUpRequest;
use App\Http\Resources\User\UserResource;
use App\Repositories\Repositories\UserRepository;
use App\Repositories\Repositories\ManagerRepository;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kreait\Laravel\Firebase\Facades\Firebase;

class HomeController extends Controller
{
    public function __construct(public UserRepository $userRepository, public ManagerRepository $managerRepository)
    {
    }

    // signin user 
    public function signin(UserSignInRequest $request)
    {
        try {
            $userData = $this->userRepository->signIn($request->all());
            if ($userData) {
                return response()->json(['response' => ['success' => true, 'data' => new UserResource($userData)]], JsonResponse::HTTP_OK);
            }
            return response()->json(['response' => ['success' => false, 'message' => "Invalid Email or Password"]], JsonResponse::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return response()->json(['response' => ['success' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    // create user or signup
    public function signUp(UserSignUpRequest $request)
    {
        try {
            Firebase::auth()->createUserWithEmailAndPassword($request['email'], $request['password']);
            $userData = $this->userRepository->create($request->all());
            return response()->json(['response' => ['success' => true, 'data' => new UserResource($userData)]], JsonResponse::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['response' => ['success' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    // logout user
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            $this->userRepository->destroy();
            return response()->json(['response' => ['success' => true, 'data' => null]], JsonResponse::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['response' => ['success' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}

<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $admin = Auth::user();
        if ($request->filled('password')) {
            $admin->password = bcrypt($request->input('password'));
        }

        $admin->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'admin' => $admin
        ]);
    }
}

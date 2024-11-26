<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return $this->sendError([], 'Invalid credentials', 401);
            }

            if (!$user->hasRole('user')) {
                return $this->sendError([], 'User does not have the right roles.', 403);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->sendResponse([
                'user' => $user,
                'token' => $token,
            ], 'Logged in successfully');

        } catch (ValidationException $e) {
            return $this->sendError($e->errors(), 'Validation Error.', 422);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }


    public function register(Request $request)
    {
        try {
            $request->validate([
                'user_name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required',
            ]);

            $user = User::create($request->all());
            $user->assignRole('user');

            return $this->sendResponse($user, 'Registered out successfully');
        } catch (ValidationException $e) {
            return $this->sendError($e->errors(), 'Validation Error.', 422);
        }
    }
}

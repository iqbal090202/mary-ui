<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    public function index(Request $request)
    {
        return $this->sendResponse($request->user(), 'User retrieved successfully.');
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'user_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'address' => 'required',
            'profile_picture' => 'nullable|image|max:2024',
        ]);

        if (!$validatedData) {
            $this->sendError([], 'User updated failed.');
        }

        $user->update($validatedData);

        if ($request->photo) {
            $url = $request->photo->store('users', 'public');
            $request->user->update(['profile_picture' => "/storage/$url"]);
        }

        return $this->sendResponse($user, 'User updated successfully.');
    }
}

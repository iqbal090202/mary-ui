<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    public function index(Request $request)
    {
        return $this->sendResponse($request->user(), 'User retrieved successfully.');
    }

    public function update()
    {
        return $this->sendResponse([], 'User updated successfully.');
    }
}

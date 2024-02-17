<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserProfileResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getOne(Request $request)
    {
        $userId = $request->get('userId');
        
        if(!$userId)
        {
            $userId = Auth::id();
        }

        $user = User::findOrFail($userId);

        return response([
            'status' => 'success',
            'user' => new UserProfileResource($user)
        ]);
    }
}

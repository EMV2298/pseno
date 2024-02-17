<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserProfileResource;
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
        $userId = $request->get('id');
        
        if(!$userId)
        {
            $userId = Auth::id();
        }

        $user = User::findOrFail($userId);

        return response([new UserProfileResource($user)]);
    }
}

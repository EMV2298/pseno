<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\UserRefreshToken;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'refresh']]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->getErrorValidateResponse($validator);
        }
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }
        $refreshTokenModel = UserRefreshToken::where('user_id', Auth::id())->first();
        $refreshToken = $refreshTokenModel ? $refreshTokenModel->token : $this->generateRefreshToken();
        $cookie = $this->getCookieRefreshToken($refreshToken);

        $user = Auth::user();

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ])->cookie($cookie);
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->getErrorValidateResponse($validator);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);
        $refreshToken = $this->generateRefreshToken();
        $cookie = $this->getCookieRefreshToken($refreshToken);
        return response()->json([
            'status' => 'success',
            'message' => 'Успешно',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ])->cookie($cookie);
    }

    public function logout()
    {
        $refreshTokenModel = UserRefreshToken::where('user_id', Auth::id())->first();
        if(!$refreshTokenModel)
        {
            return response([
                'status' => 'error',
                'message' => 'Не найден refresh token'
            ], 400);
        }
        $refreshTokenModel->delete();
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Успешно',
        ]);
    }

    public function refresh()
    {
        $refreshToken = Cookie::get('refresh-token');
        if(!$refreshToken)
        {
            return response([
                'status' => 'error',
                'message' => 'Не найден refresh token'
            ], 400);
        }
        $refreshTokenModel = UserRefreshToken::where('token', $refreshToken)->first();
        if(!$refreshTokenModel)
        {
            return response([
                'status' => 'error',
                'message' => 'Не найден refresh token'
            ], 400);
        }
        $user = User::find($refreshTokenModel->user_id);

        if(!$user)
        {
            return response([
                'status' => 'error',
                'message' => 'Не найден пользователь'
            ], 400);
        }

        Auth::login($user);

        return response()->json([
            'status' => 'success',
            'message' => 'Успешно',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    private function generateRefreshToken()
    {
        $refreshToken = Str::random(150);
        $userRefreshToken = new UserRefreshToken();
        $userRefreshToken->user_id = Auth::id();
        $userRefreshToken->token = $refreshToken;
        $userRefreshToken->save();
        return $refreshToken;
    }

    private function getCookieRefreshToken($token)
    {
        return cookie('refresh-token', $token, 60 * 24 * 365, '/api/refresh', null, false, true, false, 'none');
    }
}

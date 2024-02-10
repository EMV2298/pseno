<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function getErrorValidateResponse($validator)
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Ошибка валидации',
            'validator' => $validator->errors()
        ], 422);
    }
}

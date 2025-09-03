<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function respondWithSuccess($data, $message = 'Success')
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ]);
    }

    protected function respondWithError($message, $code = 400)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message
        ], $code);
    }
    protected function validateRequest($request, $rules)
    {
        return $request->validate($rules);
    }

    protected function authorizeRequest($request, $ability, $model = null)
    {
        return $request->user()->can($ability, $model);
    }
}
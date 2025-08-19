<?php

namespace App\Http\Controllers;

abstract class Controller
{

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

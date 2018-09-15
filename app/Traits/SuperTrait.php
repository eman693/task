<?php

namespace App\Traits;


trait SuperTrait
{
    public function jsonResponse($status, $error_code, $message ,$validation, $response)
    {
        return response()->json([
            'Error' => [
                'status' => $status,
                'code' => $error_code,
                'validation' => $validation,
                'desc' => $message,
            ],
            'Response' => $response,
        ], 200);
    }
}
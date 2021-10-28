<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function sendResponse($result, $message, $code=200){
        $response = [
            "success" => true,
            "data" => $result,
            "message" => $message
        ];
        return response()->json($response, $code);
    }

    public function sendError($error, $message, $code = 404){
        $response = [
            'success' => false,
            'error_message' => $message,
            'error_data' => $error
        ];
        return response()->json($response,$code);
    }
}

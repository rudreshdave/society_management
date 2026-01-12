<?php

namespace App\Traits;

trait ApiResponse {

    private $errorCodes = [
        1 => ['httpStatus' => 200],
        2 => ['httpStatus' => 400, 'message' => 'Bad Request'],
        3 => ['httpStatus' => 400, 'message' => 'Enter Duplicate Record'],
        4 => ['httpStatus' => 401, 'message' => 'Authenticaion Credentials Invalid!'],
        5 => ['httpStatus' => 401, 'message' => 'Token Expired.'],
        6 => ['httpStatus' => 401, 'message' => 'Invalid Token'],
        7 => ['httpStatus' => 401, 'message' => 'Invalid Access Key'],
        8 => ['httpStatus' => 200, 'message' => 'No Record Found.'],
        9 => ['httpStatus' => 406, 'message' => 'Validation Error'],
        10 => ['httpStatus' => 401, 'message' => 'Invalid Credentials'],
        -1 => ['httpStatus' => 500, 'message' => 'Internal Server Error'],
    ];

    public function customResponse($code, $message = null, $data = null, $pagination = null) {
        if (array_key_exists($code, $this->errorCodes)) {
            $response['status'] = $code == 1 || $code == 8 ? true : false;
            $response['code'] = $code;
            $response['message'] = isset($message) && !empty($message) ? $message : $response_message = $this->errorCodes[$code]['message'];
            if (isset($data) && !empty($data)) {
                $response['data'] = $data;
            }
            if (isset($pagination) && !empty($pagination)) {
                $response['pagination'] = $pagination;
            }
            return response()->json($response, $this->errorCodes[$code]['httpStatus']);
        }
    }
}

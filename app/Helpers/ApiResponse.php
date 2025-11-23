<?php

namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;

class ApiResponse
{
    /**
     * Success Response
     */
    public static function success($message = 'Success', $data = [], $code = 200)
    {
        $response = [
            'status'  => true,
            'code'    => $code,
            'message' => $message,
            'data'    => $data,
        ];

        // Auto Pagination Detection
        if ($data instanceof LengthAwarePaginator) {
            $response['data'] = $data->items(); // actual data
            $response['pagination'] = [
                'total'        => $data->total(),
                'per_page'     => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page'    => $data->lastPage(),
            ];
        }

        return response()->json($response, $code);
    }

    /**
     * General Error
     */
    public static function error($message = 'Error occurred', $code = 400, $errors = [])
    {
        return response()->json([
            'status'  => false,
            'code'    => $code,
            'message' => $message,
            'errors'  => $errors,
        ], $code);
    }

    /**
     * Validation Error (422)
     */
    public static function validationError($errors, $message = 'Validation failed', $code = 422)
    {
        return response()->json([
            'status'  => false,
            'code'    => $code,
            'message' => $message,
            'errors'  => $errors,
        ], $code);
    }

    /**
     * Unauthorized (401)
     */
    public static function unauthorized($message = 'Unauthorized')
    {
        return self::error($message, 401);
    }

    /**
     * Forbidden (403)
     */
    public static function forbidden($message = 'Forbidden access')
    {
        return self::error($message, 403);
    }

    /**
     * Not Found (404)
     */
    public static function notFound($message = 'Resource not found')
    {
        return self::error($message, 404);
    }

    /**
     * Server Error (500)
     */
    public static function serverError($message = 'Server error')
    {
        return self::error($message, 500);
    }

    /**
     * Custom response (Flexible)
     */
    public static function custom(array $payload, $code = 200)
    {
        return response()->json($payload, $code);
    }
}

// composer dump-autoload
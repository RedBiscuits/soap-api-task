<?php

namespace App\Http\Traits;

trait ResponseTrait
{
    public function respondOk($data, $message = 'Success')
    {
        return response([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'errors' => null,
        ], 200);
    }

    public function respondCreated($data, $message = 'Created successfully')
    {
        return response([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'errors' => null,
        ], 201);
    }

    public function respondError($errors, $message = 'Error Occured')
    {
        return response([
            'success' => false,
            'message' => $message,
            'data' => null,
            'errors' => $errors,
        ], 401);
    }

    public function respondNotFound($errors, $message = 'Error Occured')
    {
        return response([
            'success' => false,
            'message' => $message,
            'data' => null,
            'errors' => $errors,
        ], 404);
    }

    public function respondNoContent()
    {
        return response()->noContent();
    }
}

<?php

namespace App\Traits;

trait ApiResponse
{
    /**
     * Return a success JSON response.
     *
     * @param  array|string  $data
     * @param  string  $message
     * @param  int  $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success($data, string $message = null, int $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Return an error JSON response.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  array|string|null  $data
     * @param  array|null  $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function error(string $message = null, int $code = 400, $data = null, array $errors = null)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data,
            'errors' => $errors
        ], $code);
    }

    /**
     * Return a validation error JSON response.
     *
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function validationError($exception)
    {
        return $this->error(
            'The given data was invalid.',
            422,
            null,
            $exception->errors()
        );
    }
}

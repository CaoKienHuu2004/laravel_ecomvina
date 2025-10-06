<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Trả về JSON response với UTF-8 encoding để hiển thị đúng tiếng Việt
     *
     * @param mixed $data Dữ liệu trả về
     * @param int $status HTTP status code
     * @param array $headers Headers tùy chỉnh
     * @param int $options JSON encoding options
     * @return JsonResponse
     */
    protected function jsonResponse($data = null, int $status = 200, array $headers = [], int $options = 0): JsonResponse
    {
        $headers['Content-Type'] = 'application/json; charset=utf-8';
        $options = JSON_UNESCAPED_UNICODE | $options;

        return response()->json($data, $status, $headers, $options);
    }

    protected function success($data = [], string $message = 'Thành công', int $status = 200): JsonResponse
    {
        return $this->jsonResponse([
            'status'  => true,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    protected function error(string $message = 'Có lỗi xảy ra', array $errors = [], int $status = 400): JsonResponse
    {
        return $this->jsonResponse([
            'status'  => false,
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }
}

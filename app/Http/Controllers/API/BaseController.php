<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
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
        // Thiết lập header UTF-8
        $headers['Content-Type'] = 'application/json; charset=utf-8';

        // Luôn giữ nguyên Unicode (không escape tiếng Việt)
        $options = JSON_UNESCAPED_UNICODE | $options;

        return response()->json($data, $status, $headers, $options);
    }
}

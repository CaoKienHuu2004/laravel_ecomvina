<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponse;
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        //
    }

    // Xử lý khi chưa đăng nhập
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return $this->jsonResponse([
                'message' => 'Bạn chưa đăng nhập hoặc token không hợp lệ',
            ], 401);
        }

        return response()->view('errors.401', [], 401);
    }

    // Render lỗi chung
    public function render($request, Throwable $exception)
    {
        // ❌ Lỗi validate
        if ($exception instanceof ValidationException) {
            if ($request->expectsJson()) {
                return $this->jsonResponse([
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors'  => $exception->errors(),
                ], 422);
            }
        }

        // ❌ Lỗi 404
        if ($exception instanceof NotFoundHttpException) {
            if ($request->expectsJson()) {
                return $this->jsonResponse(['message' => 'Không tìm thấy'], 404);
            }

            return response()->view('errors.404', [], 404);
        }

        return parent::render($request, $exception);
    }
}

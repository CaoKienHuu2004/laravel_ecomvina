<?php

// namespace App\Exceptions;

// use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
// use Throwable;

// class Handler extends ExceptionHandler
// {
//     /**
//      * The list of the inputs that are never flashed to the session on validation exceptions.
//      *
//      * @var array<int, string>
//      */
//     protected $dontFlash = [
//         'current_password',
//         'password',
//         'password_confirmation',
//     ];

//     /**
//      * Register the exception handling callbacks for the application.
//      */
//     public function register(): void
//     {
//         $this->reportable(function (Throwable $e) {
//             //
//         });
//     }
// }

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        //
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json([
            'message' => 'Bạn chưa đăng nhập hoặc token không hợp lệ',
        ], 401);
    }
}




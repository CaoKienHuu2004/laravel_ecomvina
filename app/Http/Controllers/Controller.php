<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
/**
 * @OA\Info(
 *     title="EcomVina API",
 *     version="1.0.0",
 *     description="Tài liệu API cho dự án EcomVina - Laravel + Swagger",
 * ),
 * @OA\Server(
 *     url="http://127.0.0.1:8000",
 *     description="Localhost server"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}

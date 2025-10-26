<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BaseFrontendController extends Controller
{
    use ApiResponse;

    /**
     * $user = $this->authUser($req); khi cần dùng
     */
    protected function authUser(Request $req)
    {
        return $req->get('auth_user');
    }
}

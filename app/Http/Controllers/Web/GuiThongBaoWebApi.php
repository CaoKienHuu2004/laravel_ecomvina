<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\API\Frontend\GuiThongBaoFrontendAPI;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GuiThongBaoWebApi extends GuiThongBaoFrontendAPI
{
    // không có data thì có đầu mà dùng
    // public function __construct()
    // {
    //     parent::__construct();
    // }

    public function guiLienHe(Request $request)
    {
        $response = parent::guiLienHe($request);

        return $response;
    }
}

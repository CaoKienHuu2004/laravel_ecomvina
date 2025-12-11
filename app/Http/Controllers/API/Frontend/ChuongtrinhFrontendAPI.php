<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\ChuongtrinhAllResource;
use App\Models\ChuongTrinhModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ChuongtrinhFrontendAPI extends BaseFrontendController
{

    //
    public function index(Request $request)
    {

        $data = ChuongTrinhModel::with('quatangsukien','quatangsukien.bienthe')->orderBy('id','desc')->get();
        return $this->jsonResponse([
            'status' => true,
            'message' => "Chương trình quà tặng và biến thể quà tặng của nó",
            'data' => ChuongtrinhAllResource::collection($data),
        ], Response::HTTP_OK);
    }
}

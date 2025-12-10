<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestUploadController extends Controller
{
    public function index()
    {
        return view('errors.test-upload');
    }

    public function upload(Request $request)
    {
        // dd($_FILES, $request->all());
        if (!$request->hasFile('file')) {
            return redirect()->back()->with('error', 'Không nhận được file từ request!');
        }

        $file = $request->file('file');

        if (!$file->isValid()) {
            return redirect()->back()->with('error', 'File upload bị lỗi!');
        }

        $path = $file->store('uploads', 'public');

        $url = asset('storage/' . $path);

        return redirect()->back()->with([
            'success' => 'Upload thành công!',
            'path' => $path,
            'url' => $url,
        ]);
    }
}

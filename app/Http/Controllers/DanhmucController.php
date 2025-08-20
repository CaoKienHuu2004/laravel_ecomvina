<?php

namespace App\Http\Controllers;

use App\Models\Danhmuc;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DanhmucExport;
use Barryvdh\DomPDF\Facade\Pdf;

class DanhmucController extends Controller
{
    public function index()
    {
        $danhmuc = Danhmuc::all();
        return view('danhmuc', compact('danhmuc'));
    }

    public function create()
    {
        return view('taodanhmuc');
    }

    public function store(Request $request)
    {
        Danhmuc::create($request->only(['ten', 'trang_thai']));
        return redirect()->route('danh-sach');
    }

    public function edit($id)
    {
        $danhmuc = Danhmuc::findOrFail($id);
        return view('danhmuc.edit', compact('danhmuc'));
    }

    public function update(Request $request, $id)
    {
        $danhmuc = Danhmuc::findOrFail($id);
        $danhmuc->update($request->only(['ten', 'trang_thai']));
        return redirect()->route('danhmuc.index');
    }

    public function destroy($id)
    {
        Danhmuc::destroy($id);
        return redirect()->route('danh-sach-danh-muc');
    }
}

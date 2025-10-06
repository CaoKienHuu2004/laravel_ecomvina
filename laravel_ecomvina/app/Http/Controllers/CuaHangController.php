<?php

namespace App\Http\Controllers;

use App\Models\Diachi;
use App\Models\Nguoidung;
use App\Models\ThongTinNguoiBanHang;
use Illuminate\Http\Request;

class CuaHangController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $danhsach = Nguoidung::with('diachi')
            ->where('vaitro', 'assistant')
            ->get();
        // $danhsach = Nguoidung::with('diachi')->vaitro('assistant')->get();
        $diachi = Diachi::all();
        $thongTinNguoiBan = ThongTinNguoiBanHang::all();

        return view("cuahang.index", compact("danhsach","diachi"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cuahang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cuaHang = ThongTinNguoiBanHang::with([
            'nguoidung.diachi',
            'thuongHieu.sanpham.bienThe',
            'sanPhams.bienThe'
        ])
        ->whereHas('nguoidung', function ($query) {
            $query->where('vaitro', 'assistant');
        })
        ->findOrFail($id);

        return view("cuahang.show", compact("cuaHang"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}


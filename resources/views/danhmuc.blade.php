@extends('layouts.app')

@section('title', 'Danh sách danh mục | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Danh mục sản phẩm</h4>
                <h6>Theo dõi {{ $danhmuc->where('trangthai', 0)->count() }} danh mục của sản phẩm</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('tao-danh-muc') }}" class="btn btn-added">
                    <img
                        src="{{asset('')}}img/icons/plus.svg"
                        class="me-1"
                        alt="img" />Thêm danh mục
                </a>
            </div>
        </div>
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        <div class="card">
            <div class="card-body">
                <div class="table-top">
                    <div class="search-set">
                        <div class="search-path">

                        </div>
                        <div class="search-input">
                            <a class="btn btn-searchset"><img src="{{asset('')}}img/icons/search-white.svg" alt="img" /></a>
                        </div>
                    </div>
                    <!-- <div class="wordset">
                        <ul>
                            <li>
                                <button
                                    data-bs-toggle="tooltip"
                                    class="btn p-0"
                                    data-bs-placement="top"
                                    title="Xuất file PDF" id="btnPdf"><img src="{{asset('')}}img/icons/pdf.svg" alt="img" /></button>
                            </li>
                            <li>
                                <button
                                    data-bs-toggle="tooltip"
                                    class="btn p-0"
                                    data-bs-placement="top"
                                    title="Xuất file Excel" id="btnExcel"><img src="{{asset('')}}img/icons/excel.svg" alt="img" /></button>
                            </li>
                            <li>
                                <button
                                    data-bs-toggle="tooltip"
                                    class="btn p-0"
                                    data-bs-placement="top"
                                    title="In" id="btnPrint"><img src="{{asset('')}}img/icons/printer.svg" alt="img" /></button>
                            </li>
                        </ul>
                    </div> -->
                </div>


                <div class="table-responsive">
                    <table class="table datanew">
                        <thead>
                            <tr>
                                <th>Tên danh mục</th>
                                <th>Số sản phẩm</th>
                                <th>Cập nhật</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($danhmuc as $dm)
                            <tr>
                                <td>{{ $dm->ten }}</td>
                                <td data-bs-toggle="tooltip" data-bs-placement="top" title="danh mục này có {{ $dm->sanpham_count }} sản phẩm">{{ $dm->sanpham_count }}</td>
                                <td>{{ $dm->updated_at->format('d/m/Y - H:i') }}</td>
                                <td>
                                    <a class="me-3 btn" href="{{ route('chinh-sua-danh-muc',$dm->id) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Sửa">
                                        <img src="{{asset('')}}img/icons/edit.svg" alt="img" />
                                    </a>
                                    <form action="{{ route('xoa-danh-muc', $dm->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Bạn chắc chắn muốn xóa danh mục này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="me-3 btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Xóa"><img src="{{asset('img/icons/delete.svg')}}" alt="img" /></button>
                                    </form>

                                    </a>
                                </td>
                            </tr>
                            @endforeach


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<style>
    .dt-buttons {
        display: none !important;
    }
</style>



@endsection
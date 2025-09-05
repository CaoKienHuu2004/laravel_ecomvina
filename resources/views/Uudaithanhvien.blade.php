@extends('layouts.app')

@section('title', 'Danh sách ưu đãi thành viên | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>DANH SÁCH CẤP ĐỘ THÀNH VIÊN</h4>
                <h6>Theo dõi thông tin 3 cấp độ thành viên</h6>
            </div>
            <div class="page-btn">
                <a href="" class="btn btn-added">
                    <img
                        src="{{asset('img/icons/plus.svg')}}"
                        class="me-1"
                        alt="img" />Thêm cấp độ
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
                                <th>Cấp độ</th>
                                <th>Điều kiện</th>
                                <th>Giảm giá</th>
                                <th>Thành viên</th>
                                <th>Cập nhật</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <tr>
                                <td>Bậc Vàng</td>
                                <td data-bs-toggle="tooltip" data-bs-placement="top" title="Thành viên cần có tổng hóa đơn trên 200.000 đ">200.000 đ</td>
                                <td data-bs-toggle="tooltip" data-bs-placement="top" title="Mỗi hóa đơn của thành viên được giảm 20%">20%</td>
                                <td data-bs-toggle="tooltip" data-bs-placement="top" title="Cấp độ này có 2 thành viên">2</td>
                                <td>2024</td>
                                <td>
                                    <a class="me-3 btn" href="/" data-bs-toggle="tooltip" data-bs-placement="top" title="Sửa">
                                        <img src="{{asset('')}}img/icons/edit.svg" alt="img" />
                                    </a>
                                    <form action="/" method="POST" class="d-inline"
                                        onsubmit="return confirm('Bạn chắc chắn muốn xóa cấp độ này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="me-3 btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Xóa"><img src="{{asset('img/icons/delete.svg')}}" alt="img" /></button>
                                    </form>

                                    </a>
                                </td>
                            </tr>
                            


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
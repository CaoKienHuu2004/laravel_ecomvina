{{-- resources/views/magiamgia/show.blade.php --}}
{{-- HOẶC resources/views/quanlygiamgia/show.blade.php – tùy mày để ở đâu --}}
@extends('layouts.app')

@section('title', 'Chi Tiết Mã Giảm Giá | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <x-header.breadcrumb
                title="Chi Tiết Mã Giảm Giá"
                :links="[
                    ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                    ['label' => 'Danh sách Mã Giảm Giá', 'route' => 'danhsach.magiamgia']
                ]"
                active="Chi tiết"
            />
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="mb-4"><strong>{{ $magiamgia->magiamgia }}</strong></h4>

                        <table class="table table-borderless">
                            <tr>
                                <td width="200"><strong>Mã giảm giá</strong></td>
                                <td>: {{ $magiamgia->magiamgia }}</td>
                            </tr>
                            <tr>
                                <td><strong>Điều kiện áp dụng</strong></td>
                                <td>:
                                    @if($magiamgia->dieukien > 0 || $magiamgia->dieukien > 0)

                                        @if($magiamgia->dieukien > 0)
                                            Đơn hàng từ {{ number_format($magiamgia->dieukien) }} VNĐ
                                        @endif
                                    @else
                                        Không yêu cầu
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Giá trị giảm</strong></td>
                                <td>: {{ number_format($magiamgia->giatri) }} VNĐ</td>
                            </tr>
                            <tr>
                                <td><strong>Mô tả</strong></td>
                                <td>: {{ $magiamgia->mota ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Ngày bắt đầu</strong></td>
                                <td>: {{ \Carbon\Carbon::parse($magiamgia->ngaybatdau)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Ngày kết thúc</strong></td>
                                <td>: {{ \Carbon\Carbon::parse($magiamgia->ngayketthuc)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Trạng thái</strong></td>
                                <td>: {{ $magiamgia->trangthai }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="mt-3">
                    <a href="{{ route('edit.magiamgia', $magiamgia->id) }}" class="btn btn-primary">
                        Sửa mã giảm giá
                    </a>
                    <a href="{{ route('danhsach.magiamgia') }}" class="btn btn-secondary ms-2">
                        Quay lại danh sách
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

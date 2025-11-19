@extends('layouts.app')

@section('title', 'Chi tiết địa chỉ giao hàng')

{{-- // các route sư dụng diachigiaohang.edit --- của breadcrumb diachigiaohang.index trang-chu --}}

{{-- // controller truyền xuống $diachi $countDiaChiNguoiDung --}}
@section('content')
<div class="page-wrapper">
    <div class="content">

        <div class="page-header">
            <x-header.breadcrumb
                title='Xem Chi Tiết địa chỉ giao hàng của người dùng.'
                :links="[
                    ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                    ['label' => 'Danh sách địa chỉ giao hàng', 'route' => 'diachigiaohang.index']
                ]"
                active="Chi tiết"
            />
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th style="width: 200px;">Người dùng</th>
                            <td>{{ $diachi->nguoidung ? $diachi->nguoidung->hoten : 'Không xác định' }}</td>
                        </tr>
                        <tr>
                            <th>Họ tên người nhận</th>
                            <td>{{ $diachi->hoten }}</td>
                        </tr>
                        <tr>
                            <th>Số điện thoại</th>
                            <td>{{ $diachi->sodienthoai ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Địa chỉ chi tiết</th>
                            <td>{{ $diachi->diachi }}</td>
                        </tr>
                        <tr>
                            <th>Tỉnh/Thành phố</th>
                            <td>{{ $diachi->tinhthanh }}</td>
                        </tr>
                        <tr>
                            <th>Tài khoản <br> (Username / Email)</th>
                            @php
                                $usernameValue = explode(',', $diachi->nguoidung->username ?? '')[0] ?? 'Chưa cập nhật';
                                $emailValue = explode(',', $diachi->nguoidung->username ?? '')[1] ?? 'Chưa cập nhật';
                            @endphp
                            <td>{{ $usernameValue }} / {{ $emailValue }}</td>
                        </tr>
                        <tr>
                            <th>Số lượng địa chỉ giao hàng</th>
                            <td>{{ $countDiaChiNguoiDung }}</td>
                        </tr>
                        <tr>
                            <th>Trạng thái</th>
                            <td>
                                @php
                                    $statusClass = match($diachi->trangthai) {
                                        'Mặc định' => 'bg-lightgreen',
                                        'Khác' => 'bg-secondary',
                                        'Tạm ẩn' => 'bg-lightyellow',
                                        default => '',
                                    };
                                @endphp
                                <span class="badges {{ $statusClass }}">{{ $diachi->trangthai }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection

@section('styles')
<style>
    .bg-lightgreen {
        background-color: #d4edda;
        color: #155724;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 600;
    }
    .bg-lightyellow {
        background-color: #fff3cd;
        color: #856404;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 600;
    }
    .bg-lightgray {
        background-color: #e2e3e5;
        color: #6c757d;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 600;
    }
    .badges {
        display: inline-block;
    }
</style>
@endsection

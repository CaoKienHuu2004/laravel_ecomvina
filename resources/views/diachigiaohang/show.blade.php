@extends('layouts.app')

@section('title', 'Chi tiết địa chỉ giao hàng')
{{-- // các route sư dụng  diachigiaohang.index, diachigiaohang.edit --}}

{{-- // controller truyền xuống $diachi $countDiaChiNguoiDung --}}
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header mb-4">
            <div class="page-title">
                <h4>Chi tiết địa chỉ giao hàng</h4>
                <h6>Thông tin chi tiết địa chỉ giao hàng của khách hàng</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('diachigiaohang.index') }}" class="btn btn-primary">← Quay lại danh sách</a>
                <a href="{{ route('diachigiaohang.edit', $diachi->id) }}" class="btn btn-warning ms-2">✏️ Chỉnh sửa</a>
            </div>
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
                            <th>Tài Khoản</th>
                            <td><p> {{ $diachi->nguoidung->username ?? 'N/A' }} có {{ $countDiaChiNguoiDung }} địa chỉ giao hàng.</p></td>
                        </tr>

                        <tr>
                            <th>Trạng thái</th>
                            <td>
                                @php
                                    $statusClass = match($diachi->trangthai) {
                                        'Mặc định' => 'bg-lightgreen',
                                        'Khác' => 'bg-lightgray',
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

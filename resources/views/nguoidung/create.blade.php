@extends('layouts.app')

@section('title', 'Thêm mới người dùng')

{{--
    // Controller truyền xuống $tinhthanhs (mảng tỉnh thành)
    // các route sư dụng nguoidung.store --- của breadcrumb nguoidung.index trang-chu
--}}

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <x-header.breadcrumb
                title="Thêm Mới Người Dùng"
                :links="[
                    ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                    ['label' => 'Danh sách người dùng', 'route' => 'nguoidung.index']
                ]"
                active="Thêm mới"
            />
        </div>

        {{-- Hiển thị lỗi validate --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        <form action="{{ route('nguoidung.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card">
                <div class="card-header">
                    <h5>Thông tin người dùng</h5>
                </div>
                <div class="card-body row">

                    <div class="col-lg-6 mb-3">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}" required>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label for="password_confirmation" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label for="hoten" class="form-label">Họ tên <span class="text-danger">*</span></label>
                        <input type="text" name="hoten" id="hoten" class="form-control" value="{{ old('hoten') }}" required>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label for="sodienthoai" class="form-label">Số điện thoại</label>
                        <input type="text" name="sodienthoai" id="sodienthoai" class="form-control" value="{{ old('sodienthoai') }}">
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label for="gioitinh" class="form-label">Giới tính</label>
                        <select name="gioitinh" id="gioitinh" class="form-select">
                            <option value="">-- Chọn giới tính --</option>
                            <option value="Nam" {{ old('gioitinh') == 'Nam' ? 'selected' : '' }}>Nam</option>
                            <option value="Nữ" {{ old('gioitinh') == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                        </select>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label for="ngaysinh" class="form-label">Ngày sinh</label>
                        <input type="date" name="ngaysinh" id="ngaysinh" class="form-control" value="{{ old('ngaysinh') }}">
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label for="vaitro" class="form-label">Vai trò <span class="text-danger">*</span></label>
                        <select name="vaitro" id="vaitro" class="form-select" required>
                            <option value="">-- Chọn vai trò --</option>
                            <option value="admin" {{ old('vaitro') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="seller" {{ old('vaitro') == 'seller' ? 'selected' : '' }}>Seller</option>
                            <option value="client" {{ old('vaitro') == 'client' ? 'selected' : '' }}>Client</option>
                        </select>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label for="trangthai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                        <select name="trangthai" id="trangthai" class="form-select" required>
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="Hoạt động" {{ old('trangthai') == 'Hoạt động' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="Tạm khóa" {{ old('trangthai') == 'Tạm khóa' ? 'selected' : '' }}>Tạm khóa</option>
                            <option value="Dừng hoạt động" {{ old('trangthai') == 'Dừng hoạt động' ? 'selected' : '' }}>Dừng hoạt động</option>
                        </select>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label for="avatar" class="form-label">Ảnh đại diện</label>
                        <input type="file" name="avatar" id="avatar" class="form-control" accept="image/*">
                    </div>

                </div>
            </div>

            {{-- Thông tin địa chỉ giao hàng --}}
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Địa chỉ giao hàng</h5>
                </div>
                <div class="card-body row">

                    <div class="col-lg-12 mb-3">
                        <label for="diachi_diachi" class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                        <textarea name="diachi_diachi" id="diachi_diachi" class="form-control" required>{{ old('diachi_diachi') }}</textarea>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label for="diachi_tinhthanh" class="form-label">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                        <select name="diachi_tinhthanh" id="diachi_tinhthanh" class="form-select" required>
                            <option value="">-- Chọn tỉnh/thành --</option>
                            @foreach ($tinhthanhs as $tinhthanh)
                                <option value="{{ $tinhthanh }}" {{ old('diachi_tinhthanh') == $tinhthanh ? 'selected' : '' }}>
                                    {{ $tinhthanh }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label for="diachi_trangthai" class="form-label">Trạng thái địa chỉ</label>
                        <select name="diachi_trangthai" id="diachi_trangthai" class="form-select">
                            <option value="Mặc định" {{ old('diachi_trangthai') == 'Mặc định' ? 'selected' : '' }}>Mặc định</option>
                            <option value="Khác" {{ old('diachi_trangthai') == 'Khác' ? 'selected' : '' }}>Khác</option>
                            <option value="Tạm ẩn" {{ old('diachi_trangthai') == 'Tạm ẩn' ? 'selected' : '' }}>Tạm ẩn</option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Lưu người dùng</button>
            </div>

        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Thêm mới người dùng')
{{-- // các route sư dụng  nguoidung.index,nguoi.store --}}
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Thêm mới người dùng</h4>
                <h6>Nhập thông tin người dùng và địa chỉ giao hàng</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('nguoidung.index') }}" class="btn btn-secondary">
                    Quay lại danh sách
                </a>
            </div>
        </div>

        {{-- Hiển thị lỗi validate --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('nguoidung.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Thông tin người dùng --}}
            <div class="card">
                <div class="card-header">
                    <h5>Thông tin người dùng</h5>
                </div>
                <div class="card-body">

                    <div class="form-group mb-3">
                        <label for="username">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="password">Mật khẩu <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="password_confirmation">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="hoten">Họ tên <span class="text-danger">*</span></label>
                        <input type="text" name="hoten" id="hoten" class="form-control" value="{{ old('hoten') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="sodienthoai">Số điện thoại</label>
                        <input type="text" name="sodienthoai" id="sodienthoai" class="form-control" value="{{ old('sodienthoai') }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="gioitinh">Giới tính</label>
                        <select name="gioitinh" id="gioitinh" class="form-control">
                            <option value="">-- Chọn giới tính --</option>
                            <option value="Nam" {{ old('gioitinh') == 'Nam' ? 'selected' : '' }}>Nam</option>
                            <option value="Nữ" {{ old('gioitinh') == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="ngaysinh">Ngày sinh</label>
                        <input type="date" name="ngaysinh" id="ngaysinh" class="form-control" value="{{ old('ngaysinh') }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="vaitro">Vai trò <span class="text-danger">*</span></label>
                        <select name="vaitro" id="vaitro" class="form-control" required>
                            <option value="">-- Chọn vai trò --</option>
                            <option value="admin" {{ old('vaitro') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="seller" {{ old('vaitro') == 'seller' ? 'selected' : '' }}>Seller</option>
                            <option value="client" {{ old('vaitro') == 'client' ? 'selected' : '' }}>Client</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="trangthai">Trạng thái <span class="text-danger">*</span></label>
                        <select name="trangthai" id="trangthai" class="form-control" required>
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="Hoạt động" {{ old('trangthai') == 'Hoạt động' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="Tạm khóa" {{ old('trangthai') == 'Tạm khóa' ? 'selected' : '' }}>Tạm khóa</option>
                            <option value="Dừng hoạt động" {{ old('trangthai') == 'Dừng hoạt động' ? 'selected' : '' }}>Dừng hoạt động</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="avatar">Ảnh đại diện</label>
                        <input type="file" name="avatar" id="avatar" class="form-control" accept="image/*">
                    </div>

                </div>
            </div>

            {{-- Thông tin địa chỉ giao hàng --}}
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Địa chỉ giao hàng</h5>
                </div>
                <div class="card-body">

                    <div class="form-group mb-3">
                        <label for="diachi_diachi">Địa chỉ <span class="text-danger">*</span></label>
                        <textarea name="diachi_diachi" id="diachi_diachi" class="form-control" required>{{ old('diachi_diachi') }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="diachi_tinhthanh">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                        <select name="diachi_tinhthanh" id="diachi_tinhthanh" class="form-control" required>
                            <option value="">-- Chọn tỉnh/thành --</option>
                            <option value="TP. Hồ Chí Minh" {{ old('diachi_tinhthanh') == 'TP. Hồ Chí Minh' ? 'selected' : '' }}>TP. Hồ Chí Minh</option>
                            <option value="Hà Nội" {{ old('diachi_tinhthanh') == 'Hà Nội' ? 'selected' : '' }}>Hà Nội</option>
                            <option value="Đà Nẵng" {{ old('diachi_tinhthanh') == 'Đà Nẵng' ? 'selected' : '' }}>Đà Nẵng</option>
                            {{-- Thêm các tỉnh/thành khác nếu cần --}}
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="diachi_trangthai">Trạng thái địa chỉ</label>
                        <select name="diachi_trangthai" id="diachi_trangthai" class="form-control">
                            <option value="Mặc định" {{ old('diachi_trangthai') == 'Mặc định' ? 'selected' : '' }}>Mặc định</option>
                            <option value="Khác" {{ old('diachi_trangthai') == 'Khác' ? 'selected' : '' }}>Khác</option>
                            <option value="Tạm ẩn" {{ old('diachi_trangthai') == 'Tạm ẩn' ? 'selected' : '' }}>Tạm ẩn</option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Lưu người dùng</button>
                <a href="{{ route('nguoidung.index') }}" class="btn btn-secondary">Hủy</a>
            </div>

        </form>
    </div>
</div>
@endsection

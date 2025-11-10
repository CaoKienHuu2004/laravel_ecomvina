@extends('layouts.app')

@section('title', 'Thêm mới địa chỉ giao hàng')
{{-- // các route sư dụng  diachigiaohang.index, diachigiaohang.store --}}

{{-- // controller truyền xuống $tinhthanhs $nguoidungs --}}
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Thêm mới địa chỉ giao hàng</h4>
                <h6>Nhập thông tin địa chỉ giao hàng</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('diachigiaohang.index') }}" class="btn btn-secondary">
                    ← Quay lại danh sách
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('diachigiaohang.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="id_nguoidung" class="form-label">Người dùng <span class="text-danger">*</span></label>
                        <select name="id_nguoidung" id="id_nguoidung" class="form-select @error('id_nguoidung') is-invalid @enderror" required>
                            <option value="">-- Chọn người dùng --</option>
                            @foreach($nguoidungs as $nguoidung)
                                <option value="{{ $nguoidung->id }}" {{ old('id_nguoidung') == $nguoidung->id ? 'selected' : '' }}>
                                    {{ $nguoidung->hoten }} ({{ $nguoidung->username }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_nguoidung')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="hoten" class="form-label">Họ tên người nhận <span class="text-danger">*</span></label>
                        <input type="text" name="hoten" id="hoten" class="form-control @error('hoten') is-invalid @enderror" value="{{ old('hoten') }}" required maxlength="255">
                        @error('hoten')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="sodienthoai" class="form-label">Số điện thoại</label>
                        <input type="text" name="sodienthoai" id="sodienthoai" class="form-control @error('sodienthoai') is-invalid @enderror" value="{{ old('sodienthoai') }}" maxlength="10">
                        @error('sodienthoai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="diachi" class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                        <textarea name="diachi" id="diachi" rows="3" class="form-control @error('diachi') is-invalid @enderror" required>{{ old('diachi') }}</textarea>
                        @error('diachi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tinhthanh" class="form-label">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                        <select name="tinhthanh" id="tinhthanh" class="form-select @error('tinhthanh') is-invalid @enderror" required>
                            <option value="">-- Chọn tỉnh/thành --</option>
                            @foreach($tinhthanhs as $tinh)
                                <option value="{{ $tinh }}" {{ old('tinhthanh') == $tinh ? 'selected' : '' }}>{{ $tinh }}</option>
                            @endforeach
                        </select>
                        @error('tinhthanh')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="trangthai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                        <select name="trangthai" id="trangthai" class="form-select @error('trangthai') is-invalid @enderror" required>
                            <option value="">-- Chọn trạng thái --</option>
                            @foreach(['Mặc định', 'Khác', 'Tạm ẩn'] as $tt)
                                <option value="{{ $tt }}" {{ old('trangthai') == $tt ? 'selected' : '' }}>{{ $tt }}</option>
                            @endforeach
                        </select>
                        @error('trangthai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

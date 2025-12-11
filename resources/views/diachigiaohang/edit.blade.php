@extends('layouts.app')

@section('title', 'Chỉnh sửa địa chỉ người dùng)
{{-- // các route sư dụng  diachigiaohang.update --- của breadcrumb diachigiaohang.index trang-chu  --}}

{{-- // controller truyền xuống $tinhthanhs $nguoidungs $diachi --}}
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <x-header.breadcrumb
                title="Chỉnh sửa địa chỉ người dùng"
                :links="[
                    ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                    ['label' => 'Danh sách địa chỉ người dùng', 'route' => 'diachigiaohang.index']
                ]"
                active="Chỉnh sửa"
            />
        </div>

        <div class="card">
            <div class="card-body">
                {{-- Hiển thị lỗi validation --}}
                @if ($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('diachigiaohang.update', $diachi->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        {{-- Người dùng --}}
                        <div class="col-lg-6 mb-3">
                            <label for="id_nguoidung" class="form-label">Người dùng</label>
                            <select name="id_nguoidung" id="id_nguoidung" class="form-select" required>
                                <option value="">-- Chọn người dùng --</option>
                                @foreach ($nguoidungs as $nguoidung)
                                    <option value="{{ $nguoidung->id }}"
                                        {{ old('id_nguoidung', $diachi->id_nguoidung) == $nguoidung->id ? 'selected' : '' }}>
                                        {{ $nguoidung->hoten }} ({{ $nguoidung->username }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Họ tên --}}
                        <div class="col-lg-6 mb-3">
                            <label for="hoten" class="form-label">Họ tên</label>
                            <input type="text" name="hoten" id="hoten" class="form-control"
                                value="{{ old('hoten', $diachi->hoten) }}" required maxlength="255" />
                        </div>

                        {{-- Số điện thoại --}}
                        <div class="col-lg-6 mb-3">
                            <label for="sodienthoai" class="form-label">Số điện thoại</label>
                            <input type="text" name="sodienthoai" id="sodienthoai" class="form-control"
                                value="{{ old('sodienthoai', $diachi->sodienthoai) }}" maxlength="10" />
                        </div>

                        {{-- Địa chỉ --}}
                        <div class="col-lg-6 mb-3">
                            <label for="diachi" class="form-label">Địa chỉ</label>
                            <textarea name="diachi" id="diachi" class="form-control" rows="3" required>{{ old('diachi', $diachi->diachi) }}</textarea>
                        </div>

                        {{-- Tỉnh/thành --}}
                        <div class="col-lg-6 mb-3">
                            <label for="tinhthanh" class="form-label">Tỉnh/Thành phố</label>
                            <select name="tinhthanh" id="tinhthanh" class="form-select" required>
                                <option value="">-- Chọn tỉnh/thành --</option>
                                @foreach ($tinhthanhs as $tinh)
                                    <option value="{{ $tinh }}" {{ old('tinhthanh', $diachi->tinhthanh) == $tinh ? 'selected' : '' }}>
                                        {{ $tinh }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Trạng thái --}}
                        <div class="col-lg-6 mb-3">
                            <label for="trangthai" class="form-label">Trạng thái</label>
                            <select name="trangthai" id="trangthai" class="form-select" required>
                                @php
                                    $options = ['Mặc định', 'Khác', 'Tạm ẩn'];
                                @endphp
                                @foreach ($options as $option)
                                    <option value="{{ $option }}" {{ old('trangthai', $diachi->trangthai) == $option ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhật địa chỉ</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

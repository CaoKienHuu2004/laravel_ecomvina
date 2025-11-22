@extends('layouts.app')

@section('title', 'Chỉnh sửa người dùng')
{{-- // các route sư dụng  nguoidung.update --- của breadcrumb nguoidung.index trang-chu --}}
{{-- $nguoidung->avatar: Link http://148.230.100.215/storage/assets/client/images/profiles/tenfilehinhanh.jpg --}}
{{-- // controller truyền xuống $tinhthanhs,$nguoidung  --}}

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <x-header.breadcrumb
                title="Chỉnh sửa thông tin người dùng: {{ $nguoidung->hoten }}"
                :links="[
                    ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                    ['label' => 'Danh sách người dùng', 'route' => 'nguoidung.index']
                ]"
                active="Chỉnh sửa"
            />
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

        <form action="{{ route('nguoidung.update', $nguoidung->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Thông tin người dùng</h5>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="username">Username <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    class="form-control @error('username') is-invalid @enderror"
                                    id="username"
                                    name="username"
                                    value="{{ old('username', isset($nguoidung) ? $nguoidung->username : '') }}"
                                    required
                                >
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    class="form-control @error('email') is-invalid @enderror"
                                    id="email"
                                    name="email"
                                    value="{{ old('email', isset($nguoidung) ? $nguoidung->email : '') }}"
                                    required
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="password">Mật khẩu <small>(để trống nếu không đổi)</small></label>
                                <input
                                    type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    id="password"
                                    name="password"
                                    autocomplete="new-password"
                                >
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="password_confirmation">Xác nhận mật khẩu</label>
                                <input
                                    type="password"
                                    class="form-control"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    autocomplete="new-password"
                                >
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="hoten">Họ tên <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    class="form-control @error('hoten') is-invalid @enderror"
                                    id="hoten"
                                    name="hoten"
                                    value="{{ old('hoten', $nguoidung->hoten) }}"
                                    required
                                >
                                @error('hoten')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="sodienthoai">Số điện thoại <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    class="form-control @error('sodienthoai') is-invalid @enderror"
                                    id="sodienthoai"
                                    name="sodienthoai"
                                    value="{{ old('sodienthoai', $nguoidung->sodienthoai) }}"
                                    maxlength="10"
                                >
                                @error('sodienthoai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <label>Giới tính</label> <span class="text-danger">*</span><br>
                            @php
                                $gioitinh = old('gioitinh', $nguoidung->gioitinh);
                            @endphp
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gioitinh" id="gioitinh_nam" value="Nam" {{ $gioitinh === 'Nam' ? 'checked' : '' }}>
                                <label class="form-check-label" for="gioitinh_nam">Nam</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gioitinh" id="gioitinh_nu" value="Nữ" {{ $gioitinh === 'Nữ' ? 'checked' : '' }}>
                                <label class="form-check-label" for="gioitinh_nu">Nữ</label>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="ngaysinh">Ngày sinh <span class="text-danger">*</span></label>
                                <input
                                    type="date"
                                    class="form-control"
                                    id="ngaysinh"
                                    name="ngaysinh"
                                    value="{{ old('ngaysinh', optional($nguoidung->ngaysinh)->format('Y-m-d')) }}"
                                >
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="vaitro">Vai trò <span class="text-danger">*</span></label>
                                <select class="form-control @error('vaitro') is-invalid @enderror" id="vaitro" name="vaitro" required>
                                    @php $vaitro = old('vaitro', $nguoidung->vaitro); @endphp
                                    <option value="">-- Chọn vai trò --</option>
                                    <option value="seller" {{ $vaitro === 'seller' ? 'selected' : '' }}>Seller</option>
                                    <option value="client" {{ $vaitro === 'client' ? 'selected' : '' }}>Client</option>
                                </select>
                                @error('vaitro')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="trangthai">Trạng thái <span class="text-danger">*</span></label>
                                <select class="form-control @error('trangthai') is-invalid @enderror" id="trangthai" name="trangthai" required>
                                    @php $trangthai = old('trangthai', $nguoidung->trangthai); @endphp
                                    <option value="">-- Chọn trạng thái --</option>
                                    <option value="Hoạt động" {{ $trangthai === 'Hoạt động' ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="Tạm khóa" {{ $trangthai === 'Tạm khóa' ? 'selected' : '' }}>Tạm khóa</option>
                                    <option value="Dừng hoạt động" {{ $trangthai === 'Dừng hoạt động' ? 'selected' : '' }}>Dừng hoạt động</option>
                                </select>
                                @error('trangthai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="avatar">Ảnh đại diện</label>
                                <input
                                    type="file"
                                    class="form-control @error('avatar') is-invalid @enderror"
                                    id="avatar"
                                    name="avatar"
                                    accept="image/*"
                                >
                                @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                @if ($nguoidung->avatar && $nguoidung->avatar != 'khachhang.jpg')
                                    <div class="mt-2">
                                        <img src="{{ $nguoidung->avatar }}" alt="Avatar" style="max-width: 150px; border-radius: 5px;">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Thông tin địa chỉ giao hàng --}}
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="mb-3">Thông tin địa chỉ giao hàng</h5>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="diachi_diachi">Địa chỉ</label>
                                {{-- <label for="diachi_diachi">Địa chỉ <span class="text-danger">*</span></label> --}}
                                <input
                                    type="text"
                                    class="form-control @error('diachi_diachi') is-invalid @enderror"
                                    id="diachi_diachi"
                                    name="diachi_diachi"
                                    value="{{ old('diachi_diachi', $nguoidung->diachi->where('trangthai', 'Mặc định')->first()->diachi ?? '') }}"
                                    {{-- required --}}
                                >
                                @error('diachi_diachi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="diachi_tinhthanh">Tỉnh/Thành phố</label>
                                {{-- <label for="diachi_tinhthanh">Tỉnh/Thành phố <span class="text-danger">*</span></label> --}}
                                <select
                                    class="form-control @error('diachi_tinhthanh') is-invalid @enderror"
                                    id="diachi_tinhthanh"
                                    name="diachi_tinhthanh"
                                    {{-- required --}}
                                >
                                    @php
                                        $tinhthanh = old('diachi_tinhthanh', $nguoidung->diachi->where('trangthai', 'Mặc định')->first()->tinhthanh ?? '');
                                    @endphp
                                    <option value="">-- Chọn tỉnh/thành --</option>
                                    @foreach($tinhthanhs as $tinh)
                                        <option value="{{ $tinh }}" {{ $tinhthanh === $tinh ? 'selected' : '' }}>
                                            {{ $tinh }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('diachi_tinhthanh')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="diachi_trangthai">Trạng thái địa chỉ</label>
                                {{-- <label for="diachi_trangthai">Trạng thái địa chỉ <span class="text-danger">*</span></label> --}}
                                <select
                                    class="form-control"
                                    id="diachi_trangthai"
                                    name="diachi_trangthai"
                                    {{-- required --}}
                                >
                                    @php
                                        $dcTrangthai = old('diachi_trangthai', $nguoidung->diachi->where('trangthai', 'Mặc định')->first()->trangthai ?? '');
                                    @endphp
                                    <option value="Mặc định" {{ $dcTrangthai === 'Mặc định' ? 'selected' : '' }}>Mặc định</option>
                                    <option value="Khác" {{ $dcTrangthai === 'Khác' ? 'selected' : '' }}>Khác</option>
                                    <option value="Tạm ẩn" {{ $dcTrangthai === 'Tạm ẩn' ? 'selected' : '' }}>Tạm ẩn</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<style>
    .form-check-input {
        cursor: pointer;
    }
    .invalid-feedback {
        display: block;
    }
</style>
@endsection

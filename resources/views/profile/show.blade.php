@extends('layouts.app')

@section('title', 'Danh sách danh mục | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Quản lý tài khoản</h4>
                <h6>Thông tin người dùng</h6>
            </div>
        </div>
        <div class="card">
            <div class="card-body">

                <div class="errorlog">
                    {{-- Hiển thị lỗi validation --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Hiển thị thông báo thành công --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Hiển thị thông báo lỗi chung nếu có --}}
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>

                <div class="profile-set">
                <div class="profile-head"></div>
                <div class="profile-top">

                    <form action="{{ route('cap-nhat-anh-dai-dien-tai-khoan')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <div class="profile-content">
                            <div class="profile-contentimg">
                                <img src="{{ asset('storage/'.'uploads/nguoidung/avatar/'.$user->avatar)}}" alt="img" id="blah" >
                                <div class="profileupload">
                                <input type="file" name="avatar" id="imgInp" required>
                                    <a href="javascript:void(0);"><img src="{{ asset('img/icons/edit-set.svg')}}" alt="img"></a>
                                </div>
                            </div>
                            <div class="profile-contentname">
                                <h2>{{ $user->hoten}}</h2>
                                <h4>Cập nhật ảnh và thông tin cá nhân của bạn.</h4>
                            </div>
                        </div>
                        <div class="ms-auto">
                            <button type="submit" class="btn btn-submit me-2">Lưu</button>
                            <a href="{{ route('trang-chu')}}" class="btn btn-cancel">Hủy</a>
                        </div>
                    </form>
                </div>
                </div>
                <div class="row">
                    <form action="{{ route('cap-nhat-thong-tin-tai-khoan')}}" method="POST" class="row">
                    @csrf
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Họ Tên *</label>
                                <input type="text" name="hoten" value="{{ $user->hoten}}" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Tên Đăng Nhập *</label>
                                <input type="text" name="username" value="{{ $user->username}}" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Số Điện Thoại *</label>
                                <input type="text" name="sodienthoai" maxlength="10" value="{{ $user->sodienthoai}}" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Password *</label>
                                <div class="pass-group">
                                    <input name="password" type="password" class=" pass-input"  placeholder="***********">
                                    <span class="fas toggle-password fa-eye-slash"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Giới Tính *</label>
                                <select name="gioitinh" class="form-select" id="gioitinh" required>
                                    <option value="Nam" {{ $user->gioitinh == 'Nam' ? 'selected' : '' }}>Nam</option>
                                    <option value="Nữ" {{ $user->gioitinh == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Ngày Sinh *</label>
                                <input type="date" name="ngaysinh" class="form-control" value="{{ $user->ngaysinh ? $user->ngaysinh->format('Y-m-d') : '' }}" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-submit me-2">Lưu</button>
                            <a href="{{ route('trang-chu')}}" class="btn btn-cancel">Hủy</a>
                        </div>
                    </form>
                </div>
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



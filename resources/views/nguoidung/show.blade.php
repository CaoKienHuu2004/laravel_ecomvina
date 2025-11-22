@extends('layouts.app')

@section('title', 'Chi tiết Người Dùng: ' . $nguoidung->hoten)
{{-- // controller truyền xuống $nguoidung  --}}
{{-- // các route sư dụng không sửa dụng  --- của breadcrumb nguoidung.index trang-chu --}}
{{-- $nguoidung->avatar: Link http://148.230.100.215/storage/assets/client/images/profiles/tenfilehinhanh.jpg --}}
@section('content')
<div class="page-wrapper">
    <div class="content">

        <div class="page-header">
            <x-header.breadcrumb
                title="Xem Chi Tiết Người Dùng: {{ $nguoidung->hoten }}"
                :links="[
                    ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                    ['label' => 'Danh sách người dùng', 'route' => 'nguoidung.index']
                ]"
                active="Chi tiết"
            />
        </div>

        <div class="card">
            <div class="card-body">
                <div class="profile-set">

                    <div class="profile-top d-flex align-items-center mb-4">
                        <div class="profile-content d-flex align-items-center">
                            <div class="profile-contentimg me-4">
                                <img src="{{ $nguoidung->avatar ? $nguoidung->avatar : asset('img/default-avatar.png') }}" alt="Avatar" width="150" height="150" style="object-fit: cover; border-radius: 50%;">
                            </div>
                            <div class="profile-contentname">
                                <h2>{{ $nguoidung->hoten }}</h2>
                                <h5 class="text-muted">Thông tin cá nhân và tài khoản</h5>
                            </div>
                        </div>
                    </div>

                    <form>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="hoten" class="form-label"><strong>Họ tên</strong></label>
                                <input type="text" id="hoten" class="form-control" value="{{ $nguoidung->hoten }}" disabled>
                            </div>

                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="username" class="form-label"><strong>Username</strong></label>
                                <input type="text" id="username" class="form-control" value="{{ $nguoidung->username }}" disabled>
                            </div>

                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="email" class="form-label"><strong>Email</strong></label>
                                <input type="email" id="email" class="form-control" value="{{ $nguoidung->email }}" disabled>
                            </div>

                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="sodienthoai" class="form-label"><strong>Số điện thoại</strong></label>
                                <input type="text" id="sodienthoai" class="form-control" value="{{ $nguoidung->sodienthoai ?? 'Chưa cập nhật' }}" disabled>
                            </div>

                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="gioitinh" class="form-label"><strong>Giới tính</strong></label>
                                <input type="text" id="gioitinh" class="form-control" value="{{ $nguoidung->gioitinh ?? 'Chưa cập nhật' }}" disabled>
                            </div>

                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="ngaysinh" class="form-label"><strong>Ngày sinh</strong></label>
                                <input type="text" id="ngaysinh" class="form-control" value="{{ $nguoidung->ngaysinh ? $nguoidung->ngaysinh->format('d/m/Y') : 'Chưa cập nhật' }}" disabled>
                            </div>

                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="vaitro" class="form-label"><strong>Vai trò</strong></label>
                                <input type="text" id="vaitro" class="form-control" value="{{ $nguoidung->vaitro }}" disabled>
                            </div>

                            <div class="col-lg-6 col-sm-12 mb-3">
                                <label for="trangthai" class="form-label"><strong>Trạng thái</strong></label>
                                <input type="text" id="trangthai" class="form-control"
                                    value="{{ $nguoidung->trangthai }}" disabled>
                            </div>
                        </div>
                    </form>

                </div>

                <hr>

                <h4>Địa chỉ giao hàng</h4>

                @if($nguoidung->diachi->isNotEmpty())
                    <table class="table table-bordered mt-3">
                        <thead class="table-light">
                            <tr>
                                <th>Họ tên</th>
                                <th>Số điện thoại</th>
                                <th>Địa chỉ</th>
                                <th>Tỉnh/Thành phố</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($nguoidung->diachi as $dc)
                                <tr>
                                    <td>{{ $dc->hoten }}</td>
                                    <td>{{ $dc->sodienthoai }}</td>
                                    <td>{{ $dc->diachi }}</td>
                                    <td>{{ $dc->tinhthanh }}</td>
                                    <td>
                                        @if($dc->trangthai == 'Mặc định')
                                            <span class="badge bg-primary">Mặc định</span>
                                        @elseif($dc->trangthai == 'Khác')
                                            <span class="badge bg-secondary">Khác</span>
                                        @else
                                            <span class="badge bg-info">{{ $dc->trangthai }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>Chưa có địa chỉ giao hàng.</p>
                @endif

            </div>
        </div>

    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Chi tiết khách hàng: ' . $nguoidung->hoten)
{{-- // các route sư dụng  nguoidung.index, nguoidung.edit --}}
{{-- $nguoidung->avatar: Link http://148.230.100.215/storage/assets/client/images/profiles/tenfilehinhanh.jpg --}}
@section('content')
<div class="page-wrapper">
    <div class="content">

        <div class="page-header">
            <div class="page-title">
                <h4>Chi tiết khách hàng</h4>
                <h6>Thông tin chi tiết về khách hàng</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('nguoidung.index') }}" class="btn btn-primary">
                    Quay lại danh sách
                </a>
                <a href="{{ route('nguoidung.edit', ['id' => $nguoidung->id]) }}" class="btn btn-warning">
                    <img src="{{ asset('img/icons/edit.svg') }}" alt="Edit" /> Chỉnh sửa
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">

                <div class="user-profile">
                    <div class="profile-img">
                        <img width="150px"  src="{{ $nguoidung->avatar ? $nguoidung->avatar : asset('img/default-avatar.png') }}" alt="Avatar" />
                    </div>

                    <div class="profile-info">
                        <h3>{{ $nguoidung->hoten }}</h3>
                        <p><strong>Username:</strong> {{ $nguoidung->username }}</p>
                        <p><strong>Số điện thoại:</strong> {{ $nguoidung->sodienthoai ?? 'Chưa cập nhật' }}</p>
                        <p><strong>Giới tính:</strong> {{ $nguoidung->gioitinh ?? 'Chưa cập nhật' }}</p>
                        <p><strong>Ngày sinh:</strong> {{ $nguoidung->ngaysinh ? $nguoidung->ngaysinh->format('d/m/Y') : 'Chưa cập nhật' }}</p>
                        <p><strong>Vai trò:</strong> {{ $nguoidung->vaitro }}</p>
                        <p><strong>Trạng thái:</strong>
                            @if($nguoidung->trangthai == 'Hoạt động')
                                <span class="badge bg-success">Hoạt động</span>
                            @elseif($nguoidung->trangthai == 'Tạm khóa')
                                <span class="badge bg-warning">Tạm khóa</span>
                            @else
                                <span class="badge bg-secondary">{{ $nguoidung->trangthai }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                <hr>

                <h4>Địa chỉ giao hàng</h4>

                @if($nguoidung->diachi->isNotEmpty())
                    <table class="table table-bordered">
                        <thead>
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

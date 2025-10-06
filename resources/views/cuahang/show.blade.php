
{{-- diaChi cuaHang --}}

{{-- ID cửa hàng
Tên cửa hàng (ten_cuahang)
Giấy phép kinh doanh
Logo, bìa nền (nếu có)
Mô tả (mota)
Địa chỉ (diachi)
Số điện thoại, email
Trạng thái hoạt động (trangthai)
Số lượt theo dõi (theodoi) --}}

{{-- Thông tin chủ cửa hàng (quan hệ nguoidung): --}}
{{-- Thông tin chủ cửa hàng (quan hệ nguoidung):
Họ tên (hoten)
Email, số điện thoại
Vai trò, trạng thái --}}

{{-- Danh sách sản phẩm của cửa hàng (quan hệ sanPhams): --}}
{{-- Danh sách sản phẩm của cửa hàng (quan hệ sanPhams):
Tên sản phẩm
Thương hiệu (thuongHieu->ten)
Giá bán (lấy từ pivot->gia_ban)
Số lượng tồn (lấy từ pivot->soluong)
Trạng thái sản phẩm --}}


@extends('layouts.app')

@section('title', 'Chi tiết cửa hàng | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>Chi tiết cửa hàng</h4>
        <h6>Thông tin chi tiết của cửa hàng và sản phẩm</h6>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12 col-sm-12">
        <div class="card">
          <div class="card-body">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Thông tin cửa hàng</h5>
                    </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>ID:</strong> {{ $cuaHang->id }}</li>
                            <li class="list-group-item"><strong>Tên cửa hàng:</strong> {{ $cuaHang->ten_cuahang }}</li>
                            <li class="list-group-item"><strong>Giấy phép:</strong> {{ $cuaHang->giayphep_kinhdoanh }}</li>
                            <li class="list-group-item"><strong>Địa chỉ:</strong> {{ $cuaHang->diachi ?? 'Chưa cập nhật' }}</li>
                            <li class="list-group-item"><strong>SĐT:</strong> {{ $cuaHang->sodienthoai ?? 'N/A' }}</li>
                            <li class="list-group-item"><strong>Email:</strong> {{ $cuaHang->email ?? 'N/A' }}</li>
                            <li class="list-group-item"><strong>Trạng thái:</strong>
                            <span class="badge bg-{{ $cuaHang->trangthai == 'hoạt động' ? 'success' : 'secondary' }}">
                                {{ $cuaHang->trangthai }}
                            </span>
                            </li>
                            <li class="list-group-item"><strong>Lượt theo dõi:</strong> {{ $cuaHang->theodoi }}</li>
                            <li class="list-group-item"><strong>Lượt bán:</strong> {{ $cuaHang->luotban }}</li>
                        </ul>
                    </div>

                <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Chủ cửa hàng</h5>
                </div>
                @if($cuaHang->nguoidung)
                    <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Họ tên:</strong> {{ $cuaHang->nguoidung->hoten }}</li>
                    <li class="list-group-item"><strong>Email:</strong> {{ $cuaHang->nguoidung->email }}</li>
                    <li class="list-group-item"><strong>SĐT:</strong> {{ $cuaHang->nguoidung->sodienthoai }}</li>
                    <li class="list-group-item"><strong>Vai trò:</strong>
                        <span class="badge bg-warning text-dark">{{ $cuaHang->nguoidung->vaitro }}</span>
                    </li>
                    <li class="list-group-item"><strong>Trạng thái:</strong>
                        <span class="badge bg-{{ $cuaHang->nguoidung->trangthai == 'hoạt động' ? 'success' : 'danger' }}">
                        {{ $cuaHang->nguoidung->trangthai }}
                        </span>
                    </li>
                    </ul>
                @endif
                </div>

            <h5>Sản phẩm của cửa hàng</h5>
            <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered align-middle">
                <thead class="table">
                <tr>
                    <th>ID</th>
                    <th>Tên sản phẩm</th>
                    <th>Thương hiệu</th>
                    <th>Giá bán</th>
                    <th>Giá giảm</th>
                    <th>Số lượng</th>
                    <th>Trạng thái</th>
                </tr>
                </thead>
                <tbody>
                @foreach($cuaHang->sanPhams as $sp)
                    @foreach($sp->bienThe as $bt)
                    <tr>
                        <td>{{ $sp->id }}</td>
                        <td>
                        <strong>{{ $sp->ten }}</strong><br>
                        <small class="text-muted">Mã: SP{{ str_pad($sp->id, 4, '0', STR_PAD_LEFT) }}</small>
                        </td>
                        <td>{{ $sp->thuonghieu->ten ?? 'N/A' }}</td>
                        <td class="text-success fw-bold">{{ number_format($bt->gia, 0, ',', '.') }} VND</td>
                        <td class="text-danger">{{ number_format($bt->giagiam, 0, ',', '.') }} VND</td>
                        <td>
                        @if($bt->soluong > 10)
                            <span class="badge bg-success">{{ $bt->soluong }}</span>
                        @elseif($bt->soluong > 0)
                            <span class="badge bg-warning text-dark">{{ $bt->soluong }}</span>
                        @else
                            <span class="badge bg-danger">Hết hàng</span>
                        @endif
                        </td>
                        <td>
                        @if($sp->trangthai === 'hoat_dong')
                            <span class="badge bg-primary">Đang bán</span>
                        @else
                            <span class="badge bg-secondary">Ngừng bán</span>
                        @endif
                        </td>
                    </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
            </div>


          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/js/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/plugins/owlcarousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
@endpush

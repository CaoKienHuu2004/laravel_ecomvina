@extends('layouts.app')

@section('title', 'Chỉnh sửa cửa hàng | Quản trị hệ thống Siêu Thị Vina')

@section('content')
@php $cuaHang = $nguoiDung->thongTinNguoiBanHang; @endphp
<div class="page-wrapper">
    {{-- //-------------------------------------- info hero -------------------------// --}}
    <div class="content">
            <div class="page-header position-relative">
                @if($cuaHang && $cuaHang->bianen)
                    <img src="{{ asset('storage/' . $cuaHang->bianen) }}"
                        alt="Bìa nền"
                        width="100%"
                        height="300"
                        class="mt-2 rounded">
                @else
                    <img src="{{ asset('storage/uploads/cuahang/bianen/bianen.png') }}"
                        alt="Bìa nền"
                        width="100%"
                        height="300"
                        class="mt-2 rounded">
                @endif

                <!-- Logo chồng lên banner -->
                <div id="img-logo">
                    <style>
                        .page-header {
                            position: relative; /* cần để logo chồng lên */
                        }
                        #img-logo {
                            position: absolute;
                            bottom: -40px; /* một nửa chiều cao logo để nổi lên banner */
                            left: 20px; /* cách trái 20px */
                            width: 80px;
                            height: 80px;
                            border-radius: 50%;
                            overflow: hidden;
                            border: 3px solid #fff; /* viền trắng giống Facebook */
                            background-color: #fff;
                        }
                        #img-logo img {
                            width: 100%;
                            height: 100%;
                            object-fit: cover;
                        }
                    </style>
                    @if($cuaHang && $cuaHang->logo)
                        <img src="{{ asset('storage/' . $cuaHang->logo) }}" alt="logo" width="100" height="100">
                    @else
                        <img src="{{ asset('storage/uploads/cuahang/logo/logo.png') }}" alt="logo" width="100" height="100">
                    @endif
                </div>
            </div>
            <div class="row text-end">
                <div class="col"><strong>Tên cửa hàng:</strong> {{ $cuaHang->ten_cuahang ?? 'Chưa cập nhật' }}</div>
                <div class="col"><strong>Lượt theo dõi:</strong> {{ $cuaHang->theodoi ?? 0 }}</div>
                <div class="col"><strong>Lượt bán:</strong> {{ $cuaHang->luotban ?? 0 }}</div>
            </div>


    </div>
    {{-- //-------------------------------------- info hero -------------------------// --}}

    {{-- //---------------------------------- errorlog----------------------------------// --}}
    <div class="error-log">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif
    </div>
    {{-- ///---------------------------------------------- nguoi dung -----------------// --}}
    @if ($nguoiDung)
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                <h4>Chỉnh sửa thông tin tài khoản người bán hàng</h4>
                <h6>ID người bán hàng: {{ $nguoiDung->id }}</h6>
                </div>
                <div class="page-btn">
                    <button type="button" id="toggle-card-btn" class="btn btn-added">
                        <img src="{{ asset('img/icons/arrow-left.svg') }}" class="me-1" alt="">
                        Ẩn/Hiện Thông tin tài khoản
                    </button>
                </div>
            </div>




                <div id="card-thong-tin-tai-khoan" class="card">
                <div class="card-body">
                <form method="POST" id="formCapNhatNguoiDung" action="{{ route('cap-nhat-cua-hang-tai-khoan', ['id' => $nguoiDung->id]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                        <label>Tên khách hàng</label>
                        <input type="text" name="hoten" class="form-control"
                                value="{{ old('hoten', $nguoiDung->hoten ?? '') }}">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control"
                                value="{{ old('email', $nguoiDung->email ?? $nguoiDung->email ?? '') }}">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="tel" name="sodienthoai" class="form-control"
                                value="{{ old('sodienthoai', $nguoiDung->sodienthoai ?? '') }}">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                        <label>Trạng thái</label>
                        <select name="trangthai" class="form-select">
                            <option value="hoat_dong" {{ $nguoiDung->trangthai == 'hoat_dong' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="ngung_hoa_dong" {{ $nguoiDung->trangthai == 'ngung_hoa_dong' ? 'selected' : '' }}>Ngưng hoạt động</option>
                            <option value="bi_khoa" {{ $nguoiDung->trangthai == 'bi_khoa' ? 'selected' : '' }}>Bị khóa</option>
                            <option value="cho_duyet" {{ $nguoiDung->trangthai == 'cho_duyet' ? 'selected' : '' }}>Chờ duyệt</option>
                        </select>
                        </div>
                    </div>

                        <div class="col-lg-6 col-6">
                            <div class="form-group">
                                <label>Địa chỉ</label>

                                <div id="diachi-container">
                                    @php
                                        // Gộp logic: nếu $diachis có thì dùng, còn không thì lấy từ $nguoiDung->diachi
                                        $dsDiaChi = !empty($diachis) ? $diachis : ($nguoiDung->diachi ?? collect());
                                    @endphp

                                    @if($dsDiaChi->isNotEmpty())
                                        @foreach($dsDiaChi as $index => $dc)
                                            <div class="mb-2 diachi-item d-flex gap-2 align-items-center">
                                                <input type="hidden" name="diachi_nguoidung[{{ $index }}][id]" value="{{ $dc->id }}">
                                                <input type="text"
                                                    name="diachi_nguoidung[{{ $index }}][diachi]"
                                                    class="form-control"
                                                    value="{{ $dc->diachi }}"
                                                    placeholder="Nhập địa chỉ mới">
                                                <button type="button" class="btn btn-danger btn-sm xoa-diachi">X</button>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="mb-2 diachi-item d-flex gap-2 align-items-center">
                                            <input type="hidden" name="diachi_nguoidung[0][id]" value="">
                                            <input type="text"
                                                name="diachi_nguoidung[0][diachi]"
                                                class="form-control"
                                                placeholder="Nhập địa chỉ mới">
                                            <button type="button" class="btn btn-danger btn-sm xoa-diachi">X</button>
                                        </div>
                                    @endif
                                </div>

                                <button type="button" id="them-diachi" class="btn btn-sm btn-primary mt-2">+ Thêm địa chỉ</button>
                            </div>
                        </div>

                    <div class="col-lg-6 col-6">
                        <div class="form-group">
                            <label>Avatar</label>
                            <input type="file" name="avatar" class="form-control">
                            @if($nguoiDung->avatar)
                            <img src="{{ asset('storage/' . $nguoiDung->avatar) }}" alt="avatar" width="150" class="mt-2 rounded">
                            @endif
                        </div>
                        </div>


                    <div class="text-end mt-3">
                    <button type="submit" class="btn btn-submit me-2">Cập nhật</button>
                    <a href="{{ route('danh-sach-cua-hang') }}" class="btn btn-cancel">Hủy</a>
                    </div>
                </form>
                </div>
            </div>
            </div>



        </div>
    @else
        <div class="content">
        <div class="page-header">
            <div class="page-title">
            <h4>Chỉnh sửa thông tin tài khoản người bán hàng</h4>
            <h6>ID người bán hàng: chưa có</h6>
            </div>
            <div class="page-btn">
            <a href="{{ route('danh-sach-cua-hang') }}" class="btn btn-added">
                <img src="{{ asset('img/icons/arrow-left.svg') }}" class="me-1" alt="">Tạo Mới Tài Khoản seller
            </a>
            </div>
        </div>
        </div>
    @endif

{{-- ///---------------------------------------------- cua hang -----------------// --}}
  @if ($cuaHang)
    <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>Chỉnh sửa cửa hàng</h4>
        <h6>ID Cửa hàng: {{ $cuaHang->id }}</h6>
      </div>
        <div class="page-btn">
            <button type="button" id="toggle-card-cua-hang-btn" class="btn btn-added">
                <img src="{{ asset('img/icons/arrow-left.svg') }}" class="me-1" alt="">
                Ẩn/Hiện thông tin cửa hàng
            </button>
        </div>
    </div>



    <div id="card-thong-tin-cua-hang" class="card">
      <div class="card-body">
        <form method="POST" id="formCapNhatCuaHang" action="{{ route('cap-nhat-cua-hang', ['id' => $nguoiDung->id]) }}" enctype="multipart/form-data">
          @csrf @method('PUT')
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label>Tên cửa hàng</label>
                <input type="text" name="ten_cuahang" class="form-control" value="{{ old('ten_cuahang', $cuaHang->ten_cuahang) }}">
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
                <label>Giấy phép kinh doanh</label>
                <input type="text" name="giayphep_kinhdoanh" class="form-control" value="{{ old('giayphep_kinhdoanh', $cuaHang->giayphep_kinhdoanh) }}">
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text" name="sodienthoai" class="form-control" value="{{ old('sodienthoai', $cuaHang->sodienthoai) }}">
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $cuaHang->email) }}">
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group">
                <label>Địa chỉ</label>
                <input type="text" name="diachi" class="form-control" value="{{ old('diachi', $cuaHang->diachi) }}">
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group">
                <label>Mô tả</label>
                <textarea name="mota" class="form-control" rows="3">{{ old('mota', $cuaHang->mota) }}</textarea>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
                <label>Logo</label>
                <input type="file" name="logo" class="form-control">
                @if($cuaHang->logo)
                  <img src="{{ asset('storage/' . $cuaHang->logo) }}" alt="Logo" width="100" class="mt-2 rounded">
                @endif
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
                <label>Ảnh bìa nền</label>
                <input type="file" name="bianen" class="form-control">
                @if($cuaHang->bianen)
                  <img src="{{ asset('storage/' . $cuaHang->bianen) }}" alt="Bìa nền" width="150" class="mt-2 rounded">
                @endif
              </div>
            </div>

            <div class="col-lg-4">
              <div class="form-group">
                <label>Trạng thái</label>
                <select name="trangthai" class="form-select">
                  <option value="hoat_dong" {{ $cuaHang->trangthai == 'hoat_dong' ? 'selected' : '' }}>Hoạt động</option>
                  <option value="ngung_hoa_dong" {{ $cuaHang->trangthai == 'ngung_hoa_dong' ? 'selected' : '' }}>Ngưng hoạt động</option>
                  <option value="bi_khoa" {{ $cuaHang->trangthai == 'bi_khoa' ? 'selected' : '' }}>Bị khóa</option>
                  <option value="cho_duyet" {{ $cuaHang->trangthai == 'cho_duyet' ? 'selected' : '' }}>Chờ duyệt</option>
                </select>
              </div>
            </div>
          </div>

          <div class="text-end mt-3">
            <button type="submit" class="btn btn-submit me-2">Cập nhật</button>
            <a href="{{ route('danh-sach-cua-hang') }}" class="btn btn-cancel">Hủy</a>
          </div>
        </form>
      </div>
    </div>
    </div>
  @else
    <div class="content">
        <div class="page-header" id="tao-moi-cua-hang">
            <div class="page-title">
                <h4>Chỉnh sửa cửa hàng</h4>
                <h6>ID Cửa Hàng: chưa có</h6>
            </div>
            <div class="page-btn">
                <a href="javascript:void(0);"
                class="btn btn-added"
                data-bs-toggle="modal"
                data-bs-target="#modalTaoCuaHang">
                    <img src="{{ asset('img/icons/arrow-left.svg') }}" class="me-1" alt="">
                    Tạo Mới Cửa Hàng
                </a>
            </div>
            <!-- Modal Tạo Cửa Hàng -->
            <div class="modal fade" id="modalTaoCuaHang" tabindex="-1" aria-labelledby="modalLabelTaoCuaHang" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header text-white" style="background-color: ff9f43">
                            <h5 class="modal-title" id="modalLabelTaoCuaHang">Tạo Mới Cửa Hàng</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                        </div>

                        <form action="{{ route('cap-nhat-cua-hang-duyet', ['id' => $nguoiDung->id ]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Tên cửa hàng</label>
                                        <input type="text" name="ten_cuahang" class="form-control" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Giấy phép kinh doanh</label>
                                        <input type="text" name="giayphep_kinhdoanh" class="form-control">
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label>Mô tả</label>
                                        <textarea name="mota" class="form-control" rows="3"></textarea>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Địa chỉ cửa hàng</label>
                                        <input type="text" name="diachi" class="form-control">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Số điện thoại</label>
                                        <input type="text" name="sodienthoai" class="form-control" value="{{ $nguoiDung->sodienthoai }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ $nguoiDung->email }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Trạng thái</label>
                                        <div class="badge bg-success">Hoạt Động</div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Logo cửa hàng</label>
                                        <input type="file" name="logo" class="form-control">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Ảnh bìa nền</label>
                                        <input type="file" name="bianen" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
  @endif


  {{-- ///---------------------------------------------- san pham, bien the san pham, loai bien the, cua hang -----------------// --}}
  @if (!empty($cuaHang->sanpham))
    <div class="content">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div class="page-title">
            <h4>Danh sách sản phẩm của cửa hàng</h4>
            <h6>Tổng số sản phẩm: {{ $cuaHang->sanpham->count() }}</h6>
            @php
            $totalBienThe = $cuaHang->sanpham->sum(function($sp) {
                return $sp->bienThe->count();
            });
            @endphp
            <h6>Tổng số biến thể sản phẩm: {{ $totalBienThe }}</h6>
            @php
            $totalLoaiBienThe = $cuaHang->sanpham->flatMap(function($sp) {
                return $sp->bienThe->pluck('id_tenloai'); // lấy id loại biến thể
            })->unique()->count(); // loại bỏ trùng lặp và đếm
            @endphp

            <h6>Tổng số loại biến thể sản phẩm: {{ $totalLoaiBienThe }}</h6>
            </div>
            <div class="page-btn">
                <button type="button" id="toggle-card-san-pham-btn" class="btn btn-added">
                    <img src="{{ asset('img/icons/arrow-left.svg') }}" class="me-1" alt="">
                    Ẩn/Hiện bảng sản phẩm
                </button>
            </div>
        </div>

        <div id="card-thong-tin-san-pham-cua-hang" class="card mt-3">

            <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                    <th>Tên sản phẩm</th>
                    <th>Loại Biến Thể</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                    <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <div class="page-btn">
                                <button type="button" class="btn btn-primary btn-added" data-bs-toggle="modal" data-bs-target="#themMoiSanPhamModal">
                                    Thêm Mới Sản Phẩm
                                </button>
                            </div>
                        </tr>
                        {{-- //----------------------- modal thêm sản phẩm mới -----------------// --}}
                        <div class="modal fade" id="themMoiSanPhamModal" tabindex="-1" aria-labelledby="themMoiSanPhamModal" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="themSanPhamModalLabel">Thêm sản phẩm mới</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="chi-tiet-san-pham mt-3">
                                        <form method="POST" action="#" enctype="multipart/form-data">
                                            @csrf

                                            <h5>Thông tin sản phẩm</h5>
                                            <input type="hidden" name="id_cuahang" value="{{ $cuaHang->id }}">

                                            <div class="mb-3">
                                                <label>Tên sản phẩm</label>
                                                <input type="text" name="ten" class="form-control" placeholder="Nhập tên sản phẩm">
                                            </div>

                                            <div class="mb-3">
                                                <label>Mô tả</label>
                                                <textarea name="mota" class="form-control" placeholder="Mô tả sản phẩm"></textarea>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Xuất xứ</label>
                                                    <input type="text" name="xuatxu" class="form-control" placeholder="Xuất xứ">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Sản xuất</label>
                                                    <input type="text" name="sanxuat" class="form-control" placeholder="Nhà sản xuất">
                                                </div>
                                            </div>

                                            <div class="mb-3 mt-3">
                                                <label>Ảnh sản phẩm</label>
                                                <input type="file" name="mediaurl" class="form-control">
                                            </div>

                                            <h5 class="mt-4">Loại biến thể sản phẩm</h5>
                                            <div class="row">
                                                @foreach($loaiBienTheSelection as $loai)
                                                    <div class="col-auto">
                                                        <div class="form-check">
                                                            <input type="checkbox"
                                                                class="form-check-input"
                                                                name="loaibienthe_id[]"
                                                                value="{{ $loai->id }}"
                                                                id="themLoaibienthe{{ $loai->id }}">
                                                            <label class="form-check-label" for="themLoaibienthe{{ $loai->id }}">
                                                                {{ $loai->ten }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <h5 class="mt-4">Biến thể sản phẩm (tùy chọn)</h5>
                                            <div id="themBienTheContainer">
                                                <div class="border p-3 rounded mb-2 bienTheTemplate">
                                                    <div class="row g-2">
                                                        <div class="col-md-3">
                                                            <label>Giá</label>
                                                            <input type="number" min="0" step="1000" name="bienthe[gia][]" class="form-control">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Giá giảm</label>
                                                            <input type="number" min="0" step="1000" name="bienthe[giagiam][]" class="form-control">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Số lượng</label>
                                                            <input type="number" min="0" step="1" name="bienthe[soluong][]" class="form-control">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Ưu tiên</label>
                                                            <input type="number" min="1" step="1" name="bienthe[uutien][]" class="form-control">
                                                        </div>
                                                        <div class="col-md-2 d-flex justify-content-end">
                                                            <button type="button" class="btn btn-primary btn-sm mt-2" id="themBienTheBtn">+Thêm</button>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-danger btn-sm mt-2 removeBienThe">Xóa biến thể</button>
                                                </div>
                                            </div>


                                            <div class="row text-start mt-3">
                                                <div class="col-3">
                                                    <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
                                                </div>
                                                <div class="col-3">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        {{-- //----------------------- modal thêm sản phẩm mới -----------------// --}}
                    {{-- //----------------------- list input sản phẩm -----------------// --}}
                    @foreach ($cuaHang->sanpham as $index => $sp)
                        @foreach ($sp->bienThe as  $index_bt => $bt)


                        <tr data-id="{{ $sp->id }}">
                            <td><input type="text" class="form-control form-control-sm ten-sanpham" value="{{ $sp->ten}}"></td>
                            <td>
                                <select class="form-select form-select-sm ten-loaibienthe" name="bienthe[id_tenloai][]">
                                    @foreach($loaiBienTheSelection as $loai)
                                        <option value="{{ $loai->id }}"
                                            {{ $bt->loaiBienThe && $bt->loaiBienThe->id == $loai->id ? 'selected' : '' }}>
                                            {{ $loai->ten }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" min="0" step="1000" class="form-control form-control-sm gia" value="{{ $bt->gia }}"></td>
                            <td><input type="number" min="0" class="form-control form-control-sm soluong" value="{{ $bt->soluong }}"></td>
                            <td>
                            <select class="form-select form-select-sm trangthai">
                                <option value="hoat_dong" {{ $sp->trangthai == 'hoat_dong' ? 'selected' : '' }}>Hoạt Động</option>
                                <option value="bi_khoa" {{ $sp->trangthai == 'bi_khoa' ? 'selected' : '' }}>Bị Khóa</option>
                                <option value="ngung_hoat_dong" {{ $sp->trangthai == 'ngung_hoat_dong' ? 'selected' : '' }}>Ngừng Hoạt Động</option>
                                <option value="cho_duyet" {{ $sp->trangthai == 'cho_duyet' ? 'selected' : '' }}>Chờ Duyệt</option>
                            </select>
                            </td>
                            <td>
                            <button type="button" class="btn btn-sm btn-success btn-cap-nhat-san-pham">
                                <i class="fas fa-save me-1"></i> Lưu
                            </button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#chiTietSanPhamModal{{ $sp->id}}">
                                    <i class="fas fa-save me-1"></i> Chi tiết
                                </button>
                            </td>
                            {{-- //----------------------- chi tiet sản phẩm modal -----------------// --}}
                            <div class="modal fade" id="chiTietSanPhamModal{{ $sp->id}}" tabindex="-1" aria-labelledby="chiTietSanPhamModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="chiTietSanPhamModalLabel">Chi tiết sản phẩm</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="chi-tiet-san-pham mt-5">
                                            {{-- //----------- list chi tiet san pham -------------// --}}
                                            <form method="POST" action="#" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <h5>Thông tin ảnh sản phẩm carousel</h5>
                                                <div class="row">
                                                    <div id="carouselSanPham" class="carousel slide" data-bs-ride="carousel">
                                                        <div class="carousel-inner w-100">

                                                            @foreach($cuaHang->sanpham as $sp)
                                                                @foreach($sp->anhSanPham as $key => $anh)
                                                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                                                        <img src="{{ asset('storage/' . $anh->media) }}" class="d-block w-100" alt="Ảnh sản phẩm">

                                                                        {{-- Input để chỉnh sửa ảnh --}}
                                                                        <div class="mt-2 p-2 bg-light rounded">
                                                                            <label>Trạng thái</label>
                                                                            <select name="trangthai" class="form-select">
                                                                                <option value="hoat_dong" {{ $sp->trangthai == 'hoat_dong' ? 'selected' : '' }}>Hoạt động</option>
                                                                                <option value="ngung_hoat_dong" {{ $sp->trangthai == 'ngung_hoat_dong' ? 'selected' : '' }}>Ngừng hoạt động</option>
                                                                                <option value="bi_khoa" {{ $sp->trangthai == 'bi_khoa' ? 'selected' : '' }}>Bị khóa</option>
                                                                                <option value="cho_duyet" {{ $sp->trangthai == 'cho_duyet' ? 'selected' : '' }}>Chờ Duyệt</option>
                                                                            </select>
                                                                            <label for="fileAnh{{ $anh->id }}">Chọn ảnh mới</label>
                                                                            <input type="file" class="form-control form-control-sm" id="fileAnh{{ $anh->id }}" name="anh[{{ $anh->id }}][file]">

                                                                            <button type="button" class="btn btn-danger btn-sm mt-2 xoa-anh" data-id="{{ $anh->id }}">Xóa</button>
                                                                            <button type="button" class="btn btn-primary btn-sm mt-2 them-anh-sanpham-model-sua" >Thêm</button>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @endforeach

                                                        </div>
                                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselSanPham" data-bs-slide="prev">
                                                            <span class="carousel-control-prev-icon bg-black" aria-hidden="true"></span>
                                                            <span class="visually-hidden">Previous</span>
                                                        </button>
                                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselSanPham" data-bs-slide="next">
                                                            <span class="carousel-control-next-icon bg-black" aria-hidden="true"></span>
                                                            <span class="visually-hidden">Next</span>
                                                        </button>
                                                    </div>
                                                </div>


                                                <h5>Thông tin sản phẩm</h5>
                                                <strong>ID :</strong>{{$sp->id}}
                                                <input type="hidden" name="id" value="{{ $sp->id }}">
                                                <input type="hidden" name="id_cuahang" value="{{ $sp->id_cuahang }}">

                                                <div class="mb-3">
                                                    <label>Tên sản phẩm</label>
                                                    <input type="text" name="ten" value="{{ $sp->ten }}" class="form-control">
                                                </div>

                                                <div class="mb-3">
                                                    <label>Mô tả</label>
                                                    <textarea name="mota" class="form-control">{{ $sp->mota }}</textarea>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                    <label>Xuất xứ</label>
                                                    <input type="text" name="xuatxu" value="{{ $sp->xuatxu }}" class="form-control">
                                                    </div>
                                                    <div class="col-md-6">
                                                    <label>Sản xuất</label>
                                                    <input type="text" name="sanxuat" value="{{ $sp->sanxuat }}" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="mb-3 mt-3">
                                                    <label>Ảnh sản phẩm</label>
                                                    <input type="file" name="mediaurl" class="form-control">
                                                    <img src="{{ asset('storage/'.$sp->mediaurl) }}" width="100" class="mt-2 rounded">
                                                </div>

                                                <div class="mb-3">
                                                    <label>Trạng thái</label>
                                                    <select name="trangthai" class="form-select">
                                                    <option value="hoat_dong" {{ $sp->trangthai == 'hoat_dong' ? 'selected' : '' }}>Hoạt động</option>
                                                    <option value="ngung_hoat_dong" {{ $sp->trangthai == 'ngung_hoat_dong' ? 'selected' : '' }}>Ngừng hoạt động</option>
                                                    <option value="bi_khoa" {{ $sp->trangthai == 'bi_khoa' ? 'selected' : '' }}>Bị khóa</option>
                                                    <option value="cho_duyet" {{ $sp->trangthai == 'cho_duyet' ? 'selected' : '' }}>Chờ Duyệt</option>
                                                    </select>
                                                </div>

                                                <h5 class="mt-4">Biến thể sản phẩm</h5>
                                                <div class="w-100 text-end"><button type="button" id="them-bienthesp" class="btn btn-sm btn-primary mt-2">+ Thêm biến thể</button></div>
                                                @foreach($sp->bienThe as $bt)
                                                    <div class="border p-3 rounded mb-2">
                                                    <input type="hidden" name="bienthe[id][]" value="{{ $bt->id }}">
                                                    <input type="hidden" name="bienthe[id_sanpham][]" value="{{ $sp->id }}">

                                                    <div class="row">
                                                        <div class="col-md-3">
                                                        <label>Giá</label>
                                                        <input type="number" min="0" step="1000" name="bienthe[gia][]" value="{{ $bt->gia }}" class="form-control">
                                                        </div>
                                                        <div class="col-md-2">
                                                        <label>Giá giảm</label>
                                                        <input type="number" min="0" step="1000" name="bienthe[giagiam][]" value="{{ $bt->giagiam }}" class="form-control">
                                                        </div>
                                                        <div class="col-md-2">
                                                        <label>Số lượng</label>
                                                        <input type="number" min="0" step="1"  name="bienthe[soluong][]" value="{{ $bt->soluong }}" class="form-control">
                                                        </div>
                                                        <div class="col-md-2">
                                                        <label>Ưu tiên</label>
                                                        <input type="number" min="1" step="1" name="bienthe[uutien][]" value="{{ $bt->uutien }}" class="form-control">
                                                        </div>
                                                        <div class="col-md-2">
                                                        <label>Trạng thái</label>
                                                        <select name="bienthe[trangthai][]" class="form-select">
                                                            <option value="hoat_dong" {{ $bt->trangthai == 'hoat_dong' ? 'selected' : '' }}>Hoạt động</option>
                                                            <option value="ngung_hoat_dong" {{ $bt->trangthai == 'ngung_hoat_dong' ? 'selected' : '' }}>Ngừng</option>
                                                            <option value="bi_khoa" {{ $bt->trangthai == 'bi_khoa' ? 'selected' : '' }}>Khóa</option>
                                                            <option value="cho_duyet" {{ $sp->trangthai == 'cho_duyet' ? 'selected' : '' }}>Chờ Duyệt</option>
                                                        </select>

                                                        </div>
                                                        <div class="col-md-1">

                                                            <button type="button" class="btn btn-danger btn-sm xoa-bienthesp">X</button>
                                                        </div>
                                                    </div>

                                                    <div class="mt-2">
                                                        <label>Loại biến thể</label>
                                                        @php
                                                            $loai = $bt->loaibienthe; // chỉ 1 loại
                                                        @endphp

                                                        <option value="{{ $loai->id ?? '' }}" selected>
                                                            {{ $loai->ten ?? 'Không có loại' }}
                                                        </option>
                                                        </select>
                                                    </div>

                                                    </div>
                                                @endforeach
                                                <h5>Thông tin loại biến thể</h5>
                                                <div class="row">
                                                    @foreach($loaiBienTheSelection as $loai)
                                                        <div class="col-auto">
                                                            <div class="form-check">
                                                                <input type="checkbox"
                                                                    class="form-check-input"
                                                                    name="loaibienthe_id[]"       {{-- checkbox cho sản phẩm --}}
                                                                    value="{{ $loai->id }}"
                                                                    id="sp{{ $sp->id }}loai{{ $loai->id }}"
                                                                    {{ $sp->loaibienthe->contains($loai->id) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="sp{{ $sp->id }}loai{{ $loai->id }}">
                                                                    {{ $loai->ten }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>



                                                <div class="row text-start">
                                                    <div class="col-3">
                                                        <button type="submit" class="btn btn-primary mt-3">Cập nhật sản phẩm</button>
                                                    </div>
                                                    <div class="col-3">
                                                        <button type="button" class="btn btn-secondary mt-3" data-bs-dismiss="modal">Hủy</button>
                                                    </div>

                                                </div>
                                            </form>
                                            {{-- //----------- list chi tiet list san pham -------------// --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- //----------------------- chi tiet sản phẩm modal -----------------// --}}
                        </tr>




                        @endforeach
                    @endforeach
                    {{-- //----------------------- list input sản phẩm -----------------// --}}
                </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
    @else
    <div class="content" >
        <div id="tao-moi-san-pham-cua-hang" class="page-header d-flex justify-content-between align-items-center">
            <div class="page-title">
            <h4>Chỉnh sửa sản phẩm của cửa hàng</h4>
            <h6>Số lượng sản phẩm: 0</h6>
            </div>
            <div class="page-btn">
            <a href="{{ route('tao-san-pham') }}" class="btn btn-added">
                <img src="{{ asset('img/icons/plus.svg') }}" class="me-1" alt="">Thêm mới sản phẩm
            </a>
            </div>
        </div>
    </div>
    @endif
@endsection


<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('diachi-container');
    const btnThem = document.getElementById('them-diachi');

    // 🧱 Thêm địa chỉ mới
    btnThem.addEventListener('click', () => {
        const count = container.querySelectorAll('.diachi-item').length;
        const div = document.createElement('div');
        div.classList.add('mb-2', 'diachi-item', 'd-flex', 'gap-2', 'align-items-center');

        div.innerHTML = `
            <input type="hidden" name="diachi_nguoidung[${count}][id]" value="">
            <input type="text" name="diachi_nguoidung[${count}][diachi]" class="form-control" placeholder="Nhập địa chỉ mới">
            <button type="button" class="btn btn-danger btn-sm xoa-diachi">X</button>
        `;
        container.appendChild(div);
    });

    // 🗑️ Xóa địa chỉ
    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('xoa-diachi')) {
            e.target.closest('.diachi-item').remove();
        }
    });
});
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('toggle-card-btn');
    const card = document.getElementById('card-thong-tin-tai-khoan');

    toggleBtn.addEventListener('click', function () {
      // Ẩn/hiện bằng cách thêm hoặc xóa class 'd-none' (Bootstrap)
      card.classList.toggle('d-none');

      // Đổi text của nút cho thân thiện hơn
      if (card.classList.contains('d-none')) {
        toggleBtn.innerHTML = '<img src="{{ asset('img/icons/arrow-right.svg') }}" class="me-1" alt=""> Hiện thông tin tài khoản';
      } else {
        toggleBtn.innerHTML = '<img src="{{ asset('img/icons/arrow-left.svg') }}" class="me-1" alt=""> Ẩn thông tin tài khoản';
      }
    });
  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('toggle-card-cua-hang-btn');
    const card = document.getElementById('card-thong-tin-cua-hang');

    toggleBtn.addEventListener('click', function () {
      card.classList.toggle('d-none');

      if (card.classList.contains('d-none')) {
        toggleBtn.innerHTML = `
          <img src="{{ asset('img/icons/arrow-right.svg') }}" class="me-1" alt="">
          Hiện thông tin cửa hàng
        `;
      } else {
        toggleBtn.innerHTML = `
          <img src="{{ asset('img/icons/arrow-left.svg') }}" class="me-1" alt="">
          Ẩn thông tin cửa hàng
        `;
      }
    });
  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const toggleSanPhamBtn = document.getElementById('toggle-card-san-pham-btn');
    const sanPhamCard = document.getElementById('card-thong-tin-san-pham-cua-hang');

    toggleSanPhamBtn.addEventListener('click', function () {
      sanPhamCard.classList.toggle('d-none');

      if (sanPhamCard.classList.contains('d-none')) {
        toggleSanPhamBtn.innerHTML = `
          <img src="{{ asset('img/icons/arrow-right.svg') }}" class="me-1" alt="">
          Hiện thông tin sản phẩm
        `;
      } else {
        toggleSanPhamBtn.innerHTML = `
          <img src="{{ asset('img/icons/arrow-left.svg') }}" class="me-1" alt="">
          Ẩn thông tin sản phẩm
        `;
      }
    });
  });
</script>
{{-- JS để thêm/xóa biến thể của thêm sản phẩm --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('themBienTheContainer');
    const template = container.querySelector('.bienTheTemplate');

    document.getElementById('themBienTheBtn').addEventListener('click', function() {
        const clone = template.cloneNode(true);
        clone.querySelectorAll('input, select').forEach(input => input.value = '');
        container.appendChild(clone);

        clone.querySelector('.removeBienThe').addEventListener('click', function() {
            clone.remove();
        });
    });

    container.querySelectorAll('.removeBienThe').forEach(btn => {
        btn.addEventListener('click', function() {
            btn.closest('.bienTheTemplate').remove();
        });
    });
});
</script>
<script>
// document.addEventListener('DOMContentLoaded', function () {
//     // Xóa biến thể
//     function attachRemoveListeners() {
//         document.querySelectorAll('.xoa-bienthesp').forEach(function(button) {
//             button.onclick = function() {
//                 const bienTheDiv = this.closest('.border');
//                 if(bienTheDiv) bienTheDiv.remove();
//             };
//         });
//     }

//     attachRemoveListeners(); // gắn cho các biến thể hiện tại

//     // Thêm biến thể
//     const addBtn = document.getElementById('themBienTheBtn');
//     addBtn.addEventListener('click', function() {
//         const template = document.getElementById('template-bienthe');
//         const clone = template.cloneNode(true);
//         clone.id = '';
//         clone.classList.remove('d-none');
//         template.parentNode.insertBefore(clone, template); // chèn trước template
//         attachRemoveListeners(); // gắn nút xóa cho biến thể mới
//     });
// });
// </script>


<script>
    // Xóa ảnh trong carousel
    document.querySelectorAll('.xoa-anh').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const item = this.closest('.carousel-item');
            if(item) item.remove();
            // Có thể thêm ajax để xóa ảnh ở DB ngay khi click
        });
    });
</script>

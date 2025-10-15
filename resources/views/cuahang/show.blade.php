
@extends('layouts.app')

@section('title', 'Chi tiết cửa hàng | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">




    {{-- Thông tin chủ cửa hàng --}}
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Chủ cửa hàng</h4>
                <h6>Thông tin tài khoản</h6>
            </div>
        </div>
        @if($nguoiDung)
            <div class="card">
                <div class="card-body">
                    <div class="profile-set">
                        <div class="profile-head">
                        </div>
                        <div class="profile-top">
                        <div class="profile-content">
                            <div class="profile-contentimg">
                                @if($nguoiDung->avatar)
                                    <img src="{{ asset('storage/'.$nguoiDung->avatar) }}" alt="img" id="blah">
                                @else
                                    <img src="{{ asset('storage/uploads/nguoidung/avatar/nguoidung.png') }}" alt="img" id="blah">
                                @endif
                            </div>
                            <div class="profile-contentname">
                                @if ($nguoiDung->hoten)
                                    <h2>{{ $nguoiDung->hoten }}</h2>
                                @else
                                    <h2>Chưa cập nhật thông tin.</h2>
                                @endif
                                <h4>Thông tin tài khoản người dùng.</h4>
                            </div>
                        </div>
                        <div class="ms-auto">
                            <a href="{{ route('chinh-sua-cua-hang', ['id' => $nguoiDung->id ]) }}#formCapNhatNguoiDung" class="btn btn-submit me-2">Cập nhật tài khoản</a>
                            <a href="{{ route('danh-sach-cua-hang') }}" class="btn btn-cancel">Hủy</a>
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                        <div class="form-group">
                            @if ($nguoiDung->email)
                                <label>Email</label>
                                <input type="text" value="{{ $nguoiDung->email }}" placeholder="{{ $nguoiDung->email }}" readonly>
                            @else
                                <label>Email</label>
                                <input type="text" value="Chưa cập nhật thông tin" placeholder="Chưa cập nhật thông tin" readonly>
                            @endif
                        </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                        <div class="form-group">
                            @if ($nguoiDung->sodienthoai)
                                <label>Số điện thoại</label>
                                <input type="text" value="{{ $nguoiDung->sodienthoai }}" placeholder="{{ $nguoiDung->sodienthoai }} " readonly>

                            @else
                                <label>Số điện thoại</label>
                                <input type="text" value="Chưa cập nhật thông tin" placeholder="Chưa cập nhật thông tin" readonly>
                            @endif
                        </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                        <div class="form-group">
                            @if ($nguoiDung->vaitro)
                                <label>Vai Trò</label>
                                <input type="text" value="{{$nguoiDung->vaitro}}" placeholder="{{$nguoiDung->vaitro }}" readonly>
                            @else
                                <label>Vai Trò</label>
                                <input type="text" value="Chưa cập nhật thông tin" placeholder="Chưa cập nhật thông tin" readonly>
                            @endif
                        </div>
                        </div>

                        <div class="col-lg-6 col-sm-12">
                        <div class="form-group">
                            @if($nguoiDung->trangthai)
                                <label>Trạng thái tài khoản</label>
                                <input type="text" value="{{$nguoiDung->trangthai}}" placeholder="{{$nguoiDung->trangthai}}" readonly>

                            @else
                                <label>Trạng thái tài khoản</label>
                                <input type="text" value="Chưa cập nhật thông tin" placeholder="Chưa cập nhật thông tin" readonly>
                            @endif
                        </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                        <div class="form-group">
                            @if($nguoiDung->diachi && $nguoiDung->diachi->count() > 0)
                                <label>Địa Chỉ</label>
                                @foreach($nguoiDung->diachi as $dc)
                                <input type="text" class="m-1" value="{{$dc->diachi}}" placeholder="{{$dc->diachi}}" readonly>
                                @endforeach
                            @else
                                <label>Địa Chỉ</label>
                                <input type="text" value="Chưa cập nhật thông tin" placeholder="Chưa cập nhật thông tin" readonly>
                            @endif
                        </div>
                        </div>

                    </div>
                </div>
            </div>
        @else
            <div class="card">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item"><strong><a href="#">Chưa có thông tin cửa hàng</a></strong></li>
                </ul>
            </div>
        @endif

    </div>

      {{-- Thông tin cửa hàng --}}
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Chi tiết cửa hàng</h4>
                <h6>Thông tin chi tiết của cửa hàng</h6>
            </div>
        </div>
        @php $cuaHang = $nguoiDung->thongTinNguoiBanHang; @endphp
        @if($cuaHang)
            <div class="card">
                <div class="card-body">
                    <div class="profile-set">
                        @if ($cuaHang->bianen)
                        <div class="profile-head"  style="height:300px;
                            background-image: url('{{ asset('storage/'.$cuaHang->bianen) }}'); background-size: cover; background-position: center;
                        ">
                        </div>
                        @else
                        <div class="profile-head">
                        </div>
                        @endif
                        <div class="profile-top">
                            <div class="profile-content">
                                <div class="profile-contentimg "  style="width:130px; height:130px;">

                                    @if($cuaHang->logo)
                                        <img width="130" height="130" src="{{ asset('storage/'.$cuaHang->logo) }}" alt="img" id="blah">
                                    @else
                                        <img width="130" height="130" src="{{ asset('storage/uploads/cuahang/logo/logo.png') }}" alt="img" id="blah">
                                    @endif
                                </div>
                                <div class="profile-contentname">
                                    @if ($cuaHang->ten_cuahang)
                                        <h2>{{ $cuaHang->ten_cuahang }}</h2>
                                    @else
                                        <h2>Chưa cập nhật thông tin.</h2>
                                    @endif
                                    <h4>Thông tin của hàng.</h4>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <a href="{{ route('chinh-sua-cua-hang', ['id' => $nguoiDung->id ]) }}#formCapNhatCuaHang" class="btn btn-submit me-2">Cập nhật cửa hàng</a>
                                <a href="{{ route('danh-sach-cua-hang') }}" class="btn btn-cancel">Hủy</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                        <div class="form-group">
                            @if ($cuaHang->giayphep_kinhdoanh)
                                <label>Giấy phép kinh doanh</label>
                                <input type="text" value="{{ $cuaHang->giayphep_kinhdoanh }}" placeholder="{{ $cuaHang->giayphep_kinhdoanh }}" readonly>
                            @else
                                <label>Giấy phép kinh doanh</label>
                                <input type="text" value="Chưa cập nhật thông tin" placeholder="Chưa cập nhật thông tin" readonly>
                            @endif
                        </div>
                        </div>

                        <div class="col-lg-6 col-sm-12">
                        <div class="form-group">
                            @if ($cuaHang->diachi)
                                <label>Địa chỉ cửa hàng</label>
                                <input type="text" value="{{$cuaHang->diachi}}" placeholder="{{$cuaHang->diachi }}" readonly>
                            @else
                                <label>Địa chỉ cửa hàng</label>
                                <input type="text" value="Chưa cập nhật thông tin" placeholder="Chưa cập nhật thông tin" readonly>
                            @endif
                        </div>
                        </div>

                        <div class="col-lg-6 col-sm-12">
                        <div class="form-group">
                            @if($cuaHang->email)
                                <label>Email cửa hàng</label>
                                <input type="text" value="{{$cuaHang->email}}" placeholder="{{$cuaHang->email}}" readonly>
                            @else
                                <label>Email cửa hàng</label>
                                <input type="text" value="Chưa cập nhật thông tin" placeholder="Chưa cập nhật thông tin" readonly>
                            @endif
                        </div>
                        </div>

                        <div class="col-lg-6 col-sm-12">
                        <div class="form-group">
                            @if($cuaHang->sodienthoai)
                                <label>Số điện thoại cửa hàng</label>
                                <input type="text" value="{{$cuaHang->sodienthoai}}" placeholder="{{$cuaHang->sodienthoai}}" readonly>
                            @else
                                <label>Số điện thoại cửa hàng</label>
                                <input type="text" value="Chưa cập nhật thông tin" placeholder="Chưa cập nhật thông tin" readonly>
                            @endif
                        </div>
                        </div>

                        <div class="col-lg-12 col-sm-12">
                        <div class="form-group">
                            @if ($cuaHang->mota)
                                <label>Mô Tả</label>
                                <textarea  placeholder="{{ $cuaHang->mota }}" readonly>{{ $cuaHang->mota }}</textarea>
                            @else
                                <label>Mô Tả</label>
                                <textarea readonly placeholder="Chưa cập nhật thông tin" class="form-control">Chưa cập nhật thông tin</textarea>
                            @endif
                        </div>
                        </div>



                        <div class="col-lg-6 col-sm-12">
                        <div class="form-group">
                            @if($cuaHang->theodoi)
                                <label>Lượt theo dõi</label>
                                <input type="text" value="{{$cuaHang->theodoi}}" placeholder="{{$cuaHang->theodoi}}" readonly>
                            @else
                                <label>Lượt theo dõi</label>
                                <input type="text" value="Chưa cập nhật thông tin" placeholder="Chưa cập nhật thông tin" readonly>
                            @endif
                        </div>
                        </div>

                        <div class="col-lg-6 col-sm-12">
                        <div class="form-group">
                            @if($cuaHang->luotban)
                                <label>Lượt bán</label>
                                <input type="text" value="{{$cuaHang->luotban}}" placeholder="{{$cuaHang->luotban}}" readonly>
                            @else
                                <label>Lượt bán</label>
                                <input type="text" value="Chưa cập nhật thông tin" placeholder="Chưa cập nhật thông tin" readonly>
                            @endif
                        </div>
                        </div>


                        <div class="col-lg-6 col-sm-12">
                        <div class="form-group">
                            @if($cuaHang->trangthai)
                                <label>Trạng thái của hàng</label>
                                <input type="text" value="{{$cuaHang->trangthai}}" placeholder="{{$cuaHang->trangthai}}" readonly>

                            @else
                                <label>Trạng thái của hàng</label>
                                <input type="text" value="Chưa cập nhật thông tin" placeholder="Chưa cập nhật thông tin" readonly>
                            @endif
                        </div>
                        </div>

                    </div>
                </div>
            </div>
        @else
            <div class="card">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item"><strong><a href="{{ route('chinh-sua-cua-hang', ['id' => $nguoiDung->id ]) }}#tao-moi-cua-hang">Chưa có thông tin chủ cửa hàng</a></strong></li>
                </ul>
            </div>
        @endif

    </div>


    {{-- Danh sách sản phẩm --}}
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Chi tiết cửa hàng</h4>
                <h6>Thông tin chi tiết của cửa hàng và sản phẩm</h6>
            </div>
        </div>
        @if (!$cuaHang || !$cuaHang->sanpham || $cuaHang->sanpham->isEmpty())
            <div class="card">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item"><strong><a href="{{ route('chinh-sua-cua-hang', ['id' => $nguoiDung->id ]) }}#tao-moi-san-pham-cua-hang">Chưa có thông tin sản phẩm nào</a></strong></li>
                </ul>
            </div>
        @else
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="profile-set">
                                <div class="profile-head">
                                </div>
                                <div class="profile-top">
                                    <div class="profile-content">
                                        <div class="profile-contentimg "  style="width:130px; height:130px;">
                                        @if($cuaHang)
                                        <img width="130" height="130" src="{{ asset('storage/'.$cuaHang->logo) }}" alt="img" id="blah">
                                        @else
                                        <img width="130" height="130" src="{{ asset('storage/uploads/cuahang/logo/logo.png') }}" alt="img" id="blah">
                                        @endif
                                        </div>
                                        <div class="profile-contentname">
                                        @if ($cuaHang && $cuaHang->sanpham && $cuaHang->sanpham->isNotEmpty())
                                        <h4>Tổng số sản phẩm: {{ $cuaHang->sanpham->count() }}</h4>
                                        @php
                                        $totalBienThe = $cuaHang->sanpham->sum(function($sp) {
                                        return $sp->bienThe->count();
                                        });
                                        @endphp
                                        <h4>Tổng số biến thể sản phẩm: {{ $totalBienThe }}</h4>
                                        @php
                                        $totalLoaiBienThe = $cuaHang->sanpham->flatMap(function($sp) {
                                        return $sp->bienThe->pluck('id_tenloai'); // lấy id loại biến thể
                                        })->unique()->count(); // loại bỏ trùng lặp và đếm
                                        @endphp
                                        <h4>Tổng số loại biến thể sản phẩm: {{ $totalLoaiBienThe }}</h4>
                                        </div>
                                        @else
                                        <h4>Chưa cập nhật thông tin.</h4>
                                        @endif
                                    </div>
                                    <div class="ms-auto">
                                        <a href="{{ route('chinh-sua-cua-hang', ['id' => $nguoiDung->id ]) }}#card-thong-tin-san-pham-cua-hang" class="btn btn-submit me-2">Cập nhật sản phẩm</a>
                                        <a href="{{ route('danh-sach-cua-hang') }}" class="btn btn-cancel">Hủy</a>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tên sản phẩm</th>
                                            <th>Loại Biến Thể</th>
                                            <th>Giá bán</th>
                                            <th>Giá giảm</th>
                                            <th>Số lượng</th>
                                            <th>Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!$cuaHang || $cuaHang->sanpham->isEmpty())
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Chưa có sản phẩm trong cửa hàng</td>
                                        </tr>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted"><a  href="#">Thêm sản phẩm vào thương hiệu</a></td>
                                        </tr>
                                        @else
                                        @foreach($cuaHang->sanpham as $sp)
                                        @foreach($sp->bienThe as $bt)
                                        <tr>
                                            <td>
                                            <strong>{{ $sp->ten }}</strong><br>
                                            <small class="text-muted">Mã: SP{{ str_pad($sp->id, 4, '0', STR_PAD_LEFT) }}</small>
                                            </td>
                                            <td>{{ $bt->loaibienthe->ten ?? 'N/A' }}</td>
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
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @endif
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


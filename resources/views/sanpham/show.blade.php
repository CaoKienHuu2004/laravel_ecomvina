@extends('layouts.app')

@section('title')
{{ $sanpham->ten }} | Sản phẩm | Quản trị hệ thống Siêu Thị Vina
@endsection
{{-- // controller truyền xuống $sanpham  --}}
{{-- // các route sư dụng không có    --}}
{{--  $sanphams->hinhanhsanpham->first()->hihanh: Link http://148.230.100.215/assets/client/images/thumbs/tenfilehinhanh.jpg --}}

{{-- bản củ // controller truyền xuống $sanphams,$thuonghieus danhmucs  --}}
{{-- {{-- dir_part asset storage/uploads/anh_sanpham/media/anh_sanpham.png --}}
{{--  tao-san-pham danh-sach xoa-san-pham chinh-sua-san-pham chi-tiet-san-pham   --}}
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Sản phẩm "{{ $sanpham->ten }}"</h4>
                <h6>Xem chi tiết thông tin của sản phẩm.</h6>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="productdetails">
                            <ul class="product-bar">
                                <li>
                                    <h4><strong>Tên sản phẩm</strong></h4>
                                    <h6>{{ $sanpham->ten }}</h6>
                                </li>
                                <li>
                                    <h4><strong>Danh mục</strong></h4>
                                    <h6>
                                        {!! $sanpham->danhmuc->pluck('ten')->implode(', ') ?: 'Chưa có danh mục' !!}
                                    </h6>
                                </li>
                                <li>
                                    <h4><strong>Thương hiệu</strong></h4>
                                    <h6>{{ $sanpham->thuonghieu->ten }}</h6>
                                </li>
                                <li>
                                    <h4><strong>Nơi sản xuất</strong></h4>
                                    <h6>{{ $sanpham->sanxuat }}</h6>
                                </li>
                                <li>
                                    <h4><strong>Xuất xứ</strong></h4>
                                    <h6>{{ $sanpham->xuatxu }}</h6>
                                </li>
                                <li>
                                    <h4><strong>Mô tả sản phẩm</strong></h4>
                                    <h6>{!! $sanpham->mota !!}</h6>
                                </li>
                                <li>
                                    <h4><strong>Biến thể sản phẩm</strong></h4>
                                    <h6>
                                        <ul>
                                            <li>
                                                @foreach ($sanpham->bienthe as $bt)
                                                @if ($bt->soluong > 5)
                                                <span class="text-success fs-6" title="Còn hàng"><strong>Loại: </strong> {{ $bt->loaiBienThe->ten }} <span class="badges bg-lightgreen p-1">Còn hàng</span></span>
                                                @elseif($bt->soluong == 0)
                                                <span class="text-danger fs-6" title="Hết hàng"><strong>Loại: </strong> {{ $bt->loaiBienThe->ten }} <span class="badges bg-lightred p-1">Hết hàng</span></span>
                                                @else
                                                <span class="text-warning fs-6" title="Sắp hết hàng"><strong>Loại: </strong> {{ $bt->loaiBienThe->ten }} <span class="badges bg-lightyellow p-1">Sắp hết hàng</span></span>
                                                @endif

                                                <ul>
                                                    <li><strong>- Giá Gốc:</strong> {{ number_format($bt->giagoc, 0, ',', '.') }} đ</li>
                                                    <li><strong>- Số lượng:</strong> {{ $bt->soluong }}</li>

                                                </ul>
                                                @if (! $loop->last)
                                                ------ <br>
                                                @endif
                                                @endforeach

                                            </li>
                                        </ul>
                                    </h6>
                                </li>
                                <li>
                                    <h4><strong>Trạng thái</strong></h4>
                                    <h6>
                                        @if ($sanpham->trangthai=="Công khai")
                                            <span class="badges bg-lightgreen">{{$sanpham->trangthai}}</span>
                                        @elseif($sanpham->trangthai=="Chờ duyệt")
                                            <span class="badges bg-warning text-dark">{{$sanpham->trangthai}}</span>
                                        @else
                                            <span class="badges bg-lightred">{{$sanpham->trangthai}}</span>
                                        @endif
                                    </h6>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="slider-product-details">
                            <div class="owl-carousel owl-theme product-slide">
                                @foreach ($sanpham->hinhanhsanpham as $anh)
                                <div class="slider-product">

                                    <a href="{{ $anh->hinhanh }}" class="image-popup-desc" data-title="{{ $sanpham->ten }}" data-description="{{ $anh->media }}">
                                        <img src="{{ $anh->hinhanh }}" class="img-fluid" alt="work-thumbnail" />
                                    </a>
                                    {{-- <a href="{{ asset('img/product/' . $anh->media) }}" class="image-popup-desc" data-title="{{ $sanpham->ten }}" data-description="{{ $anh->media }}">
                                        <img src="{{ asset('img/product/' . $anh->media) }}" class="img-fluid" alt="work-thumbnail" />
                                    </a> --}}
                                    <h4>{{ $anh->media }}</h4>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

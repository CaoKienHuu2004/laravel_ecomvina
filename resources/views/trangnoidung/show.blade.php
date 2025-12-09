@extends('layouts.app')

@section('title')
{{ $trangnoidung->tieude }} | Trang nội dung | Quản trị hệ thống Siêu Thị Vina
@endsection

@section('content')
<div class="page-wrapper">
    <div class="content">

        <div class="page-header">
            <x-header.breadcrumb
                title='Chi tiết trang "{{ $trangnoidung->tieude }}"'
                :links="[
                    ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                    ['label' => 'Danh sách trang nội dung', 'route' => 'trangnoidung.index']
                ]"
                active="Chi tiết"
            />
        </div>

        <div class="row">

            <!-- LEFT: Thông tin -->
            <div class="col-lg-8 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="productdetails">
                            <ul class="product-bar">

                                <li>
                                    <h4><strong>Tiêu đề</strong></h4>
                                    <h6>{{ $trangnoidung->tieude }}</h6>
                                </li>

                                <li>
                                    <h4><strong>Slug</strong></h4>
                                    <h6>{{ $trangnoidung->slug }}</h6>
                                </li>

                                <li>
                                    <h4><strong>Mô tả</strong></h4>
                                    <h6>{!! $trangnoidung->mota !!}</h6>
                                </li>

                                <li>
                                    <h4><strong>Trạng thái</strong></h4>
                                    <h6>
                                        @if ($trangnoidung->trangthai == "Hiển thị")
                                            <span class="badges bg-lightgreen">Hiển thị</span>
                                        @else
                                            <span class="badges bg-warning text-dark">Tạm ẩn</span>
                                        @endif
                                    </h6>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Hình ảnh -->
            <div class="col-lg-4 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="slider-product-details">

                            <div class="owl-carousel owl-theme product-slide">

                                <div class="slider-product">
                                    <a href="{{ $trangnoidung->hinhanh }}" class="image-popup-desc"
                                       data-title="{{ $trangnoidung->tieude }}">
                                        <img src="{{ $trangnoidung->hinhanh }}" class="img-fluid" alt="Ảnh trang" />
                                    </a>
                                    <h4>{{ basename($trangnoidung->hinhanh) }}</h4>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection

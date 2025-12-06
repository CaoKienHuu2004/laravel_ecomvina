@extends('layouts.app')

@section('title', 'Chi tiết hình ảnh sản phẩm')

{{--
    // Controller truyền xuống $hinhanh
    $hinhanh->hinhanh chứa đường dẫn URL đầy đủ, ví dụ:
    http://148.230.100.215/assets/client/images/thumbs/tenfilehinhanh.jpg
--}}
{{--
    // các route sư dụng không --- của breadcrumb hinhanhsanpham.index trang-chu
--}}

@section('content')
<div class="page-wrapper">
    <div class="content">

        {{-- Breadcrumb --}}
        <div class="page-header">
            <x-header.breadcrumb
                title="Chi tiết hình ảnh sản phẩm"
                :links="[
                    ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                    ['label' => 'Danh sách hình ảnh sản phẩm', 'route' => 'hinhanhsanpham.index']
                ]"
                active="Chi tiết"
            />
        </div>

        {{-- Thông báo success, error --}}
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

        <div class="row">
            {{-- Thông tin chi tiết --}}
            <div class="col-lg-8 col-sm-12">
                <div class="card">
                    <div class="card-body">

                        <div class="mb-3">
                            <strong>ID:</strong> {{ $hinhanh->id }}
                        </div>

                        @php
                            $bientheList = [];
                            if ($hinhanh->sanpham && $hinhanh->sanpham->bienthe) {
                                foreach ($hinhanh->sanpham->bienthe as $bienthe) {
                                    if ($bienthe->loaibienthe) {
                                        $bientheList[] = $bienthe->loaibienthe->ten;
                                    }
                                }
                            }
                            $bientheString = implode(', ', $bientheList); // nối các tên loại biến thể bằng dấu phẩy
                        @endphp
                        <div class="mb-3">
                            <strong>Sản phẩm:</strong>
                            {!! wordwrap(
                                ($hinhanh->sanpham ? $hinhanh->sanpham->ten : 'Không xác định') .
                                ($bientheString ? ' - ' . $bientheString : ''),
                                200,
                                "<br>",
                                true
                            ) !!}
                        </div>

                        @if (!empty($hinhanh->hinhanh))
                            <div class="mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <strong>Đường dẫn hình ảnh:</strong>
                                    <a href="{{ $hinhanh->hinhanh }}" target="_blank" rel="noopener noreferrer">
                                        <img src="{{ asset('img/icons/eye.svg') }}" alt="Xem" />
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <strong>Hình ảnh:</strong><br>
                            @if ($hinhanh->hinhanh)
                                <img src="{{ $hinhanh->hinhanh }}" class="img-thumbnail" style="max-width: 100%; height: auto;" alt="Hình ảnh sản phẩm">
                            @else
                                <p class="text-muted fst-italic">Không có hình ảnh</p>
                            @endif
                        </div>

                        <div class="mb-3">
                            <strong>Trạng thái:</strong>
                            <span class="{{ $hinhanh->trangthai == 'Hiển thị' ? 'text-success' : 'text-danger' }}">
                                {{ $hinhanh->trangthai }}
                            </span>
                        </div>

                        {{-- <div class="mt-3">
                            <a href="{{ route('hinhanhsanpham.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Quay lại danh sách
                            </a>
                        </div> --}}

                    </div>
                </div>
            </div>

            {{-- Gallery / Các hình ảnh liên quan (nếu có) --}}
            <div class="col-lg-4 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Ảnh liên quan sản phẩm</h5>

                        @if ($hinhanh->sanpham && $hinhanh->sanpham->hinhanhsanpham && $hinhanh->sanpham->hinhanhsanpham->count() > 0)
                            <div id="relatedImagesCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach ($hinhanh->sanpham->hinhanhsanpham as $index => $anh)
                                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                            <a href="{{ $anh->hinhanh }}" target="_blank" rel="noopener noreferrer">
                                                <img src="{{ $anh->hinhanh }}" class="d-block w-100" style="max-height: 300px; object-fit: contain;" alt="Ảnh sản phẩm">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#relatedImagesCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon bg-primary" aria-hidden="true"></span>
                                    <span class="visually-hidden">Trước</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#relatedImagesCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon bg-primary" aria-hidden="true"></span>
                                    <span class="visually-hidden">Tiếp</span>
                                </button>
                            </div>
                        @else
                            <p class="text-muted fst-italic">Không có ảnh liên quan</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

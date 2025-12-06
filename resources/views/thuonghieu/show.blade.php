@extends('layouts.app')

@section('title', 'Chi tiết thương hiệu.')

{{-- // controller truyền xuống $thuonghieu  --}}
{{-- // các route sư dụng ko có --- của breadcrumb thuonghieu.index trang-chu  --}}
{{-- $thuonghieu->logo: Link http://148.230.100.215/assets/client/images/brands/tenfilehinhanh.jpg --}}
@section('content')
<div class="page-wrapper">
    <div class="content">
        {{-- === Breadcrumb === --}}
        <div class="page-header">
            <x-header.breadcrumb
                title='Chi Tiết Thương Hiệu'
                :links="[
                    ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                    ['label' => 'Danh sách thương hiệu', 'route' => 'thuonghieu.index']
                ]"
                active="Chi tiết"
            />
        </div>

        <div class="card">
            <div class="card-body">

                <div class="row mb-3">
                    <label class="col-sm-3 fw-bold">Tên thương hiệu:</label>
                    <div class="col-sm-9">{{ $thuonghieu->ten }}</div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 fw-bold">Slug:</label>
                    <div class="col-sm-9">{{ $thuonghieu->slug }}</div>
                </div>

                @if (!empty($thuonghieu->logo))
                    <div class="row mb-3">
                        <label class="col-sm-3 fw-bold">Đường dẫn hình ảnh:</label>
                        <div class="col-sm-9">
                            <a href="{{ $thuonghieu->logo }}" target="_blank" rel="noopener noreferrer">
                                <img src="{{ asset('img/icons/eye.svg') }}" alt="Xem" />
                            </a>
                        </div>
                    </div>
                @endif

                <div class="row mb-3">
                    <label class="col-sm-3 fw-bold">Logo:</label>
                    <div class="col-sm-9">
                        @if ($thuonghieu->logo)
                            <img src="{{ $thuonghieu->logo }}" alt="Logo {{ $thuonghieu->ten }}" style="max-width: 200px; border: 1px solid #ddd; padding: 5px; border-radius: 5px;">
                        @else
                            <span class="text-muted">Chưa có logo</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 fw-bold">Mô tả:</label>
                    <div class="col-sm-9">{!! nl2br(e($thuonghieu->mota)) ?: '<span class="text-muted">Không có mô tả</span>' !!}</div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 fw-bold">Trạng thái:</label>
                    <div class="col-sm-9">
                        <span class="badge
                            @if ($thuonghieu->trangthai == 'Hoạt động') bg-success
                            @elseif ($thuonghieu->trangthai == 'Tạm khóa') bg-warning
                            @else bg-danger
                            @endif
                        ">
                            {{ $thuonghieu->trangthai }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 fw-bold">Số sản phẩm:</label>
                    <div class="col-sm-9">{{ $thuonghieu->sanpham()->count() }}</div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

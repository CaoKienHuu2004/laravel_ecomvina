@extends('layouts.app')

@section('title', 'Chi tiết Quà Tặng Sự Kiện | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
  <div class="content container-fluid">
    <div class="page-header">
        <x-header.breadcrumb
            title="Xem Chi Tiết Quà Tặng Sự Kiện"
            :links="[
                ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                ['label' => 'Danh sách Quà Tặng Sự Kiện', 'route' => 'quatangsukien.index']
            ]"
            active="Chi tiết Quà Tặng Sự Kiện"
        />
    </div>

    <div class="card p-4">
      <div class="mb-3">
        <strong>Tiêu đề:</strong>
        <p>{{ $quatang->tieude }}</p>
      </div>
      <div class="mb-3">
        <strong>Slug:</strong>
        <p>{{ $quatang->slug }}</p>
      </div>

      <div class="mb-3">
        <strong>Biến thể sản phẩm:</strong>
        <p>
          @if($quatang->bienthe)
            {{ $quatang->bienthe->id }} - {{ $quatang->bienthe->sanpham->ten ?? 'N/A' }} ({{ $quatang->bienthe->loaibienthe->ten ?? 'N/A' }})
          @else
            <span class="text-muted">Không có biến thể</span>
          @endif
        </p>
      </div>

      <div class="mb-3">
        <strong>Chương trình:</strong>
        <p>{{ $quatang->chuongtrinh->tieude ?? 'Không có chương trình' }}</p>
      </div>

      <div class="mb-3">
        <strong>Điều kiện số lượng:</strong>
        <p>{{ $quatang->dieukiensoluong ?: 'Không có' }}</p>
      </div>

      <div class="mb-3">
        <strong>Điều kiện giá trị:</strong>
        <p>{{ $quatang->dieukiengiatri ?: 'Không có' }}</p>
      </div>

      <div class="mb-3">
        <strong>Thông tin:</strong>
        <p>{!! nl2br(e($quatang->thongtin ?: 'Không có')) !!}</p>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <strong>Ngày bắt đầu:</strong>
          <p>{{ $quatang->ngaybatdau ? $quatang->ngaybatdau : 'Chưa có' }}</p>
        </div>
        <div class="col-md-6">
          <strong>Ngày kết thúc:</strong>
          <p>{{ $quatang->ngayketthuc ? $quatang->ngayketthuc : 'Chưa có' }}</p>
        </div>
      </div>

      <div class="mb-3">
        <strong>Trạng thái:</strong>
        <p>{{ $quatang->trangthai }}</p>
      </div>

      <div class="mb-3">
        <strong>Hình ảnh:</strong><br>
        @if($quatang->hinhanh)
          <img src="{{ $quatang->hinhanh }}" alt="{{ $quatang->tieude }}" style="max-width: 300px; max-height: 150px; object-fit: contain;">
        @else
          <span class="text-muted">Không có hình ảnh</span>
        @endif
      </div>

      <div class="d-flex mt-4">
        <a href="{{ route('quatangsukien.edit', $quatang->id) }}" class="btn btn-primary me-2">Chỉnh sửa</a>
        <a href="{{ route('quatangsukien.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
      </div>
    </div>
  </div>
</div>
@endsection

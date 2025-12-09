@extends('layouts.app')

@section('title', 'Chi Tiết Mã Giảm Giá | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>CHI TIẾT MÃ GIẢM GIÁ</h4>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <h5>Mã Giảm Giá: {{ $discount->magiamgia }}</h5>
        <p><strong>Điều Kiện:</strong> {{ $discount->dieukien }}</p>
        <p><strong>Mô Tả:</strong> {{ $discount->mota }}</p>
        <p><strong>Giảm Giá:</strong> {{ $discount->giatri }}%</p>
        <p><strong>Ngày Bắt Đầu:</strong> {{ $discount->ngaybatdau->format('d/m/Y') }}</p>
        <p><strong>Ngày Kết Thúc:</strong> {{ $discount->ngayketthuc->format('d/m/Y') }}</p>
        <p><strong>Trạng Thái:</strong> {{ $discount->getTrangThaiLabelAttribute() }}</p>

        <a href="{{ route('magiamgia.edit', $discount->id) }}" class="btn btn-warning">Sửa</a>
        <a href="{{ route('magiamgia.index') }}" class="btn btn-secondary">Quay lại</a>
      </div>
    </div>
  </div>
</div>
@endsection

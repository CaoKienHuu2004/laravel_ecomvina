@extends('layouts.app')

@section('title', 'Thêm mới Phương Thức Thanh Toán')
{{-- // controller truyền xuống $trangthais (dùng để làm selectbox_phuongthuc_trangthais) --}}
{{-- // các route sư dụng phuongthuc.store phuongthuc.index --- của breadcrumb phuongthuc.index trang-chu --}}
@section('content')
<div class="page-wrapper">
  <div class="content container-fluid">

    <div class="page-header">
      <div class="row">
        <div class="col">
            <h3 class="page-title">Thêm mới Phương Thức Thanh Toán</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('trang-chu') }}">Tổng quan</a></li>
                <li class="breadcrumb-item"><a href="{{ route('phuongthuc.index') }}">Danh sách Phương Thức</a></li>
                <li class="breadcrumb-item active">Thêm mới</li>
            </ul>
        </div>
      </div>
    </div>

    {{-- Hiển thị lỗi validate --}}
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="card">
      <div class="card-body">
        <form action="{{ route('phuongthuc.store') }}" method="POST">
          @csrf

          <div class="mb-3">
            <label for="ten" class="form-label">Tên phương thức <span class="text-danger">*</span></label>
            <input
              type="text"
              class="form-control @error('ten') is-invalid @enderror"
              id="ten"
              name="ten"
              value="{{ old('ten') }}"
              required
            >
            @error('ten')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="maphuongthuc" class="form-label">Mã phương thức</label>
            <input
              type="text"
              class="form-control @error('maphuongthuc') is-invalid @enderror"
              id="maphuongthuc"
              name="maphuongthuc"
              value="{{ old('maphuongthuc') }}"
            >
            @error('maphuongthuc')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="trangthai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
            <select
              class="form-select @error('trangthai') is-invalid @enderror"
              id="trangthai"
              name="trangthai"
              required
            >
              @foreach($trangthais as $option)
                <option value="{{ $option }}" {{ old('trangthai') === $option ? 'selected' : '' }}>
                  {{ $option }}
                </option>
              @endforeach
            </select>
            @error('trangthai')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <button type="submit" class="btn btn-primary">Lưu phương thức</button>
          <a href="{{ route('phuongthuc.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
      </div>
    </div>

  </div>
</div>
@endsection

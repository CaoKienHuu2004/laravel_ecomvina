@extends('layouts.app')

@section('title', 'Thêm Loại Biến Thể | Quản trị hệ thống Siêu Thị Vina')
{{-- // controller truyền xuống $trangthais (nó là selectbox_loadbienthe_trangthais)  --}}
{{-- // các route sư dụng loaibienthe.store loaibienthe.index  --}}

@section('content')
<div class="page-wrapper">
  <div class="content">

    {{-- Thông báo lỗi / success --}}
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

    <div class="page-header">
      <div class="page-title">
        <h4>Thêm Loại Biến Thể Mới</h4>
        <h6>Nhập thông tin loại biến thể để tạo mới</h6>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <form action="{{ route('loaibienthe.store') }}" method="POST" class="row g-3">
          @csrf

          <div class="col-md-6">
            <label for="ten" class="form-label">Tên Loại Biến Thể <span class="text-danger">*</span></label>
            <input
              type="text"
              id="ten"
              name="ten"
              class="form-control @error('ten') is-invalid @enderror"
              value="{{ old('ten') }}"
              placeholder="Nhập tên loại biến thể"
              required
            >
            @error('ten')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-6">
            <label for="trangthai" class="form-label">Trạng Thái <span class="text-danger">*</span></label>
            <select
              id="trangthai"
              name="trangthai"
              class="form-select @error('trangthai') is-invalid @enderror"
              required
            >
              <option value="" disabled {{ old('trangthai') ? '' : 'selected' }}>-- Chọn trạng thái --</option>
              @foreach ($trangthais as $trangthai)
                <option value="{{ $trangthai }}" {{ old('trangthai') === $trangthai ? 'selected' : '' }}>
                  {{ $trangthai }}
                </option>
              @endforeach
            </select>
            @error('trangthai')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-12">
            <button type="submit" class="btn btn-primary">Thêm Loại Biến Thể</button>
            <a href="{{ route('loaibienthe.index') }}" class="btn btn-secondary ms-2">Hủy</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

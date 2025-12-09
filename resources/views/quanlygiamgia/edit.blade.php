@extends('layouts.app')

@section('title', 'Chỉnh Sửa Mã Giảm Giá | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
  <div class="content">
    <div class="page-header">
        <x-header.breadcrumb
            title="Chỉnh Sửa Mã Giảm Giá"
            :links="[
                ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                ['label' => 'Danh sách Mã Giảm Giá', 'route' => 'danhsach.magiamgia']
            ]"
            active="Chỉnh sửa"
        />
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Hiển thị lỗi validate -->
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <ul class="mb-0">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card">
      <div class="card-body">
        <form action="{{ route('magiamgia.update', $magiamgia->id) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="row">
            <div class="col-lg-6 col-12">
              <div class="form-group">
                <label for="magiamgia">Mã Giảm Giá <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="magiamgia" name="magiamgia"
                       value="{{ old('magiamgia', $magiamgia->magiamgia) }}" required>
              </div>
            </div>
            <div class="col-lg-6 col-12">
              <div class="form-group">
                <label for="giatri">Giá Trị Giảm (VNĐ) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="giatri" name="giatri"
                       value="{{ old('giatri', $magiamgia->giatri) }}" required min="1">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-6 col-12">
              <div class="form-group">
                <label for="dieukien">Điệu Kiện</label>
                <input type="number" class="form-control" id="dieukiensoluong" name="dieukien"
                       value="{{ old('dieukien', $magiamgia->dieukien) }}" min="0" placeholder="0 = Không yêu cầu">
                <small class="text-muted">Ví dụ: 5 → phải mua ít nhất 5 sản phẩm</small>
              </div>
            </div>

          </div>

          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="form-group">
                <label for="mota">Mô Tả</label>
                <textarea class="form-control" id="mota" name="mota" rows="3">{{ old('mota', $magiamgia->mota) }}</textarea>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-6 col-12">
              <div class="form-group">
                <label for="ngaybatdau">Ngày Bắt Đầu <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="ngaybatdau" name="ngaybatdau"
                       value="{{ old('ngaybatdau', $magiamgia->ngaybatdau) }}" required>
              </div>
            </div>
            <div class="col-lg-6 col-12">
              <div class="form-group">
                <label for="ngayketthuc">Ngày Kết Thúc <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="ngayketthuc" name="ngayketthuc"
                       value="{{ old('ngayketthuc', $magiamgia->ngayketthuc) }}" required>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-6 col-12">
              <div class="form-group">
                <label for="trangthai">Trạng Thái <span class="text-danger">*</span></label>
                <select class="form-control" id="trangthai" name="trangthai" required>
                  <option value="Hoạt động" {{ old('trangthai', $magiamgia->trangthai) == 'Hoạt động' ? 'selected' : '' }}>Hoạt động</option>
                  <option value="Tạm khóa" {{ old('trangthai', $magiamgia->trangthai) == 'Tạm khóa' ? 'selected' : '' }}>Tạm khóa</option>
                  <option value="Dừng hoạt động" {{ old('trangthai', $magiamgia->trangthai) == 'Dừng hoạt động' ? 'selected' : '' }}>Dừng hoạt động</option>
                </select>
              </div>
            </div>
            <div class="col-lg-6 col-12 d-flex align-items-end">
              <button type="submit" class="btn btn-primary w-100">Cập Nhật Mã Giảm Giá</button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
@endsection

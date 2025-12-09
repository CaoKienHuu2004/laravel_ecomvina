@extends('layouts.app')

@section('title', 'Chỉnh Sửa Mã Giảm Giá | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>CHỈNH SỬA MÃ GIẢM GIÁ</h4>
      </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
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
                <label for="magiamgia">Mã Giảm Giá</label>
                <input type="text" class="form-control" id="magiamgia" name="magiamgia" value="{{ $magiamgia->magiamgia }}" required>
              </div>
            </div>
            <div class="col-lg-6 col-12">
              <div class="form-group">
                <label for="giatri">Giảm Giá (VNĐ)</label>
                <input type="number" class="form-control" id="giatri" name="giatri" value="{{ $magiamgia->giatri }}" required min="0" max="10000000000000">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6 col-12">
              <div class="form-group">
                <label for="dieukien">Điều Kiện</label>
                <input type="text" class="form-control" id="dieukien" name="dieukien" value="{{ $magiamgia->dieukien }}">
              </div>
            </div>
            <div class="col-lg-6 col-12">
              <div class="form-group">
                <label for="mota">Mô Tả</label>
                <textarea class="form-control" id="mota" name="mota">{{ $magiamgia->mota }}</textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6 col-12">
              <div class="form-group">
                <label for="ngaybatdau">Ngày Bắt Đầu</label>
                <input type="date" class="form-control" id="ngaybatdau" name="ngaybatdau" value="{{ $magiamgia->ngaybatdau->format('Y-m-d') }}" required>
              </div>
            </div>
            <div class="col-lg-6 col-12">
              <div class="form-group">
                <label for="ngayketthuc">Ngày Kết Thúc</label>
                <input type="date" class="form-control" id="ngayketthuc" name="ngayketthuc" value="{{ $magiamgia->ngayketthuc->format('Y-m-d') }}" required>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="trangthai">Trạng Thái</label>
            <select class="form-control" id="trangthai" name="trangthai" required>
               <option value="Hoạt Động" {{ $magiamgia->trangthai == 'Hoạt Động' ? 'selected' : '' }}>Hoạt Động</option>
              <option value="Tạm khóa" {{ $magiamgia->trangthai == 'Tạm khóa' ? 'selected' : '' }}>Tạm khóa</option>
              <option value="Dừng hoạt động" {{ $magiamgia->trangthai == 'Dừng hoạt động' ? 'selected' : '' }}>Dừng hoạt động</option> 
            </select>
          </div>

          <button type="submit" class="btn btn-primary">Cập Nhật Mã Giảm Giá</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

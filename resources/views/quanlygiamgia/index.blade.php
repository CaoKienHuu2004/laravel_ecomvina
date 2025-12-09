@extends('layouts.app')

@section('title', 'Danh sách mã giảm giá | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>DANH SÁCH MÃ GIẢM GIÁ</h4>
      </div>
      <div class="page-btn">
        <a href="{{ route('create.magiamgia') }}" class="btn btn-added">
          <img src="{{ asset('img/icons/plus.svg') }}" alt="img" class="me-1" /> Tạo mã giảm giá
        </a>
      </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table datanew">
            <thead>
              <tr>
                <th>Mã Giảm Giá</th>
                <th>Điều Kiện</th>
                <th>Mô Tả</th>
                <th>Giảm Giá (VNĐ)</th>
                <th>Ngày Bắt Đầu</th>
                <th>Ngày Kết Thúc</th>
                <th>Trạng Thái</th>
                <th>Thao Tác</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($magiamgia as $discount)
              <tr>
                <td>{{ $discount->magiamgia }}</td>
                <td>{{ $discount->dieukien }}</td>
                <td>{{ $discount->mota }}</td>
                <td>{{ number_format($discount->giatri, 0, ',', '.') }} VNĐ</td>
                <td>{{ $discount->ngaybatdau->format('d/m/Y') }}</td>
                <td>{{ $discount->ngayketthuc->format('d/m/Y') }}</td>
                <td>{{ $discount->getTrangThaiLabelAttribute() }}</td>
                <td>
                  <a href="{{ route('edit.magiamgia', $discount->id) }}" class="me-3" title="Sửa">
                    <img src="{{ asset('img/icons/edit.svg') }}" alt="img" />
                  </a>
                  <form action="{{ route('delete.magiamgia', $discount->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?');">
                      <img src="{{ asset('img/icons/delete.svg') }}" alt="img" />
                    </button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <!-- Phân trang -->
        <div class="d-flex justify-content-center">
          {{ $magiamgia->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

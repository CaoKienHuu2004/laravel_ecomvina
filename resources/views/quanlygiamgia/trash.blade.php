@extends('layouts.app')

@section('title', 'Thùng rác Mã Giảm Giá | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
  <div class="content container-fluid">
    <div class="page-header">
        <x-header.breadcrumb
            title="THÙNG RÁC MÃ GIẢM GIÁ"
            :links="[
                ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                ['label' => 'Danh sách Mã Giảm Giá', 'route' => 'danhsach.magiamgia']
            ]"
            active="Thùng rác Mã Giảm Giá"
        />
    </div>

    {{-- Thông báo thành công --}}
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="card">
      <div class="card-body">
        @if($magiamgias->count() > 0)
          <div class="table-responsive">
            <table class="table table-bordered align-middle">
              <thead>
                <tr>
                  <th>Mã giảm giá</th>
                  <th>Điều kiện</th>
                  <th>Giá trị</th>
                  <th>Trạng thái</th>
                  <th>Ngày xóa</th>
                  <th class="text-center">Hành động</th>
                </tr>
              </thead>
              <tbody>
                @foreach($magiamgias as $mg)
                  <tr>
                    <td>{{ $mg->magiamgia }}</td>
                    <td>{{ $mg->dieukien }}</td>
                    <td>{{ $mg->giatri }}</td>
                    <td>{{ $mg->getTrangThaiLabelAttribute() }}</td>

                    <td>
                      {{ $mg->deleted_at ? $mg->deleted_at->format('d/m/Y H:i') : '' }}
                    </td>

                    <td class="text-center" style="width: 160px;">
                      {{-- Restore --}}
                      <form action="{{ route('magiamgia.restore', $mg->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm me-1" title="Khôi phục"
                            onclick="return confirm('Bạn có chắc chắn muốn khôi phục mã giảm giá này?')">
                          <img src="{{ asset('img/icons/return1.svg') }}" alt="Khôi phục" style="width:16px; height:16px;">
                        </button>
                      </form>

                      {{-- Force Delete --}}
                      <form action="{{ route('magiamgia.forceDelete', $mg->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm" title="Xóa vĩnh viễn"
                            onclick="return confirm('Bạn có chắc chắn muốn xóa VĨNH VIỄN mã giảm giá này?')">
                          <img src="{{ asset('img/icons/delete.svg') }}" alt="Xóa" style="width:16px; height:16px;">
                        </button>
                      </form>
                    </td>

                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          {{-- Phân trang --}}
          {{-- <div class="d-flex justify-content-end mt-3">
            {{ $magiamgia->links() }}
          </div> --}}

        @else
          <p class="text-center">Thùng rác hiện đang trống.</p>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Danh sách quảng cáo')

{{-- $quangcaos->hinhanh: Link http://148.230.100.215/assets/client/images/bg/tenfilehinhanh.jpg --}}

@section('content')
<div class="page-wrapper">
    <div class="content">

        <div class="page-header">
            <div class="page-title">
                <h4>DANH SÁCH BANNER QUẢNG CÁO</h4>
                <div class="d-flex align-items-center" style="gap: 2rem;">
                    {{-- 'Hiển thị','Tạm ẩn' --}}
                    <h6>
                    Tổng Banner Quảng Cáo: {{ $quangcaos->count() }} <br>
                    Hiển thị: {{ $quangcaos->where('trangthai', 'Hiển thị')->count() }} <br>
                    Tạm ẩn: {{ $quangcaos->where('trangthai', 'Tạm ẩn')->count() }} <br>
                    </h6>
                </div>
            </div>
            <div class="page-btn">
                <a href="{{ route('quangcao.create') }}" class="btn btn-added">
                    <img src="{{ asset('img/icons/plus.svg') }}" class="me-1" alt="img">
                    Tạo Banner Quảng Cáo
                </a>
            </div>
        </div>

        {{-- FLASH MESSAGE --}}
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
                {{-- Bảng dữ liệu --}}
                <div class="table-responsive">
                    <table class="table datanew">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Vị trí</th>
                                <th>Hình ảnh</th>
                                <th>Liên kết</th>
                                <th>Mô tả</th>
                                <th>Trạng thái</th>
                                <th class="text-center" width="220px">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($quangcaos as $qc)
                                <tr>
                                    <td>{{ $qc->id }}</td>
                                    <td>{{ $qc->vitri }}</td>
                                    <td>
                                        @if ($qc->hinhanh)
                                            <img src="{{ $qc->hinhanh }}" alt="Hình ảnh quảng cáo" width="100px" style="object-fit:contain;">
                                        @else
                                            <span class="text-muted">Không có hình</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ $qc->lienket }}" target="_blank" rel="noopener noreferrer">
                                            {{ $qc->lienket }}
                                        </a>
                                    </td>
                                    <td style="max-width: 250px; white-space: normal; word-wrap: break-word;">
                                        {{ $qc->mota }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $qc->trangthai == 'Hiển thị' ? 'success' : 'warning' }}">
                                            {{ $qc->trangthai }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('quangcao.show', $qc->id) }}" title="Xem chi tiết" class="me-2">
                                            <img src="{{ asset('img/icons/eye.svg') }}" alt="Xem" />
                                        </a>
                                        <a href="{{ route('quangcao.edit', $qc->id) }}" title="Chỉnh sửa" class="me-2">
                                            <img src="{{ asset('img/icons/edit.svg') }}" alt="Sửa" />
                                        </a>
                                        {{-- <form action="{{ route('quangcao.destroy', $qc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa banner quảng cáo này không?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline" title="Xóa">
                                            <img src="{{ asset('img/icons/delete.svg') }}" alt="Xóa" />
                                            </button>
                                        </form> --}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Không có quảng cáo nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

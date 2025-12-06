@extends('layouts.app')

@section('title', 'Danh sách thương hiệu')
{{-- // controller truyền xuống $thuonghieus --}}
{{-- // các route sư dụng thuonghieu.create  thuonghieu.show thuonghieu.edit thuonghieu.destroy   --}}
{{-- $thuonghieus->logo: Link http://148.230.100.215/assets/client/images/brands/tenfilehinhanh.jpg --}}
@section('content')
<div class="page-wrapper">
    <div class="content">

        <div class="page-header">
            <div class="page-title">
                <h4>DANH SÁCH THƯƠNG HIỆU</h4>
                <div class="d-flex align-items-center" style="gap: 2rem;">
                    <h6>
                    Tổng thương hiệu: {{ $thuonghieus->count() }} <br>
                    Hoạt động: {{ $thuonghieus->where('trangthai', 'Hoạt động')->count() }} <br>
                    Tạm khóa: {{ $thuonghieus->where('trangthai', 'Tạm khóa')->count() }} <br>
                    Dừng hoạt động: {{ $thuonghieus->where('trangthai', 'Dừng hoạt động')->count() }}
                    </h6>
                </div>
            </div>
            <div class="page-btn">
                <a href="{{ route('thuonghieu.create') }}" class="btn btn-added">
                    <img src="{{ asset('img/icons/plus.svg') }}" class="me-1" alt="img">
                    Tạo Thương Hiệu
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

        {{-- Bảng danh sách thương hiệu --}}
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table datanew">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên thương hiệu</th>
                                <th>Logo</th>
                                <th>Mô tả</th>
                                <th>Trạng thái</th>
                                <th class="text-center" width="200px">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($thuonghieus as $thuonghieu)
                                <tr>
                                    <td>{{ $thuonghieu->id }}</td>
                                    <td>{{ $thuonghieu->ten }}</td>
                                    <td>
                                        @if($thuonghieu->logo)
                                            <img src="{{ $thuonghieu->logo }}" alt="{{ $thuonghieu->ten }}" width="80px">
                                        @else
                                            <span class="text-muted">Chưa có logo</span>
                                        @endif
                                    </td>
                                    <td style="max-width: 300px; white-space: normal; word-wrap: break-word;">
                                        {{-- {!! nl2br(e($thuonghieu->mota)) !!} --}}
                                        {{-- {!! wordwrap(($thuonghieu->mota), 50, "<br>", true) !!} --}}
                                        {!! nl2br(wordwrap(e($thuonghieu->mota), 50, "<br>", true)) !!}
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = match($thuonghieu->trangthai) {
                                                'Hoạt động' => 'success',
                                                'Tạm khóa' => 'warning',
                                                'Dừng hoạt động' => 'danger',
                                                default => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $badgeClass }}">
                                            {{ $thuonghieu->trangthai }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('thuonghieu.show', $thuonghieu->id) }}" title="Xem chi tiết" class="me-2">
                                            <img src="{{ asset('img/icons/eye.svg') }}" alt="Xem" />
                                        </a>
                                        <a href="{{ route('thuonghieu.edit', $thuonghieu->id) }}" title="Chỉnh sửa" class="me-2">
                                            <img src="{{ asset('img/icons/edit.svg') }}" alt="Sửa" />
                                        </a>
                                        <form action="{{ route('thuonghieu.destroy', $thuonghieu->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa thương hiệu này không?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline" title="Xóa">
                                            <img src="{{ asset('img/icons/delete.svg') }}" alt="Xóa" />
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Không có thương hiệu nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Phân trang --}}
        {{-- <div class="d-flex justify-content-center mt-3">
            {{ $thuonghieus->links() }}
        </div> --}}
    </div>
</div>
@endsection

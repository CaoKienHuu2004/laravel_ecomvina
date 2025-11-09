@extends('layouts.app')

@section('title', 'Danh sách quảng cáo')

{{-- $quangcaos->hinhanh: Link http://148.230.100.215/assets/client/images/bg/tenfilehinhanh.jpg --}}

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Danh sách quảng cáo</h4>
                <h6>Quản lý và cập nhật quảng cáo</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('quangcao.create') }}" class="btn btn-primary">
                    ➕ Thêm quảng cáo mới
                </a>
            </div>
        </div>

        {{-- Hiển thị thông báo thành công --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form tìm kiếm --}}
        <form action="{{ route('quangcao.index') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo vị trí, mô tả, trạng thái..." value="{{ $search ?? '' }}">
                <button class="btn btn-outline-secondary" type="submit">🔍 Tìm kiếm</button>
            </div>
        </form>

        <div class="card">
            <div class="card-body p-0">
                {{-- Bảng dữ liệu --}}
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-primary">
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
                                    <a href="{{ route('quangcao.show', $qc->id) }}" class="btn btn-sm btn-primary" title="Xem chi tiết">
                                        👁️
                                    </a>
                                    <a href="{{ route('quangcao.edit', $qc->id) }}" class="btn btn-sm btn-info" title="Sửa">
                                        ✏️
                                    </a>
                                    <form action="{{ route('quangcao.destroy', $qc->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa quảng cáo này không?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa">🗑️</button>
                                    </form>
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

        {{-- Phân trang --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $quangcaos->links() }}
        </div>
    </div>
</div>
@endsection

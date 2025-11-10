@extends('layouts.app')

@section('title', 'Thùng rác địa chỉ giao hàng | Quản trị hệ thống Siêu Thị Vina')
{{-- // các route sư dụng  diachigiaohang.index, diachigiaohang.restore diachigiaohang.forceDelete --}}

{{-- // controller truyền xuống $diachis --}}
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header d-flex justify-content-between align-items-center mb-4">
            <div class="page-title">
                <h4>Thùng rác địa chỉ giao hàng</h4>
                <h6>Danh sách các địa chỉ đã bị xóa mềm</h6>
            </div>
            <div>
                <a href="{{ route('diachigiaohang.index') }}" class="btn btn-primary">
                    ← Quay lại danh sách
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($diachis->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#ID</th>
                        <th>Họ tên</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Tỉnh/Thành</th>
                        <th>Trạng thái</th>
                        <th>Ngày xóa</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($diachis as $diachi)
                        <tr>
                            <td>{{ $diachi->id }}</td>
                            <td>{{ $diachi->hoten }}</td>
                            <td>{{ $diachi->sodienthoai ?? '-' }}</td>
                            <td style="max-width: 300px;">{{ $diachi->diachi }}</td>
                            <td>{{ $diachi->tinhthanh }}</td>
                            <td>{{ $diachi->trangthai }}</td>
                            <td>{{ $diachi->deleted_at ? $diachi->deleted_at->format('d/m/Y H:i') : '-' }}</td>
                            <td>
                                <form action="{{ route('diachigiaohang.restore', $diachi->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="Khôi phục" onclick="return confirm('Bạn có chắc muốn khôi phục địa chỉ này?');">
                                        Khôi phục
                                    </button>
                                </form>

                                <form action="{{ route('diachigiaohang.forceDelete', $diachi->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Xóa vĩnh viễn" onclick="return confirm('Xóa vĩnh viễn sẽ không thể khôi phục. Bạn có chắc?');">
                                        Xóa vĩnh viễn
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($diachis->hasPages())
            <div class="mt-3">
                {{ $diachis->links() }}
            </div>
        @endif

        @else
            <p class="text-center text-muted">Không có địa chỉ nào trong thùng rác.</p>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
@endsection

@extends('layouts.app')

@section('title')
Chi tiết loại biến thể: {{ $loaibienthe->ten }} | Quản trị hệ thống
@endsection

{{-- // controller truyền xuống $loaibienthe  --}}
{{-- // các route sư dụng  loaibienthe.index loaibienthe.edit    --}}

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Loại biến thể "{{ $loaibienthe->ten }}"</h4>
                <h6>Xem chi tiết thông tin loại biến thể.</h6>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong>Tên loại biến thể:</strong>
                                <span>{{ $loaibienthe->ten }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Trạng thái:</strong>
                                @if ($loaibienthe->trangthai === 'Hiển thị')
                                    <span class="badge bg-success">{{ $loaibienthe->trangthai }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $loaibienthe->trangthai }}</span>
                                @endif
                            </li>
                            <li class="list-group-item">
                                <strong>Số biến thể con (bienthe):</strong>
                                <span>{{ $loaibienthe->bienthe->count() }}</span>
                            </li>
                            @if($loaibienthe->bienthe->isNotEmpty())
                            <li class="list-group-item">
                                <strong>Danh sách biến thể con:</strong>
                                <ul>
                                    @foreach ($loaibienthe->bienthe as $bt)
                                    <li>
                                        <strong>Mã biến thể:</strong> {{ $bt->id }} |
                                        <strong>Tên biến thể:</strong> {{ $bt->ten ?? 'N/A' }} |
                                        <strong>Số lượng:</strong> {{ $bt->soluong ?? 'N/A' }} |
                                        <strong>Giá gốc:</strong> {{ isset($bt->giagoc) ? number_format($bt->giagoc, 0, ',', '.') . ' đ' : 'N/A' }}
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <a href="{{ route('loaibienthe.index') }}" class="btn btn-primary mt-3">Quay lại danh sách</a>
                <a href="{{ route('loaibienthe.edit', $loaibienthe->id) }}" class="btn btn-warning mt-3">Chỉnh sửa loại biến thể</a>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title')
Chi tiết loại biến thể: {{ $loaibienthe->ten }} | Quản trị hệ thống Siêu Thị Vina
@endsection

{{-- // controller truyền xuống $loaibienthe  --}}
{{-- // các route sư dụng  ko sử dụng --- của breadcrumb loaibienthe.index trang-chu   --}}

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <x-header.breadcrumb
                title='Chi tiết loại biến thể "{{ $loaibienthe->ten }}"'
                :links="[
                    ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                    ['label' => 'Danh sách loại biến thể', 'route' => 'loaibienthe.index']
                ]"
                active="Chi tiết"
            />
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
                                <strong>Số biến thể con:</strong>
                                <span>{{ $loaibienthe->bienthe->count() }}</span>
                            </li>

                            @if($loaibienthe->bienthe->isNotEmpty())
                            <li class="list-group-item">
                                <strong>Danh sách biến thể con:</strong>
                                <ul>
                                    @foreach ($loaibienthe->bienthe as $bt)
                                    <li>
                                        <strong>Mã biến thể:</strong> {{ $bt->id }} |
                                        <strong>Tên biến thể:</strong> {{ $bt->sanpham->ten ?? 'N/A' }} |
                                        <strong>Số lượng:</strong> {{ $bt->soluong ?? 'N/A' }} |
                                        <strong>Giá gốc:</strong>
                                        {{ isset($bt->giagoc) ? number_format($bt->giagoc, 0, ',', '.') . ' đ' : 'N/A' }}
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

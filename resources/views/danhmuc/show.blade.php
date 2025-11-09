@extends('layouts.app')

@section('title', 'Chi tiết danh mục | Quản trị hệ thống Siêu Thị Vina')
{{--
    $danhmuc->logo chứa đường dẫn URL đầy đủ, ví dụ:
    http://148.230.100.215/assets/client/images/categories/tenfilehinhanh.jpg
--}}
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Chi tiết danh mục</h4>
                <h6>Xem thông tin chi tiết danh mục sản phẩm</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('danhmuc.index') }}" class="btn btn-secondary">
                    ← Quay lại danh sách
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row align-items-center mb-4">
                    <div class="col-md-3 text-center">
                        @if ($danhmuc->logo && file_exists(public_path(parse_url($danhmuc->logo, PHP_URL_PATH))))
                            <img src="{{ $danhmuc->logo }}" alt="{{ $danhmuc->ten }}" class="rounded img-fluid" style="max-width: 150px;">
                        @else
                            <img src="{{ asset('assets/client/images/categories/danhmuc.jpg') }}" alt="Default" class="rounded img-fluid" style="max-width: 150px;">
                        @endif
                    </div>

                    <div class="col-md-9">
                        <h5 class="fw-bold">{{ $danhmuc->ten }}</h5>
                        <p><strong>Slug:</strong> {{ $danhmuc->slug }}</p>
                        @if(!empty($danhmuc->logo))
                            <div class="mb-3">
                                <a href="{{ $danhmuc->logo }}" rel="noopener noreferrer" target="_blank">
                                    <strong>Tên Logo:</strong> {{ $danhmuc->logo }}
                                </a>
                            </div>
                        @endif
                        <p><strong>Phân loại:</strong> {{ $danhmuc->parent }}</p>
                        <p>
                            <strong>Trạng thái:</strong>
                            <span class="badge {{ $danhmuc->trangthai == 'Hiển thị' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $danhmuc->trangthai }}
                            </span>
                        </p>

                    </div>
                </div>

                <hr>

                <div class="mt-3">
                    <h6>Sản phẩm thuộc danh mục này:</h6>
                    @if ($danhmuc->sanpham->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach ($danhmuc->sanpham as $sp)
                                <li class="list-group-item">
                                    <a href="{{ route('sanpham.show', $sp->id) }}" target="_blank">{{ $sp->ten }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">Chưa có sản phẩm nào trong danh mục này.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

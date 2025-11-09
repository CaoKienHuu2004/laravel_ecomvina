@extends('layouts.app')

@section('title', 'Chi tiết thương hiệu: ' . $thuonghieu->ten)
{{-- $thuonghieu->logo: Link http://148.230.100.215/assets/client/images/brands/tenfilehinhanh.jpg --}}
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header mb-4">
            <div class="page-title">
                <h4>Chi tiết thương hiệu</h4>
                <h6>{{ $thuonghieu->ten }}</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('thuonghieu.index') }}" class="btn btn-secondary">← Quay lại danh sách</a>
                <a href="{{ route('thuonghieu.edit', $thuonghieu->id) }}" class="btn btn-primary">✏️ Chỉnh sửa</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">

                <div class="row mb-3">
                    <label class="col-sm-3 fw-bold">Tên thương hiệu:</label>
                    <div class="col-sm-9">{{ $thuonghieu->ten }}</div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 fw-bold">Slug:</label>
                    <div class="col-sm-9">{{ $thuonghieu->slug }}</div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 fw-bold">Tên Hình Ảnh:</label>
                    <div class="col-sm-9">
                        @if(!empty($thuonghieu->logo))
                            <a href="{{ $thuonghieu->logo }}" rel="noopener noreferrer" target="_blank">
                                <strong>{{ $thuonghieu->logo }}</strong>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 fw-bold">Logo:</label>
                    <div class="col-sm-9">
                        @if ($thuonghieu->logo)
                            <img src="{{ $thuonghieu->logo }}" alt="Logo {{ $thuonghieu->ten }}" style="max-width: 200px; border: 1px solid #ddd; padding: 5px; border-radius: 5px;">
                        @else
                            <span class="text-muted">Chưa có logo</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 fw-bold">Mô tả:</label>
                    <div class="col-sm-9">{!! nl2br(e($thuonghieu->mota)) ?: '<span class="text-muted">Không có mô tả</span>' !!}</div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 fw-bold">Trạng thái:</label>
                    <div class="col-sm-9">
                        <span class="badge
                            @if ($thuonghieu->trangthai == 'Hoạt động') bg-success
                            @elseif ($thuonghieu->trangthai == 'Tạm khóa') bg-warning
                            @else bg-danger
                            @endif
                        ">
                            {{ $thuonghieu->trangthai }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 fw-bold">Số sản phẩm:</label>
                    <div class="col-sm-9">{{ $thuonghieu->sanpham()->count() }}</div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

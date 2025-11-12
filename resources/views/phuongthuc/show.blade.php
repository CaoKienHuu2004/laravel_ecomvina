@extends('layouts.app')

@section('title')
Chi tiết phương thức: {{ $phuongthuc->ten }} | Quản trị hệ thống
@endsection

{{-- controller truyền xuống $phuongthuc --}}
{{-- // các route sư dụng phuongthuc.edit phuongthuc.index --- của breadcrumb phuongthuc.index trang-chu --}}

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-title">
                        <h4>Phương thức thanh toán "{{ $phuongthuc->ten }}"</h4>
                        <h6>Xem chi tiết thông tin phương thức thanh toán.</h6>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('trang-chu') }}">Tổng quan</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('phuongthuc.index') }}">Danh sách Phương Thức</a></li>
                        <li class="breadcrumb-item active">Chi tiết</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="list-unstyled product-bar">
                            <li>
                                <h5><strong>Tên phương thức</strong></h5>
                                <p>{{ $phuongthuc->ten }}</p>
                            </li>
                            <li>
                                <h5><strong>Mã phương thức</strong></h5>
                                <p>{{ $phuongthuc->maphuongthuc ?? '-' }}</p>
                            </li>
                            <li>
                                <h5><strong>Trạng thái</strong></h5>
                                <p>
                                    @if ($phuongthuc->trangthai == 'Hoạt động')
                                        <span class="badge bg-success">{{ $phuongthuc->trangthai }}</span>
                                    @elseif ($phuongthuc->trangthai == 'Tạm khóa')
                                        <span class="badge bg-warning text-dark">{{ $phuongthuc->trangthai }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ $phuongthuc->trangthai }}</span>
                                    @endif
                                </p>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="{{ route('phuongthuc.edit', $phuongthuc->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </a>
                    <a href="{{ route('phuongthuc.index') }}" class="btn btn-secondary">
                        Quay lại danh sách
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

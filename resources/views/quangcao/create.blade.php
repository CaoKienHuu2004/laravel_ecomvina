@extends('layouts.app')

@section('title', 'Thêm quảng cáo mới')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Thêm quảng cáo mới</h4>
                <h6>Nhập thông tin để tạo quảng cáo mới</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('quangcao.index') }}" class="btn btn-secondary">
                    ← Quay lại danh sách
                </a>
            </div>
        </div>

        {{-- Hiển thị lỗi validation --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Vui lòng sửa các lỗi sau:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>⚠️ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('quangcao.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="vitri" class="form-label">Vị trí quảng cáo <span class="text-danger">*</span></label>
                <select name="vitri" id="vitri" class="form-select" required>
                    <option value="">-- Chọn vị trí --</option>
                    <option value="home_banner_slider" {{ old('vitri') == 'home_banner_slider' ? 'selected' : '' }}>Home Banner Slider</option>
                    <option value="home_banner_event_1" {{ old('vitri') == 'home_banner_event_1' ? 'selected' : '' }}>Home Banner Event 1</option>
                    <option value="home_banner_event_2" {{ old('vitri') == 'home_banner_event_2' ? 'selected' : '' }}>Home Banner Event 2</option>
                    <option value="home_banner_event_3" {{ old('vitri') == 'home_banner_event_3' ? 'selected' : '' }}>Home Banner Event 3</option>
                    <option value="home_banner_event_4" {{ old('vitri') == 'home_banner_event_4' ? 'selected' : '' }}>Home Banner Event 4</option>
                    <option value="home_banner_promotion_1" {{ old('vitri') == 'home_banner_promotion_1' ? 'selected' : '' }}>Home Banner Promotion 1</option>
                    <option value="home_banner_promotion_2" {{ old('vitri') == 'home_banner_promotion_2' ? 'selected' : '' }}>Home Banner Promotion 2</option>
                    <option value="home_banner_promotion_3" {{ old('vitri') == 'home_banner_promotion_3' ? 'selected' : '' }}>Home Banner Promotion 3</option>
                    <option value="home_banner_ads" {{ old('vitri') == 'home_banner_ads' ? 'selected' : '' }}>Home Banner Ads</option>
                    <option value="home_banner_product" {{ old('vitri') == 'home_banner_product' ? 'selected' : '' }}>Home Banner Product</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="hinhanh" class="form-label">Hình ảnh quảng cáo <span class="text-danger">*</span></label>
                <input type="file" name="hinhanh" id="hinhanh" class="form-control" accept="image/*" required>
            </div>

            <div class="mb-3">
                <label for="lienket" class="form-label">Liên kết <span class="text-danger">*</span></label>
                <input type="url" name="lienket" id="lienket" class="form-control" value="{{ old('lienket') }}" placeholder="https://example.com" required>
            </div>

            <div class="mb-3">
                <label for="mota" class="form-label">Mô tả <span class="text-danger">*</span></label>
                <textarea name="mota" id="mota" rows="3" class="form-control" required>{{ old('mota') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="trangthai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                <select name="trangthai" id="trangthai" class="form-select" required>
                    <option value="">-- Chọn trạng thái --</option>
                    <option value="Hiển thị" {{ old('trangthai') == 'Hiển thị' ? 'selected' : '' }}>Hiển thị</option>
                    <option value="Tạm ẩn" {{ old('trangthai') == 'Tạm ẩn' ? 'selected' : '' }}>Tạm ẩn</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Lưu quảng cáo</button>
            <a href="{{ route('quangcao.index') }}" class="btn btn-secondary">Hủy bỏ</a>
        </form>
    </div>
</div>
@endsection

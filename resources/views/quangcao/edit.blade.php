@extends('layouts.app')

@section('title', 'Chỉnh sửa quảng cáo')
{{-- $quangcao->hinhanh: Link http://148.230.100.215/assets/client/images/bg/tenfilehinhanh.jpg --}}
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div class="page-title">
                <h4>Chỉnh sửa quảng cáo #{{ $quangcao->id }}</h4>
                <h6>Chỉnh sửa thông tin quảng cáo</h6>
            </div>
            <a href="{{ route('quangcao.index') }}" class="btn btn-secondary">
                ← Quay lại danh sách
            </a>
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

        <form action="{{ route('quangcao.update', $quangcao->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="vitri" class="form-label">Vị trí quảng cáo</label>
                <select name="vitri" id="vitri" class="form-select" required>
                    <option value="">-- Chọn vị trí --</option>
                    @php
                        $positions = [
                            'home_banner_slider', 'home_banner_event_1', 'home_banner_event_2', 'home_banner_event_3', 'home_banner_event_4',
                            'home_banner_promotion_1', 'home_banner_promotion_2', 'home_banner_promotion_3', 'home_banner_ads', 'home_banner_product'
                        ];
                    @endphp
                    @foreach ($positions as $pos)
                        <option value="{{ $pos }}" {{ old('vitri', $quangcao->vitri) === $pos ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $pos)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="hinhanh" class="form-label">Hình ảnh quảng cáo <span class="text-danger">*</span></label>
                @if ($quangcao->hinhanh)
                    <div class="mb-2">
                        <img src="{{ $quangcao->hinhanh }}" alt="Hình ảnh quảng cáo" width="200">
                    </div>
                @endif
                <input type="file" name="hinhanh" id="hinhanh" class="form-control" accept="image/*">
                <small class="text-muted">Nếu không muốn thay đổi hình ảnh, hãy để trống.</small>
            </div>

            <div class="mb-3">
                <label for="lienket" class="form-label">Liên kết <span class="text-danger">*</span></label>
                <input type="url" name="lienket" id="lienket" class="form-control" value="{{ old('lienket', $quangcao->lienket) }}" placeholder="https://example.com" required>
            </div>

            <div class="mb-3">
                <label for="mota" class="form-label">Mô tả <span class="text-danger">*</span></label>
                <textarea name="mota" id="mota" rows="3" class="form-control" required>{{ old('mota', $quangcao->mota) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="trangthai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                <select name="trangthai" id="trangthai" class="form-select" required>
                    <option value="">-- Chọn trạng thái --</option>
                    <option value="Hiển thị" {{ old('trangthai', $quangcao->trangthai) === 'Hiển thị' ? 'selected' : '' }}>Hiển thị</option>
                    <option value="Tạm ẩn" {{ old('trangthai', $quangcao->trangthai) === 'Tạm ẩn' ? 'selected' : '' }}>Tạm ẩn</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật quảng cáo</button>
            <a href="{{ route('quangcao.index') }}" class="btn btn-secondary">Hủy bỏ</a>
        </form>
    </div>
</div>
@endsection

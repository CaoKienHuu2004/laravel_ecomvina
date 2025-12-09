    {{-- resources/views/quanlybaiviet/show.blade.php --}}
    @extends('layouts.app')

    @section('title')
        {{ $baiviet->tieude }} | Bài viết | Quản trị hệ thống Siêu Thị Vina
    @endsection

    @section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Bài viết: "{{ Str::limit($baiviet->tieude, 60) }}"</h4>
                    <h6>Xem chi tiết nội dung bài viết</h6>
                </div>
                <div class="page-btn">
                    <a href="{{ route('baiviet.edit', $baiviet->id) }}" class="btn btn-primary me-2">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </a>
                    <a href="{{ route('baiviet.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại danh sách
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Cột trái: Thông tin chi tiết -->
                <div class="col-lg-8 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="productdetails">
                                <ul class="product-bar">

                                    <li>
                                        <h4><strong>Tiêu đề</strong></h4>
                                        <h6>{{ $baiviet->tieude }}</h6>
                                    </li>

                                   <li>
    <h4><strong>Người đăng</strong></h4>
    <h6>
        @if($baiviet->nguoidung)
            <span class="text-primary">{{ $baiviet->nguoidung->username ?? 'Không xác định' }}</span> <!-- Hoặc $baiviet->nguoidung->name nếu bạn muốn tên đầy đủ -->
        @else
            <span class="text-muted">Không có thông tin</span>
        @endif
    </h6>
</li>
    

                                    <li>
                                        <h4><strong>Trạng thái</strong></h4>
                                        <h6>
                                            @if($baiviet->trangthai === 'Hiển thị')
                                                <span class="badges bg-lightgreen">Hiển thị công khai</span>
                                            @else
                                                <span class="badges bg-lightred">Ẩn</span>
                                            @endif
                                        </h6>
                                    </li>

                                    <li>
                                        <h4><strong>Lượt xem</strong></h4>
                                        <h6>
                                            <span class="text-info fw-bold">{{ number_format($baiviet->luotxem) }}</span> lượt
                                        </h6>
                                    </li>
<li>
    <h4><strong>Ngày tạo</strong></h4>
    {{ $baiviet->created_at instanceof \Carbon\Carbon ? $baiviet->created_at->format('d/m/Y H:i') : 'Chưa có' }}
</li>

<li>
    <h4><strong>Cập nhật lần cuối</strong></h4>
    {{ $baiviet->updated_at instanceof \Carbon\Carbon ? $baiviet->updated_at->format('d/m/Y H:i') : 'Chưa cập nhật' }}
</li>


                                    <li>
                                        <h4><strong>Nội dung bài viết</strong></h4>
                                        <div class="mt-3 p-4 bg-light rounded border" style="min-height: 300px;">
                                            {!! $baiviet->noidung !!}
                                        </div>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cột phải: Ảnh đại diện -->
                <div class="col-lg-4 col-sm-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-image"></i> Ảnh bài viết</h5>
                        </div>
                        <div class="card-body text-center">
                            @if($baiviet->hinhanh)
                                <a href="{{ asset($baiviet->hinhanh) }}" class="image-popup-desc" data-fancybox="gallery">
                                    <img src="{{ asset($baiviet->hinhanh) }}" 
                                        class="img-fluid rounded shadow" 
                                        alt="{{ $baiviet->tieude }}" 
                                        style="max-height: 500px; object-fit: cover;">
                                </a>
                                <div class="mt-3">
                                    <small class="text-muted">{{ basename($baiviet->hinhanh) }}</small>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <img src="{{ asset('img/icons/image-placeholder.svg') }}" alt="No image" style="width: 120px; opacity: 0.5;">
                                    <p class="mt-3 text-muted">Chưa có ảnh đại diện</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Thống kê nhanh -->
                    <div class="card mt-3">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Thống kê nhanh</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span>ID bài viết:</span>
                                <strong>#{{ $baiviet->id }}</strong>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span>Trạng thái:</span>
                                <strong>
                                    @if($baiviet->trangthai === 'Hiển thị')
                                        <span class="text-success">Công khai</span>
                                    @else
                                        <span class="text-danger">Ẩn</span>
                                    @endif
                                </strong>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span>Lượt xem:</span>
                                <strong class="text-primary">{{ number_format($baiviet->luotxem) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css">
    @endsection

    @section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
    <script>
        // Tự động bật popup khi click ảnh
        Fancybox.bind('[data-fancybox="gallery"]', {
            Thumbs: { autoStart: false },
            Image: { zoom: true }
        });
    </script>
    @endsection
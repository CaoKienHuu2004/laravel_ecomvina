@extends('layouts.app')

@section('title', $chuongtrinh->tieude . ' | Chương Trình Sự Kiện')
{{-- // controller truyền xuống $chuongtrinh --}}
{{-- // các route sư dụng  ko có --- của breadcrumb phuongthuc.index trang-chu --}}
{{-- $chuongtrinh->hinhanh: Link http://148.230.100.215/assets/client/images/thumbs/tenfilehinhanh.jpg --}}
{{-- foreach($chuongtrinh)  $quatang->hinhanh: Link http://148.230.100.215/assets/client/images/thumbs/tenfilehinhanh.jpg --}}



@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <x-header.breadcrumb
                    title='Chương trình "{{ $chuongtrinh->tieude }}" '
                    :links="[
                            ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                            ['label' => 'Danh sách Chương Trình Sự Kiện', 'route' => 'chuongtrinh.index']
                    ]"
                    active="Chi tiết"
                />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="program-details">
                            <ul class="list-unstyled">
                                <li>
                                    <h5><strong>Tiêu đề chương trình</strong></h5>
                                    <p>{{ $chuongtrinh->tieude }}</p>
                                </li>
                                <li>
                                    <h5><strong>Trạng thái</strong></h5>
                                    <p>
                                        @if ($chuongtrinh->trangthai == 'Hiển thị')
                                            <span class="badge bg-success">{{ $chuongtrinh->trangthai }}</span>
                                        @elseif ($chuongtrinh->trangthai == 'Tạm ẩn')
                                            <span class="badge bg-warning text-dark">{{ $chuongtrinh->trangthai }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $chuongtrinh->trangthai }}</span>
                                        @endif
                                    </p>
                                </li>
                                <li>
                                    <h5><strong>Ảnh chương trình</strong></h5>
                                    @if ($chuongtrinh->hinhanh)
                                        <img src="{{ $chuongtrinh->hinhanh }}" alt="Ảnh chương trình" style="max-width: 300px; max-height: 180px;">
                                    @else
                                        <p>Chưa có ảnh</p>
                                    @endif
                                </li>
                                <li>
                                    <h5><strong>Nội dung</strong></h5>
                                    <p>{!! nl2br(e($chuongtrinh->noidung)) !!}</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Danh sách Quà Tặng Sự Kiện ({{ $chuongtrinh->quatangsukien->count() }})</h5>
                    </div>
                    <div class="card-body">
                        @if($chuongtrinh->quatangsukien->isEmpty())
                            <p>Chương trình chưa có quà tặng.</p>
                        @else
                            @foreach ($chuongtrinh->quatangsukien as $quatang)
                                <div class="gift-item mb-4 border rounded p-3">
                                    <h6><strong>{{ $quatang->tieude }}</strong></h6>
                                    <p><strong>Thông tin:</strong> {!! nl2br(e($quatang->thongtin)) ?: 'Không có' !!}</p>
                                    <p><strong>Điều kiện:</strong> {{ $quatang->dieukien ?: 'Không có' }}</p>
                                    <p><strong>Ngày bắt đầu:</strong> {{ $quatang->ngaybatdau ? \Carbon\Carbon::parse($quatang->ngaybatdau)->format('d/m/Y') : 'Không xác định' }}</p>
                                    <p><strong>Ngày kết thúc:</strong> {{ $quatang->ngayketthuc ? \Carbon\Carbon::parse($quatang->ngayketthuc)->format('d/m/Y') : 'Không xác định' }}</p>
                                    <p>
                                        <strong>Trạng thái:</strong>
                                        @if ($quatang->trangthai == 'Hiển thị')
                                            <span class="badge bg-success">{{ $quatang->trangthai }}</span>
                                        @elseif ($quatang->trangthai == 'Tạm ẩn')
                                            <span class="badge bg-warning text-dark">{{ $quatang->trangthai }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $quatang->trangthai }}</span>
                                        @endif
                                    </p>
                                    @if ($quatang->hinhanh)
                                        <p><strong>Ảnh quà tặng:</strong></p>
                                        <img src="{{ $quatang->hinhanh }}" alt="Ảnh quà tặng" style="max-width: 250px; max-height: 140px;">
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title')
Chi tiết Thông Báo | Quản trị hệ thống Siêu Thị Vina
@endsection

{{-- // controller truyền xuống $thongbao  --}}
{{-- // các route sư dụng ko có --- của breadcrumb thongbao.index trang-chu  --}}

@section('content')
<div class="page-wrapper">
    <div class="content">

        {{-- === Breadcrumb === --}}
        <div class="page-header">
            <x-header.breadcrumb
                title='Chi Tiết Thông Báo'
                :links="[
                    ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                    ['label' => 'Danh sách thông báo', 'route' => 'thongbao.index']
                ]"
                active="Chi tiết"
            />
        </div>

        <div class="row">
            <div class="col-lg-8 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="productdetails">
                            <ul class="product-bar">

                                <li>
                                    <h4><strong>Tiêu đề</strong></h4>
                                    <h6>{{ $thongbao->tieude }}</h6>
                                </li>

                                <li>
                                    <h4><strong>Nội dung</strong></h4>
                                    <h6>{!! nl2br(e($thongbao->noidung)) !!}</h6>
                                </li>

                                <li>
                                    <h4><strong>Liên kết</strong></h4>
                                    <h6>
                                        @if ($thongbao->lienket)
                                            <a href="{{ $thongbao->lienket }}" target="_blank">
                                                {{ $thongbao->lienket }}
                                            </a>
                                        @else
                                            Không có
                                        @endif
                                    </h6>
                                </li>

                                <li>
                                    <h4><strong>Người nhận</strong></h4>
                                    <h6>
                                        {{ $thongbao->nguoidung->hoten ?? 'Không xác định' }}
                                        <br>
                                        {{ $thongbao->nguoidung->username ?? 'Không xác định' }}
                                        <br>
                                        <small class="text-muted">ID: {{ $thongbao->id_nguoidung }}</small>

                                    </h6>
                                </li>

                                <li>
                                    <h4><strong>Loại Thông Báo</strong></h4>
                                    <h6>
                                        <small class="text-muted">{{ $thongbao->loaithongbao }}</small>

                                    </h6>
                                </li>

                                <li>
                                    <h4><strong>Trạng thái</strong></h4>
                                    <h6>
                                        @if ($thongbao->trangthai == "Đã đọc")
                                            <span class="badges bg-lightgreen">{{ $thongbao->trangthai }}</span>
                                        @elseif ($thongbao->trangthai == "Chưa đọc")
                                            <span class="badges bg-warning text-dark">{{ $thongbao->trangthai }}</span>
                                        @else
                                            <span class="badges bg-lightred">{{ $thongbao->trangthai }}</span>
                                        @endif
                                    </h6>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>



        </div>

    </div>
</div>
@endsection

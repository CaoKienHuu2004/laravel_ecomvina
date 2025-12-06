@extends('layouts.app')

@section('title', 'Danh sách Thông Báo | Quản trị hệ thống Siêu Thị Vina')

{{-- // controller truyền xuống $thongbaos, $thongbaos_admin --}}
{{-- // các route sư dụng thongbao.create  thongbao.show thongbao.edit thongbao.destroy   --}}
{{-- Đối với image default  $thongbao->nguoidung->avatar: Link http://148.230.100.215/assets/client/images/thumbs/khachhang.jpg --}}
{{-- Đối với image được upload qua profile  $thongbao->nguoidung->avatar: Link http://148.230.100.215/storage/assets/client/images/profiles/avatar.jpg --}}


@section('content')
<div class="page-wrapper">
    <div class="content">

        <div class="page-header">
            <div class="page-title">
                <h4>DANH SÁCH THÔNG BÁO</h4>
                <div class="d-flex justify-content-center align-items-center" style="gap: 2rem;">
                    <h6>
                    Tổng thông báo: {{ $thongbaos->count() }} <br>
                    Chưa đọc: {{ $thongbaos->where('trangthai', 'Chưa đọc')->count() }} <br>
                    Đã đọc: {{ $thongbaos->where('trangthai', 'Đã đọc')->count() }} <br>
                    Tạm ẩn: {{ $thongbaos->where('trangthai', 'Tạm ẩn')->count() }}
                    </h6>
                    <h6>
                        Tổng thông báo Admin: {{ $thongbaos_admin->count() }} <br>
                        Chưa đọc: {{ $thongbaos_admin->where('trangthai', 'Chưa đọc')->count() }} <br>
                        Đã đọc: {{ $thongbaos_admin->where('trangthai', 'Đã đọc')->count() }} <br>
                        Tạm ẩn: {{ $thongbaos_admin->where('trangthai', 'Tạm ẩn')->count() }}
                    </h6>
                </div>
            </div>
            <div class="page-btn">
                <a href="{{ route('thongbao.create') }}" class="btn btn-added">
                    <img src="{{ asset('img/icons/plus.svg') }}" class="me-1" alt="img">
                    Tạo Thông Báo
                </a>
            </div>
        </div>

        {{-- FLASH MESSAGE --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        {{-- Tab navigation --}}
        <ul class="nav nav-tabs" id="thongbaoTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="user-tab" data-bs-toggle="tab" data-bs-target="#user" type="button" role="tab" aria-controls="user" aria-selected="true">
                    Thông báo User ({{ $thongbaos->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin" type="button" role="tab" aria-controls="admin" aria-selected="false">
                    Thông báo Admin ({{ $thongbaos_admin->count() }})
                </button>
            </li>
        </ul>

        {{-- Tab content --}}
        <div class="tab-content" id="thongbaoTabContent" style="margin-top:20px;">
            {{-- Tab User --}}
            <div class="tab-pane fade show active" id="user" role="tabpanel" aria-labelledby="user-tab">
                @include('thongbao.partials.table', ['thongbaos' => $thongbaos])
            </div>

            {{-- Tab Admin --}}
            <div class="tab-pane fade" id="admin" role="tabpanel" aria-labelledby="admin-tab">
                @include('thongbao.partials.table', ['thongbaos' => $thongbaos_admin])
            </div>
        </div>



    </div>
</div>
@endsection

@section('scripts')
<style>
    .dt-buttons { display: none !important; }
</style>
@endsection

@extends('layouts.app')

@section('title', 'Danh sách Thông Báo | Quản trị hệ thống Siêu Thị Vina')

{{-- // controller truyền xuống $thongbaos  --}}
{{-- // các route sư dụng thongbao.create  thongbao.show thongbao.edit thongbao.destroy   --}}
{{-- Đối với image default  $thongbao->nguoidung->avatar: Link http://148.230.100.215/assets/client/images/thumbs/khachhang.jpg --}}
{{-- Đối với image được upload qua profile  $thongbao->nguoidung->avatar: Link http://148.230.100.215/storage/assets/client/images/profiles/avatar.jpg --}}


@section('content')
<div class="page-wrapper">
    <div class="content">

        <div class="page-header">
            <div class="page-title">
                <h4>DANH SÁCH THÔNG BÁO</h4>
                <h6>
                    Tổng thông báo: {{ $thongbaos->count() }} <br>
                    Chưa đọc: {{ $thongbaos->where('trangthai', 'Chưa đọc')->count() }} <br>
                    Đã đọc: {{ $thongbaos->where('trangthai', 'Đã đọc')->count() }} <br>
                    Tạm ẩn: {{ $thongbaos->where('trangthai', 'Tạm ẩn')->count() }}
                </h6>
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
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- TABLE --}}
        <div class="card">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table datanew">
                        <thead>
                            <tr>
                                <th>Người nhận</th>
                                <th>Avatar</th>
                                <th>Tiêu đề</th>
                                <th>Nội dung</th>
                                <th>Liên kết</th>
                                <th>Trạng thái</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($thongbaos as $tb)
                            <tr>
                                {{-- Người dùng --}}
                                <td>
                                    <strong>{{ $tb->nguoidung->hoten ?? 'Không xác định' }}</strong><br>
                                    <small>SĐT: {{ $tb->nguoidung->sodienthoai ?? 'N/A' }}</small>
                                </td>

                                {{-- Avatar --}}
                                <td class="text-center">
                                    @if($tb->nguoidung && $tb->nguoidung->avatar)
                                        <img src="{{ $tb->nguoidung->avatar }}"
                                             style="width:45px;height:45px;border-radius:50%;object-fit:cover;">
                                    @else
                                        <img src="{{ asset('img/default_user.png') }}"
                                             style="width:45px;height:45px;border-radius:50%;object-fit:cover;">
                                    @endif
                                </td>

                                {{-- Tiêu đề --}}
                                <td>
                                    {!! wordwrap(e($tb->tieude), 25, "<br>") !!}
                                </td>

                                {{-- Nội dung --}}
                                <td>
                                    <small>{!! wordwrap(e($tb->noidung), 40, "<br>") !!}</small>
                                </td>

                                {{-- Liên kết --}}
                                <td>
                                    @if($tb->lienket)
                                        <a href="{{ $tb->lienket }}" target="_blank" class="text-primary">
                                            Mở liên kết
                                        </a>
                                    @else
                                        <span class="text-muted">Không có</span>
                                    @endif
                                </td>

                                {{-- Trạng thái --}}
                                <td>
                                    @if($tb->trangthai == 'Chưa đọc')
                                        <span class="badge bg-danger">Chưa đọc</span>
                                    @elseif($tb->trangthai == 'Đã đọc')
                                        <span class="badge bg-success">Đã đọc</span>
                                    @elseif($tb->trangthai == 'Tạm ẩn')
                                        <span class="badge bg-secondary">Tạm ẩn</span>
                                    @endif
                                </td>

                                {{-- Hành động --}}
                                <td class="text-center">

                                    <a href="{{ route('thongbao.show', $tb->id) }}"
                                       class="me-2"
                                       title="Xem chi tiết">
                                        <img src="{{ asset('img/icons/eye.svg') }}">
                                    </a>

                                    <a href="{{ route('thongbao.edit', $tb->id) }}"
                                       class="me-2"
                                       title="Chỉnh sửa">
                                        <img src="{{ asset('img/icons/edit.svg') }}">
                                    </a>

                                    <a href="#"
                                       title="Xóa"
                                       onclick="event.preventDefault();
                                       if(confirm('Bạn có chắc muốn xóa thông báo này?')){
                                           document.getElementById('delete-form-{{ $tb->id }}').submit();
                                       }">
                                        <img src="{{ asset('img/icons/delete.svg') }}">
                                    </a>

                                    <form id="delete-form-{{ $tb->id }}"
                                          action="{{ route('thongbao.destroy', $tb->id) }}"
                                          method="POST"
                                          style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

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

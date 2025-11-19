@extends('layouts.app')

@section('title', 'Thùng rác người dùng | Quản trị hệ thống Siêu Thị Vina')
{{-- // controller truyền xuống $nguoidungs  --}}
{{-- // các route sư dụng nguoidung.restore, nguoidung.forceDelete --- của breadcrumb nguoidung.index trang-chu  --}}
{{-- $nguoidungs->avatar: Link http://148.230.100.215/storage/assets/client/images/profiles/tenfilehinhanh.jpg --}}
@section('content')
<div class="page-wrapper">
    <div class="content">

        <div class="page-header">
            <x-header.breadcrumb
                title="Danh sách người dùng đang tạm xóa"
                :links="[
                    ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                    ['label' => 'Danh sách người dùng', 'route' => 'nguoidung.index']
                ]"
                active="Thùng rác"
            />
        </div>

        <div class="card">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table datanew">
                        <thead>
                            <tr>
                                <th>Tên khách hàng</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Số điện thoại</th>
                                <th>Địa chỉ (thành phố)</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($nguoidungs as $nguoidung)
                            <tr>
                                <td class="productimgname">
                                    <a href="javascript:void(0);" class="product-img">
                                        <img src="{{ $nguoidung->avatar ? $nguoidung->avatar : asset('img/avatar-default.png') }}" alt="avatar" />
                                    </a>
                                    <a href="javascript:void(0);">{{ $nguoidung->hoten }}</a>
                                </td>
                                @php
                                    $usernameValue = explode(',', $nguoidung->username)[0] ?? 'Chưa cập nhật';
                                    $emailValue = explode(',', $nguoidung->username)[1] ?? 'Chưa cập nhật';
                                @endphp
                                <td>{{ $usernameValue }}</td>
                                <td>{{ $emailValue }}</td>
                                <td>
                                    @if($nguoidung->diachi->isNotEmpty())
                                        {{ $nguoidung->diachi->first()->diachi }} ({{ $nguoidung->diachi->first()->tinhthanh }})
                                    @else
                                        Chưa có địa chỉ
                                    @endif
                                </td>
                                <td>
                                    <span class="badges bg-lightred">Đã xóa</span>
                                </td>
                                <td>
                                    <form action="{{ route('nguoidung.restore', $nguoidung->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" title="Khôi phục" onclick="return confirm('Bạn có chắc muốn khôi phục người dùng này?')">
                                            <img src="{{ asset('img/icons/restore.svg') }}" alt="restore" />
                                        </button>
                                    </form>

                                    <form action="{{ route('nguoidung.forceDelete', $nguoidung->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Xóa vĩnh viễn" onclick="return confirm('Bạn có chắc muốn xóa vĩnh viễn người dùng này?')">
                                            <img src="{{ asset('img/icons/delete.svg') }}" alt="delete" />
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Không có người dùng nào trong thùng rác.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- <div class="pagination-wrapper">
                    {{ $nguoidungs->links() }}
                </div> --}}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
    .dt-buttons {
        display: none !important;
    }
    .btn-sm {
        padding: 3px 6px;
        font-size: 12px;
    }
    .btn img {
        width: 16px;
        height: 16px;
    }
</style>
@endsection

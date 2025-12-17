@extends('layouts.app')

@section('title')
    Sửa thông báo "{{ $thongbao->tieude }}" | Quản trị hệ thống Siêu Thị Vina
@endsection

{{-- // controller truyền xuống $nguoidungs, $thongbao, $trangthais  --}}
{{-- // các route sư dụng thongbao.update --- của breadcrumb thongbao.index trang-chu  --}}

{{-- selet form và chọn nhiều --}}

@section('content')
<div class="page-wrapper">
    <div class="content">

        <div class="page-header">
            <x-header.breadcrumb
                title="Sửa thông báo '{{ $thongbao->tieude }}'"
                :links="[
                    ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                    ['label' => 'Danh sách thông báo', 'route' => 'thongbao.index']
                ]"
                active="Chỉnh sửa"
            />
        </div>

        {{-- HIỆN THÔNG BÁO --}}
        <div class="error-log">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                </div>
            @endif
        </div>

        <div class="card">
            <div class="card-body">
                <form class="row" action="{{ route('thongbao.update', $thongbao->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- NGƯỜI DÙNG --}}
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Người nhận <span class="text-danger">*</span></label>
                            <select name="id_nguoidung" class="form-select">
                                @foreach($nguoidungs as $nd)
                                    <option value="{{ $nd->id }}"
                                        {{ old('id_nguoidung', $thongbao->id_nguoidung) == $nd->id ? 'selected' : '' }}>
                                        {{ $nd->username }} - {{ $nd->hoten }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_nguoidung')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- TIÊU ĐỀ --}}
                    <div class="col-lg-8 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" name="tieude" class="form-control"
                                   value="{{ old('tieude', $thongbao->tieude) }}" placeholder="Nhập tiêu đề...">
                            @error('tieude')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- NỘI DUNG --}}
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Nội dung <span class="text-danger">*</span></label>
                            <textarea name="noidung" class="form-control" rows="5">{{ old('noidung', $thongbao->noidung) }}</textarea>
                            @error('noidung')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- LIÊN KẾT --}}
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Liên kết (nếu có)</label>
                            <input type="text" name="lienket" class="form-control"
                                   value="{{ old('lienket', $thongbao->lienket) }}" placeholder="https://...">
                            @error('lienket')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- LOẠI THÔNG BÁO --}}
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Loại Thông Báo <span class="text-danger">*</span></label>
                            <select name="loaithongbao" class="form-select">
                                @foreach($loaithongbaos as $ltb)
                                    <option value="{{ $ltb }}"
                                        {{ old('loaithongbao', $thongbao->loaithongbao) == $ltb ? 'selected' : '' }}>
                                        {{ $ltb }}
                                    </option>
                                @endforeach
                            </select>
                            @error('loaithongbao')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- TRẠNG THÁI --}}
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Trạng thái <span class="text-danger">*</span></label>
                            <select name="trangthai" class="form-select">
                                @foreach($trangthais as $tt)
                                    <option value="{{ $tt }}"
                                        {{ old('trangthai', $thongbao->trangthai) == $tt ? 'selected' : '' }}>
                                        {{ $tt }}
                                    </option>
                                @endforeach
                            </select>
                            @error('trangthai')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-submit me-2">Cập nhật</button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
@endsection

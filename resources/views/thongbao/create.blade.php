@extends('layouts.app')

@section('title', 'Tạo Thông Báo | Quản trị hệ thống Siêu Thị Vina')
{{-- // controller truyền xuống $nguoidungs, $trangthais  --}}
{{-- // các route sư dụng thongbao.store --- của breadcrumb thongbao.index trang-chu  --}}
@section('content')
<div class="page-wrapper">




    <div class="content">
        <div class="page-header">
            <x-header.breadcrumb
                title="Tạo Mới Thông Báo"
                :links="[
                    ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                    ['label' => 'Danh sách thông báo', 'route' => 'thongbao.index']
                ]"
                active="Thêm mới"
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
                <form class="row" action="{{ route('thongbao.store') }}" method="POST">
                    @csrf

                    {{-- ID Người dùng --}}
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Người dùng nhận thông báo
                                <span class="text-danger">*</span>
                            </label>
                            <select name="id_nguoidung" class="form-select select">
                                <option value="">-- Chọn người dùng --</option>
                                @foreach($nguoidungs as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->username .' - '. $user->hoten  }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_nguoidung')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Tiêu đề --}}
                    <div class="col-lg-8 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" name="tieude" class="form-control" value="{{ old('tieude') }}" placeholder="Nhập tiêu đề thông báo...">
                            @error('tieude')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Nội dung --}}
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Nội dung <span class="text-danger">*</span></label>
                            <textarea name="noidung" class="form-control" rows="5" placeholder="Nhập nội dung thông báo...">{{ old('noidung') }}</textarea>
                            @error('noidung')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Liên kết --}}
                    <div class="col-lg-6 col-sm-12">
                        <div class="form-group">
                            <label>Liên kết (nếu có)</label>
                            <input type="text" name="lienket" class="form-control" value="{{ old('lienket') }}" placeholder="Đường dẫn chuyển hướng (optional)...">
                            @error('lienket')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Loại Thông Báo --}}
                    <div class="col-lg-6 col-sm-12">
                        <div class="form-group">
                            <label>Loại Thông Báo</label>
                            <select name="loaithongbao" class="form-select">
                                @foreach ($loaithongbaos as $ltb)
                                    <option value="{{ $ltb }}">{{ $ltb }}</option>
                                @endforeach
                            </select>
                            @error('loaithongbao')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    {{-- Trạng thái --}}
                    <div class="col-lg-6 col-sm-12">
                        <div class="form-group">
                            <label>Trạng thái</label>
                            <select name="trangthai" class="form-select">
                                @foreach ($trangthais as $tt)
                                    <option value="{{ $tt }}">{{ $tt }}</option>
                                @endforeach
                            </select>
                            @error('trangthai')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-12 mt-3">
                        <button type="submit" class="btn btn-submit me-2">Tạo Thông Báo</button>
                    </div>

                </form>
            </div>
        </div>

    </div>

</div>
@endsection

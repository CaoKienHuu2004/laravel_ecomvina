@extends('layouts.app')

@section('title', 'Tạo Trang Nội Dung | Quản trị hệ thống Siêu Thị Vina')
{{-- bỏ hinh anh, slug  --}}
@section('content')
<div class="page-wrapper">


    <div class="content">
        <div class="page-header">
            <x-header.breadcrumb
                title="Thêm Trang Nội Dung"
                :links="[
                    ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                    ['label' => 'Danh sách trang nội dung', 'route' => 'trangnoidung.index']
                ]"
                active="Thêm mới"
            />
        </div>

        {{-- HIỂN THỊ THÔNG BÁO --}}
        <div class="error-log">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        <div class="card">
            <div class="card-body">

                <form action="{{ route('trangnoidung.store') }}" method="POST" enctype="multipart/form-data" class="row">
                    @csrf

                    {{-- TIÊU ĐỀ --}}
                    <div class="col-lg-12 col-sm-12">
                        <div class="form-group">
                            <label>Tiêu đề<span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Bắt buộc"> *</span></label>
                            <input type="text" name="tieude" class="form-control"
                                   placeholder="Nhập tiêu đề..." value="{{ old('tieude') }}">
                            @error('tieude') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- SLUG --}}
                    <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                            <label>Slug<span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Bắt buộc"> *</span></label>
                            <input type="text"  class="form-control"
                                   placeholder="Slug của dự án phía client" required>
                        </div>
                    </div>

                    {{-- TRẠNG THÁI --}}
                    <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                            <label>Trạng thái<span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Bắt buộc"> *</span></label>
                            <select name="trangthai" class="form-select">
                                @foreach ($selectbox_trangthai as $tt)
                                    <option value="{{ $tt }}">{{ $tt }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- ẢNH HIỂN THỊ --}}
                    <div class="col-lg-12 col-sm-12">
                        <div class="form-group">
                        <label>Ảnh trang nội dung<span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Bắt buộc"> *</span></label>
                        <div class="image-upload">
                            <input type="file" name="hinhanh" multiple class="form-control" id="hinhanh"/>
                            <div class="image-uploads">
                            <img src="{{ asset('img/icons/upload.svg') }}" alt="img" />
                            <h4>Tải lên file ảnh tại đây.</h4>
                            </div>
                            <!-- <div id="preview-anh" class="mt-2 d-flex flex-wrap"></div> -->


                            @error('hinhanh')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        </div>
                    </div>

                    {{-- MÔ TẢ --}}
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Mô tả<span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Bắt buộc"> *</span></label>
                            <textarea id="mota" name="mota" rows="100"  cols="100" class="form-control">{{ old('mota') }}</textarea>
                            @error('mota') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>


                    {{-- SUBMIT --}}
                    <div class="col-lg-12">
                        <button class="btn btn-submit">Tạo trang nội dung</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
{{-- <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
const editorConfig = {
    toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
};

document.addEventListener("DOMContentLoaded", function () {
    ClassicEditor.create(document.querySelector('#mota'), editorConfig);
});
</script> --}}
@endsection

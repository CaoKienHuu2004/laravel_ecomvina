    {{-- resources/views/quanlybaiviet/edit.blade.php --}}
    @extends('layouts.app')

    @section('title')
        Sửa "{{ Str::limit($baiviet->tieude, 50) }}" | Bài viết | Quản trị hệ thống Siêu Thị Vina
    @endsection

    @section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Sửa "{{ Str::limit($baiviet->tieude, 60) }}"</h4>
                    <h6>Chỉnh sửa bài viết</h6>
                </div>
            </div>

            <!-- Thông báo -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form class="row" action="{{ route('baiviet.update', $baiviet->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Tiêu đề -->
                        <div class="col-lg-8 col-sm-12">
                            <div class="form-group">
                                <label>Tiêu đề bài viết <span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Bắt buộc">*</span></label>
                                <input type="text" name="tieude" class="form-control" value="{{ old('tieude', $baiviet->tieude) }}" placeholder="Nhập tiêu đề..." required>
                                @error('tieude')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <!-- TRẠNG THÁI – ĐÃ FIX 100% KHÔNG CÒN LỖI Data truncated -->
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Trạng thái <span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Bắt buộc">*</span></label>
                                <select class="form-    select" name="trangthai" required>
                                    <option value="Hiển thị" {{ old('trangthai', $baiviet->trangthai) === 'Hiển thị' ? 'selected' : '' }}>
                                        Hiển thị
                                    </option>
                                    <option value="Tạm ẩn" {{ old('trangthai', $baiviet->trangthai) === 'Tạm ẩn' ? 'selected' : '' }}>
                                        Tạm ẩn
                                    </option>
                                </select>
                            </div>
                        </div>
                       <!-- Chọn người viết -->
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Chọn người viết<span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Bắt buộc">*</span></label>
                                <select class="form-select" name="id_nguoidung">
                                <option class="text-secondary">--Chọn người viết--</option>
                                @foreach ($nguoiDung as $nd)
                                    <option value="{{ $nd->id }}">{{ $nd->username }}</option>
                                @endforeach
                                @error('id_nguoidung')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                </select>
                            </div>
                        </div>


                        <!-- Ảnh hiện tại -->
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Ảnh hiện tại</label>
                                <div class="product-list mb-3">
                                    <ul class="row">
                                        @if($baiviet->hinhanh)
                                            <li>
                                                <div class="productviews">
                                                    <div class="productviewsimg">
                                                        <img src="{{ asset($baiviet->hinhanh) }}" alt="Ảnh bài viết">
                                                    </div>
                                                    <div class="productviewscontent">
                                                        <div class="productviewsname">
                                                            <h2>{{ basename($baiviet->hinhanh) }}</h2>
                                                            <h3>Ảnh hiện tại</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                    </ul>
                                </div>

                                <!-- Upload ảnh mới -->
                                <label>Thay ảnh mới (không bắt buộc)</label>
                                <div class="image-upload">
                                    <input type="file" name="hinhanh" accept="image/*">
                                    <div class="image-uploads">
                                        <img src="{{ asset('img/icons/upload.svg') }}" alt="upload">
                                        <h4>Kéo & thả hoặc click để upload</h4>
                                    </div>
                                </div>
                                @error('hinhanh')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <!-- Nội dung bài viết -->
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Nội dung bài viết <span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Bắt buộc">*</span></label>
                                <textarea name="noidung" id="noidung_edit" class="form-control">{!! old('noidung', $baiviet->noidung) !!}</textarea>
                                @error('noidung')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <!-- Nút hành động -->
                        <div class="col-lg-12 mt-4 text-end">
                            <a href="{{ route('baiviet.index') }}" class="btn btn-cancel me-2">Hủy bỏ</a>
                            <button type="submit" class="btn btn-submit">
                                <i class="fas fa-save me-1"></i> Cập nhật bài viết
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @section('styles')
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/41.0.0/ckeditor5.css">
    @endsection

    @section('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#noidung_edit'), {
                toolbar: [  
                    'heading', '|',
                    'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote',
                    'insertTable', 'mediaEmbed', 'undo', 'redo'
                ],
                height: 400
            })
            .catch(error => console.error(error));
    </script>
    @endsection
@extends('layouts.app')

@section('title', 'Thêm Mới Quà Tặng Sự Kiện')

@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <x-header.breadcrumb
                title="Thêm Mới Quà Tặng Sự Kiện"
                :links="[
                    ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                    ['label' => 'Danh sách Quà Tặng Sự Kiện', 'route' => 'quatangsukien.index']
                ]"
                active="Thêm mới"
            />
        </div>

        {{-- Hiển thị lỗi validate --}}
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('quatangsukien.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">

                {{-- ================== Thông tin quà tặng ================== --}}
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4>Thông tin quà tặng</h4>
                        </div>

                        <div class="card-body">

                            <div class="mb-3">
                                <label>Thuộc Chương Trình Sự Kiện:</label>

                                <select class="form-control" name="id_chuongtrinh" id="chuongtrinh_id">
                                    @foreach ($chuongtrinhs as $ct)
                                        <option value="{{ $ct->id }}" data-img="{{ $ct->hinhanh }}">
                                        {{ $ct->tieude }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Tiêu đề quà tặng:</label>
                                <input type="text" name="tieude" class="form-control" value="{{ old('tieude') }}" required>
                            </div>

                            <div class="mb-3">
                                <label>Điều kiện:</label>
                                <input type="text" name="dieukien" class="form-control" value="{{ old('dieukien') }}">
                            </div>


                            <div class="mb-3">
                                <label>Ngày Bắt Đầu:</label>
                                <input type="datetime-local" name="ngaybatdau" class="form-control" value="{{ old('ngaybatdau') }}">
                            </div>

                            <div class="mb-3">
                                <label>Ngày Kết Thúc:</label>
                                <input type="datetime-local" name="ngayketthuc" class="form-control" value="{{ old('ngayketthuc') }}">
                            </div>

                            <div class="mb-3">
                                <label>Trạng thái:</label>
                                <select name="trangthai" class="form-select">
                                    @foreach ($trangthais as $tt)
                                        <option value="{{ $tt }}">{{ $tt }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Thông tin quà tặng --}}
                            <div class="mb-3">
                                <label>Thông tin <span class="text-danger">*</span></label>
                                <textarea name="thongtin" id="thongtin" class="form-control">{{ old('thongtin') }}</textarea>
                            </div>

                            {{-- Ảnh --}}
                            <div class="mb-3">
                                <label>Ảnh quà tặng:</label>
                                <div class="image-upload">
                                    <input type="file" name="hinhanh" class="d-none" id="hinhanh-input" accept="image/*">
                                    <label for="hinhanh-input"
                                        class="upload-label d-flex align-items-center justify-content-center border rounded"
                                        style="cursor:pointer; height:150px; background:#f8f9fa;">
                                        <img src="{{ asset('img/icons/upload.svg') }}" style="width:50px; margin-right:10px;">
                                        <span>Bấm để tải ảnh lên</span>
                                    </label>
                                    <div id="preview-hinhanh" class="mt-2"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                {{-- ================== Chọn biến thể sản phẩm ================== --}}
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4>Chọn Biến Thể Sản Phẩm</h4>
                        </div>

                        <div class="card-body">

                            {{-- Input hiển thị biến thể đã chọn --}}
                            <div class="mb-3">
                                <input type="hidden" name="id_bienthe" id="id_bienthe">
                                <input type="text" id="bienthe_name" class="form-control" placeholder="Chưa chọn biến thể" readonly>
                            </div>

                            {{-- Bảng chọn biến thể --}}
                            <div class="table-responsive">
                                <table class="table datanew">
                                    <thead>
                                        <tr>
                                            <th>Chọn</th>
                                            <th>Sản phẩm</th>
                                            <th>Loại biến thể</th>
                                            <th>Giá gốc</th>
                                            <th>Số lượng</th>
                                            <th>Lượt tặng</th>
                                            <th>Lượt bán</th>
                                            <th>Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bienthes as $item)
                                        <tr>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm selectVariant"
                                                    data-id="{{ $item->id }}"
                                                    data-name="{{ $item->sanpham->ten }} - {{ $item->loaibienthe->ten }}">
                                                    Chọn
                                                </button>
                                            </td>

                                            <td class="productimgname">
                                                @php $image = $item->sanpham->hinhanhsanpham->first(); @endphp
                                                <a class="product-img">
                                                    <img src="{{ asset($image->hinhanh ?? '') }}">
                                                </a>
                                                 <a>{!! wordwrap($item->sanpham->ten, 30, '<br>') !!}</a>
                                            </td>


                                            <td>{{ $item->loaibienthe->ten }}</td>
                                            <td>{{ number_format($item->giagoc) }} đ</td>
                                            <td>{{ $item->soluong }}</td>
                                            <td>{{ $item->luottang }}</td>
                                            <td>{{ $item->luotban }}</td>

                                            <td>
                                                @if ($item->trangthai == 'Hết hàng')
                                                    <span class="badge bg-danger">Hết hàng</span>
                                                @elseif ($item->trangthai == 'Sắp hết hàng')
                                                    <span class="badge bg-warning">Sắp hết</span>
                                                @else
                                                    <span class="badge bg-success">Còn hàng</span>
                                                @endif
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

            <div class="text-center mb-4">
                <button type="submit" class="btn btn-success">Tạo Quà Tặng</button>
            </div>

        </form>

    </div>
</div>
@endsection


@section('scripts')
<script>
    // Chọn biến thể
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.selectVariant').forEach(btn => {
            btn.addEventListener('click', function() {
                let id = this.dataset.id;
                let name = this.dataset.name;

                document.getElementById('id_bienthe').value = id;
                document.getElementById('bienthe_name').value = name;

                alert("Đã chọn biến thể: " + name);
            });
        });
    });

    // Preview ảnh
    document.getElementById('hinhanh-input').addEventListener('change', function() {
        const preview = document.getElementById('preview-hinhanh');
        preview.innerHTML = '';

        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" style="max-width:200px;">`;
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    // CKEditor
    ClassicEditor
        .create(document.querySelector('#thongtin'))
        .catch(error => console.error(error));


    function formatOption (option) {
        if (!option.id) { return option.text; }
        var imgUrl = $(option.element).data('img');
        if(imgUrl){
            return $('<span><img src="' + imgUrl + '" style="width:20px; height:20px; margin-right:5px;" /> ' + option.text + '</span>');
        }
        return option.text;
        }

    $(document).ready(function() {
        $('#chuongtrinh_id').select2({
            templateResult: formatOption,
            templateSelection: formatOption,
        });
    });
</script>
@endsection

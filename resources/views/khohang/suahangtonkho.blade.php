@extends('layouts.app')

@section('title')
    Sửa hàng tồn cho "{{ $bienthe->sanpham->ten }}" | Sản phẩm | Quản trị hệ thống Siêu Thị Vina
@endsection

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Sửa hàng tồn cho sản phẩm"{{ $bienthe->sanpham->ten }}"</h4>
                <h6>Chỉnh sửa thông tin hàng tồn kho trong sản phẩm của bạn.</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form class="row" action="{{ route('cap-nhat-hang-ton-kho',$bienthe->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div id="bienthe-wap">
                        <div class="bienthe-item row mb-2" data-old-id="{{ $bienthe->id }}">
                            <input type="hidden" name="id_sanpham" value="{{ $bienthe->sanpham->id }}">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <label>Loại hàng <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select class="form-select sua_bienthe_tonkho" name="id_tenloai">
                                        @foreach($loaibienthes as $loai)
                                        <option value="{{ $loai->id }}"
                                            {{ $bienthe->id_tenloai == $loai->id ? 'selected' : '' }}>{{ $loai->ten }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <label>Giá hàng <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" name="gia" value="{{ $bienthe->gia }}"
                                        class="form-control" />
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <label>Số lượng hàng <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" name="soluong" value="{{ $bienthe->soluong }}"
                                        class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('.sua_bienthe_tonkho').select2({
    tags: true,   // Cho phép nhập thêm
    placeholder: "Chọn hoặc nhập tên loại biến thể",
});
</script>
@endsection

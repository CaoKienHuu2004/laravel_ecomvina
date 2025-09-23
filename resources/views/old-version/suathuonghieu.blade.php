@extends('layouts.app')

@section('title')
    Sửa "{{ $thuonghieu->ten }}" | Thương hiệu | Quản trị hệ thống Siêu Thị Vina
@endsection

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Sửa thương hiệu "{{ $thuonghieu->ten }}"</h4>
                <h6>Chỉnh sửa thông tin thương hiệu sản phẩm</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form class="row" action="{{ route('cap-nhat-thuong-hieu',$thuonghieu->id) }}" method="POST">
                    @csrf
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Tên thương hiệu <span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Bắt buộc">*</span></label>
                            <input type="text" name="ten" class="form-control" value="{{ old('ten', $thuonghieu->ten) }}"/>
                            @error('ten')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Trạng thái</label>
                            <select class="form-select" name="trangthai">
                                <option value="0" {{ old('trangthai')==0?'selected':'' }}>Hiển thị</option>
                                <option value="1" {{ old('trangthai')==1?'selected':'' }}>Ẩn</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Mô tả <span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Bắt buộc">*</span></label>
                            <textarea name="mota" id="motathuonghieu">{!! old('mota', $thuonghieu->mota) !!}</textarea>
                            @error('mota')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-submit" title="Cập nhật">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        ClassicEditor
            .create(document.querySelector('#motathuonghieu'), editorConfig)
            .catch(error => {
                console.error(error);
            });
    });
</script>
@endsection

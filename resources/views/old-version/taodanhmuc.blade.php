@extends('layouts.app')

@section('title', 'Tạo danh mục | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Product Add Category</h4>
                <h6>Create new product Category</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form class="row" action="{{ route('luu-danh-muc') }}" method="POST">
                    @csrf
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Tên danh mục <span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Bắt buộc">*</span></label>
                            <input type="text" name="ten" class="form-control" />
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
                        <button type="submit" class="btn btn-submit" title="Tạo danh mục">Tạo danh mục</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

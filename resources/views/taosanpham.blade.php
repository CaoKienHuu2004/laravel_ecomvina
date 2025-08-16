@extends('layouts.app')

@section('title', 'Tạo sản phẩm | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>Tạo sản phẩm</h4>
        <h6>Tạo mới một sản phẩm của bạn</h6>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-lg-4 col-sm-6 col-12">
            <div class="form-group">
              <label>Tên sản phẩm</label>
              <input type="text" />
            </div>
          </div>
          <div class="col-lg-4 col-sm-6 col-12">
            <div class="form-group">
              <label>Danh mục</label>
              <select class="select">
                <option>Chọn danh mục</option>
                <option>Computers</option>
              </select>
            </div>
          </div>
          <div class="col-lg-4 col-sm-6 col-12">
            <div class="form-group">
              <label>Thương hiệu</label>
              <select class="select">
                <option>Chọn thương hiệu</option>
                <option>Fruits</option>
              </select>
            </div>
          </div>
          <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
              <label>Nơi xuất xứ</label>
              <input type="text" />
            </div>
          </div>
          <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
              <label>Nơi sản xuất</label>
              <input type="text" />
            </div>
          </div>
          <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
              <label>Áp dụng voucher</label>
              <select class="select">
                <option>không có</option>
                <option>10%</option>
                <option>20%</option>
              </select>
            </div>
          </div>
          <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
              <label>Trạng thái</label>
              <select class="select">
                <option>Còn hàng</option>
                <option>Hết hàng</option>
              </select>
            </div>
          </div>
          <div class="col-lg-12">
            <div class="form-group">
              <label>Mô tả sản phẩm</label>
              <textarea class="form-control" name="mo_ta" id="mo_ta"></textarea>
            </div>
          </div>

          <div id="bienthe-wap">
            <label>Biến thể sản phẩm</label>
            <div class="row">
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <select class="select">
                    <option>--Loại biến thể--</option>
                    <option>hộp</option>
                    <option>Lọ</option>
                  </select>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <input type="text" name="bienthe[0][gia]" placeholder="Giá" />
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <input type="text" name="bienthe[0][soluong]" placeholder="Số lượng" />
                </div>
              </div>
            </div>

          </div>

          <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
              <label>Áp dụng voucher</label>
              <select class="select">
                <option>không có</option>
                <option>10%</option>
                <option>20%</option>
              </select>
            </div>
          </div>

          <div class="col-lg-12">
            <div class="form-group">
              <label>Ảnh sản phẩm</label>
              <div class="image-upload">
                <input type="file" />
                <div class="image-uploads">
                  <img src="assets/img/icons/upload.svg" alt="img" />
                  <h4>Tải lên file ảnh tại đây.</h4>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-12">
            <a href="" class="btn btn-submit me-2">Tạo sản phẩm</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
  CKEDITOR.replace('mo_ta');
</script>
@endsection
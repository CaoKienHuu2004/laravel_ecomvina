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
        <form class="row" action="" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="col-lg-4 col-sm-6 col-12">
            <div class="form-group">
              <label>Tên sản phẩm</label>
              <input type="text" name="tensp" id="tensp" value="{{old('tensp')}}"/>
              @error('tensp')
                  <span class="text-danger">{{ $message }}</span>
              @enderror
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

            <div class="bienthe-item row mb-2">
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <select class="form-select" name="bienthe[0][id_tenloai]">
                            <option>--Loại biến thể--</option>
                            <option value="hop">Hộp</option>
                            <option value="lo">Lọ</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <input type="text" name="bienthe[0][gia]" placeholder="Giá (*vd: 24000)" />
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <input type="text" name="bienthe[0][soluong]" placeholder="Số lượng (*vd: 10)" />
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <button type="button" class="btn btn-outline-danger remove-btn" title="Xóa">X</button>
                </div>
            </div>

            <button class="btn btn-primary mb-4" type="button" id="add-bienthe">+ Thêm biến thể</button>
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
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
  ClassicEditor
        .create(document.querySelector('#mo_ta'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote']
        })
        .catch(error => {
            console.error(error);
        });
</script>

<script>
    let index = 1;

    function updateRemoveButtons() {
        const items = document.querySelectorAll('#bienthe-wap .bienthe-item .remove-btn');
        if (items.length === 1) {
            items[0].style.display = "none"; // ẩn nút xóa nếu chỉ có 1 biến thể
        } else {
            items.forEach(btn => btn.style.display = "inline-block"); // hiện lại nếu >1
        }
    }

    // Thêm biến thể
    document.getElementById('add-bienthe').addEventListener('click', function() {
        let wrapper = document.getElementById('bienthe-wap');
        let btnAdd = document.getElementById('add-bienthe');
        let html = `
        <div class="bienthe-item row mb-2">
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                    <select class="form-select" name="bienthe[${index}][id_tenloai]">
                        <option>--Loại biến thể--</option>
                        <option value="hop">Hộp</option>
                        <option value="lo">Lọ</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                    <input type="text" name="bienthe[${index}][gia]" placeholder="Giá" />
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                    <input type="text" name="bienthe[${index}][soluong]" placeholder="Số lượng" />
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <button type="button" class="btn btn-outline-danger remove-btn" title="Xóa">X</button>
            </div>
        </div>`;
        // chèn biến thể MỚI ngay TRƯỚC nút "Thêm biến thể"
        btnAdd.insertAdjacentHTML('beforebegin', html);
        index++;
        updateRemoveButtons();
    });

    // Xóa biến thể
    document.getElementById('bienthe-wap').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-btn')) {
            e.target.closest('.bienthe-item').remove();
            updateRemoveButtons();
        }
    });

    // chạy lần đầu để ẩn nút xóa (nếu chỉ có 1 biến thể ban đầu)
    updateRemoveButtons();
</script>


@endsection
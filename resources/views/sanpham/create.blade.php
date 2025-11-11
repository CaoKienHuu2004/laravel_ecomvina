@extends('layouts.app')

@section('title', 'Tạo sản phẩm | Quản trị hệ thống Siêu Thị Vina')
{{-- // controller truyền xuống $thuonghieus,$danhmucs loaibienthes $selectbox_sanpham_trangthais  --}}
{{-- // các route sư dụng sanpham.store  --}}

{{-- trường trangthai giá trị mới 'Công khai','Chờ duyệt','Tạm ẩn','Tạm khóa' --}}

{{-- bản củ // controller truyền xuống $cuaHang,$danhmucs loaibienthes  --}}
{{--  luu-san-pham   --}}
@section('content')
<div class="page-wrapper">



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

  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>Tạo sản phẩm</h4>
        <h6>Tạo mới một sản phẩm của bạn</h6>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <form class="row" action="{{ route('sanpham.store') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="col-lg-4 col-sm-6 col-12">
            <div class="form-group">
              <label>Tên sản phẩm <span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Bắt buộc">*</span></label>
              <input class="form-control" type="text" name="tensp" id="tensp" value="{{ old('tensp') }}" placeholder="tên sản phẩm..."/>
              @error('tensp')
                  <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="col-lg-4 col-sm-6 col-12">
            <div class="form-group">
              <label>Danh mục <span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Bắt buộc">*</span></label>
              <select class="form-control select" name="id_danhmuc[]" id="id_danhmuc" multiple>
                @foreach($danhmucs as $dm)
                  <option value="{{ $dm->id }}">
                    {{ $dm->ten }}
                  </option>
                @endforeach
                @error('id_danhmuc') <span class="text-danger">{{ $message }}</span> @enderror
              </select>
            </div>
          </div>

          <div class="col-lg-4 col-sm-6 col-12">
            <div class="form-group">
              <label>Thương Hiệu<span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Bắt buộc">*</span></label>
              <select class="form-select" name="id_thuonghieu">
                <option class="text-secondary">--Chọn Thương Hiệu--</option>
                @foreach ($thuonghieus as $th)
                    <option value="{{ $th->id }}">{{ $th->ten }}</option>
                @endforeach
                @error('id_thuonghieu')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </select>
            </div>
          </div>

          <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
              <label>Nơi xuất xứ <span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Bắt buộc">*</span></label>
              <input type="text" name="xuatxu" value="{{ old('xuatxu') }}" class="form-control" placeholder="xuất xứ ở..."/>
              @error('xuatxu')
                  <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
              <label>Nơi sản xuất <span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Bắt buộc">*</span></label>
              <input type="text" name="sanxuat" value="{{ old('sanxuat') }}" class="form-control" placeholder="sản xuất tại..."/>
              @error('sanxuat')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
          </div>

          <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
              <label>Giảm Giá(%)</label>
              <input type="number" class="form-control" min="0" max="100" step="1" name="giamgia" placeholder="Quy định giảm giá theo %..."/>
              @error('giamgia')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
          </div>
          <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
              <label>Lượt Xem</label>
              <input readonly disabled type="number" class="form-control" min="0" step="1" name="luotxem" placeholder="Mặc định 0..."/>
            </div>
          </div>
          <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
              <label>Slug</label>
              <input readonly type="text" disabled name="slug" placeholder="Tự động sinh theo tên sản phẩm..."/>
            </div>
          </div>


            {{-- 'Công khai','Chờ duyệt','Tạm ẩn','Tạm khóa' --}}
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                    <label>Trạng thái</label>
                    <select class="form-select" name="trangthai">
                        @foreach ($selectbox_sanpham_trangthais as $trangthai)
                            <option value="{{ $trangthai }}">
                                {{ $trangthai }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

          <div class="col-lg-12">
            <div class="form-group">
              <label>Mô tả sản phẩm <span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Bắt buộc">*</span></label>
              <textarea name="mo_ta" id="mo_ta" class="form-control">{{ old('mo_ta') }}</textarea>
              @error('mo_ta')
                  <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div id="bienthe-wap">
            <label>Biến thể sản phẩm <span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Bắt buộc">*</span></label>

            <div class="bienthe-item row mb-2">
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <select class="form-control loai_bienthe" name="bienthe[0][id_tenloai]">
                            <option>--Loại biến thể--</option>
                            @foreach($loaibienthes as $loai)
                                <option value="{{ $loai->id }}">{{ $loai->ten }}</option>
                            @endforeach
                        </select>
                        @error('bienthe.0.id_tenloai')
                          <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <input type="text" name="bienthe[0][gia]" placeholder="Giá (*vd: 24000)" />
                        @error('bienthe.0.gia')
                          <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <input type="text" name="bienthe[0][soluong]" placeholder="Số lượng (*vd: 10)" />
                        @error('bienthe.0.soluong')
                          <span class="text-danger">{{ $message }}</span>
                        @enderror
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
              <label>Ảnh sản phẩm<span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Bắt buộc"> *</span></label>
              <div class="image-upload">
                <input type="file" name="anhsanpham[]" multiple class="form-control" id="anhsanpham"/>
                <div class="image-uploads">
                  <img src="{{ asset('img/icons/upload.svg') }}" alt="img" />
                  <h4>Tải lên file ảnh tại đây.</h4>
                </div>
                  <!-- <div id="preview-anh" class="mt-2 d-flex flex-wrap"></div> -->


                @error('anhsanpham.*')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>
          </div>
          <div class="col-12">
                  <div class="product-list">
                    <ul class="row" id="preview-anh">
                      <!-- <li>
                        <div class="productviews">
                          <div class="productviewsimg">
                            <img src="assets/img/icons/macbook.svg" alt="img" />
                          </div>
                          <div class="productviewscontent">
                            <div class="productviewsname">
                              <h2>macbookpro.jpg</h2>
                              <h3>581kb</h3>
                            </div>
                            <a href="javascript:void(0);" class="hideset">x</a>
                          </div>
                        </div>
                      </li> -->
                    </ul>
                  </div>
                </div>

          <div class="col-lg-12">
            <button type="submit" class="btn btn-submit me-2" title="Tạo sản phẩm">Tạo sản phẩm</button>
          </div>
          </form>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  /**
 * This configuration was generated using the CKEditor 5 Builder. You can modify it anytime using this link:
 * https://ckeditor.com/ckeditor-5/builder/?redirect=portal#installation/NoNgNARATAdA7DKFIhATgIwBY0gKwZxwAccaZW6xIWeeUAzFgAzEFZT4jIQBuAlsmZhgGMMOFjJAXUhYAZgEM089BGlA
 */

ClassicEditor.create(document.querySelector('#mo_ta'), editorConfig);

</script>

<script>
    let index = 1;
    const loaibienthe = @json($loaibienthes); // dữ liệu từ DB
    console.log(loaibienthe);
    function updateRemoveButtons() {
        const items = document.querySelectorAll('#bienthe-wap .bienthe-item .remove-btn');
        items.forEach(btn => btn.style.display = items.length === 1 ? 'none' : 'inline-block');
    }

    // Thêm biến thể
    document.getElementById('add-bienthe').addEventListener('click', function() {
        let btnAdd = document.getElementById('add-bienthe');

        // Tạo options từ DB
        let options = '<option value="">--Loại biến thể--</option>';
        loaibienthe.forEach(loai => {
            options += `<option value="${loai.id}">${loai.ten}</option>`;
        });

        let html = `
        <div class="bienthe-item row mb-2">
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                    <select class="form-select loai_bienthe select2" name="bienthe[${index}][id_tenloai]">
                        ${options}
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                    <input type="text" name="bienthe[${index}][gia]" placeholder="Giá (*vd: 24000)" class="form-control"/>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                    <input type="text" name="bienthe[${index}][soluong]" placeholder="Số lượng (*vd: 10)" class="form-control"/>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <button type="button" class="btn btn-outline-danger remove-btn">X</button>
            </div>
        </div>`;

        // Sau khi thêm html vào DOM
        btnAdd.insertAdjacentHTML('beforebegin', html);
        $(`.loai_bienthe`).select2({
            tags: true,
            placeholder: "--Loại biến thể--",
            allowClear: true
        });

        index++;
        updateRemoveButtons();
    });

    // Xóa biến thể
    document.getElementById('bienthe-wap').addEventListener('click', function(e) {
        if(e.target.classList.contains('remove-btn')) {
            e.target.closest('.bienthe-item').remove();
            updateRemoveButtons();
        }
    });

    // Chạy lần đầu để ẩn nút xóa nếu chỉ có 1 biến thể
    updateRemoveButtons();
</script>

<style>
    #preview-anh li .productviewsname h2 {
    white-space: nowrap;      /* không xuống dòng */
    overflow: hidden;         /* ẩn phần vượt quá */
    text-overflow: ellipsis;  /* hiển thị dấu ... */
    max-width: 200px;         /* điều chỉnh theo width thumbnail */
}
</style>
<script>
let selectedFiles = [];

document.getElementById('anhsanpham').addEventListener('change', function(e) {
    selectedFiles = [...selectedFiles, ...e.target.files];
    renderPreview();
});

function renderPreview() {
    const preview = document.getElementById('preview-anh');
    preview.innerHTML = '';

    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(event) {
            const li = document.createElement('li');

            li.innerHTML = `
                <div class="productviews">
                    <div class="productviewsimg">
                        <img src="${event.target.result}" alt="img" />
                    </div>
                    <div class="productviewscontent">
                        <div class="productviewsname">
                            <h2>${file.name}</h2>
                            <h3>${(file.size / 1024).toFixed(1)}kb</h3>
                        </div>
                        <a href="javascript:void(0);" class="hideset">x</a>
                    </div>
                </div>
            `;

            // Xử lý nút xóa
            li.querySelector('.hideset').addEventListener('click', function() {
                selectedFiles.splice(index, 1);
                renderPreview();
            });

            preview.appendChild(li);
        }
        reader.readAsDataURL(file);
    });

    // Cập nhật lại input để submit đúng
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(file => dataTransfer.items.add(file));
    document.getElementById('anhsanpham').files = dataTransfer.files;
}

</script>
<script>
    $('.loai_bienthe').select2({
    tags: true,   // Cho phép nhập thêm
    placeholder: "Chọn hoặc nhập tên loại biến thể",
});
</script>

@endsection

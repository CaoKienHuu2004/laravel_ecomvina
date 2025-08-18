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
        <form class="row" action="{{ route('luu-san-pham') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="col-lg-4 col-sm-6 col-12">
            <div class="form-group">
              <label>Tên sản phẩm*</label>
              <input class="form-control" type="text" name="tensp" id="tensp" value="{{old('tensp')}}" placeholder="tên sản phẩm..."/>
              @error('tensp')
                  <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="col-lg-4 col-sm-6 col-12">
            <div class="form-group">
              <label>Danh mục*</label>
              <select class="form-select" name="id_danhmuc">
                <option class="text-secondary">--Chọn danh mục--</option>
                @foreach ($danhmucs as $dm)
                    <option value="{{ $dm->id }}">{{ $dm->ten }}</option>
                @endforeach
                @error('id_danhmuc')
                  <span class="text-danger">{{ $message }}</span> 
                @enderror
              </select>
            </div>
          </div>

          <div class="col-lg-4 col-sm-6 col-12">
            <div class="form-group">
              <label>Thương hiệu*</label>
              <select class="form-select" name="id_thuonghieu">
                <option class="text-secondary">--Chọn thương hiệu--</option>
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
              <label>Nơi xuất xứ*</label>
              <input type="text" name="xuatxu" value="{{ old('xuatxu') }}" class="form-control" placeholder="xuất xứ ở..."/>
              @error('xuatxu')
                  <span class="text-danger">{{ $message }}</span> 
              @enderror
            </div>
          </div>

          <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
              <label>Nơi sản xuất*</label>
              <input type="text" name="sanxuat" value="{{ old('sanxuat') }}" class="form-control" placeholder="sản xuất tại..."/>
              @error('sanxuat')
                  <span class="text-danger">{{ $message }}</span> 
                @enderror
            </div>
          </div>

          <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
              <label>Video giới thiệu sản phẩm</label>
              <input type="text" name="mediaurl" placeholder="Url Youtube..."/>
              @error('mediaurl')
                  <span class="text-danger">{{ $message }}</span> 
                @enderror
            </div>
          </div>

          <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
              <label>Trạng thái</label>
              <select class="form-select" name="trangthai">
                <option value="0" {{ old('trangthai')==0?'selected':'' }}>Còn hàng</option>
                <option value="1" {{ old('trangthai')==1?'selected':'' }}>Hết hàng</option>
              </select>
            </div>
          </div>

          <div class="col-lg-12">
            <div class="form-group">
              <label>Mô tả sản phẩm*</label>
              <textarea name="mo_ta" id="mo_ta" class="form-control">{{ old('mo_ta') }}</textarea>
              @error('mo_ta')
                  <span class="text-danger">{{ $message }}</span> 
              @enderror
            </div>
          </div>

          <div id="bienthe-wap">
            <label>Biến thể sản phẩm*</label> 

            <div class="bienthe-item row mb-2">
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <select class="form-select" name="bienthe[0][id_tenloai]">
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
              <label>Ảnh sản phẩm*</label>
              <div class="image-upload">
                <input type="file" name="anhsanpham[]" multiple class="form-control" id="anhsanpham"/>
                <div class="image-uploads">
                  <img src="{{ asset('img/icons/upload.svg') }}" alt="img" />
                  <h4>Tải lên file ảnh tại đây.</h4>
                </div>
                <div id="preview-anh" class="mt-2 d-flex flex-wrap"></div>
                @error('anhsanpham.*')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
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
                    <select class="form-select" name="bienthe[${index}][id_tenloai]">
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
        
        btnAdd.insertAdjacentHTML('beforebegin', html);
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


<script>
let selectedFiles = []; // mảng lưu file đã chọn

document.getElementById('anhsanpham').addEventListener('change', function(e) {
    // gộp file mới vào mảng
    selectedFiles = [...selectedFiles, ...e.target.files];
    renderPreview();
});

// render preview với nút xóa
function renderPreview() {
    let preview = document.getElementById('preview-anh');
    preview.innerHTML = "";
    selectedFiles.forEach((file, index) => {
        let reader = new FileReader();
        reader.onload = function(event) {
            let div = document.createElement('div');
            div.classList.add("m-2", "position-relative");

            let img = document.createElement('img');
            img.src = event.target.result;
            img.width = 120;
            img.classList.add("border", "rounded");

            // nút xóa
            let btn = document.createElement('button');
            btn.type = "button";
            btn.innerHTML = "X";
            btn.classList.add("btn", "btn-sm", "btn-danger", "position-absolute");
            btn.style.top = "0";
            btn.style.right = "0";
            btn.onclick = function() {
                removeFile(index);
            };

            div.appendChild(img);
            div.appendChild(btn);
            preview.appendChild(div);
        }
        reader.readAsDataURL(file);
    });

    // cập nhật lại input file (vì mặc định ko xóa đc file trong FileList)
    updateFileInput();
}

// xóa file trong mảng
function removeFile(index) {
    selectedFiles.splice(index, 1);
    renderPreview();
}

// cập nhật lại input file để submit đúng
function updateFileInput() {
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(file => dataTransfer.items.add(file));
    document.getElementById('anhsanpham').files = dataTransfer.files;
}
</script>

@endsection
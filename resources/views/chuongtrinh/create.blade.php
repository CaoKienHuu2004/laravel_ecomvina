@extends('layouts.app')

@section('title', 'Thêm mới Chương Trình Sự Kiện')
{{-- // controller truyền xuống $trangthais_quatang $trangthais_chuongtrinh $bienthes_combobox --}}
{{-- // các route sư dụng chuongtrinh.store chuongtrinh.index --- của breadcrumb phuongthuc.index trang-chu --}}
@section('content')
<div class="page-wrapper">
  <div class="content container-fluid">

    <div class="page-header">
        <x-header.breadcrumb
            title="Thêm Mới Chương Trình Sự Kiện"
            :links="[
                ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                ['label' => 'Danh sách Chương Trình Sự Kiện', 'route' => 'chuongtrinh.index']
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

    <form action="{{ route('chuongtrinh.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="row">
        {{-- Phần thông tin chương trình --}}
        <div class="col-lg-6">
          <div class="card mb-4">
            <div class="card-header">
              <h4>Thông tin chương trình</h4>
            </div>
            <div class="card-body">
              <div class="mb-3">
                  <label>Tiêu đề:</label>
                  <input type="text" name="tieude" class="form-control" required value="{{ old('tieude') }}">
              </div>

              <div class="mb-3">
                  <label>Nội dung <span class="text-danger">*</span></label>
                  <textarea name="noidung" id="noi_dung" class="form-control">{{ old('noidung') }}</textarea>
                  @error('noidung')
                      <span class="text-danger">{{ $message }}</span>
                  @enderror
              </div>

              <div class="mb-3">
                  <label>Trạng thái:</label>
                  <select name="trangthai" class="form-select">
                      @foreach ($trangthais_chuongtrinh as $tt)
                          <option value="{{ $tt }}" {{ old('trangthai') == $tt ? 'selected' : '' }}>{{ $tt }}</option>
                      @endforeach
                  </select>
              </div>

              <div class="mb-3">
                    <label>Ảnh chương trình:</label>
                    <div class="image-upload">
                        <input type="file" name="hinhanh" class="form-control d-none" id="hinhanh-upload" accept="image/*" />
                        <label for="hinhanh-upload" class="upload-label d-flex align-items-center justify-content-center border rounded" style="cursor:pointer; height:150px; background:#f8f9fa;">
                        <img src="{{ asset('img/icons/upload.svg') }}" alt="Upload Icon" style="width:50px; margin-right: 15px;">
                        <span>Tải lên file ảnh tại đây.</span>
                        </label>

                        @error('hinhanh')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror

                        <div id="preview-hinhanh" class="mt-2">
                        {{-- preview ảnh sẽ hiển thị ở đây --}}
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>

        {{-- Phần danh sách quà tặng --}}
        <div class="col-lg-6">
          <div class="card mb-4">
            <div class="card-header">
              <h4>Danh sách quà tặng sự kiện</h4>
            </div>
            <div class="card-body">
              <div id="gift-list">
                <div class="gift-item border rounded p-3 mb-3 position-relative">
                  <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 btn-remove-gift" title="Xóa quà tặng">&times;</button>

                  <div class="mb-2">
                      <label>Tiêu đề quà tặng:</label>
                      <input type="text" name="quatangsukien[0][tieude]" class="form-control" required>
                  </div>
                  <div class="mb-2">
                      <label>Biến thể sản phẩm:</label>
                      <select id="bienthe-select-0" name="quatangsukien[0][id_bienthe]" class="form-select" required>
                          @foreach ($bienthes_combobox as $bt)
                              <option
                                  value="{{ $bt->id }}"
                                  data-img="{{ $bt->sanpham->hinhanhsanpham->first()->hinhanh ?? '' }}"
                              >
                                <div style=" max-width: 250px;
                                white-space: normal;
                                word-wrap: break-word;
                                overflow-wrap: break-word;">
                                    {{ $bt->sanpham->ten ?? 'N/A' }} - {{ $bt->loaibienthe->ten ?? 'N/A' }} - {{ number_format($bt->giagoc) }} VND
                                </div>

                              </option>
                          @endforeach
                      </select>
                  </div>
                  <div class="mb-2">
                        <label>Thông tin <span class="text-danger">*</span></label>
                        <textarea name="quatangsukien[0][thongtin]" id="thong_tin" class="form-control">{{ old('thong_tin') }}</textarea>
                        @error('thong_tin')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                  <div class="mb-2">
                      <label>Điều kiện:</label>
                      <input type="text" name="quatangsukien[0][dieukien]" class="form-control">
                  </div>
                  <div class="mb-2">
                      <label>Ngày Bắt Đầu:</label>
                      <input type="datetime-local" name="quatangsukien[0][ngaybatdau]" class="form-control">
                  </div>
                  <div class="mb-2">
                      <label>Ngày Kết Thúc:</label>
                      <input type="datetime-local" name="quatangsukien[0][ngayketthuc]" class="form-control">
                  </div>
                  <div class="mb-2">
                      <label>Trạng thái:</label>
                      <select name="quatangsukien[0][trangthai]" class="form-select">
                          @foreach ($trangthais_quatang as $ttq)
                              <option value="{{ $ttq }}">{{ $ttq }}</option>
                          @endforeach
                      </select>
                  </div>
                  {{-- <div class="mb-2">
                      <label>Ảnh quà tặng:</label>
                      <input type="file" name="quatangsukien[0][hinhanh]" class="form-control">
                  </div> --}}
                  <div class="mb-2">
                    <label>Ảnh quà tặng:</label>
                    <div class="image-upload">
                        <input type="file" name="quatangsukien[0][hinhanh]" class="form-control d-none gift-image-input" id="gift-hinhanh-0" accept="image/*" />
                        <label for="gift-hinhanh-0" class="upload-label d-flex align-items-center justify-content-center border rounded" style="cursor:pointer; height:120px; background:#f8f9fa;">
                        <img src="{{ asset('img/icons/upload.svg') }}" alt="Upload Icon" style="width:40px; margin-right: 10px;">
                        <span>Tải lên file ảnh tại đây.</span>
                        </label>

                        @error('quatangsukien.0.hinhanh')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror

                        <div class="gift-preview mt-2" id="preview-gift-hinhanh-0"></div>
                    </div>
                    </div>
                </div>
              </div>

              <button type="button" class="btn btn-secondary" id="add-gift">+ Thêm quà tặng</button>
            </div>
          </div>
        </div>
      </div>

      <div class="mb-4 text-center">
        <button type="submit" class="btn btn-primary">Lưu chương trình</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
    // Hàm formatState dùng cho select2
    function formatState(state) {
        if (!state.id) {
            return state.text;
        }
        var imgSrc = $(state.element).data('img');
        if(imgSrc){
            var $state = $(`
                <span><img src="${imgSrc}" style="width: 50px; height: auto; margin-right: 10px;" /> ${state.text}</span>
            `);
            return $state;
        }
        return state.text;
    }

    let index = 1;

    document.getElementById('add-gift').addEventListener('click', () => {
        const container = document.getElementById('gift-list');
        const clone = container.querySelector('.gift-item').cloneNode(true);

        clone.querySelectorAll('input, textarea, select').forEach(input => {
            input.name = input.name.replace(/\[\d+\]/, `[${index}]`);

            if (input.tagName.toLowerCase() === 'select') {
                if ($(input).hasClass('select2-hidden-accessible')) {
                    $(input).select2('destroy');
                }
                input.selectedIndex = 0;
            } else {
                input.value = '';
            }
        });

        const select = clone.querySelector('select');
        const img = clone.querySelector('img');
        if (select && img) {
            img.id = `bienthe-img-${index}`;
            select.id = `bienthe-select-${index}`;
            img.src = select.options[0].dataset.img || '';

            $(select).select2({
                templateResult: formatState,
                templateSelection: formatState,
                escapeMarkup: markup => markup
            });
        }

        container.appendChild(clone);
        index++;
    });

    document.getElementById('gift-list').addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-gift')) {
            const giftItem = e.target.closest('.gift-item');
            if (giftItem) {
                const allGifts = document.querySelectorAll('#gift-list .gift-item');
                if (allGifts.length === 1) {
                    alert('Phải có ít nhất một quà tặng sự kiện.');
                    return;
                }
                giftItem.remove();
            }
        }
    });

    $(document).ready(function() {
        $('#bienthe-select-0').select2({
            templateResult: formatState,
            templateSelection: formatState,
            escapeMarkup: markup => markup
        });
    });
</script>

<script>
  ClassicEditor.create(document.querySelector('#noi_dung'), editorConfig);
  ClassicEditor.create(document.querySelector('#thong_tin'), editorConfig);
</script>
<script>
document.getElementById('hinhanh-upload').addEventListener('change', function(event) {
  const preview = document.getElementById('preview-hinhanh');
  preview.innerHTML = ''; // xóa preview cũ
  if (this.files && this.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
      const img = document.createElement('img');
      img.src = e.target.result;
      preview.appendChild(img);
    }
    reader.readAsDataURL(this.files[0]);
  }
});

// Dùng delegation cho quà tặng (nếu có nhiều input ảnh động)
document.getElementById('gift-list').addEventListener('change', function(event) {
  if (event.target.classList.contains('gift-image-input')) {
    const input = event.target;
    const previewId = 'preview-' + input.id;
    const preview = document.getElementById(previewId);
    if (!preview) return;
    preview.innerHTML = '';
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        const img = document.createElement('img');
        img.src = e.target.result;
        preview.appendChild(img);
      }
      reader.readAsDataURL(input.files[0]);
    }
  }
});
</script>
@endsection



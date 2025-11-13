@extends('layouts.app')

@section('title', 'Chỉnh sửa Chương Trình Sự Kiện')
{{-- controller truyền xuống $chuongtrinh, $trangthais_quatang, $trangthais_chuongtrinh, $bienthes_combobox --}}

{{-- // các route sư dụng chuongtrinh.update --- của breadcrumb phuongthuc.index trang-chu --}}
{{-- $chuongtrinh->hinhanh: Link http://148.230.100.215/assets/client/images/thumbs/tenfilehinhanh.jpg --}}
{{--  $chuongtrinh->quatangsukien[$index]->hinhanh: Link http://148.230.100.215/assets/client/images/thumbs/tenfilehinhanh.jpg --}}

@section('content')
<div class="page-wrapper">
  <div class="content container-fluid">

    <div class="page-header">
      <x-header.breadcrumb
          title="Chỉnh sửa Chương Trình Sự Kiện"
          :links="[
                ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                ['label' => 'Danh sách Chương Trình Sự Kiện', 'route' => 'chuongtrinh.index']
          ]"
          active="Chỉnh sửa"
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

    <form action="{{ route('chuongtrinh.update', $chuongtrinh->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="row">
        {{-- Phần thông tin chương trình --}}
        <div class="col-lg-6">
          <div class="card mb-4">
            <div class="card-header"><h4>Thông tin chương trình</h4></div>
            <div class="card-body">

              <div class="mb-3">
                <label>Tiêu đề:</label>
                <input type="text" name="tieude" class="form-control" required value="{{ old('tieude', $chuongtrinh->tieude) }}">
              </div>

              <div class="mb-3">
                <label>Nội dung <span class="text-danger">*</span></label>
                <textarea name="noidung" id="noi_dung" class="form-control">{{ old('noidung', $chuongtrinh->noidung) }}</textarea>
                @error('noidung')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              <div class="mb-3">
                <label>Trạng thái:</label>
                <select name="trangthai" class="form-select" required>
                  @foreach ($trangthais_chuongtrinh as $tt)
                    <option value="{{ $tt }}" {{ old('trangthai', $chuongtrinh->trangthai) == $tt ? 'selected' : '' }}>{{ $tt }}</option>
                  @endforeach
                </select>
              </div>

              <div class="mb-3">
                <label>Ảnh chương trình hiện tại:</label><br>
                @if ($chuongtrinh->hinhanh)
                  <img src="{{ $chuongtrinh->hinhanh }}" alt="Ảnh chương trình" style="max-width: 250px; max-height: 150px; display: block; margin-bottom: 10px;">
                @else
                  <p>Chưa có ảnh</p>
                @endif
                <label>Thay đổi ảnh chương trình:</label>
                <div class="image-upload">
                  <input type="file" name="hinhanh" class="form-control d-none" id="hinhanh-upload" accept="image/*" />
                  <label for="hinhanh-upload" class="upload-label d-flex align-items-center justify-content-center border rounded" style="cursor:pointer; height:150px; background:#f8f9fa;">
                    <img src="{{ asset('img/icons/upload.svg') }}" alt="Upload Icon" style="width:50px; margin-right: 15px;">
                    <span>Tải lên file ảnh tại đây.</span>
                  </label>
                  <div id="preview-hinhanh" class="mt-2"></div>
                  @error('hinhanh')
                    <div class="text-danger mt-1">{{ $message }}</div>
                  @enderror
                </div>
              </div>

            </div>
          </div>
        </div>

        {{-- Phần danh sách quà tặng --}}
        <div class="col-lg-6">
          <div class="card mb-4">
            <div class="card-header"><h4>Danh sách quà tặng sự kiện</h4></div>
            <div class="card-body">
              <div id="gift-list">
                @php
                  $quatangs = old('quatangsukien', $chuongtrinh->quatangsukien);
                @endphp

                @foreach ($quatangs as $index => $gift)
                  @php
                    $tieude = is_array($gift) ? ($gift['tieude'] ?? '') : $gift->tieude;
                    $thongtin = is_array($gift) ? ($gift['thongtin'] ?? '') : $gift->thongtin;
                    $dieukien = is_array($gift) ? ($gift['dieukien'] ?? '') : $gift->dieukien;
                    $ngaybatdau = is_array($gift) ? ($gift['ngaybatdau'] ?? '') : (isset($gift->ngaybatdau) ? date('Y-m-d\TH:i', strtotime($gift->ngaybatdau)) : '');
                    $ngayketthuc = is_array($gift) ? ($gift['ngayketthuc'] ?? '') : (isset($gift->ngayketthuc) ? date('Y-m-d\TH:i', strtotime($gift->ngayketthuc)) : '');
                    $trangthai = is_array($gift) ? ($gift['trangthai'] ?? '') : $gift->trangthai;
                    $id_bienthe = is_array($gift) ? ($gift['id_bienthe'] ?? '') : $gift->id_bienthe;
                    $hinhanh = is_array($gift) ? null : $gift->hinhanh;
                  @endphp

                  <div class="gift-item border rounded p-3 mb-3 position-relative">
                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 btn-remove-gift" title="Xóa quà tặng">&times;</button>

                    <div class="mb-2">
                      <label>Tiêu đề quà tặng:</label>
                      <input type="text" name="quatangsukien[{{ $index }}][tieude]" class="form-control" required value="{{ $tieude }}">
                    </div>

                    <div class="mb-2">
                      <label>Biến thể sản phẩm:</label>
                      <select id="bienthe-select-{{ $index }}" name="quatangsukien[{{ $index }}][id_bienthe]" class="form-select" required>
                        @foreach ($bienthes_combobox as $bt)
                          <option
                            value="{{ $bt->id }}"
                            data-img="{{ $bt->sanpham->hinhanhsanpham->first()->hinhanh ?? '' }}"
                            {{ $id_bienthe == $bt->id ? 'selected' : '' }}
                          >
                            {{ $bt->sanpham->ten ?? 'N/A' }} - {{ $bt->loaibienthe->ten ?? 'N/A' }} - {{ number_format($bt->giagoc) }} VND
                          </option>
                        @endforeach
                      </select>
                    </div>

                    <div class="mb-2">
                      <label>Thông tin <span class="text-danger">*</span></label>
                      <textarea name="quatangsukien[{{ $index }}][thongtin]" id="thong_tin_{{ $index }}" class="form-control">{{ $thongtin }}</textarea>
                      @error("quatangsukien.$index.thongtin")
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>

                    <div class="mb-2">
                      <label>Điều kiện:</label>
                      <input type="text" name="quatangsukien[{{ $index }}][dieukien]" class="form-control" value="{{ $dieukien }}">
                    </div>

                    <div class="mb-2">
                      <label>Ngày Bắt Đầu:</label>
                      <input type="datetime-local" name="quatangsukien[{{ $index }}][ngaybatdau]" class="form-control" value="{{ $ngaybatdau }}">
                    </div>

                    <div class="mb-2">
                      <label>Ngày Kết Thúc:</label>
                      <input type="datetime-local" name="quatangsukien[{{ $index }}][ngayketthuc]" class="form-control" value="{{ $ngayketthuc }}">
                    </div>

                    <div class="mb-2">
                      <label>Trạng thái:</label>
                      <select name="quatangsukien[{{ $index }}][trangthai]" class="form-select">
                        @foreach ($trangthais_quatang as $ttq)
                          <option value="{{ $ttq }}" {{ $trangthai == $ttq ? 'selected' : '' }}>{{ $ttq }}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="mb-2">
                      <label>Ảnh quà tặng: (Nếu muốn thay đổi upload mới)</label>
                      <div class="image-upload">
                        <input type="file"
                               name="quatangsukien[{{ $index }}][hinhanh]"
                               class="form-control d-none gift-image-input"
                               id="gift-hinhanh-{{ $index }}" accept="image/*" />
                        <label for="gift-hinhanh-{{ $index }}" class="upload-label d-flex align-items-center justify-content-center border rounded" style="cursor:pointer; height:120px; background:#f8f9fa;">
                          <img src="{{ asset('img/icons/upload.svg') }}" alt="Upload Icon" style="width:40px; margin-right: 10px;">
                          <span>Tải lên file ảnh tại đây.</span>
                        </label>
                        @error("quatangsukien.$index.hinhanh")
                          <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                        <div class="gift-preview mt-2" id="preview-gift-hinhanh-{{ $index }}">
                          @if ($hinhanh)
                            <img src="{{ $hinhanh }}" alt="Ảnh quà tặng hiện tại" style="max-width: 200px; max-height: 120px;">
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>

              <button type="button" class="btn btn-secondary" id="add-gift">+ Thêm quà tặng</button>
            </div>
          </div>
        </div>
      </div>

      <div class="mb-4 text-center">
        <button type="submit" class="btn btn-primary">Cập nhật chương trình</button>
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
    if (imgSrc) {
      var $state = $(`
        <span><img src="${imgSrc}" style="width: 50px; height: auto; margin-right: 10px;" /> ${state.text}</span>
      `);
      return $state;
    }
    return state.text;
  }

  let index = {{ count($quatangs) }};

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

    // Xóa preview ảnh nếu có
    clone.querySelectorAll('.gift-preview img').forEach(img => img.remove());

    // Thêm nút xóa nếu chưa có
    if (!clone.querySelector('.btn-remove-gift')) {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'btn btn-danger btn-sm position-absolute top-0 end-0 m-2 btn-remove-gift';
      btn.title = 'Xóa quà tặng';
      btn.innerHTML = '&times;';
      clone.appendChild(btn);
    }

    // Cập nhật id cho select và textarea mới
    const select = clone.querySelector('select');
    const textarea = clone.querySelector('textarea');
    if (select) {
      select.id = `bienthe-select-${index}`;
      $(select).select2({
        templateResult: formatState,
        templateSelection: formatState,
        escapeMarkup: markup => markup
      });
    }
    if (textarea) {
      textarea.id = `thong_tin_${index}`;
      // Khởi tạo CKEditor cho textarea mới
      ClassicEditor.create(textarea, editorConfig).catch(error => console.error(error));
    }

    container.appendChild(clone);
    index++;
  });

  // Xóa quà tặng khi click nút xóa
  document.getElementById('gift-list').addEventListener('click', function(e) {
    if (e.target.classList.contains('btn-remove-gift')) {
      const giftItem = e.target.closest('.gift-item');
      if (giftItem) {
        const allGifts = document.querySelectorAll('#gift-list .gift-item');
        if (allGifts.length === 1) {
          alert('Phải có ít nhất quà tặng sự kiện.');
          return;
        }
        giftItem.remove();
      }
    }
  });

  $(document).ready(function() {
    // Khởi tạo select2 cho select đầu tiên (nếu có)
    $('#gift-list select').each(function() {
      if (!$(this).hasClass('select2-hidden-accessible')) {
        $(this).select2({
          templateResult: formatState,
          templateSelection: formatState,
          escapeMarkup: markup => markup
        });
      }
    });

    // Khởi tạo CKEditor cho tất cả textarea thông tin quà tặng
    $('#gift-list textarea').each(function() {
      if (!this.classList.contains('ck-editor__editable')) {
        ClassicEditor.create(this, editorConfig).catch(error => console.error(error));
      }
    });
  });

  // Preview ảnh chương trình
  document.getElementById('hinhanh-upload').addEventListener('change', function(event) {
    const preview = document.getElementById('preview-hinhanh');
    preview.innerHTML = ''; // xóa preview cũ
    if (this.files && this.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        const img = document.createElement('img');
        img.src = e.target.result;
        img.style.maxWidth = '250px';
        img.style.maxHeight = '150px';
        preview.appendChild(img);
      }
      reader.readAsDataURL(this.files[0]);
    }
  });

  // Preview ảnh quà tặng (delegation)
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
          img.style.maxWidth = '200px';
          img.style.maxHeight = '120px';
          preview.appendChild(img);
        }
        reader.readAsDataURL(input.files[0]);
      }
    }
  });
</script>

<script>
  ClassicEditor.create(document.querySelector('#noi_dung'), editorConfig);
</script>
@endsection

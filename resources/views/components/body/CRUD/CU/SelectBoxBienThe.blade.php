@props(['index', 'bienthes', 'trangthais', 'oldData' => null])

<div class="gift-item border rounded p-3 mb-3 position-relative">
  <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 btn-remove-gift" title="Xóa quà tặng">&times;</button>

  <div class="mb-2">
      <label>Tiêu đề quà tặng:</label>
      <input type="text" name="quatangsukien[{{ $index }}][tieude]" class="form-control" required
             value="{{ old("quatangsukien.$index.tieude", $oldData['tieude'] ?? '') }}">
  </div>

  <div class="mb-2">
      <label>Biến thể sản phẩm:</label>
      <select id="bienthe-select-{{ $index }}" name="quatangsukien[{{ $index }}][id_bienthe]" class="form-select" required>
          @foreach ($bienthes as $bt)
              <option
                  value="{{ $bt->id }}"
                  data-img="{{ $bt->sanpham->hinhanhsanpham->first()->hinhanh ?? '' }}"
                  {{ (old("quatangsukien.$index.id_bienthe", $oldData['id_bienthe'] ?? '') == $bt->id) ? 'selected' : '' }}
              >
                {{ $bt->sanpham->ten ?? 'N/A' }} - {{ $bt->loaibienthe->ten ?? 'N/A' }} - {{ number_format($bt->giagoc) }} VND
              </option>
          @endforeach
      </select>
  </div>

  <div class="mb-2">
      <label>Thông tin <span class="text-danger">*</span></label>
      <textarea name="quatangsukien[{{ $index }}][thongtin]" class="form-control" required>{{ old("quatangsukien.$index.thongtin", $oldData['thongtin'] ?? '') }}</textarea>
  </div>

  <div class="mb-2">
      <label>Điều kiện:</label>
      <input type="text" name="quatangsukien[{{ $index }}][dieukien]" class="form-control" value="{{ old("quatangsukien.$index.dieukien", $oldData['dieukien'] ?? '') }}">
  </div>

  <div class="mb-2">
      <label>Ngày Bắt Đầu:</label>
      <input type="datetime-local" name="quatangsukien[{{ $index }}][ngaybatdau]" class="form-control" value="{{ old("quatangsukien.$index.ngaybatdau", $oldData['ngaybatdau'] ?? '') }}">
  </div>

  <div class="mb-2">
      <label>Ngày Kết Thúc:</label>
      <input type="datetime-local" name="quatangsukien[{{ $index }}][ngayketthuc]" class="form-control" value="{{ old("quatangsukien.$index.ngayketthuc", $oldData['ngayketthuc'] ?? '') }}">
  </div>

  <div class="mb-2">
      <label>Trạng thái:</label>
      <select name="quatangsukien[{{ $index }}][trangthai]" class="form-select">
          @foreach ($trangthais as $ttq)
              <option value="{{ $ttq }}" {{ (old("quatangsukien.$index.trangthai", $oldData['trangthai'] ?? '') == $ttq) ? 'selected' : '' }}>{{ $ttq }}</option>
          @endforeach
      </select>
  </div>

  <div class="mb-2">
    <label>Ảnh quà tặng:</label>
    <div class="image-upload">
        <input type="file" name="quatangsukien[{{ $index }}][hinhanh]" class="form-control d-none gift-image-input" id="gift-hinhanh-{{ $index }}" accept="image/*" />
        <label for="gift-hinhanh-{{ $index }}" class="upload-label d-flex align-items-center justify-content-center border rounded" style="cursor:pointer; height:120px; background:#f8f9fa;">
            <img src="{{ asset('img/icons/upload.svg') }}" alt="Upload Icon" style="width:40px; margin-right: 10px;">
            <span>Tải lên file ảnh tại đây.</span>
        </label>
        @error("quatangsukien.$index.hinhanh")
        <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
        <div class="gift-preview mt-2" id="preview-gift-hinhanh-{{ $index }}"></div>
    </div>
  </div>
</div>


{{-- // ----------------------------------------- cách dung ở cha ----------------------------------------- --}}


{{-- <div id="gift-list">
    <x-gift-item :index="0" :bienthes="$bienthes_combobox" :trangthais="$trangthais_quatang" />
</div>

<button type="button" class="btn btn-secondary" id="add-gift">+ Thêm quà tặng</button> --}}


{{-- <script>
    // Hàm formatState dùng cho select2
    function formatState(state) {
        if (!state.id) {
            return state.text;
        }
        var imgSrc = $(state.element).data('img');
        if(imgSrc){
            var $state = $(`<span><img src="${imgSrc}" style="width: 50px; height: auto; margin-right: 10px;" /> ${state.text}</span>`);
            return $state;
        }
        return state.text;
    }

    let index = 1;

    // Hàm khởi tạo select2 cho select biến thể (theo id)
    function initSelect2(select) {
        $(select).select2({
            templateResult: formatState,
            templateSelection: formatState,
            escapeMarkup: markup => markup
        });

        // Khi chọn thay đổi, cập nhật ảnh preview đi kèm
        $(select).on('change', function() {
            let idx = $(this).attr('id').split('-').pop();
            let imgSrc = $(this).find('option:selected').data('img') || '';
            $(`#bienthe-img-${idx}`).attr('src', imgSrc);
        });
    }

    // Khởi tạo select2 cho phần đầu tiên
    $(document).ready(function() {
        $('#bienthe-select-0').each(function() {
            initSelect2(this);
        });
    });

    document.getElementById('add-gift').addEventListener('click', () => {
        const container = document.getElementById('gift-list');
        const clone = container.querySelector('.gift-item').cloneNode(true);

        // Cập nhật tên, id, xóa dữ liệu cũ
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

        // Cập nhật id cho select và ảnh preview
        const select = clone.querySelector('select');
        const label = clone.querySelector('label[for^="gift-hinhanh-"]');
        const inputFile = clone.querySelector('input[type="file"]');
        const previewDiv = clone.querySelector('.gift-preview');

        if (select) {
            select.id = `bienthe-select-${index}`;
            initSelect2(select);
        }

        if (inputFile && label && previewDiv) {
            inputFile.id = `gift-hinhanh-${index}`;
            label.setAttribute('for', `gift-hinhanh-${index}`);
            previewDiv.id = `preview-gift-hinhanh-${index}`;
        }

        container.appendChild(clone);
        index++;
    });

    // Xử lý xóa quà tặng
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

    // Xử lý preview ảnh cho quà tặng
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
</script> --}}

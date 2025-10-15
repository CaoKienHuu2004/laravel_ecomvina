@extends('layouts.app')

@section('title')
    Sửa "{{ $sanpham->ten }}" | Sản phẩm | Quản trị hệ thống Siêu Thị Vina
@endsection

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Sửa "{{ $sanpham->ten }}"</h4>
                <h6>Chỉnh sửa sản phẩm của bạn.</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form class="row" action="{{ route('cap-nhat-san-pham',$sanpham->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Tên sản phẩm <span class="text-danger" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Bắt buộc">*</span></label>
                            <input class="form-control" type="text" name="ten" id="ten"
                                value="{{ old('ten', $sanpham->ten) }}" placeholder="tên sản phẩm..." />
                            @error('ten')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Danh mục <span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Bắt buộc">*</span></label>
                            <select class="form-control select" name="id_danhmuc[]" id="id_danhmuc" multiple>
                                @foreach($danhmucs as $dm)
                                <option value="{{ $dm->id }}"
                                    {{ in_array($dm->id, $sanpham->danhmuc->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $dm->ten }}
                                </option>
                                @endforeach
                                @error('id_danhmuc')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Thương hiệu <span class="text-danger" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Bắt buộc">*</span></label>
                            <select class="form-select" name="id_thuonghieu" id="id_thuonghieu">
                                @foreach ($thuonghieus as $th)
                                <option value="{{ $th->id }}"
                                    {{ $sanpham->thuong_hieu_id == $th->id ? 'selected' : '' }}>
                                    {{ $th->ten }}
                                </option>
                                @endforeach
                                @error('id_thuonghieu')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Nơi xuất xứ <span class="text-danger" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Bắt buộc">*</span></label>
                            <input type="text" name="xuatxu" value="{{ old('xuatxu', $sanpham->xuatxu) }}"
                                class="form-control" placeholder="xuất xứ ở..." />
                            @error('xuatxu')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Nơi sản xuất <span class="text-danger" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Bắt buộc">*</span></label>
                            <input type="text" name="sanxuat" value="{{ old('sanxuat', $sanpham->sanxuat) }}"
                                class="form-control" placeholder="sản xuất tại..." />
                            @error('sanxuat')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Video giới thiệu sản phẩm</label>
                            <input type="text" name="mediaurl" value="{{ old('mediaurl', $sanpham->mediaurl) }}"
                                placeholder="Url Youtube..." />
                            @error('mediaurl')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Trạng thái</label>
                            <select class="form-select" name="trangthai">
                                <option value="0" {{ old('trangthai',$sanpham->trangthai)==0?'selected':'' }}>Còn hàng
                                </option>
                                <option value="1" {{ old('trangthai',$sanpham->trangthai)==1?'selected':'' }}>Hết hàng
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Mô tả sản phẩm <span class="text-danger" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Bắt buộc">*</span></label>
                            <textarea name="mo_ta" id="mo_ta_suasp"
                                class="form-control">{{ old('mo_ta',$sanpham->mota) }}</textarea>
                            @error('mo_ta')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>



                    <div id="bienthe-wap">
                        <label>Biến thể sản phẩm <span class="text-danger">*</span></label>

                        @foreach($sanpham->bienthe as $i => $bienthe)
                        <div class="bienthe-item row mb-2" data-old-id="{{ $bienthe->id }}">
                            <input type="hidden" name="bienthe[{{ $i }}][id]" value="{{ $bienthe->id }}">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <select class="form-select sua_bienthe" name="bienthe[{{ $i }}][id_tenloai]">
                                        @foreach($loaibienthes as $loai)
                                        <option value="{{ $loai->id }}"
                                            {{ $bienthe->id_tenloai == $loai->id ? 'selected' : '' }}>{{ $loai->ten }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <input type="text" name="bienthe[{{ $i }}][gia]" value="{{ $bienthe->gia }}"
                                        class="form-control" />
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <input type="text" name="bienthe[{{ $i }}][soluong]" value="{{ $bienthe->soluong }}"
                                        class="form-control" />
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <button type="button" class="btn btn-outline-danger remove-btn">X</button>
                            </div>
                        </div>
                        @endforeach


                        <button type="button" class="btn btn-primary mb-4" id="add-bienthe">+ Thêm biến thể</button>
                    </div>




                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Ảnh sản phẩm<span class="text-danger" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Bắt buộc"> *</span></label>
                            <div class="image-upload">
                                <input type="file" name="anhsanpham[]" multiple class="form-control" id="anhsanpham" />
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
                        <button type="submit" class="btn btn-submit me-2" title="Cập nhật">Cập nhật</button>
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

    ClassicEditor.create(document.querySelector('#mo_ta_suasp'), editorConfig);
</script>

<script>
    $('.sua_bienthe').select2({
    tags: true,   // Cho phép nhập thêm
    placeholder: "Chọn hoặc nhập tên loại biến thể",
});
</script>

<script>
    let index = {{ count($sanpham->bienthe) }};
 // index bắt đầu từ số biến thể hiện tại
    const loaibienthe = @json($loaibienthes);

    // Hàm cập nhật hiển thị nút xóa
    function updateRemoveButtons() {
        const items = document.querySelectorAll('#bienthe-wap .bienthe-item');
        items.forEach(item => {
            const btn = item.querySelector('.remove-btn');
            btn.style.display = items.length === 1 ? 'none' : 'inline-block';
        });
    }

    // Thêm biến thể mới
    document.getElementById('add-bienthe').addEventListener('click', function() {
        let options = '<option value="">--Loại biến thể--</option>';
        loaibienthe.forEach(loai => options += `<option value="${loai.id}">${loai.ten}</option>`);

        let html = `
    <div class="bienthe-item row mb-2">
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
                <select class="form-select sua_bienthe select2" name="bienthe[${index}][id_tenloai]">${options}</select>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
                <input type="text" name="bienthe[${index}][gia]" placeholder="Giá" class="form-control"/>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
                <input type="text" name="bienthe[${index}][soluong]" placeholder="Số lượng" class="form-control"/>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <button type="button" class="btn btn-outline-danger remove-btn">X</button>
        </div>
    </div>`;

        document.getElementById('add-bienthe').insertAdjacentHTML('beforebegin', html);
        $(`.sua_bienthe`).select2({
            tags: true,
            placeholder: "--Loại biến thể--",
            allowClear: true
        });
        index++;
        updateRemoveButtons();
    });

    // Xóa biến thể
    document.getElementById('bienthe-wap').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-btn')) {
            let item = e.target.closest('.bienthe-item');

            // Nếu là biến thể cũ
            let oldId = item.dataset.oldId;
            if (oldId) {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'deleted_bienthe_ids[]';
                input.value = oldId;
                document.querySelector('form').appendChild(input);
            }

            item.remove();
            updateRemoveButtons();
        }
    });

    // Chạy lần đầu
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
let existingImages = @json($sanpham->anhsanpham); // ảnh cũ
let selectedFiles = []; // ảnh mới

const previewContainer = document.getElementById('preview-anh');
const fileInput = document.getElementById('anhsanpham');

function renderPreview() {
    previewContainer.innerHTML = '';

    // 1. Render ảnh cũ
    existingImages.forEach(img => {
        const li = document.createElement('li');

        li.innerHTML = `
            <div class="productviews">
                <div class="productviewsimg">
                    <img src="/img/product/${img.media}" alt="img" />
                </div>
                <div class="productviewscontent">
                    <div class="productviewsname">
                        <h2>${img.media}</h2>
                        <h3>Ảnh cũ</h3>
                    </div>
                    <a href="javascript:void(0);" class="hideset">x</a>
                </div>
            </div>
        `;

        li.querySelector('.hideset').addEventListener('click', function() {
            // thêm input hidden để backend biết xóa
            document.querySelector('form').insertAdjacentHTML('beforeend',
                `<input type="hidden" name="deleted_image_ids[]" value="${img.id}">`
            );
            existingImages = existingImages.filter(i => i.id !== img.id);
            renderPreview();
        });

        previewContainer.appendChild(li);
    });

    // 2. Render ảnh mới
    selectedFiles.forEach((file, idx) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const li = document.createElement('li');

            li.innerHTML = `
                <div class="productviews">
                    <div class="productviewsimg">
                        <img src="${e.target.result}" alt="img" />
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

            li.querySelector('.hideset').addEventListener('click', function() {
                selectedFiles.splice(idx, 1);
                renderPreview();
            });

            previewContainer.appendChild(li);
        };
        reader.readAsDataURL(file);
    });

    // 3. Cập nhật lại input file
    const dt = new DataTransfer();
    selectedFiles.forEach(f => dt.items.add(f));
    fileInput.files = dt.files;
}

// chọn file mới
fileInput.addEventListener('change', function(e) {
    selectedFiles = [...selectedFiles, ...e.target.files];
    renderPreview();
});

// render lần đầu ảnh cũ
renderPreview();


</script>



@endsection

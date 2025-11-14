<script>

    document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.selectVariant').forEach(btn => {
        btn.addEventListener('click', function () {

            let id = this.dataset.id;
            let name = this.dataset.name;

            // Gán vào input hidden
            document.getElementById('id_bienthe').value = id;

            document.getElementById('bienthe_name').value = name;

            // Optional: highlight
            alert("Đã chọn biến thể: " + name);
        });
    });

});
</script>
<div class="row">
    <div class="col-lg-12 col-sm-6 col-12">
        <div class="form-group">
            <label>Biến Thể</label>
            <div class="input-groupicon">
                <input type="text" placeholder="Scan/Search Product by code and select..." id="searchProduct">
                <div class="addonset">
                    <img src="{{ asset('assets/img/icons/scanners.svg') }}" alt="img">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <td>Chọn Biến Thể</td>
                    <th>Tên Sản phẩm</th>
                    <th>Tên Loại Biến Thể</th>
                    <th>Giá Góc</th>
                    <th>Số Lượng</th>
                    <th>Lượt Tặng</th>
                    <th>Lượt Bán</th>
                    <th>Trạng Thái</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($bienthes_filter_product_and_table_responsive as $item)
                    <tr>
                        <td>
                            <button type="button"
                                    class="btn btn-primary btn-sm selectVariant"
                                    data-id="{{ $item->id }}"
                                    data-name="{{ $item->sanpham->ten }} - {{ $item->loaibienthe->ten }}">
                                Chọn
                            </button>
                        </td>

                        {{-- Ảnh + tên sản phẩm --}}
                        <td class="productimgname">
                            <a class="product-img">
                                @php
                                    $image = $item->sanpham->hinhanhsanpham->first();
                                @endphp

                                <img src="{{ asset($image) }}" alt="Hình Sản Phẩm">
                            </a>

                            <a href="javascript:void(0)">
                                {{ $item->sanpham->ten ?? 'Không có tên' }}
                            </a>
                        </td>
                        <td >
                            {{ $item->loaibienthe->ten ?? 'Không có tên' }}
                        </td>

                        {{-- Giá gốc từ bảng biến thể --}}
                        <td>{{ number_format($item->giagoc, 0, ',', '.') }} đ</td>

                        {{-- Số lượng tồn kho của biến thể --}}
                        <td>{{ $item->soluong }}</td>

                        {{-- Lượt bán --}}
                        <td>{{ $item->luottang }}</td>

                        {{-- Lượt bán --}}
                        <td>{{ $item->luotban }}</td>

                        {{-- Trạng thái kho --}}
                        <td>
                            @if ($item->trangthai == 'Hết hàng')
                                <span class="badge bg-danger">Hết hàng</span>
                            @elseif ($item->trangthai == 'Sắp hết hàng')
                                <span class="badge bg-warning">Sắp hết</span>
                            @else
                                <span class="badge bg-success">Còn hàng</span>
                            @endif
                        </td>
                        <td>
                            <a class="delete-set">
                                <img src="{{ asset('assets/img/icons/delete.svg') }}" alt="svg">
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>

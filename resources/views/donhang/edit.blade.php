@extends('layouts.app')

@section('title', 'Chỉnh sửa đơn hàng | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>CHỈNH SỬA ĐƠN HÀNG</h4>
        <h6>Mã đơn: {{ $donhang->ma_donhang }}</h6>
      </div>
      <div class="page-btn">
        <a href="{{ route('danh-sach-don-hang') }}" class="btn btn-added">
          <img src="{{ asset('img/icons/arrow-left.svg') }}" class="me-1" alt="">Quay lại danh sách
        </a>
      </div>
    </div>

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
      </div>
    @endif

    <div class="card">
      <div class="card-body">
        <form method="POST" action="{{ route('cap-nhat-don-hang', $donhang->id) }}">
          @csrf @method('PUT')
          <div class="row">
            <div class="col-lg-4 col-sm-6 col-12">
              <div class="form-group">
                <label>Mã đơn</label>
                <input type="text" class="form-control" value="{{ $donhang->ma_donhang }}" disabled>
              </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-12">
              <div class="form-group">
                <label>Tên khách</label>
                <input type="text" name="ten_khach" class="form-control" value="{{ old('ten_khach', $donhang->khachhang->username ?? 'N/A') }}">
              </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-12">
              <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text" name="sdt" class="form-control" value="{{ old('sdt', $donhang->sdt) }}">
              </div>
            </div>
          </div>

          <!-- Danh sách sản phẩm -->
          <h5 class="mt-4 mb-2">Danh sách sản phẩm</h5>
          <table class="table table-bordered" id="product-table">
            <thead>
              <tr>
                <th>Sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach($donhang->chitiet as $i => $ct)
              <tr>
                <td>
                  <select name="products[{{ $i }}][id]" class="form-select product-select" required>
                    @foreach($products as $p)
                      <option value="{{ $p->id }}" data-price="{{ $p->gia }}"
                        {{ $ct->sanpham_id == $p->id ? 'selected' : '' }}>
                        {{ $p->ten }}
                      </option>
                    @endforeach
                  </select>
                </td>
                <td class="product-price">
                  {{ $ct->sanpham ? number_format($ct->sanpham->gia) : 'Chưa có' }}
                </td>
                <td>
                  <div class="input-group">
                    <button type="button" class="btn btn-sm btn-light btn-minus">-</button>
                    <input type="number" name="products[{{ $i }}][qty]" class="form-control text-center qty-input" value="{{ $ct->so_luong }}" min="1">
                    <button type="button" class="btn btn-sm btn-light btn-plus">+</button>
                  </div>
                </td>
                <td class="product-total">
                  {{ $ct->sanpham ? number_format($ct->so_luong * $ct->sanpham->gia) : '0' }}
                </td>
                <td><button type="button" class="btn btn-danger btn-sm btn-remove">X</button></td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <button type="button" class="btn btn-primary btn-sm" id="add-product">+ Thêm sản phẩm</button>

          <!-- Tổng tiền -->
          <div class="mt-3 text-end">
            <h5>Tổng tiền: <span id="grand-total">{{ number_format($donhang->tong_tien) }}</span> đ</h5>
            <input type="hidden" name="tong_tien" id="tong-tien-input" value="{{ $donhang->tong_tien }}">
          </div>

          <!-- Trạng thái -->
          <div class="row mt-4">
            <div class="col-lg-4 col-sm-6 col-12">
              <div class="form-group">
                <label>Trạng thái</label>
                <select class="form-select" name="trangthai">
                  <option value="0" {{ $donhang->trang_thai == 0 ? 'selected' : '' }}>0 - Chờ thanh toán</option>
                  <option value="1" {{ $donhang->trang_thai == 1 ? 'selected' : '' }}>1 - Đang giao</option>
                  <option value="2" {{ $donhang->trang_thai == 2 ? 'selected' : '' }}>2 - Đã giao</option>
                  <option value="3" {{ $donhang->trang_thai == 3 ? 'selected' : '' }}>3 - Đã hủy</option>
                </select>
              </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-12">
              <div class="form-group">
                <label>Thanh toán</label>
                <select class="form-select" name="thanh_toan">
                  <option value="0" {{ $donhang->thanh_toan == 0 ? 'selected' : '' }}>Chưa</option>
                  <option value="1" {{ $donhang->thanh_toan == 1 ? 'selected' : '' }}>Đã thanh toán</option>
                  <option value="2" {{ $donhang->thanh_toan == 2 ? 'selected' : '' }}>Hoàn</option>
                </select>
              </div>
            </div>
          </div>

          <div class="text-end mt-3">
            <button type="submit" class="btn btn-submit me-2">Cập nhật</button>
            <a href="{{ route('danh-sach-don-hang') }}" class="btn btn-cancel">Hủy</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Script xử lý tính tiền -->
<script>
document.addEventListener("DOMContentLoaded", function() {
  // Function to update totals
  function updateTotals() {
    let grandTotal = 0;
    document.querySelectorAll("#product-table tbody tr").forEach(function(row) {
      let price = parseFloat(row.querySelector(".product-select")?.selectedOptions[0]?.dataset.price || 0);
      let qty = parseInt(row.querySelector(".qty-input").value) || 1;
      let total = price * qty;
      row.querySelector(".product-price").textContent = price.toLocaleString();
      row.querySelector(".product-total").textContent = total.toLocaleString();
      grandTotal += total;
    });
    document.getElementById("grand-total").textContent = grandTotal.toLocaleString();
    document.getElementById("tong-tien-input").value = grandTotal;
  }

  // Event listener for add-product button to add new row to table
  document.getElementById("add-product").addEventListener("click", function() {
    let tableBody = document.querySelector("#product-table tbody");
    let newRow = tableBody.rows[0].cloneNode(true); // Clone the first row
    let index = tableBody.rows.length;

    newRow.querySelector(".product-select").name = `products[${index}][id]`;
    newRow.querySelector(".qty-input").name = `products[${index}][qty]`;
    newRow.querySelector(".qty-input").value = 1;
    newRow.querySelector(".product-price").textContent = "0";
    newRow.querySelector(".product-total").textContent = "0";
    
    tableBody.appendChild(newRow); // Append the cloned row
    updateTotals();  // Update totals
  });

  // Event listener for input change or click
  document.getElementById("product-table").addEventListener("change", updateTotals);
  document.getElementById("product-table").addEventListener("input", updateTotals);
  document.getElementById("product-table").addEventListener("click", function(e) {
    if (e.target.classList.contains("btn-plus")) {
      let input = e.target.closest("tr").querySelector(".qty-input");
      input.value = parseInt(input.value) + 1;
      updateTotals();
    }
    if (e.target.classList.contains("btn-minus")) {
      let input = e.target.closest("tr").querySelector(".qty-input");
      if (parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
      updateTotals();
    }
    if (e.target.classList.contains("btn-remove")) {
      e.target.closest("tr").remove();
      updateTotals();
    }
  });

  updateTotals();  // Initialize totals
});
</script>
@endsection

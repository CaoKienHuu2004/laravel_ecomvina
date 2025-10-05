@extends('layouts.app')

@section('title','Tạo đơn hàng | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>TẠO ĐƠN HÀNG</h4>
        <h6>Nhập thông tin đơn hàng mới</h6>
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
        <form method="POST" action="{{ route('luu-don-hang') }}">
          @csrf
          <div class="row">
            <!-- Khách hàng -->
            <div class="col-lg-6 col-sm-12">
              <div class="form-group">
                <label>Khách hàng <span class="text-danger">*</span></label>
                <select name="khachhang_id" class="form-select" required>
                  <option value="">-- Chọn khách hàng --</option>
                  @foreach($customers as $kh)
                    <option value="{{ $kh->id }}" {{ old('khachhang_id')==$kh->id?'selected':'' }}>
                      {{ $kh->hoten ?? $kh->username }} - {{ $kh->sdt }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <!-- Chọn sản phẩm -->
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
              <tr>
                <td>
                  <select name="products[0][id]" class="form-select product-select" required>
                    <option value="">-- Chọn sản phẩm --</option>
                    @foreach($products as $p)
                      <option value="{{ $p->id }}" data-price="{{ $p->gia }}">{{ $p->ten }}</option>
                    @endforeach
                  </select>
                </td>
                <td class="product-price">0</td>
                <td>
                  <div class="input-group">
                    <button type="button" class="btn btn-sm btn-light btn-minus">-</button>
                    <input type="number" name="products[0][qty]" class="form-control text-center qty-input" value="1" min="1">
                    <button type="button" class="btn btn-sm btn-light btn-plus">+</button>
                  </div>
                </td>
                <td class="product-total">0</td>
                <td><button type="button" class="btn btn-danger btn-sm btn-remove">X</button></td>
              </tr>
            </tbody>
          </table>
          <button type="button" class="btn btn-primary btn-sm" id="add-product">+ Thêm sản phẩm</button>

          <!-- Tổng tiền -->
          <div class="mt-3 text-end">
            <h5>Tổng tiền: <span id="grand-total">0</span> đ</h5>
            <input type="hidden" name="tong_tien" id="tong-tien-input" value="0">
          </div>

          <!-- Trạng thái + Thanh toán -->
          <div class="row mt-4">
            <div class="col-lg-4 col-sm-6 col-12">
              <div class="form-group">
                <label>Trạng thái <span class="text-danger">*</span></label>
                <select class="form-select" name="trangthai" required>
                  <option value="0">0 - Chờ thanh toán</option>
                  <option value="1">1 - Đang giao</option>
                  <option value="2">2 - Đã giao</option>
                  <option value="3">3 - Đã hủy</option>
                </select>
              </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-12">
              <div class="form-group">
                <label>Thanh toán <span class="text-danger">*</span></label>
                <select class="form-select" name="thanh_toan" required>
                  <option value="0">Chưa</option>
                  <option value="1">Đã thanh toán</option>
                  <option value="2">Hoàn</option>
                </select>
              </div>
            </div>
          </div>

          <div class="text-end mt-3">
            <button type="submit" class="btn btn-submit me-2">Lưu</button>
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

  document.getElementById("add-product").addEventListener("click", function() {
    let index = document.querySelectorAll("#product-table tbody tr").length;
    let newRow = document.querySelector("#product-table tbody tr").cloneNode(true);
    newRow.querySelector(".product-select").name = `products[${index}][id]`;
    newRow.querySelector(".qty-input").name = `products[${index}][qty]`;
    newRow.querySelector(".qty-input").value = 1;
    newRow.querySelector(".product-price").textContent = "0";
    newRow.querySelector(".product-total").textContent = "0";
    document.querySelector("#product-table tbody").appendChild(newRow);
  });

  updateTotals();
});
</script>
@endsection

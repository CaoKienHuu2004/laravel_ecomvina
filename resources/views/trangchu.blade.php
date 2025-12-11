@extends('layouts.app')

@section('title', 'Trang chủ | Quản trị hệ thống Siêu Thị Vina')

@section('content')
    {{-- <div class="page-wrapper"> //begin:origin
        <div class="content">
          <div class="row">
            <div class="col-lg-3 col-sm-6 col-12">
              <div class="dash-widget">
                <div class="dash-widgetimg">
                  <span
                    ><img src="{{asset('')}}img/icons/dash1.svg" alt="img"
                  /></span>
                </div>
                <div class="dash-widgetcontent">
                  <h5>
                    $<span class="counters" data-count="307144.00"
                      >$307,144.00</span
                    >
                  </h5>
                  <h6>Total Purchase Due</h6>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
              <div class="dash-widget dash1">
                <div class="dash-widgetimg">
                  <span
                    ><img src="{{asset('')}}img/icons/dash2.svg" alt="img"
                  /></span>
                </div>
                <div class="dash-widgetcontent">
                  <h5>
                    $<span class="counters" data-count="4385.00"
                      >$4,385.00</span
                    >
                  </h5>
                  <h6>Total Sales Due</h6>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
              <div class="dash-widget dash2">
                <div class="dash-widgetimg">
                  <span
                    ><img src="{{asset('')}}img/icons/dash3.svg" alt="img"
                  /></span>
                </div>
                <div class="dash-widgetcontent">
                  <h5>
                    $<span class="counters" data-count="385656.50"
                      >385,656.50</span
                    >
                  </h5>
                  <h6>Total Sale Amount</h6>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
              <div class="dash-widget dash3">
                <div class="dash-widgetimg">
                  <span
                    ><img src="{{asset('')}}img/icons/dash4.svg" alt="img"
                  /></span>
                </div>
                <div class="dash-widgetcontent">
                  <h5>
                    $<span class="counters" data-count="40000.00">400.00</span>
                  </h5>
                  <h6>Total Sale Amount</h6>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12 d-flex">
              <div class="dash-count">
                <div class="dash-counts">
                  <h4>100</h4>
                  <h5>Customers</h5>
                </div>
                <div class="dash-imgs">
                  <i data-feather="user"></i>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12 d-flex">
              <div class="dash-count das1">
                <div class="dash-counts">
                  <h4>100</h4>
                  <h5>Suppliers</h5>
                </div>
                <div class="dash-imgs">
                  <i data-feather="user-check"></i>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12 d-flex">
              <div class="dash-count das2">
                <div class="dash-counts">
                  <h4>100</h4>
                  <h5>Purchase Invoice</h5>
                </div>
                <div class="dash-imgs">
                  <i data-feather="file-text"></i>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12 d-flex">
              <div class="dash-count das3">
                <div class="dash-counts">
                  <h4>105</h4>
                  <h5>Sales Invoice</h5>
                </div>
                <div class="dash-imgs">
                  <i data-feather="file"></i>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-7 col-sm-12 col-12 d-flex">
              <div class="card flex-fill">
                <div
                  class="card-header pb-0 d-flex justify-content-between align-items-center"
                >
                  <h5 class="card-title mb-0">Purchase & Sales</h5>
                  <div class="graph-sets">
                    <ul>
                      <li>
                        <span>Sales</span>
                      </li>
                      <li>
                        <span>Purchase</span>
                      </li>
                    </ul>
                    <div class="dropdown">
                      <button
                        class="btn btn-white btn-sm dropdown-toggle"
                        type="button"
                        id="dropdownMenuButton"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                      >
                        2022
                        <img
                          src="{{asset('')}}img/icons/dropdown.svg"
                          alt="img"
                          class="ms-2"
                        />
                      </button>
                      <ul
                        class="dropdown-menu"
                        aria-labelledby="dropdownMenuButton"
                      >
                        <li>
                          <a href="javascript:void(0);" class="dropdown-item"
                            >2022</a
                          >
                        </li>
                        <li>
                          <a href="javascript:void(0);" class="dropdown-item"
                            >2021</a
                          >
                        </li>
                        <li>
                          <a href="javascript:void(0);" class="dropdown-item"
                            >2020</a
                          >
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div id="sales_charts"></div>
                </div>
              </div>
            </div>
            <div class="col-lg-5 col-sm-12 col-12 d-flex">
              <div class="card flex-fill">
                <div
                  class="card-header pb-0 d-flex justify-content-between align-items-center"
                >
                  <h4 class="card-title mb-0">Recently Added Products</h4>
                  <div class="dropdown">
                    <a
                      href="javascript:void(0);"
                      data-bs-toggle="dropdown"
                      aria-expanded="false"
                      class="dropset"
                    >
                      <i class="fa fa-ellipsis-v"></i>
                    </a>
                    <ul
                      class="dropdown-menu"
                      aria-labelledby="dropdownMenuButton"
                    >
                      <li>
                        <a href="productlist.html" class="dropdown-item"
                          >Product List</a
                        >
                      </li>
                      <li>
                        <a href="addproduct.html" class="dropdown-item"
                          >Product Add</a
                        >
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="card-body">
                  <div class="table-responsive dataview">
                    <table class="table datatable">
                      <thead>
                        <tr>
                          <th>Sno</th>
                          <th>Products</th>
                          <th>Price</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td class="productimgname">
                            <a href="productlist.html" class="product-img">
                              <img
                                src="{{asset('')}}img/product/product22.jpg"
                                alt="product"
                              />
                            </a>
                            <a href="productlist.html">Apple Earpods</a>
                          </td>
                          <td>$891.2</td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <td class="productimgname">
                            <a href="productlist.html" class="product-img">
                              <img
                                src="{{asset('')}}img/product/product23.jpg"
                                alt="product"
                              />
                            </a>
                            <a href="productlist.html">iPhone 11</a>
                          </td>
                          <td>$668.51</td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <td class="productimgname">
                            <a href="productlist.html" class="product-img">
                              <img
                                src="{{asset('')}}img/product/product24.jpg"
                                alt="product"
                              />
                            </a>
                            <a href="productlist.html">samsung</a>
                          </td>
                          <td>$522.29</td>
                        </tr>
                        <tr>
                          <td>4</td>
                          <td class="productimgname">
                            <a href="productlist.html" class="product-img">
                              <img
                                src="{{asset('')}}img/product/product6.jpg"
                                alt="product"
                              />
                            </a>
                            <a href="productlist.html">Macbook Pro</a>
                          </td>
                          <td>$291.01</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card mb-0">
            <div class="card-body">
              <h4 class="card-title">Expired Products</h4>
              <div class="table-responsive dataview">
                <table class="table datatable">
                  <thead>
                    <tr>
                      <th>SNo</th>
                      <th>Product Code</th>
                      <th>Product Name</th>
                      <th>Brand Name</th>
                      <th>Category Name</th>
                      <th>Expiry Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>1</td>
                      <td><a href="javascript:void(0);">IT0001</a></td>
                      <td class="productimgname">
                        <a class="product-img" href="productlist.html">
                          <img
                            src="{{asset('')}}img/product/product2.jpg"
                            alt="product"
                          />
                        </a>
                        <a href="productlist.html">Orange</a>
                      </td>
                      <td>N/D</td>
                      <td>Fruits</td>
                      <td>12-12-2022</td>
                    </tr>
                    <tr>
                      <td>2</td>
                      <td><a href="javascript:void(0);">IT0002</a></td>
                      <td class="productimgname">
                        <a class="product-img" href="productlist.html">
                          <img
                            src="{{asset('')}}img/product/product3.jpg"
                            alt="product"
                          />
                        </a>
                        <a href="productlist.html">Pineapple</a>
                      </td>
                      <td>N/D</td>
                      <td>Fruits</td>
                      <td>25-11-2022</td>
                    </tr>
                    <tr>
                      <td>3</td>
                      <td><a href="javascript:void(0);">IT0003</a></td>
                      <td class="productimgname">
                        <a class="product-img" href="productlist.html">
                          <img
                            src="{{asset('')}}img/product/product4.jpg"
                            alt="product"
                          />
                        </a>
                        <a href="productlist.html">Stawberry</a>
                      </td>
                      <td>N/D</td>
                      <td>Fruits</td>
                      <td>19-11-2022</td>
                    </tr>
                    <tr>
                      <td>4</td>
                      <td><a href="javascript:void(0);">IT0004</a></td>
                      <td class="productimgname">
                        <a class="product-img" href="productlist.html">
                          <img
                            src="{{asset('')}}img/product/product5.jpg"
                            alt="product"
                          />
                        </a>
                        <a href="productlist.html">Avocat</a>
                      </td>
                      <td>N/D</td>
                      <td>Fruits</td>
                      <td>20-11-2022</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
    </div> //end:origin  --}}
    <div class="page-wrapper">
        <div class="content">
            <div class="row">
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="dash-widget dash1">
                        <div class="dash-widgetimg">
                            <span><img src="{{asset('img/icons/dash2.svg')}}" alt="img" /></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5>
                                <span class="counters">{{ number_format($tongDoanhThu, 0, ',', '.') }} </span>đ
                            </h5>
                            <h6>Tổng doanh thu</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="dash-widget">
                        <div class="dash-widgetimg">
                            <span><i class="text-warning" data-feather="calendar"></i></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5>
                                <span class="counters">{{ number_format($tongDoanhThuThang, 0, ',', '.') }} </span>đ
                            </h5>
                            <h6>Tổng doanh thu tháng này</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="dash-widget dash2">
                        <div class="dash-widgetimg">
                            <span><i data-feather="file-text" style="color:#00d1e8;"></i></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5>
                                <span class="counters">{{ number_format($tongDoanhThuTuan, 0, ',', '.') }} </span>đ
                            </h5>
                            <h6>Tổng doanh thu tuần này</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="dash-widget dash3">
                        <div class="dash-widgetimg">
                            <span><i data-feather="compass" style="color:#ea5454;"></i></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5>
                                <span class="counters">{{ number_format($tongDoanhThuNgay, 0, ',', '.') }} </span>đ
                            </h5>
                            <h6>Tổng doanh thu hôm nay</h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-7 col-sm-12 col-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Thống kê doanh thu</h5>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#basictab1" data-bs-toggle="tab">Trong tuần</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#basictab2" data-bs-toggle="tab">Trong tháng</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#basictab3" data-bs-toggle="tab">Trong năm</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane show active" id="basictab1">
                                    <div id="sales_charts1"></div>
                                </div>
                                <div class="tab-pane" id="basictab2">
                                    <div id="sales_charts2"></div>
                                </div>
                                <div class="tab-pane" id="basictab3">
                                    <div id="sales_charts3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-sm-12 col-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0 d-flex gap-2 align-items-center">Đơn hàng mới
                                <span class="bg-danger text-white text-center rounded-circle blinking-flash"
                                    style="font-size: 13px; width: 20px;">!</span>
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive dataview">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Thông tin</th>
                                            <th>Ngày đặt</th>
                                            <th class="text-end">Tổng giá trị</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($donHangsMoi as $dh)
                                            <tr>
                                                <td class="text-start">
                                                    <span class="fw-bold text-black" style="font-size: 14px;">{{ $dh->nguoidung->hoten }}</span><br/>
                                                    <p><a href="productlist.html">{{ $dh->madonhang }}</a></p>
                                                </td>
                                                <td>{{ $dh->created_at->format('d/m/Y - H:i') }}</td>
                                                <td class="text-end">{{ number_format($dh->thanhtien) }} đ</td>
                                                <td class="text-center">
                                                    <a href="{{ route('chi-tiet-don-hang', $dh->id) }}">
                                                        <i data-feather="clipboard" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="Xem chi tiết"
                                                        style="color:#ea5454;"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-sm-12 col-12 d-flex">
                    <div class="card mb-0 w-100">
                        <div class="card-body">
                            <h4 class="card-title">Sản phẩm hết hàng</h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-start">Tên sản phẩm</th>
                                            <th class="text-center">Số lượng</th>
                                            <th class="text-center">Giá bán</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sanPhamHetHang as $bt)
                                            <tr>
                                                <td class="productimgname text-start">
                                                    <a class="product-img" href="{{ route('sanpham.show', $bt->sanpham->id) }}">
                                                        @if($bt->sanpham->hinhanhsanpham && $bt->sanpham->hinhanhsanpham->first())
                                                            <img style="object-fit: cover;" src="{{ $bt->sanpham->hinhanhsanpham->first()->hinhanh }}" alt="product" />
                                                        @else
                                                            <img style="object-fit: cover;" src="link-mac-dinh.jpg" alt="no image" />
                                                        @endif
                                                    </a>
                                                    <div>
                                                        <p><a class="fw-bold" href="{{ route('sanpham.show', $bt->sanpham->id) }}">{{ $bt->sanpham->ten }}</a></p>
                                                        {{ $bt->variant_name }} <!-- Tên biến thể sản phẩm nếu có -->
                                                    </div>
                                                </td>
                                                <td class="text-center">{{ $bt->soluong }}</td>
                                                <td class="text-center">{{ number_format($bt->giagoc) }} đ</td>
                                                <td class="text-center">
                                                    <a href="{{ route('sanpham.show', $bt->sanpham->id) }}">
                                                        <i data-feather="eye" data-bs-toggle="tooltip" data-bs-placement="top" title="Xem chi tiết" style="color:#ea5454;"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-12 col-12 d-flex">
                    <div class="card mb-0 w-100">
                        <div class="card-body">
                            <h4 class="card-title">Sản phẩm tồn kho nhiều</h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-start">Tên sản phẩm</th>
                                            <th class="text-center">Số lượng</th>
                                            <th class="text-center">Giá bán</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sanPhamTonKho as $bt)
                                            <tr>
                                                <td class="productimgname text-start">
                                                    <a class="product-img" href="{{ route('sanpham.show', $bt->sanpham->id) }}">
                                                        @if($bt->sanpham->hinhanhsanpham && $bt->sanpham->hinhanhsanpham->first())
                                                            <img style="object-fit: cover;" src="{{ $bt->sanpham->hinhanhsanpham->first()->hinhanh }}" alt="product" />
                                                        @else
                                                            <img style="object-fit: cover;" src="link-mac-dinh.jpg" alt="no image" />
                                                        @endif
                                                    </a>
                                                    <div>
                                                        <p><a class="fw-bold" href="{{ route('sanpham.show', $bt->sanpham->id) }}">{{ $bt->sanpham->ten }}</a></p>
                                                        {{ $bt->variant_name }} <!-- Tên biến thể sản phẩm nếu có -->
                                                    </div>
                                                </td>
                                                <td class="text-center">{{ $bt->soluong }}</td>
                                                <td class="text-center">{{ number_format($bt->giagoc) }} đ</td>
                                                <td class="text-center">
                                                    <a href="{{ route('sanpham.show', $bt->sanpham->id) }}">
                                                        <i data-feather="eye" data-bs-toggle="tooltip" data-bs-placement="top" title="Xem chi tiết" style="color:#ea5454;"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- script nằm ở public/js/trangchu.js Nguyên xây dựng --}}

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=0"
    />
    <meta name="description" content="Quản trị hệ thống Siêu Thị Vina" />
    <meta
      name="keywords"
      content="Siêu Thị Vina, quản trị hệ thống, quản lý siêu thị, quản lý bán hàng"
    />
    <meta name="author" content="Quản Trị Viên"/>
    <meta name="robots" content="noindex, nofollow"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản trị hệ thống Siêu Thị Vina')</title>

    <link
      rel="shortcut icon"
      type="image/x-icon"
      href="{{asset('img/favicon_tayninhquan.png')}}"
    />

    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}" />

    <link rel="stylesheet" href="{{asset('css/animate.css')}}" />

    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap4.min.css')}}" />

    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"> -->

    <link rel="stylesheet" href="{{ asset('plugins/dragula/css/dragula.min.css') }}" />

    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/46.0.2/ckeditor5.css" crossorigin>

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}" />

    <link rel="stylesheet" href="{{ asset('plugins/owlcarousel/owl.carousel.min.css') }}"/>

    <link rel="stylesheet" href="{{ asset('plugins/lightbox/glightbox.min.css') }}" />

    <link
      rel="stylesheet"
      href="{{asset('plugins/fontawesome/css/fontawesome.min.css')}}"
    />
    <link rel="stylesheet" href="{{asset('plugins/fontawesome/css/all.min.css')}}" />

    <link rel="stylesheet" href="{{asset('css/style.css')}}" />
  </head>
  @php

      $domain = env('DOMAIN', 'http://148.230.100.215/');
  @endphp
  <body>
    <!-- <div id="global-loader">
      <div class="whirly-loader"></div>
    </div> -->

    <div class="main-wrapper">
      <div class="header">
        <div class="header-left active">
          <a href="{{ route ('trang-chu') }}" class="logo">
            <img src="{{asset('img/logo.png')}}" alt="" />
          </a>
          <a href="/" class="logo-small">
            <img src="{{asset('img/logo-small.png')}}" alt="" />
          </a>
          <a id="toggle_btn" href="javascript:void(0);"> </a>
        </div>

        <a id="mobile_btn" class="mobile_btn" href="#sidebar">
          <span class="bar-icon">
            <span></span>
            <span></span>
            <span></span>
          </span>
        </a>

        <ul class="nav user-menu">

        {{-- // -- Thông báo của Admin --// --}}
          <li class="nav-item dropdown">
            <a
              href="javascript:void(0);"
              class="dropdown-toggle nav-link"
              data-bs-toggle="dropdown"
            >
              <img src="{{asset('img/icons/notification-bing.svg')}}" alt="img" />
              <span class="badge rounded-pill">{{ auth()->user()->thongbao()->where('trangthai', 'Chưa đọc')->count() }}</span>
            </a>
            <div class="dropdown-menu notifications">
              <div class="topnav-dropdown-header">
                <span class="notification-title"><b>Thông báo</b></span>
              </div>
                {{-- ✅ Thay bằng include --}}
                @include('layouts.child_layouts.noticontent')

              <div class="topnav-dropdown-footer">
                <a href="{{$domain}}thongbao">Xem tất cả thông báo </a>
              </div>
            </div>
          </li>
         {{-- // -- Thông báo của Admin --// --}}

          <li class="nav-item dropdown has-arrow main-drop">
            <a
              href="javascript:void(0);"
              class="dropdown-toggle nav-link userset"
              data-bs-toggle="dropdown"
            >
              <span class="user-img"
                ><img src="{{asset('img/favicon_tayninhquan.png')}}" alt="" />
                <span class="status online"></span
              ></span>
            </a>
            <div class="dropdown-menu menu-drop-user">
              <div class="profilename">
                <div class="profileset">
                  <span class="user-img"
                    ><img src="{{asset('img/favicon_tayninhquan.png')}}" alt="" />
                    <span class="status online"></span
                  ></span>
                  <div class="profilesets">
                    <h6>Ecomvina</h6>
                    <h5>{{ auth()->user()->hoten }}</h5>
                  </div>
                </div>
                <hr class="m-0" />
                <a class="dropdown-item" href="{{ route('thong-tin-tai-khoan') }} ">
                  <i class="me-2" data-feather="user"></i> Hồ sơ</a
                >
                <a class="dropdown-item" href="generalsettings.html"
                  ><i class="me-2" data-feather="settings"></i>Cài đặt</a
                >
                <hr class="m-0" />
                <a class="dropdown-item logout pb-0" href="{{ route('dang-xuat') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <img src="{{ asset('img/icons/log-out.svg') }}" class="me-2" alt="img" />
                    Đăng xuất
                </a>

                <form id="logout-form" action="{{ route('dang-xuat') }}" method="POST" class="d-none">
                    @csrf
                </form>
              </div>
            </div>
          </li>
        </ul>

        <div class="dropdown mobile-user-menu">
          <a
            href="javascript:void(0);"
            class="nav-link dropdown-toggle"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            ><i class="fa fa-ellipsis-v"></i
          ></a>
          <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="{{ route('thong-tin-tai-khoan') }}">Hồ sơ</a>
            <a class="dropdown-item" href="generalsettings.html">Cài đặt</a>
            <a class="dropdown-item" href="{{ route('dang-xuat') }}">Đăng xuất</a>
          </div>
        </div>
      </div>

      <div class="sidebar" id="sidebar">
        <div class="sidebar-inner slimscroll">
          <div id="sidebar-menu" class="sidebar-menu">
            <ul>
              <li class="{{ request()->routeIs('trang-chu') ? 'active' : '' }}">
                <a href="/trang-chu"
                  ><img src="{{asset('img/icons/dashboard.svg')}}" alt="img" /><span>
                    Tổng quan</span
                  >
                </a>
              </li>
                {{-- // -- Nguoidung --// --}}
                <li class="submenu">
                    <a href="javascript:void(0);"
                    ><img src="{{asset('img/icons/users1.svg')}}" alt="img" /><span>
                        Người dùng</span
                    >
                    <span class="menu-arrow"></span
                    ></a>
                    <ul>
                        {{-- /* ===================== NGƯỜI DÙNG ===================== */ --}}
                        {{-- <li><a class="{{ request()->routeIs('nguoidung.index') || request()->routeIs('nguoidung.edit') || request()->routeIs('nguoidung.create') || request()->routeIs('nguoidung.show') || request()->routeIs('nguoidung.trash') ? 'active' : '' }}" href="{{ route('nguoidung.index') }}">Danh sách tài khoản người dùng</a></li> --}}
                        <li>
                            <a class="{{ request()->routeIs('nguoidung.*') ? 'active' : '' }}" href="{{ route('nguoidung.index') }}">
                                Danh sách tài khoản người dùng
                            </a>
                        </li>
                        {{-- /* ===================== ĐỊA CHỈ GIAO HÀNG ===================== */ --}}
                        <li><a class="{{ request()->routeIs('diachigiaohang.index') || request()->routeIs('diachigiaohang.edit') || request()->routeIs('diachigiaohang.create') || request()->routeIs('diachigiaohang.show') || request()->routeIs('diachigiaohang.trash') ? 'active' : '' }}" href="{{ route('diachigiaohang.index') }}">Danh sách địa chỉ giao hàng</a></li>
                        {{--  --}}
                    </ul>
                </li>
                {{-- // -- phuong thuc thanh toan --// --}}
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><img src="{{asset('img/icons/payment-method-cash-svgrepo-com.svg')}}" alt="img" /><span>
                    Phương thức Thanh toán</span
                  >
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                  {{-- <li><a href="expenselist.html">Phương thức thanh toán</a></li>
                  <li><a href="createexpense.html">Lịch sử thanh toán</a></li> --}}
                  {{-- //---- Phương Thức Thanh Toán ---// --}}
                  <li><a class="{{ request()->routeIs('phuongthuc.index') || request()->routeIs('phuongthuc.edit') || request()->routeIs('phuongthuc.create') || request()->routeIs('phuongthuc.show') ? 'active' : '' }}" href="{{ route('phuongthuc.index') }}">Danh sách phương thức thanh toán</a></li>
                </ul>
              </li>
                {{-- // -- quản lý kho --// --}}
                <li class="submenu">
                    <a href="javascript:void(0);"
                    ><img src="{{asset('img/icons/sales1.svg')}}" alt="img" /><span>
                        Quản lý kho</span
                    >
                    <span class="menu-arrow"></span
                    ></a>
                    <ul>
                        <li><a class="{{ request()->routeIs('danh-sach-kho-hang') ? 'active' : '' }}" href="{{ route('danh-sach-kho-hang') }}">Kho hàng</a></li>

                    </ul>
                </li>
                {{-- // -- quản lý quà tăng --// --}}
                <li class="submenu">
                    <a href="javascript:void(0);"
                    ><img src="{{asset('img/icons/gitlfs-svgrepo-com.svg')}}" alt="img" /><span>
                        Quản lý quà tăng</span
                    >
                    <span class="menu-arrow"></span
                    ></a>
                    <ul>
                        {{-- //----Quà tăng sự kiện ---// --}}
                        <li><a class="{{ request()->routeIs('quatangsukien.index') || request()->routeIs('quatangsukien.edit') || request()->routeIs('quatangsukien.create') || request()->routeIs('quatangsukien.show') ||  request()->routeIs('quatangsukien.trash') ? 'active' : '' }}" href="{{ route('quatangsukien.index') }}">Danh sách quà tặng sự kiện</a></li>

                    </ul>
                </li>
                {{-- // -- Sanpham --// --}}
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><img src="{{asset('img/icons/product.svg')}}" alt="img" /><span>
                    Sản phẩm</span
                  >
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                    {{-- //----Sản Phẩm ---// --}}
                  <li><a class="{{ request()->routeIs('sanpham.index') || request()->routeIs('sanpham.edit') || request()->routeIs('sanpham.create') || request()->routeIs('sanpham.show') ||  request()->routeIs('sanpham.trash') ? 'active' : '' }}" href="{{ route('sanpham.index') }}">Danh sách sản phẩm</a></li>

                  {{-- //---- Hình Ảnh Sản Phẩm ---// --}}
                  <li><a class="{{ request()->routeIs('hinhanhsanpham.index') || request()->routeIs('hinhanhsanpham.edit') || request()->routeIs('hinhanhsanpham.create') || request()->routeIs('hinhanhsanpham.show') || request()->routeIs('hinhanhsanpham.trash') ? 'active' : '' }}" href="{{ route('hinhanhsanpham.index') }}">Hình ảnh sản phẩm</a></li>
                  {{-- //---- Loại Biến Thể ---// --}}
                  <li><a class="{{ request()->routeIs('loaibienthe.index') || request()->routeIs('loaibienthe.edit') || request()->routeIs('loaibienthe.create') || request()->routeIs('loaibienthe.show') ? 'active' : '' }}" href="{{ route('loaibienthe.index') }}">Danh sách loại biến thể</a></li>
                  {{-- <li><a class="{{ request()->routeIs('danh-sach-thuong-hieu') || request()->routeIs('chinh-sua-thuong-hieu') || request()->routeIs('tao-thuong-hieu') ? 'active' : '' }}" href="{{ route('danh-sach-thuong-hieu') }}">Thương hiệu sản phẩm</a></li> --}}
                  {{-- <li><a class="{{ request()->routeIs('danh-sach-kho-hang') ? 'active' : '' }}" href="{{ route('danh-sach-kho-hang') }}">Kho hàng</a></li> --}}
                </ul>
              </li>

              {{-- // -- Quản lý đơn hàng --// --}}
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><img src="{{asset('img/icons/expense1.svg')}}" alt="img" /><span>
                    Quản lý đơn hàng</span
                  >
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                  <li><a href="saleslist.html">Voucher</a></li>
                  <li><a href="pos.html">Ưu đãi sản phẩm</a></li>
                  <li><a href="pos.html">Sự kiện khuyến mãi</a></li>
                  <li><a href="/">Đánh giá sản phẩm</a></li>
                  <li><a href="pos.html">Danh sách đơn hàng</a></li>

                </ul>
              </li>

              {{-- // -- Quản lý thương hiệu --// --}}
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><img src="{{asset('img/icons/brands-svgrepo-com.svg')}}" alt="img" /><span>
                    Quản lý thương hiệu</span
                  >
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                  <li><a class="{{ request()->routeIs('thuonghieu.index') || request()->routeIs('thuonghieu.edit') || request()->routeIs('thuonghieu.create') || request()->routeIs('thuonghieu.show') ? 'active' : '' }}" href="{{ route('thuonghieu.index') }}">Danh sách thương hiệu</a></li>
                </ul>
              </li>

              {{-- // -- Quản lý danh mục --// --}}
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><img src="{{asset('img/icons/category-svgrepo-com.svg')}}" alt="img" /><span>
                    Quản lý danh mục</span
                  >
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                    <li><a class="{{ request()->routeIs('danhmuc.index') || request()->routeIs('danhmuc.edit') || request()->routeIs('danhmuc.create') || request()->routeIs('danhmuc.show') ? 'active' : '' }}" href="{{ route('danhmuc.index') }}">Danh mục sản phẩm</a></li>
                </ul>
              </li>

              {{-- // -- Quản lý bài viết --// --}}
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><img src="{{asset('img/icons/post-svgrepo-com.svg')}}" alt="img" /><span>
                    Quản lý bài viết</span
                  >
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                  <li><a class="{{ request()->routeIs('baiviet.index') || request()->routeIs('baiviet.edit') || request()->routeIs('baiviet.create') || request()->routeIs('baiviet.show') ? 'active' : '' }}" href="{{ route('baiviet.index') }}">Danh Sách Bài Viết</a></li>

                </ul>
              </li>

              {{-- // -- Quản lý thông báo --// --}}
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><img src="{{asset('img/icons/notification-bing.svg')}}" alt="img" /><span>
                    Quản lý thông báo</span
                  >
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                  {{-- //----Thông báo ---// --}}
                  <li><a class="{{ request()->routeIs('thongbao.index') || request()->routeIs('thongbao.edit') || request()->routeIs('thongbao.create') || request()->routeIs('thongbao.show')  ? 'active' : '' }}" href="{{ route('thongbao.index') }}">Danh sách thông báo</a></li>

                </ul>
              </li>

              {{-- // -- Quản lý sự kiện --// --}}
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><img src="{{asset('img/icons/event-promotion-sale.svg')}}" alt="img" /><span>
                    Quản lý sự kiện</span
                  >
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                    <li><a class="{{ request()->routeIs('chuongtrinh.index') || request()->routeIs('chuongtrinh.edit') || request()->routeIs('chuongtrinh.create') || request()->routeIs('chuongtrinh.show') ? 'active' : '' }}" href="{{ route('chuongtrinh.index') }}">Danh Sách Chương Trình Sự Kiện</a></li>
                    <li><a class="{{ request()->routeIs('quangcao.index') || request()->routeIs('quangcao.edit') || request()->routeIs('quangcao.create') || request()->routeIs('quangcao.show') ? 'active' : '' }}" href="{{ route('quangcao.index') }}">Danh sách Banner<br>Quảng Cáo(trang chủ)</a></li>

                </ul>
              </li>

              {{-- // -- Quản lý mã giảm giá --// --}}
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><img src="{{asset('img/icons/voucher-discount-tag-svgrepo-com.svg')}}" alt="img" /><span>
                    Quản lý mã giảm giá</span
                  >
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                  <li><a class="{{ request()->routeIs('danhsach.magiamgia') || request()->routeIs('create.magiamgia') || request()->routeIs('edit.magiamgia') || request()->routeIs('magiamgia.show') || request()->routeIs('magiamgia.trash')   ? 'active' : '' }}" href="{{ route('danhsach.magiamgia') }}">Danh Sách Mã Giảm Giá</a></li>

                </ul>
              </li>














              <li class="submenu">
                <a href="javascript:void(0);"
                  ><img src="{{asset('img/icons/settings.svg')}}" alt="img" /><span>
                    Cài đặt</span
                  >
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                  {{-- //----Trang Nội Dung ---// --}}
                  <li><a class="{{ request()->routeIs('trangnoidung.index') || request()->routeIs('trangnoidung.edit') || request()->routeIs('trangnoidung.create') || request()->routeIs('trangnoidung.show')  ? 'active' : '' }}" href="{{ route('trangnoidung.index') }}">Danh sách trang nội dung</a></li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </div>

         @yield('content')

    </div>


    <script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>

    <script src="{{asset('js/feather.min.js')}}"></script>

    <script src="{{asset('js/jquery.slimscroll.min.js')}}"></script>

    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>

    <script src="{{asset('plugins/apexchart/apexcharts.min.js')}}"></script>
    <script src="{{asset('plugins/apexchart/chart-data.js')}}"></script>

    <script src="{{asset('plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{ asset('plugins/select2/js/custom-select.js') }}"></script>

    <script src="{{asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('plugins/sweetalert/sweetalerts.min.js')}}"></script>

    <script src="{{ asset('plugins/dragula/js/dragula.min.js') }}"></script>
    <script src="{{ asset('plugins/dragula/js/drag-drop.min.js') }}"></script>

    <script src="{{asset('plugins/fileupload/fileupload.min.js')}}"></script>

    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>

    <script src="{{ asset('plugins/owlcarousel/owl.carousel.min.js') }}"></script>

    <script src="{{ asset('plugins/lightbox/glightbox.min.js') }}"></script>
    <script src="{{ asset('plugins/lightbox/lightbox.js') }}"></script>

    <script src="https://cdn.ckeditor.com/ckeditor5/46.0.2/ckeditor5.umd.js" crossorigin></script>
		<script src="https://cdn.ckeditor.com/ckeditor5/46.0.2/translations/vi.umd.js" crossorigin></script>

    <script src="{{asset('js/script.js')}}"></script>
    <script src="{{asset('js/child_layouts.js')}}"></script>
    <script>
      /**
        * This configuration was generated using the CKEditor 5 Builder. You can modify it anytime using this link:
        * https://ckeditor.com/ckeditor-5/builder/?redirect=portal#installation/NoNgNARATAdA7DAzBSU5RAFgIwAZeYCcucAHNnAKzoXG6mlyGam6FSVQOUgoQBuASxS4wwbGFGiJ0gLqQApgEMARriVLCEWUA===
        */

        const {
          ClassicEditor,
          Alignment,
          AutoImage,
          AutoLink,
          Autosave,
          BlockQuote,
          Bold,
          Bookmark,
          Code,
          CodeBlock,
          Essentials,
          FindAndReplace,
          FontBackgroundColor,
          FontColor,
          FontFamily,
          FontSize,
          GeneralHtmlSupport,
          Heading,
          Highlight,
          HorizontalLine,
          ImageBlock,
          ImageCaption,
          ImageInsert,
          ImageInsertViaUrl,
          ImageToolbar,
          ImageUpload,
          Indent,
          IndentBlock,
          Italic,
          Link,
          LinkImage,
          Markdown,
          Paragraph,
          PasteFromOffice,
          RemoveFormat,
          SimpleUploadAdapter,
          SpecialCharacters,
          SpecialCharactersArrows,
          SpecialCharactersCurrency,
          SpecialCharactersEssentials,
          SpecialCharactersLatin,
          SpecialCharactersMathematical,
          SpecialCharactersText,
          Strikethrough,
          Style,
          Subscript,
          Superscript,
          Table,
          TableCaption,
          TableCellProperties,
          TableColumnResize,
          TableProperties,
          TableToolbar,
          TextPartLanguage,
          Underline,
          WordCount
        } = window.CKEDITOR;

        const LICENSE_KEY =
          'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3ODczNTY3OTksImp0aSI6IjM3OTJmZDBhLTgxOTQtNDRlZi05MDFmLTRhMjAwOWQ2NDAyMiIsImxpY2Vuc2VkSG9zdHMiOlsiMTI3LjAuMC4xIiwibG9jYWxob3N0IiwiMTkyLjE2OC4qLioiLCIxMC4qLiouKiIsIjE3Mi4qLiouKiIsIioudGVzdCIsIioubG9jYWxob3N0IiwiKi5sb2NhbCJdLCJ1c2FnZUVuZHBvaW50IjoiaHR0cHM6Ly9wcm94eS1ldmVudC5ja2VkaXRvci5jb20iLCJkaXN0cmlidXRpb25DaGFubmVsIjpbImNsb3VkIiwiZHJ1cGFsIl0sImxpY2Vuc2VUeXBlIjoiZGV2ZWxvcG1lbnQiLCJmZWF0dXJlcyI6WyJEUlVQIiwiRTJQIiwiRTJXIl0sInZjIjoiNDExMjYxNTkifQ.9YG3z9toN0pcTRdXGXTj42xVsK5KTxt-ZUqJpw-6QeaoPtDbkgbY3CjMdxWNi7Dorln7D8yWImhoSrcWHIqZEg';

        const editorConfig = {
          toolbar: {
            items: [
              'undo',
              'redo',
              '|',
              'findAndReplace',
              'textPartLanguage',
              '|',
              'heading',
              'style',
              '|',
              'fontSize',
              'fontFamily',
              'fontColor',
              'fontBackgroundColor',
              '|',
              'bold',
              'italic',
              'underline',
              'strikethrough',
              'subscript',
              'superscript',
              'code',
              'removeFormat',
              '|',
              'specialCharacters',
              'horizontalLine',
              'link',
              'bookmark',
              'insertImage',
              'insertTable',
              'highlight',
              'blockQuote',
              'codeBlock',
              '|',
              'alignment',
              '|',
              'outdent',
              'indent'
            ],
            shouldNotGroupWhenFull: false
          },
          plugins: [
            Alignment,
            AutoImage,
            AutoLink,
            Autosave,
            BlockQuote,
            Bold,
            Bookmark,
            Code,
            CodeBlock,
            Essentials,
            FindAndReplace,
            FontBackgroundColor,
            FontColor,
            FontFamily,
            FontSize,
            GeneralHtmlSupport,
            Heading,
            Highlight,
            HorizontalLine,
            ImageBlock,
            ImageCaption,
            ImageInsert,
            ImageInsertViaUrl,
            ImageToolbar,
            ImageUpload,
            Indent,
            IndentBlock,
            Italic,
            Link,
            LinkImage,
            Markdown,
            Paragraph,
            PasteFromOffice,
            RemoveFormat,
            SimpleUploadAdapter,
            SpecialCharacters,
            SpecialCharactersArrows,
            SpecialCharactersCurrency,
            SpecialCharactersEssentials,
            SpecialCharactersLatin,
            SpecialCharactersMathematical,
            SpecialCharactersText,
            Strikethrough,
            Style,
            Subscript,
            Superscript,
            Table,
            TableCaption,
            TableCellProperties,
            TableColumnResize,
            TableProperties,
            TableToolbar,
            TextPartLanguage,
            Underline,
            WordCount
          ],
          fontFamily: {
            supportAllValues: true
          },
          fontSize: {
            options: [10, 12, 14, 'default', 18, 20, 22],
            supportAllValues: true
          },
          heading: {
            options: [
              {
                model: 'paragraph',
                title: 'Paragraph',
                class: 'ck-heading_paragraph'
              },
              {
                model: 'heading1',
                view: 'h1',
                title: 'Heading 1',
                class: 'ck-heading_heading1'
              },
              {
                model: 'heading2',
                view: 'h2',
                title: 'Heading 2',
                class: 'ck-heading_heading2'
              },
              {
                model: 'heading3',
                view: 'h3',
                title: 'Heading 3',
                class: 'ck-heading_heading3'
              },
              {
                model: 'heading4',
                view: 'h4',
                title: 'Heading 4',
                class: 'ck-heading_heading4'
              },
              {
                model: 'heading5',
                view: 'h5',
                title: 'Heading 5',
                class: 'ck-heading_heading5'
              },
              {
                model: 'heading6',
                view: 'h6',
                title: 'Heading 6',
                class: 'ck-heading_heading6'
              }
            ]
          },
          htmlSupport: {
            allow: [
              {
                name: /^.*$/,
                styles: true,
                attributes: true,
                classes: true
              }
            ]
          },
          image: {
            toolbar: ['toggleImageCaption']
          },
          language: 'vi',
          licenseKey: LICENSE_KEY,
          link: {
            addTargetToExternalLinks: true,
            defaultProtocol: 'https://',
            decorators: {
              toggleDownloadable: {
                mode: 'manual',
                label: 'Downloadable',
                attributes: {
                  download: 'file'
                }
              }
            }
          },
          placeholder: 'Type or paste your content here!',
          style: {
            definitions: [
              {
                name: 'Article category',
                element: 'h3',
                classes: ['category']
              },
              {
                name: 'Title',
                element: 'h2',
                classes: ['document-title']
              },
              {
                name: 'Subtitle',
                element: 'h3',
                classes: ['document-subtitle']
              },
              {
                name: 'Info box',
                element: 'p',
                classes: ['info-box']
              },
              {
                name: 'CTA Link Primary',
                element: 'a',
                classes: ['button', 'button--green']
              },
              {
                name: 'CTA Link Secondary',
                element: 'a',
                classes: ['button', 'button--black']
              },
              {
                name: 'Marker',
                element: 'span',
                classes: ['marker']
              },
              {
                name: 'Spoiler',
                element: 'span',
                classes: ['spoiler']
              }
            ]
          },
          table: {
            contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties', 'tableCellProperties']
          }
        };

        ClassicEditor.create(document.querySelector('#editor'), editorConfig).then(editor => {
          const wordCount = editor.plugins.get('WordCount');
          document.querySelector('#editor-word-count').appendChild(wordCount.wordCountContainer);

          return editor;
        });

    </script>
    @yield('scripts')
  </body>
</html>

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

          <li class="nav-item dropdown">
            <a
              href="javascript:void(0);"
              class="dropdown-toggle nav-link"
              data-bs-toggle="dropdown"
            >
              <img src="{{asset('img/icons/notification-bing.svg')}}" alt="img" />
              <span class="badge rounded-pill">4</span>
            </a>
            <div class="dropdown-menu notifications">
              <div class="topnav-dropdown-header">
                <span class="notification-title"><b>Thông báo</b></span>
              </div>
              <div class="noti-content">
                <ul class="notification-list">

                  <li class="notification-message">
                    <a href="activities.html">
                      <div class="media d-flex">

                        <div class="media-body flex-grow-1">
                          <p class="noti-details">
                            Hiện có <span class="noti-title">3</span> sản phẩm đã hết hạn !
                          </p>
                          <p class="noti-time">
                            <span class="notification-time"><u>nhấn để xem chi tiết</u></span>
                          </p>
                        </div>
                      </div>
                    </a>
                  </li>

                </ul>
              </div>
              <div class="topnav-dropdown-footer">
                <a href="activities.html">Xem tất cả thông báo </a>
              </div>
            </div>
          </li>

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
                    <h6>Tây Ninh Quán</h6>
                    <h5>Quản trị viên</h5>
                  </div>
                </div>
                <hr class="m-0" />
                <a class="dropdown-item" href="profile.html">
                  <i class="me-2" data-feather="user"></i> Hồ sơ</a
                >
                <a class="dropdown-item" href="generalsettings.html"
                  ><i class="me-2" data-feather="settings"></i>Cài đặt</a
                >
                <hr class="m-0" />
                <a class="dropdown-item logout pb-0" href="signin.html"
                  ><img
                    src="{{asset('img/icons/log-out.svg')}}"
                    class="me-2"
                    alt="img"
                  />Đăng xuất</a
                >
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
            <a class="dropdown-item" href="profile.html">Hồ sơ</a>
            <a class="dropdown-item" href="generalsettings.html">Cài đặt</a>
            <a class="dropdown-item" href="signin.html">Đăng xuất</a>
          </div>
        </div>
      </div>

      <div class="sidebar" id="sidebar">
        <div class="sidebar-inner slimscroll">
          <div id="sidebar-menu" class="sidebar-menu">
            <ul>
              <li class="{{ request()->routeIs('trang-chu') ? 'active' : '' }}">
                <a href="/"
                  ><img src="{{asset('img/icons/dashboard.svg')}}" alt="img" /><span>
                    Tổng quan</span
                  >
                </a>
              </li>
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><img src="{{asset('img/icons/product.svg')}}" alt="img" /><span>
                    Sản phẩm</span
                  >
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                  <li><a class="{{ request()->routeIs('danh-sach') || request()->routeIs('chinh-sua-san-pham') || request()->routeIs('tao-san-pham') || request()->routeIs('chi-tiet-san-pham') ? 'active' : '' }}" href="{{ route('danh-sach') }}">Danh sách sản phẩm</a></li>
                  <li><a class="{{ request()->routeIs('danh-sach-danh-muc') || request()->routeIs('chinh-sua-danh-muc') || request()->routeIs('tao-danh-muc') ? 'active' : '' }}" href="{{ route('danh-sach-danh-muc') }}">Danh mục sản phẩm</a></li>
                  <li><a class="{{ request()->routeIs('danh-sach-thuong-hieu') || request()->routeIs('chinh-sua-thuong-hieu') || request()->routeIs('tao-thuong-hieu') ? 'active' : '' }}" href="{{ route('danh-sach-thuong-hieu') }}">Thương hiệu sản phẩm</a></li>
                  <li><a class="{{ request()->routeIs('danh-sach-kho-hang') ? 'active' : '' }}" href="{{ route('danh-sach-kho-hang') }}">Kho hàng</a></li>
                </ul>
              </li>
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><img src="{{asset('img/icons/sales1.svg')}}" alt="img" /><span>
                    Bán hàng</span
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
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><img src="{{asset('img/icons/expense1.svg')}}" alt="img" /><span>
                    Thanh toán</span
                  >
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                  <li><a href="expenselist.html">Phương thức thanh toán</a></li>
                  <li><a href="createexpense.html">Lịch sử thanh toán</a></li>
                </ul>
              </li>
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><img src="{{asset('img/icons/users1.svg')}}" alt="img" /><span>
                    Người dùng</span
                  >
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                    {{-- /* ===================== KHÁCH HÀNG ===================== */ --}}
                  <li><a class="{{ request()->routeIs('danh-sach-khach-hang') || request()->routeIs('chinh-sua-khach-hang') || request()->routeIs('tao-khach-hang') || request()->routeIs('chi-tiet-khach-hang') ? 'active' : '' }}" href="{{ route('danh-sach-khach-hang') }}">Danh sách khách hàng</a></li>
                  {{-- /* ===================== CỬA HÀNG ===================== */ --}}
                  <li><a class="{{ request()->routeIs('danh-sach-cua-hang') || request()->routeIs('chinh-sua-cua-hang') || request()->routeIs('tao-cua-hang') || request()->routeIs('chi-tiet-cua-hang') ? 'active' : '' }}" href="{{ route('danh-sach-cua-hang') }}">Danh sách cửa hàng</a></li>
                  {{-- /* ===================== ĐỘI NGŨ QUẢN TRỊ ===================== */ --}}
                  <li><a class="{{ request()->routeIs('danh-sach-doi-ngu-quan-tri') || request()->routeIs('chinh-sua-doi-ngu-quan-tri')  || request()->routeIs('chi-tiet-doi-ngu-quan-tri') ? 'active' : '' }}" href="{{ route('danh-sach-doi-ngu-quan-tri') }}">Đội ngũ quản trị</a></li>
                </ul>
              </li>
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><img src="{{asset('img/icons/quotation1.svg')}}" alt="img" /><span>
                    Quotation</span
                  >
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                  <li><a href="quotationList.html">Quotation List</a></li>
                  <li><a href="addquotation.html">Add Quotation</a></li>
                </ul>
              </li>
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><img src="{{asset('img/icons/transfer1.svg')}}" alt="img" /><span>
                    Transfer</span
                  >
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                  <li><a href="transferlist.html">Transfer List</a></li>
                  <li><a href="addtransfer.html">Add Transfer </a></li>
                  <li><a href="importtransfer.html">Import Transfer </a></li>
                </ul>
              </li>
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><img src="{{asset('img/icons/return1.svg')}}" alt="img" /><span>
                    Return</span
                  >
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                  <li><a href="salesreturnlist.html">Sales Return List</a></li>
                  <li>
                    <a href="createsalesreturn.html">Add Sales Return </a>
                  </li>
                  <li>
                    <a href="purchasereturnlist.html">Purchase Return List</a>
                  </li>
                  <li>
                    <a href="createpurchasereturn.html">Add Purchase Return </a>
                  </li>
                </ul>
              </li>

              <li class="submenu">
                <a href="javascript:void(0);"
                  ><img src="{{asset('img/icons/places.svg')}}" alt="img" /><span>
                    Places</span
                  >
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                  <li><a href="newcountry.html">New Country</a></li>
                  <li><a href="countrieslist.html">Countries list</a></li>
                  <li><a href="newstate.html">New State </a></li>
                  <li><a href="statelist.html">State list</a></li>
                </ul>
              </li>
              <li>
                <a href="components.html"
                  ><i data-feather="layers"></i><span> Components</span>
                </a>
              </li>
              <li>
                <a href="blankpage.html"
                  ><i data-feather="file"></i><span> Blank Page</span>
                </a>
              </li>
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><i data-feather="alert-octagon"></i>
                  <span> Error Pages </span> <span class="menu-arrow"></span
                ></a>
                <ul>
                  <li><a href="error-404.html">404 Error </a></li>
                  <li><a href="error-500.html">500 Error </a></li>
                </ul>
              </li>
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><i data-feather="box"></i> <span>Elements </span>
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                  <li><a href="sweetalerts.html">Sweet Alerts</a></li>
                  <li><a href="tooltip.html">Tooltip</a></li>
                  <li><a href="popover.html">Popover</a></li>
                  <li><a href="ribbon.html">Ribbon</a></li>
                  <li><a href="clipboard.html">Clipboard</a></li>
                  <li><a href="drag-drop.html">Drag & Drop</a></li>
                  <li><a href="rangeslider.html">Range Slider</a></li>
                  <li><a href="rating.html">Rating</a></li>
                  <li><a href="toastr.html">Toastr</a></li>
                  <li><a href="text-editor.html">Text Editor</a></li>
                  <li><a href="counter.html">Counter</a></li>
                  <li><a href="scrollbar.html">Scrollbar</a></li>
                  <li><a href="spinner.html">Spinner</a></li>
                  <li><a href="notification.html">Notification</a></li>
                  <li><a href="lightbox.html">Lightbox</a></li>
                  <li><a href="stickynote.html">Sticky Note</a></li>
                  <li><a href="timeline.html">Timeline</a></li>
                  <li><a href="form-wizard.html">Form Wizard</a></li>
                </ul>
              </li>
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><i data-feather="bar-chart-2"></i> <span> Charts </span>
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                  <li><a href="chart-apex.html">Apex Charts</a></li>
                  <li><a href="chart-js.html">Chart Js</a></li>
                  <li><a href="chart-morris.html">Morris Charts</a></li>
                  <li><a href="chart-flot.html">Flot Charts</a></li>
                  <li><a href="chart-peity.html">Peity Charts</a></li>
                </ul>
              </li>
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><i data-feather="award"></i><span> Icons </span>
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                  <li><a href="icon-fontawesome.html">Fontawesome Icons</a></li>
                  <li><a href="icon-feather.html">Feather Icons</a></li>
                  <li><a href="icon-ionic.html">Ionic Icons</a></li>
                  <li><a href="icon-material.html">Material Icons</a></li>
                  <li><a href="icon-pe7.html">Pe7 Icons</a></li>
                  <li><a href="icon-simpleline.html">Simpleline Icons</a></li>
                  <li><a href="icon-themify.html">Themify Icons</a></li>
                  <li><a href="icon-weather.html">Weather Icons</a></li>
                  <li><a href="icon-typicon.html">Typicon Icons</a></li>
                  <li><a href="icon-flag.html">Flag Icons</a></li>
                </ul>
              </li>
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><i data-feather="columns"></i> <span> Forms </span>
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                  <li><a href="form-basic-inputs.html">Basic Inputs </a></li>
                  <li><a href="form-input-groups.html">Input Groups </a></li>
                  <li><a href="form-horizontal.html">Horizontal Form </a></li>
                  <li><a href="form-vertical.html"> Vertical Form </a></li>
                  <li><a href="form-mask.html">Form Mask </a></li>
                  <li><a href="form-validation.html">Form Validation </a></li>
                  <li><a href="form-select2.html">Form Select2 </a></li>
                  <li><a href="form-fileupload.html">File Upload </a></li>
                </ul>
              </li>
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><i data-feather="layout"></i> <span> Table </span>
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                  <li><a href="tables-basic.html">Basic Tables </a></li>
                  <li><a href="data-tables.html">Data Table </a></li>
                </ul>
              </li>
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><img src="{{asset('img/icons/product.svg')}}" alt="img" /><span>
                    Application</span
                  >
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                  <li><a href="chat.html">Chat</a></li>
                  <li><a href="calendar.html">Calendar</a></li>
                  <li><a href="email.html">Email</a></li>
                </ul>
              </li>
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><img src="{{asset('img/icons/time.svg')}}" alt="img" /><span>
                    Report</span
                  >
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                  <li>
                    <a href="purchaseorderreport.html">Purchase order report</a>
                  </li>
                  <li><a href="inventoryreport.html">Inventory Report</a></li>
                  <li><a href="salesreport.html">Sales Report</a></li>
                  <li><a href="invoicereport.html">Invoice Report</a></li>
                  <li><a href="purchasereport.html">Purchase Report</a></li>
                  <li><a href="supplierreport.html">Supplier Report</a></li>
                  <li><a href="customerreport.html">Customer Report</a></li>
                </ul>
              </li>
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><img src="{{asset('img/icons/users1.svg')}}" alt="img" /><span>
                    Users</span
                  >
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                  <li><a href="newuser.html">New User </a></li>
                  <li><a href="userlists.html">Users List</a></li>
                </ul>
              </li>
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><img src="{{asset('img/icons/settings.svg')}}" alt="img" /><span>
                    Settings</span
                  >
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                  <li><a href="generalsettings.html">General Settings</a></li>
                  <li><a href="emailsettings.html">Email Settings</a></li>
                  <li><a href="paymentsettings.html">Payment Settings</a></li>
                  <li><a href="currencysettings.html">Currency Settings</a></li>
                  <li><a href="grouppermissions.html">Group Permissions</a></li>
                  <li><a href="taxrates.html">Tax Rates</a></li>
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

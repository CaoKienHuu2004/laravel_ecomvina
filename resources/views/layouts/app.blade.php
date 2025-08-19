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

    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}" />

    <link
      rel="stylesheet"
      href="{{asset('plugins/fontawesome/css/fontawesome.min.css')}}"
    />
    <link rel="stylesheet" href="{{asset('plugins/fontawesome/css/all.min.css')}}" />

    <link rel="stylesheet" href="{{asset('css/style.css')}}" />
  </head>
  <body>
    <div id="global-loader">
      <div class="whirly-loader"></div>
    </div>

    <div class="main-wrapper">
      <div class="header">
        <div class="header-left active">
          <a href="{{ url('/') }}" class="logo">
            <img src="{{asset('img/logo.png')}}" alt="" />
          </a>
          <a href="{{ url('/') }}" class="logo-small">
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
          <li class="nav-item">
            <div class="top-nav-search">
              <a href="javascript:void(0);" class="responsive-search">
                <i class="fa fa-search"></i>
              </a>
              <form action="#">
                <div class="searchinputs">
                  <input type="text" placeholder="Tìm kiếm ..." />
                  <div class="search-addon">
                    <span
                      ><img src="{{asset('img/icons/closes.svg')}}" alt="img"
                    /></span>
                  </div>
                </div>
                <a class="btn" id="searchdiv"
                  ><img src="{{asset('img/icons/search.svg')}}" alt="img"
                /></a>
              </form>
            </div>
          </li>

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
              <li class="{{ Request::is('/') ? 'strong' : 'submenu' }}">
                <a href="{{ url('/') }}"
                  ><img src="{{asset('img/icons/dashboard.svg')}}" alt="img" /><span>
                    Tổng quan</span
                  >
                </a>
              </li>
              <li class="{{ Request::is('danh-sach-san-pham') || Request::is('danh-muc') || Request::is('thuong-hieu') ? 'submenu' : 'submenu' }}">
                <a href="javascript:void(0);"
                  ><img src="{{asset('img/icons/product.svg')}}" alt="img" /><span>
                    Sản phẩm</span
                  >
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                  <li><a href="{{ route('danh-sach') }}">Danh sách sản phẩm</a></li>
                  <li><a href="{{ route('tao-san-pham') }}">Thêm sản phẩm</a></li>
                  <li><a href="categorylist.html">Danh mục sản phẩm</a></li>
                  <li><a href="brandlist.html">Thương hiệu sản phẩm</a></li>
                  <li><a href="barcode.html">In Barcode</a></li>
                </ul>
              </li>
              <li class="{{ Request::is('/don-hang') ? 'active' : 'submenu' }}">
                <a href="javascript:void(0);"
                  ><img src="{{asset('img/icons/sales1.svg')}}" alt="img" /><span>
                    Bán hàng</span
                  >
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                  <li><a href="saleslist.html">Danh sách hàng đã bán</a></li>
                  <li><a href="pos.html">Trạm POS</a></li>
                  <li><a href="pos.html">Danh sách hóa đơn</a></li>
                </ul>
              </li>
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><img src="{{asset('img/icons/expense1.svg')}}" alt="img" /><span>
                    Chi phí</span
                  >
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                  <li><a href="expenselist.html">Expense List</a></li>
                  <li><a href="createexpense.html">Add Expense</a></li>
                  <li><a href="expensecategory.html">Expense Category</a></li>
                </ul>
              </li>
              <li class="submenu">
                <a href="javascript:void(0);"
                  ><img src="{{asset('mg/icons/quotation1.svg')}}i" alt="img" /><span>
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
                  ><img src="{{asset('img/icons/users1.svg')}}" alt="img" /><span>
                    People</span
                  >
                  <span class="menu-arrow"></span
                ></a>
                <ul>
                  <li><a href="customerlist.html">Customer List</a></li>
                  <li><a href="addcustomer.html">Add Customer </a></li>
                  <li><a href="supplierlist.html">Supplier List</a></li>
                  <li><a href="addsupplier.html">Add Supplier </a></li>
                  <li><a href="userlist.html">User List</a></li>
                  <li><a href="adduser.html">Add User</a></li>
                  <li><a href="storelist.html">Store List</a></li>
                  <li><a href="addstore.html">Add Store</a></li>
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
    @yield('scripts')

    <script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>

    <script src="{{asset('js/feather.min.js')}}"></script>

    <script src="{{asset('js/jquery.slimscroll.min.js')}}"></script>

    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap4.min.js')}}"></script>

    <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>

    <script src="{{asset('plugins/apexchart/apexcharts.min.js')}}"></script>
    <script src="{{asset('plugins/apexchart/chart-data.js')}}"></script>
    
    <script src="{{asset('plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{ asset('plugins/select2/js/custom-select.js') }}"></script>

    <script src="{{asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('plugins/sweetalert/sweetalerts.min.js')}}"></script>

    <script src="{{asset('plugins/fileupload/fileupload.min.js')}}"></script>

    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
    
    <script src="{{asset('js/script.js')}}"></script>
    
  </body>
</html>

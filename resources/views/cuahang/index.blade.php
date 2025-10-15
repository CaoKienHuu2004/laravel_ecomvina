@extends('layouts.app')

@section('title','Danh sách cửa hàng | Quản trị hệ thống Siêu Thị Vina')

@section('content')

<div class="page-wrapper">
        <div class="content">
          <div class="page-header">
            <div class="page-title">
              <h4>Danh sách cửa hàng</h4>
              <h6>Quản lý thông tin cửa hàng của bạn.</h6>
            </div>
            <div class="page-btn">
                <a href="{{route('tao-cua-hang')}}" class="btn btn-added">
                    <img src="{{asset('')}}img/icons/plus.svg" alt="img" />Thêm cửa hàng
                </a>
            </div>
          </div>

            <div class="error-log">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                    </div>
                @endif
            </div>

          <div class="card">
            <div class="card-body">
              <div class="table-top">
                <div class="search-set">
                  <div class="search-input">
                    <a class="btn btn-searchset"
                      ><img src="{{asset('')}}img/icons/search-white.svg" alt="img"
                    /></a>
                  </div>
                </div>

              </div>



              <div class="table-responsive">
                <table class="table datanew">
                  <thead>
                    <tr>
                      <th>Tên người bán hàng</th>
                      <th>email (tài khoản)</th>
                      {{-- <th>Banner</th> --}}
                      <th>SĐT (tài khoản)</th>
                      <th>Địa chỉ (tài khoản)</th>
                      <th>Shop</th>
                      <th>Trạng thái</th>
                      <th>Hành động</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($danhsach as $ds)

                      <tr>
                        <td>
                            @if ($ds  && $ds->hoten)
                                <p class="text-success">{{$ds->hoten}}</p>
                            @else
                                <p class="text-success">Không có</p>
                            @endif
                            {{-- @if ($ds->thongTinNguoiBanHang && $ds->thongTinNguoiBanHang->logo)
                                @php
                                    $logo = $ds->thongTinNguoiBanHang->logo;
                                    $isUrl = filter_var($logo, FILTER_VALIDATE_URL);
                                @endphp

                                @if ($isUrl)
                                    <img src="{{ $logo }}"
                                        alt="Logo"
                                        width="60" height="60"
                                        style="object-fit: cover; border-radius: 8px;">
                                @else
                                    <img src="{{ asset('storage/' . $logo) }}"
                                        alt="Logo"
                                        width="60" height="60"
                                        style="object-fit: cover; border-radius: 8px;">
                                @endif
                            @else
                                <img src="{{ asset('storage/uploads/cuahang/logo/logo.png') }}"alt="logo" width="100" height="60"
                                    style="object-fit: cover; border-radius: 8px;">
                            @endif --}}
                        </td>
                        <td>
                            @if ($ds->email && $ds->email)
                                <a href="mailto:{{$ds->email}}" class="__cf_email__" data-cfemail="1165797e7c7062517469707c617d743f727e7c">
                                    <pre>{!! wordwrap($ds->email, 30, '<br>') !!}</pre>
                                </a>
                            @else
                                <p class="text-success">Không có</p>
                            @endif
                          {{-- <a href="mailto:{{$ds->email}}" class="__cf_email__" data-cfemail="1165797e7c7062517469707c617d743f727e7c">
                              {{$ds->email}}
                          </a>
                          <p>{{$ds->sodienthoai}}</p>
                          <p><a href="javascript:void(0);">{{ $ds->hoten }}</a></p> --}}
                            {{-- <div class="productimgname">

                                <a href="javascript:void(0);" class="product-img">
                                    @if($ds->avatar)
                                        @php
                                            $isUrl = filter_var($ds->avatar, FILTER_VALIDATE_URL);
                                        @endphp

                                        @if($isUrl)
                                            <img src="{{ $ds->avatar }}"
                                                alt="Avatar"
                                                style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                        @else
                                            <img src="{{ asset('storage/' . $ds->avatar) }}"
                                                alt="Avatar"
                                                style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                        @endif
                                    @else
                                        <img src="storage/uploads/nguoidung/avatar/nguoidung.png"
                                                alt="Avatar"
                                                style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                    @endif
                                </a>

                            </div> --}}
                        </td>
                        <td>
                            @if ($ds->sodienthoai)

                                <pre>{!! wordwrap($ds->sodienthoai, 30, '<br>') !!}</pre>

                            @else
                                <p class="text-success">Không có</p>
                            @endif
                        </td>
                        {{-- <td>
                            @if ($ds->thongTinNguoiBanHang && $ds->thongTinNguoiBanHang->bianen)
                                @php
                                    $banner = $ds->thongTinNguoiBanHang->bianen;
                                    $isUrl = filter_var($banner, FILTER_VALIDATE_URL);
                                @endphp

                                @if($isUrl)
                                    <img src="{{ $banner }}"
                                        alt="Banner"
                                        width="100" height="60"
                                        style="object-fit: cover; border-radius: 8px;">
                                @else
                                    <img src="{{ asset('storage/' . $banner) }}"
                                        alt="Banner"
                                        width="100" height="60"
                                        style="object-fit: cover; border-radius: 8px;">
                                @endif
                            @else
                                <img src="{{ asset('storage/uploads/cuahang/bianen/bianen.png') }}"
                                        alt="Banner"
                                        width="100" height="60"
                                        style="object-fit: cover; border-radius: 8px;">
                            @endif
                        </td> --}}
                        <td>
                            @if ($ds->diachi->count() > 0)
                                @foreach ($ds->diachi as $key => $dc )

                                    <pre><strong>ĐC{{$key+1}}:</strong> {!! wordwrap($dc->diachi, 30, '<br>') !!}</pre>
                                @endforeach
                            @else
                                <p class="text-success">Không có</p>
                            @endif
                            {{-- <div>
                                <p class="text-success">Địa chỉ của tài khoản</p>


                                @if ($ds->diachi->count() > 0)
                                @foreach ($ds->diachi as $dc)
                                    <pre>{!! wordwrap($dc->diachi, 30, '<br>') !!}</pre>
                                @endforeach
                                @else
                                    <span class="text-muted">
                                        <a href="#">Chưa cập nhật</a>
                                    </span>
                                @endif
                            </div>
                            <div class="">
                                <p class="text-success">
                                    Địa chỉ Cửa Hàng
                                </p>
                                @if ($ds->thongTinNguoiBanHang  && $ds->thongTinNguoiBanHang->diachi)
                                    {!! wordwrap($ds->thongTinNguoiBanHang ->diachi, 30, '<br>') !!}
                                @else
                                    <span class="text-muted"><a href="#">Chưa cập nhật</a></span>
                                @endif
                            </div> --}}
                        </td>
                        {{-- thông tin shop --}}
                        <td>
                            @php
                                $cuaHang = $ds->thongTinNguoiBanHang;
                                $thuongHieuCount = $cuaHang ? 1 : 0;
                                $sanPhamCount = $cuaHang && $cuaHang->sanpham ? $cuaHang->sanpham->count() : 0;
                            @endphp

                            {{-- Sản phẩm --}}  {{-- Thương hiệu --}} {{-- mockup chi tiết --}}
                            <span style="cursor: pointer;"
                                data-bs-toggle="modal"
                                data-bs-target="#modalCuaHang{{ $ds->id }}">
                                <span style="color: {{ $thuongHieuCount > 0 ? 'blue' : '#aaa' }};">
                                    &#128278; {{ $thuongHieuCount }}
                                </span>
                                <span style="color: {{ $sanPhamCount > 0 ? 'green' : '#aaa' }}; margin-left: 10px;">
                                    &#128230; {{ $sanPhamCount }}
                                </span>
                            </span>
                            <!-- Modal chi tiết -->
                            <div class="modal fade" id="modalCuaHang{{ $ds->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $ds->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalLabel{{ $ds->id }}">{{ $cuaHang->ten_cuahang ?? 'Chưa có tên cửa hàng' }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                        </div>
                                        <div class="modal-body">
                                            <img src="{{ asset('storage/' . ($cuaHang->logo ?? 'uploads/cuahang/logo/logo.png')) }}"
                                                width="80" height="80" style="border-radius:50%;object-fit:cover;">
                                            <p><strong>Mô tả:</strong> {{ $cuaHang->mota ?? 'Chưa cập nhật' }}</p>
                                            <p><strong>Lượt theo dõi:</strong> {{ $cuaHang->theodoi ?? 0 }}</p>
                                            <p><strong>Lượt bán:</strong> {{ $cuaHang->luotban ?? 0 }}</p>
                                            <p><strong>Số sản phẩm:</strong> {{ $sanPhamCount }}</p>
                                            @if (!empty($ds->thongTinNguoiBanHang))
                                                {{-- <select class="form-select cap-nhat-trangthai-cuahang" data-id="{{$ds->id}}" style="width:150px">
                                                    <option value="hoat_dong" selected>Hoạt động</option>
                                                    <option value="ngung_hoat_dong">Ngừng hoạt động</option>
                                                    <option value="bi_khoa">Bị khóa</option>
                                                    <option value="cho_duyet">Chờ duyệt</option>
                                                </select> --}}
                                                <form class="form-cap-nhat-trang-thai-cua-hang" name="form-cap-nhat-trang-thai-cua-hang" action="{{ route('cap-nhat-trang-thai-cua-hang', $ds->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="trangthai" class="form-select form-select-sm" style="width:100px" onchange="this.form.submit()">
                                                        <option value="hoat_dong" {{ $ds->thongTinNguoiBanHang->trangthai == 'hoat_dong' ? 'selected' : '' }}>Hoạt động</option>
                                                        <option value="ngung_hoat_dong" {{ $ds->thongTinNguoiBanHang->trangthai == 'ngung_hoat_dong' ? 'selected' : '' }}>Ngừng hoạt động</option>
                                                        <option value="bi_khoa" {{ $ds->thongTinNguoiBanHang->trangthai == 'bi_khoa' ? 'selected' : '' }}>Bị khóa</option>
                                                        <option value="cho_duyet" {{ $ds->thongTinNguoiBanHang->trangthai == 'cho_duyet' ? 'selected' : '' }}>Chờ duyệt</option>
                                                    </select>
                                                </form>
                                                {{-- <form class="form-cap-nhat-trang-thai-cua-hang" data-id="{{ $ds->id }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="trangthai" class="form-select form-select-sm" style="width:100px">
                                                        <option value="hoat_dong" {{ $ds->trangthai == 'hoat_dong' ? 'selected' : '' }}>Hoạt động</option>
                                                        <option value="ngung_hoat_dong" {{ $ds->trangthai == 'ngung_hoat_dong' ? 'selected' : '' }}>Ngừng hoạt động</option>
                                                        <option value="bi_khoa" {{ $ds->trangthai == 'bi_khoa' ? 'selected' : '' }}>Bị khóa</option>
                                                        <option value="cho_duyet" {{ $ds->trangthai == 'cho_duyet' ? 'selected' : '' }}>Chờ duyệt</option>
                                                    </select>
                                                </form> --}}
                                            @endif
                                        </div>
                                        @if (empty($ds->thongTinNguoiBanHang))
                                            <div class="btn btn-primary">
                                                <a href="{{ route('chinh-sua-cua-hang', ['id' => $ds->id]) }}">Tạo cửa hàng cho tài khoản</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        {{-- <td>
                            @php
                                $cuaHang = $ds->thongTinNguoiBanHang;
                                $thuongHieuCount = $cuaHang ? 1 : 0;
                                $sanPhamCount = $cuaHang && $cuaHang->sanpham ? $cuaHang->sanpham->count() : 0;
                            @endphp
                            <span style="color: {{ $thuongHieuCount > 0 ? 'blue' : '#aaa' }};">
                                &#128278;
                                {{ $thuongHieuCount }}
                            </span>

                            <span style="color: {{ $sanPhamCount > 0 ? 'green' : '#aaa' }}; margin-left: 10px;">
                                &#128230;
                                {{ $sanPhamCount }}
                            </span>
                        --}}
                        <td>
                            <form action="{{ route('cap-nhat-trang-thai', $ds->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="trangthai" class="form-select form-select-sm" style="width:100px" onchange="this.form.submit()">
                                    <option value="hoat_dong" {{ $ds->trangthai == 'hoat_dong' ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="ngung_hoat_dong" {{ $ds->trangthai == 'ngung_hoat_dong' ? 'selected' : '' }}>Ngừng hoạt động</option>
                                    <option value="bi_khoa" {{ $ds->trangthai == 'bi_khoa' ? 'selected' : '' }}>Bị khóa</option>
                                    <option value="cho_duyet" {{ $ds->trangthai == 'cho_duyet' ? 'selected' : '' }}>Chờ duyệt</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <a class="me-3" href="{{ route('chi-tiet-cua-hang', ['id' => $ds->id]) }}" title="Xem chi tiết">
                                <img src="{{ asset('img/icons/eye.svg') }}" alt="img" />
                            </a>
                          <a class="me-3" href="{{ route('chinh-sua-cua-hang', ['id' => $ds->id]) }}">
                            <img src="{{asset('')}}img/icons/edit.svg" alt="img" />
                          </a>
                          <a class="me-3 confirm-text" href="javascript:void(0);">
                            <img src="{{asset('')}}img/icons/delete.svg" alt="img" />
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
@endsection
@section('scripts')
<style>
    .dt-buttons {
        display: none !important;
    }
</style>
@endsection


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('.form-cap-nhat-trang-thai');

    forms.forEach(form => {
        const select = form.querySelector('select');
        select.addEventListener('change', async function () {
            const id = form.dataset.id;
            const trangthai = this.value;
            const token = form.querySelector('input[name="_token"]').value;

            try {
                const res = await fetch(`/cua-hang/${id}/cap-nhat-trang-thai`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ trangthai })
                });

                const data = await res.json();

                if (res.ok) {
                    // ✅ Thông báo thành công
                    alert(data.message || 'Cập nhật trạng thái thành công!');
                } else {
                    // ⚠️ Nếu Laravel trả lỗi validation
                    alert(data.message || 'Lỗi khi cập nhật trạng thái!');
                }
            } catch (err) {
                console.error(err);
                alert('Lỗi hệ thống! Không thể cập nhật trạng thái.');
            }
        });
    });

    const forms = document.querySelectorAll('.form-cap-nhat-trang-thai-cua-hang');

    forms.forEach(form => {
        const select = form.querySelector('select');
        select.addEventListener('change', async function () {
            const id = form.dataset.id;
            const trangthai = this.value;
            const token = form.querySelector('input[name="_token"]').value;

            try {
                const res = await fetch(`/cua-hang/${id}/cap-nhat-trang-thai-cua-hang`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ trangthai })
                });

                const data = await res.json();

                if (res.ok) {
                    // ✅ Thông báo thành công
                    alert(data.message || 'Cập nhật trạng thái thành công!');
                } else {
                    // ⚠️ Nếu Laravel trả lỗi validation
                    alert(data.message || 'Lỗi khi cập nhật trạng thái!');
                }
            } catch (err) {
                console.error(err);
                alert('Lỗi hệ thống! Không thể cập nhật trạng thái.');
            }
        });
    });
});
</script>
@endsection


@extends('layouts.app')

@section('title', 'Chi Tiết Đơn Hàng | Quản trị hệ thống Siêu Thị Vina')
{{-- // controller truyền xuống $donhang ,$trangthais , $trangthais_thanhtoan  --}}
{{-- // các route sư dụng không có  --- của breadcrumb donhang.index trang-chu   --}}

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <x-header.breadcrumb
                title='Xem Chi Tiết Đơn Hàng "{{ $donhang->madon }}"'
                :links="[
                    ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                    ['label' => 'Danh sách đơn hàng', 'route' => 'donhang.index']
                ]"
                active="Chi tiết"
            />
        </div>
        <div class="card">
            <div class="card-body">

                <div class="card-sales-split">
                    <h2>Mã đơn hàng : {{ $donhang->madon }}</h2>
                    <ul>
                        <li>
                            <a href="javascript:void(0);"><img src="{{asset('img/icons/pdf.svg')}}" alt="img"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);"><img src="{{asset('img/icons/excel.svg')}}" alt="img"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);"><img src="{{asset('img/icons/printer.svg')}}" alt="img"></a>
                        </li>
                    </ul>
                </div>

                <div class="invoice-box table-height"
                    style="max-width: 1600px;width:100%;overflow: auto;margin:15px auto;padding: 0;font-size: 14px;line-height: 24px;color: #555;">
                    <table cellpadding="0" cellspacing="0"
                        style="width: 100%;line-height: inherit;text-align: left;">
                        <tbody>
                            <tr class="top">
                                <td colspan="6" style="padding: 5px;vertical-align: top;">

                                    {{-- Đơn hàng Info --}}
                                    <table style="width: 100%;line-height: inherit;text-align: left;">
                                        <tbody>
                                            <tr>
                                                <td
                                                    style="padding:5px;vertical-align:top;text-align:left;padding-bottom:20px">
                                                    <font style="vertical-align: inherit;margin-bottom:25px;">
                                                        <font
                                                            style="vertical-align: inherit;font-size:18px;color:#7367F0;font-weight:600;line-height: 35px; ">
                                                            Thông tin người nhận</font>
                                                    </font><br>
                                                    <font style="vertical-align: inherit;">
                                                        <font
                                                            style="vertical-align: inherit;font-size: 15px;color:#000;font-weight: bold;">
                                                            {{$donhang->nguoinhan}}</font>
                                                    </font> |
                                                    <font style="vertical-align: inherit;">
                                                        <font
                                                            style="vertical-align: inherit;font-size: 15px;color:#000;font-weight: 400;">
                                                            {{$donhang->sodienthoai}}</font>
                                                    </font><br>
                                                    <div style="width: 100px;">
                                                        <div class="text-black" style="font-size: 14px; width: 340px; white-space: break-spaces; overflow: hidden; text-overflow: ellipsis;">{{$donhang->diachinhan}}
                                                        </div>
                                                        <br>
                                                        </div>
                                                </td>
                                                <td
                                                    style="padding:5px;vertical-align:top;text-align:left;padding-bottom:20px">
                                                    <font style="vertical-align: inherit;margin-bottom:25px;">
                                                        <font
                                                            style="vertical-align: inherit;font-size:18px;color:#7367F0;font-weight:600;line-height: 35px; ">
                                                            Hình thức vận chuyển</font>
                                                    </font><br>
                                                    <font style="vertical-align: inherit;">
                                                        <font
                                                            style="vertical-align: inherit;font-size: 15px;color:#000;font-weight: bold;">
                                                            {{$donhang->hinhthucvanchuyen}} (TP.HCM)</font>
                                                    </font> <br>
                                                    <div>
                                                        <div class="text-black d-flex flex-row align-items-center">
                                                            <b class="fw-bold me-2">Khu vực giao:</b>
                                                            <span>{{$donhang->khuvucgiao}}</span>
                                                        </div>
                                                    </div>

                                                </td>
                                                <td
                                                    style="padding:5px;vertical-align:top;text-align:left;padding-bottom:20px">
                                                    <font style="vertical-align: inherit;margin-bottom:25px;">
                                                        <font
                                                            style="vertical-align: inherit;font-size:18px;color:#7367F0;font-weight:600;line-height: 35px; ">
                                                            Thông tin người đặt</font>
                                                    </font><br>
                                                    <font style="vertical-align: inherit;">
                                                        <font
                                                        @php
                                                            use App\Models\DiaChiGiaoHangModel;

                                                            $diachi = DiaChiGiaoHangModel::find($donhang->id_diachinguoidung);
                                                        @endphp
                                                            style="vertical-align: inherit;font-size: 15px;color:#000;font-weight: bold;">
                                                            {{($diachi->hoten)?? 'Không có'}}</font></font>
                                                            {{-- {{($donhang->nguoidung->diachi->sortBy('id')->where('trangthai','Mặc định')->first()->hoten)?? 'Không có'}}</font></font> --}}
                                                    </font> |
                                                    <font style="vertical-align: inherit;">
                                                        <font
                                                            style="vertical-align: inherit;font-size: 15px;color:#000;font-weight: 400;">
                                                            {{($diachi->sodienthoai)?? 'Không có'}}</font></font>
                                                            {{-- {{($donhang->nguoidung->diachi->sortBy('id')->where('trangthai','Mặc định')->first()->sodienthoai)?? 'Không có'}}</font></font> --}}
                                                    </font><br>
                                                    <div style="width: 100px;">
                                                        <div class="text-black" style="font-size: 14px; width: 340px; white-space: break-spaces; overflow: hidden; text-overflow: ellipsis;">{{($diachi->diachi)?? 'Không có'}}</div><br>
                                                        {{-- <div class="text-black" style="font-size: 14px; width: 340px; white-space: break-spaces; overflow: hidden; text-overflow: ellipsis;">{{($donhang->nguoidung->diachi->sortBy('id')->where('trangthai','Mặc định')->first()->diachi)?? 'Không có'}}</div><br> --}}
                                                        </div>
                                                </td>
                                                <td
                                                    style="padding:5px;vertical-align:top;text-align:left;padding-bottom:20px">
                                                    <font style="vertical-align: inherit;margin-bottom:25px;">
                                                        <font
                                                            style="vertical-align: inherit;font-size:18px;color:#7367F0;font-weight:600;line-height: 35px; ">
                                                            Thông tin Trạng Thái Đơn</font>
                                                    </font><br>
                                                    <font style="vertical-align: inherit;">
                                                        <font
                                                            style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                                            {{ $donhang->created_at->format('d/m/Y H:i:s') }} </font>
                                                    </font><br>
                                                    <font style="vertical-align: inherit;">
                                                        <font
                                                            style="vertical-align: inherit;font-size: 14px;color:#2E7D32;font-weight: 400;">
                                                            {{$donhang->hinhthucthanhtoan}} : {{$donhang->trangthaithanhtoan}}</font>
                                                    </font><br>
                                                    <font style="vertical-align: inherit;">
                                                        <font
                                                            style="vertical-align: inherit;font-size: 14px;color:#2E7D32;font-weight: 400;">
                                                            {{$donhang->trangthai}}</font>
                                                    </font><br>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    {{-- Đơn hàng Info --}}

                                </td>
                            </tr>

                            <tr class="heading " style="background: #F3F2F7;">
                                <td
                                    style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                    Sản phẩm
                                </td>
                                <td
                                    style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                    Số Lượng
                                </td>
                                <td
                                    style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                    Đơn Giá
                                </td>
                                <td
                                    style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                    Giảm Giá
                                </td>
                                <td
                                    style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                    TAX
                                </td>
                                <td
                                    style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                    Thành Tiền
                                </td>
                            </tr>
                            @foreach ($donhang->chitiet as $ct)
                            @php
                                $bienthe = $ct->bienthe;
                                $sanpham = $bienthe->sanpham;
                                $loaibienthe = $bienthe->loaibienthe;
                                $hinhanhsanpham = $sanpham->hinhanhsanpham->first()->hinhanh;
                            @endphp
                            <tr class="details" style="border-bottom:1px solid #E9ECEF ;">
                                <td style="padding: 10px; vertical-align: top; display: flex; align-items: center; position: relative;">
                                    @if ($ct->dongia === 0)
                                        <span class="product-card__badge bg-success px-2 py-1 text-sm text-white position-absolute"
                                            style="top: 1px; left: 0;">
                                            Quà Tặng
                                        </span>
                                    @endif
                                    <img src="{{ $hinhanhsanpham }}" alt="img" class="me-2" style="width:40px; height:40px;">
                                    {!! wordwrap("{$sanpham->ten} - {$loaibienthe->ten}", 35, '<br>') !!}
                                </td>
                                <td style="padding: 10px;vertical-align: top; ">
                                    {{ $ct->soluong }}
                                </td>
                                <td style="padding: 10px;vertical-align: top; ">
                                    {{ number_format($ct->dongia, 0, ',', '.') }} vnđ
                                </td>
                                <td style="padding: 10px;vertical-align: top; ">
                                    {{ $bienthe->giamgia }} %
                                </td>
                                <td style="padding: 10px;vertical-align: top; ">
                                    0 vnđ
                                </td>
                                <td style="padding: 10px;vertical-align: top; ">
                                    @php
                                        $sub_total = ($ct->dongia != 0) ? (($ct->dongia - ($ct->dongia *  $bienthe->giamgia) /100 ) * $ct->soluong) : 0;
                                    @endphp
                                    {{ number_format($sub_total, 0, ',', '.') }} vnđ
                                </td>
                            </tr>
                            @endforeach



                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>TAX</label>
                            <input type="text" value="0 vnđ">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Giá Giảm</label>
                            @php
                                $giagiam = number_format($donhang->giagiam, 0, ',', '.')." vnđ";
                            @endphp
                            <input type="text" value="{{$giagiam}}" >
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Phí Vận Chuyển</label>
                            @php
                                $phi_vanchuyen = number_format($donhang->phigiaohang, 0, ',', '.')." vnđ";
                            @endphp
                            <input type="text" value="{{$phi_vanchuyen}}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Trang Thái</label>
                            <select class="form-select" name="trangthai">
                                @foreach ($trangthais as $trangthai)
                                    <option value="{{ $trangthai }}" {{ old('trangthai',$donhang->trangthai)== $trangthai ?'selected':'' }}>
                                        {{ $trangthai }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 ">
                            <div class="total-order w-100 max-widthauto m-auto mb-4">
                                <ul>
                                    <li>
                                        <h4>TAX</h4>
                                        <h5>0 vnđ (0.00%)</h5>
                                    </li>
                                    <li>
                                        <h4>Giá Giảm </h4>
                                        <h5>{{$giagiam}}</h5>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-6 ">
                            <div class="total-order w-100 max-widthauto m-auto mb-4">
                                <ul>
                                    <li>
                                        <h4>Phí Vận Chuyển</h4>
                                        <h5>{{$phi_vanchuyen}}</h5>
                                    </li>
                                    <li class="total">
                                        <h4>Tổng Tiền</h4>
                                        <div class="d-flex flex-column w-100 justify-items-end">
                                            @php
                                                $tamtinh = number_format($donhang->tamtinh, 0, ',', '.')." vnđ";
                                                $thanhtien = number_format($donhang->thanhtien, 0, ',', '.')." vnđ";
                                            @endphp
                                            <h5 class="w-100">{{$tamtinh}}</h5>
                                            {{-- <h5>{{$donhang->tamtinh + $donhang->phigiaohang}}</h5>
                                            <h5>{{$donhang->tamtinh + $donhang->phigiaohang - $donhang->giagiam}}</h5> --}}
                                            <h5 class="w-100">{{$thanhtien}}</h5>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <a href="javascript:void(0);" class="btn btn-submit me-2">Update</a>
                        <a href="javascript:void(0);" class="btn btn-cancel">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <style>
    .dt-buttons {
        display: flex !important;
    }
</style> --}}
@endsection

{{-- <div class="page-wrapper">
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>Order Details</h4>
        <h6>Thông tin chi tiết đơn hàng</h6>
      </div>
    </div>

    <div class="row">
      <!-- Thông tin đơn hàng -->
      <div class="col-lg-8 col-sm-12">
        <div class="card">
          <div class="card-body">
            <div class="bar-code-view mb-4">
              <img src="{{ asset('assets/img/barcode1.png') }}" alt="barcode">
              <a class="printimg" href="javascript:void(0)">
                <img src="{{ asset('assets/img/icons/printer.svg') }}" alt="print">
              </a>
            </div>

            <div class="productdetails">
              <ul class="product-bar">
                <li>
                  <h4>Mã đơn hàng</h4>
                  <h6>#{{ $donhang->id }}</h6>
                </li>
                <li>
                  <h4>Khách hàng</h4>
                  <h6>{{ $donhang->khachhang->hoten ?? '—' }}</h6>
                </li>
                <li>
                  <h4>Ngày đặt</h4>
                  <h6>{{ \Carbon\Carbon::parse($donhang->ngaytao)->format('H:i - d/m/Y') }}</h6>
                </li>
                <li>
                  <h4>Ngày giao</h4>
                  <h6>{{ $donhang->ngaygiao ? \Carbon\Carbon::parse($donhang->ngaygiao)->format('H:i - d/m/Y') : 'Chưa giao' }}</h6>
                </li>
                <li>
                  <h4>Tổng tiền</h4>
                  <h6 class="text-success">{{ number_format($donhang->tongtien, 0, ',', '.') }} VND</h6>
                </li>
                <li>
                  <h4>Trạng thái</h4>
                  <h6>{{ $donhang->trangthai_text }}</h6>
                </li>
                <li>
                  <h4>Ghi chú</h4>
                  <h6>{{ $donhang->ghichu ?? 'Không có ghi chú' }}</h6>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Danh sách sản phẩm -->
      <div class="col-lg-4 col-sm-12">
        <div class="card">
          <div class="card-body">
            <div class="slider-product-details">
              <div class="owl-carousel owl-theme product-slide">
                @foreach($donhang->chitiet as $item)
                  <div class="slider-product text-center">
                    <img src="{{ asset($item->sanpham->mediaurl ?? 'assets/img/product/default.jpg') }}" alt="img" style="max-height:200px;object-fit:cover;">
                    <h4 class="mt-2">{{ $item->sanpham->ten ?? 'Sản phẩm' }}</h4>
                    <h6>Số lượng: {{ $item->soluong }}</h6>
                    <h6>Giá: {{ number_format($item->gia, 0, ',', '.') }} VND</h6>
                    <h6 class="text-success">Thành tiền: {{ number_format($item->soluong * $item->gia, 0, ',', '.') }} VND</h6>
                    @if($item->bienthe)
                      <small class="text-muted">Trạng thái biến thể: {{ $item->bienthe->trangthai ?? 'N/A' }}</small>
                    @endif
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>

    </div><!-- row -->
  </div>
</div> --}}


{{-- <tr class="details" style="border-bottom:1px solid #E9ECEF ;">
    <td
        style="padding: 10px;vertical-align: top; display: flex;align-items: center;">
        <img src="assets/img/product/product7.jpg" alt="img" class="me-2"
            style="width:40px;height:40px;">
        Apple Earpods
    </td>
    <td style="padding: 10px;vertical-align: top; ">
        1.00
    </td>
    <td style="padding: 10px;vertical-align: top; ">
        2000.00
    </td>
    <td style="padding: 10px;vertical-align: top; ">
        0.00
    </td>
    <td style="padding: 10px;vertical-align: top; ">
        0.00
    </td>
    <td style="padding: 10px;vertical-align: top; ">
        1500.00
    </td>
</tr>
<tr class="details" style="border-bottom:1px solid #E9ECEF ;">
    <td
        style="padding: 10px;vertical-align: top; display: flex;align-items: center;">
        <img src="assets/img/product/product8.jpg" alt="img" class="me-2"
            style="width:40px;height:40px;">
        samsung
    </td>
    <td style="padding: 10px;vertical-align: top; ">
        1.00
    </td>
    <td style="padding: 10px;vertical-align: top; ">
        8000.00
    </td>
    <td style="padding: 10px;vertical-align: top; ">
        0.00
    </td>
    <td style="padding: 10px;vertical-align: top; ">
        0.00
    </td>
    <td style="padding: 10px;vertical-align: top; ">
        1500.00
    </td>
</tr> --}}

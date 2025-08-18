@extends('layouts.app')

@section('title', 'Danh sách sản phẩm | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
        <div class="content">
          <div class="page-header">
            <div class="page-title">
              <h4>DANH SÁCH SẢN PHẨM</h4>
              <h6>
                Quản lý {{ $sanpham->where('trangthai', 0)->count() }} sản phẩm của bạn
              </h6>
            </div>
            <div class="page-btn">
              <a href="{{route('tao-san-pham')}}" class="btn btn-added"
                ><img
                  src="{{asset('img/icons/plus.svg')}}"
                  alt="img"
                  class="me-1"
                />Tạo sản phẩm</a
              >
            </div>
          </div>

          <div class="card">
            <div class="card-body">
              <div class="table-top">
                <div class="search-set">
                  <div class="search-path">
                    <a class="btn btn-filter" id="filter_search">
                      <img src="{{asset('img/icons/filter.svg')}}" alt="img" />
                      <span
                        ><img src="{{asset('img/icons/closes.svg')}}" alt="img"
                      /></span>
                    </a>
                  </div>
                  <div class="search-input">
                    <a class="btn btn-searchset"
                      ><img src="{{asset('img/icons/search-white.svg')}}" alt="img"
                    /></a>
                  </div>
                </div>
                <div class="wordset">
                  <ul>
                    <li>
                      <a
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="pdf"
                        ><img src="{{asset('img/icons/pdf.svg')}}" alt="img"
                      /></a>
                    </li>
                    <li>
                      <a
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="excel"
                        ><img src="{{asset('img/icons/excel.svg')}}" alt="img"
                      /></a>
                    </li>
                    <li>
                      <a
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="print"
                        ><img src="{{asset('img/icons/printer.svg')}}" alt="img"
                      /></a>
                    </li>
                  </ul>
                </div>
              </div>

              <div class="card mb-0" id="filter_inputs">
                <div class="card-body pb-0">
                  <div class="row">
                    <div class="col-lg-12 col-sm-12">
                      <div class="row">
                        <div class="col-lg col-sm-6 col-12">
                          <div class="form-group">
                            <select class="select">
                              <option>Choose Product</option>
                              <option>Macbook pro</option>
                              <option>Orange</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg col-sm-6 col-12">
                          <div class="form-group">
                            <select class="select">
                              <option>Choose Category</option>
                              <option>Computers</option>
                              <option>Fruits</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg col-sm-6 col-12">
                          <div class="form-group">
                            <select class="select">
                              <option>Choose Sub Category</option>
                              <option>Computer</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg col-sm-6 col-12">
                          <div class="form-group">
                            <select class="select">
                              <option>Brand</option>
                              <option>N/D</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg col-sm-6 col-12">
                          <div class="form-group">
                            <select class="select">
                              <option>Price</option>
                              <option>150.00</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-1 col-sm-6 col-12">
                          <div class="form-group">
                            <a class="btn btn-filters ms-auto"
                              ><img
                                src="{{asset('img/icons/search-whites.svg')}}"
                                alt="img"
                            /></a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="table-responsive">
                <table class="table datanew">
                  <thead>
                    <tr>
                      <!-- <th>
                        <label class="checkboxs">
                          <input type="checkbox" id="select-all" />
                          <span class="checkmarks"></span>
                        </label>
                      </th> -->
                      <th>Tên sản phẩm</th>
                      <th>Danh mục</th>
                      <th>Thương hiệu</th>
                      <th>Giá</th>
                      <th>Loại</th>
                      <th>Số lượng</th>
                      <th>Lượt mua</th>
                      <th>Ngày cập nhật</th>
                      <th>Hành động</th>
                    </tr>
                  </thead>
                  <tbody>

                    @foreach($sanpham as $sp)
                    <tr>
                      <!-- <td>
                        <label class="checkboxs">
                          <input type="checkbox" />
                          <span class="checkmarks"></span>
                        </label>
                      </td> -->
                      <td class="productimgname">
                        <a href="{{url('/')}}" class="product-img">
                          <img
                            src="{{ asset('img/product/' . $sp->anhSanPham->first()->media) }}"
                            alt="Not found"
                          />
                        </a>
                        <a href="{{url('/')}}" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{$sp->ten}}">{{$sp->ten}}</a>
                      </td>
                      <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{!! $sp->danhmuc->pluck('ten')->implode(', ') ?: 'Chưa có danh mục' !!}">{!! $sp->danhmuc->pluck('ten')->implode(', ') ?: 'Chưa có danh mục' !!}</td>
                      <td>{{ $sp->thuonghieu->ten ?? 'Không có' }}</td>
                      <td>
                      @if($sp->bienthe->count())
                        @php
                            $giaMin = $sp->bienthe->min('gia');
                            $giaMax = $sp->bienthe->max('gia');
                        @endphp

                         {{ number_format($sp->bienthe->min('gia'), 0, ',', '.') }} đ

                        {{-- Chỉ hiển thị giá max nếu > giá min --}}
                        @if($giaMax > $giaMin)
                             ~  {{ number_format($giaMax, 0, ',', '.') }} đ
                        @endif
                    @else
                        Chưa có giá
                    @endif
                      </td>
                      <td>
                        @php
                            $tenbt = $sp->bienthe->pluck('loaiBienThe.ten')->implode(', ');
                        @endphp
                        {{$tenbt ?: 'Không có biến thể'}}
                      </td>
                      <td>{{ $sp->bienthe->sum('soluong') }}</td>
                      <td>null</td>
                      <td>{{ $sp->updated_at->format('d/m/Y') }}</td>
                      <td>
                        <a class="me-3" href="{{url('/')}}">
                          <img src="{{asset('img/icons/eye.svg')}}" alt="img" />
                        </a>
                        <a class="me-3" href="{{url('/')}}">
                          <img src="{{asset('img/icons/edit.svg')}}" alt="img" />
                        </a>
                        <a class="confirm-text" href="{{url('/')}}">
                          <img src="{{asset('img/icons/delete.svg')}}" alt="img" />
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
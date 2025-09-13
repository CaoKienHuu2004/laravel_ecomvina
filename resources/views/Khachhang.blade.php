@extends('layouts.app')

@section('title','Danh sách khách hàng | Quản trị hệ thống Siêu Thị Vina')

@section('content')

<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Danh sách khách hàng</h4>
                <h6>Quản lý các mục trong danh sách khách hàng của bạn</h6>
            </div>
            <div class="page-btn">
                <a href="newuser.html" class="btn btn-added"><img src="{{asset('')}}img/icons/plus.svg" alt="img" />Add User</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-top">
                    <div class="search-set">
                        <div class="search-path">
                            <a class="btn btn-filter" id="filter_search">
                                <img src="{{asset('')}}img/icons/filter.svg" alt="img" />
                                <span><img src="{{asset('')}}img/icons/closes.svg" alt="img" /></span>
                            </a>
                        </div>
                        <div class="search-input">
                            <a class="btn btn-searchset"><img src="{{asset('')}}img/icons/search-white.svg" alt="img" /></a>
                        </div>
                    </div>
                    <div class="wordset">
                        <ul>
                            <li>
                                <a
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="pdf"><img src="{{asset('')}}img/icons/pdf.svg" alt="img" /></a>
                            </li>
                            <li>
                                <a
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="excel"><img src="{{asset('')}}img/icons/excel.svg" alt="img" /></a>
                            </li>
                            <li>
                                <a
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="print"><img src="{{asset('')}}img/icons/printer.svg" alt="img" /></a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card" id="filter_inputs">
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-lg-2 col-sm-6 col-12">
                                <div class="form-group">
                                    <input type="text" placeholder="Enter User Name" />
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 col-12">
                                <div class="form-group">
                                    <input type="text" placeholder="Enter Phone" />
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 col-12">
                                <div class="form-group">
                                    <input type="text" placeholder="Enter Email" />
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 col-12">
                                <div class="form-group">
                                    <input
                                        type="text"
                                        class="datetimepicker cal-icon"
                                        placeholder="Choose Date" />
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 col-12">
                                <div class="form-group">
                                    <select class="select">
                                        <option>Disable</option>
                                        <option>Enable</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-1 col-sm-6 col-12 ms-auto">
                                <div class="form-group">
                                    <a class="btn btn-filters ms-auto"><img
                                            src="{{asset('')}}img/icons/search-whites.svg"
                                            alt="img" /></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table datanew">
                        <thead>
                            <tr>
                                <th>User name</th>
                                <th>Phone</th>
                                <th>email</th>
                                <th>Role</th>
                                <th>Created On</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="productimgname"><a href="{{url('/')}}" class="product-img">
                                    <img
                                    src="{{ asset('img/customer/customer.jpg') }}"
                                    alt="Not found" />
                                </a>
                                <a href="{{url('/')}}" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" data-bs-toggle="tooltip" data-bs-placement="top" title="">Cao Kiến Hựu</a>
                                </td>
                                <td>+12163547758</td>
                                <td>
                                    <a
                                        href="/cdn-cgi/l/email-protection"
                                        class="__cf_email__"
                                        data-cfemail="fb8f9394969a88bb9e839a968b979ed5989496">[email&#160;protected]</a>
                                </td>
                                <td>Admin</td>
                                <td>3/15/2022</td>
                                <td><span class="bg-lightgreen badges">Active</span></td>
                                <td>
                                    <a class="me-3" href="newuseredit.html">
                                        <img src="{{asset('')}}img/icons/edit.svg" alt="img" />
                                    </a>
                                    <a class="me-3 confirm-text" href="javascript:void(0);">
                                        <img src="{{asset('')}}img/icons/delete.svg" alt="img" />
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="productimgname"><a href="{{url('/')}}" class="product-img">
                                    <img
                                    src="{{ asset('img/customer/customer.jpg') }}"
                                    alt="Not found" />
                                </a>
                                <a href="{{url('/')}}" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" data-bs-toggle="tooltip" data-bs-placement="top" title="">Cao Kiến Hựu</a>
                                </td>
                                <td>123-456-888</td>
                                <td>
                                    <a
                                        href="/cdn-cgi/l/email-protection"
                                        class="__cf_email__"
                                        data-cfemail="60031513140f0d0512200518010d100c054e030f0d">[email&#160;protected]</a>
                                </td>
                                <td>Manager</td>
                                <td>2/27/2022</td>
                                <td>
                                    <span class="bg-lightred badges">Restricted</span>
                                </td>
                                <td>
                                    <a class="me-3" href="newuseredit.html">
                                        <img src="{{asset('')}}img/icons/edit.svg" alt="img" />
                                    </a>
                                    <a class="me-3 confirm-text" href="javascript:void(0);">
                                        <img src="{{asset('')}}img/icons/delete.svg" alt="img" />
                                    </a>
                                </td>
                            </tr>
                            
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
@extends('layouts.app')

@section('title', 'Danh sách danh mục | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Danh mục sản phẩm</h4>
                <h6>Theo dõi {{ $danhmuc->where('trangthai', 0)->count() }} danh mục của sản phẩm</h6>
            </div>
            <div class="page-btn">
                <a href="addcategory.html" class="btn btn-added">
                    <img
                        src="{{asset('')}}img/icons/plus.svg"
                        class="me-1"
                        alt="img" />Add Category
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-top">
                    <div class="search-set">
                        <div class="search-path">

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
                                    title="pdf" id="btnPdf"><img src="{{asset('')}}img/icons/pdf.svg" alt="img" /></a>
                            </li>
                            <li>
                                <a
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="excel" id="btnExcel"><img src="{{asset('')}}img/icons/excel.svg" alt="img" /></a>
                            </li>
                            <li>
                                <a
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="print" id="btnPrint"><img src="{{asset('')}}img/icons/printer.svg" alt="img" /></a>
                            </li>
                        </ul>
                    </div>
                </div>


                <div class="table-responsive">
                    <table class="table datanew" id="productTable">
                        <thead>
                            <tr>
                                <th>Tên danh mục</th>
                                <th>Cập nhật</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($danhmuc as $dm)
                            <tr>
                                <td>{{ $dm->ten }}</td>
                                <td>{{ $dm->updated_at->format('d/m/Y - H:i') }}</td>
                                <td>
                                    <a class="me-3 btn" href="editcategory.html">
                                        <img src="{{asset('')}}img/icons/edit.svg" alt="img" />
                                    </a>
                                    <form action="{{ route('danhmuc.destroy', $dm->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Bạn chắc chắn muốn xóa danh mục này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="me-3 btn"><img src="{{asset('img/icons/delete.svg')}}" alt="img" /></button>
                                    </form>
                                        
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
<script>
    $(document).ready(function() {
        var table = $('#productTable').DataTable({
            dom: 't', // chỉ hiển thị table, không render nút sẵn
            buttons: [{
                    extend: 'excelHtml5',
                    title: 'Danh_sach_san_pham'
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Danh_sach_san_pham'
                },
                {
                    extend: 'print',
                    title: 'Danh_sach_san_pham'
                }
            ]
        });


        // Gắn sự kiện cho thẻ <a>
        $('#btnExcel').on('click', function(e) {
            e.preventDefault();
            table.button('.buttons-excel').trigger();
        });

        $('#btnPdf').on('click', function(e) {
            e.preventDefault();
            table.button('.buttons-pdf').trigger();
        });

        $('#btnPrint').on('click', function(e) {
            e.preventDefault();
            table.button('.buttons-print').trigger();
        });
    });
</script>



@endsection
@extends('layouts.app')

@section('title', 'Chi tiết danh mục | Quản trị hệ thống Siêu Thị Vina')

{{-- // controller truyền xuống $danhmuc  --}}
{{-- // các route sư dụng ko có, của quan hệ sanpham.show --- của breadcrumb danhmuc.index trang-chu  --}}
{{--
    $danhmuc->logo chứa đường dẫn URL đầy đủ, ví dụ:
    http://148.230.100.215/assets/client/images/categories/tenfilehinhanh.jpg
--}}
@section('content')
<div class="page-wrapper">
    <div class="content">

        {{-- Breadcrumb --}}
        <div class="page-header">
            <x-header.breadcrumb
                title="Chi tiết danh mục"
                :links="[
                    ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                    ['label' => 'Danh sách danh mục', 'route' => 'danhmuc.index']
                ]"
                active="Chi tiết"
            />
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row align-items-center mb-4">
                    <div class="col-md-3 text-center">
                        @if ($danhmuc->logo && file_exists(public_path(parse_url($danhmuc->logo, PHP_URL_PATH))))
                            <img src="{{ $danhmuc->logo }}" alt="{{ $danhmuc->ten }}" class="rounded img-fluid" style="max-width: 150px;">
                        @else
                            <img src="{{ asset('assets/client/images/categories/danhmuc.jpg') }}" alt="Default" class="rounded img-fluid" style="max-width: 150px;">
                        @endif
                    </div>

                    <div class="col-md-9">
                        <h5 class="fw-bold">{{ $danhmuc->ten }}</h5>
                        <p><strong>Slug:</strong> {{ $danhmuc->slug }}</p>
                        @if(!empty($danhmuc->logo))
                            <div class="mb-3 d-flex align-item-center">
                                <strong class="me-2">Tên Logo:</strong>
                                <a href="{{ $danhmuc->logo }}" target="_blank" rel="noopener noreferrer">
                                    <img src="{{ asset('img/icons/eye.svg') }}" alt="Xem" />
                                </a>
                            </div>
                        @endif
                        <p><strong>Phân loại:</strong> {{ $danhmuc->parent }}</p>
                        <p>
                            <strong>Trạng thái:</strong>
                            <span class="badge {{ $danhmuc->trangthai == 'Hiển thị' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $danhmuc->trangthai }}
                            </span>
                        </p>

                    </div>
                </div>

                <hr>

                <div class="mt-3">
                    <h6>Sản phẩm thuộc danh mục này:</h6>
                     <div class="table-reponsite">
                        <table class="table datanew">
                            <thead>
                                <th>Id</th>
                                <th>Tên Sản phẩm</th>
                                <th>Hình ảnh</th>
                            </thead>
                            <tbody>

                                @foreach ($danhmuc->sanpham as $sp)
                                <tr>
                                    <td>{{ $sp->id }}</td>
                                    <td><a href="{{ route('sanpham.show', $sp->id) }}" target="_blank">{{ $sp->ten }}</a></td>
                                    <td>
                                        <img
                                        class="w-50-md w-30-ms w-100-mx"
                                        style="object-fit: cover; width: 100%; height: 100px;"
                                        src="{{ $sp->hinhanhsanpham->first()->hinhanh }}"
                                        alt="hinhanhsanpham{{ $sp->id }}">
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>

                        </table>
                     </div>
                    @if ($danhmuc->sanpham->count() > 0)

                    @else
                        <p class="text-muted">Chưa có sản phẩm nào trong danh mục này.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

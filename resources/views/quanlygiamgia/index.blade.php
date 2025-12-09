{{-- resources/views/magiamgia/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Danh sách mã giảm giá | Quản trị hệ thống Siêu Thị Vina')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>DANH SÁCH MÃ GIẢM GIÁ</h4>
            </div>
            <div class="page-btn">
                <a href="{{ route('create.magiamgia') }}" class="btn btn-added">
                    <img src="{{ asset('img/icons/plus.svg') }}" alt="img" class="me-1">
                    Tạo mã giảm giá
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table datanew">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Mã giảm giá</th>
                                <th>Điều kiện</th>
                                <th>Giá trị giảm</th>
                                <th>Mô tả</th>
                                <th>Ngày bắt đầu</th>
                                <th>Ngày kết thúc</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($magiamgia as $i => $item)
                                <tr>
                                    <td>$item->id</td>
                                    <td><strong>{{ $item->magiamgia }}</strong></td>
                                    <td>
                                        @if($item->dieukien > 0 || $item->dieukien > 0)

                                            @if($item->dieukien > 0)
                                                ≥ {{ number_format($item->dieukien) }}₫
                                            @endif
                                        @else
                                            Không yêu cầu
                                        @endif
                                    </td>
                                    <td>{{ number_format($item->giatri) }} VNĐ</td>
                                    <td>
                                        {{ Str::limit($item->mota ?? '-', 80, '...') }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($item->ngaybatdau)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->ngayketthuc)->format('d/m/Y') }}</td>
                                    <td>{{ $item->trangthai }}</td>
                                    <td>
                                        <!-- NÚT XEM CHI TIẾT - DÙNG ĐÚNG ROUTE MÀY ĐÃ CÓ -->
                                        <a href="{{ route('magiamgia.show', $item->id) }}" title="Xem chi tiết">
                                            <img src="{{ asset('img/icons/eye.svg') }}" alt="view" width="18">
                                        </a>

                                        <!-- NÚT SỬA -->
                                        <a href="{{ route('edit.magiamgia', $item->id) }}" class="ms-2" title="Sửa">
                                            <img src="{{ asset('img/icons/edit.svg') }}" alt="edit" width="18">
                                        </a>

                                        <!-- NÚT XÓA -->
                                        <form action="{{ route('delete.magiamgia', $item->id) }}" method="POST" style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="border-0 bg-transparent ms-2"
                                                    onclick="return confirm('Xóa mã {{ $item->magiamgia }} ?')">
                                                <img src="{{ asset('img/icons/delete.svg') }}" alt="delete" width="18">
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        Chưa có mã giảm giá nào
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- <div class="d-flex justify-content-center mt-4">
                    {{ $magiamgia->links() }}
                </div> --}}
            </div>
        </div>
    </div>
</div>
@endsection

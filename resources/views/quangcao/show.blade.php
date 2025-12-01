@extends('layouts.app')

@section('title', 'Chi tiết quảng cáo #' . $quangcao->id)
{{-- $quangcao->hinhanh: Link http://148.230.100.215/assets/client/images/bg/tenfilehinhanh.jpg --}}
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div class="page-title">
                <h4>Chi tiết quảng cáo #{{ $quangcao->id }}</h4>
                <h6>Thông tin đầy đủ về quảng cáo</h6>
            </div>
            <a href="{{ route('quangcao.index') }}" class="btn btn-secondary">
                ← Quay lại danh sách
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>ID</th>
                            <td>{{ $quangcao->id }}</td>
                        </tr>
                        <tr>
                            <th>Vị trí</th>
                            <td>{{ ucwords(str_replace('_', ' ', $quangcao->vitri)) }}</td>
                        </tr>
                        <tr>
                            <th>Hình ảnh</th>
                            <td>
                                @if ($quangcao->hinhanh)
                                    <img src="{{ $quangcao->hinhanh }}" alt="Hình ảnh quảng cáo" style="max-width: 300px;">
                                @else
                                    <span class="text-muted">Không có hình ảnh</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Tên Hình ảnh</th>
                            <td>
                                @if(!empty($quangcao->hinhanh))
                                        <a href="{{ $quangcao->hinhanh }}" rel="noopener noreferrer" target="_blank">
                                            <strong>{{ $quangcao->hinhanh }}</strong>
                                        </a>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Liên kết</th>
                            <td>
                                <a href="{{ $quangcao->lienket }}" target="_blank" rel="noopener noreferrer">{{ $quangcao->lienket }}</a>
                            </td>
                        </tr>
                        <tr>
                            <th>Mô tả</th>
                            <td>{{ $quangcao->mota }}</td>
                        </tr>
                        <tr>
                            <th>Trạng thái</th>
                            <td>
                                <span class="badge bg-{{ $quangcao->trangthai == 'Hiển thị' ? 'success' : 'warning' }}">
                                    {{ $quangcao->trangthai }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>


            </div>
        </div>
    </div>
</div>
@endsection

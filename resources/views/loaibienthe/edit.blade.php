@extends('layouts.app')

@section('title')
    Sửa loại biến thể: "{{ $loaibienthe->ten }}"
@endsection

{{-- // controller truyền xuống $loaibienthe $trangthais (dùng để làm selectbox_loaibienthe_trangthais) --}}
{{-- // các route sư dụng  loaibienthe.update --- của breadcrumb loaibienthe.index trang-chu   --}}


@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
        <x-header.breadcrumb
                title="Sửa loại biến thể: '{{ $loaibienthe->ten }}'"
                :links="[
                    ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                    ['label' => 'Danh sách loại biến thể', 'route' => 'loaibienthe.index']
                ]"
                active="Thêm mới"
            />
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('loaibienthe.update', $loaibienthe->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="ten">Tên loại biến thể <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            id="ten"
                            name="ten"
                            class="form-control @error('ten') is-invalid @enderror"
                            value="{{ old('ten', $loaibienthe->ten) }}"
                            placeholder="Nhập tên loại biến thể..."
                            required
                        >
                        @error('ten')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="trangthai">Trạng thái <span class="text-danger">*</span></label>
                        <select
                            id="trangthai"
                            name="trangthai"
                            class="form-select @error('trangthai') is-invalid @enderror"
                            required
                        >
                            <option value="">-- Chọn trạng thái --</option>
                            @foreach ($trangthais as $status)
                                <option value="{{ $status }}"
                                    {{ old('trangthai', $loaibienthe->trangthai) === $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                        @error('trangthai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

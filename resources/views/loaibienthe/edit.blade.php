@extends('layouts.app')

@section('title')
    Sửa loại biến thể: "{{ $loaibienthe->ten }}"
@endsection

{{-- // controller truyền xuống $loaibienthe $trangthais (dùng để làm selectbox_loaibienthe_trangthais) --}}
{{-- // các route sư dụng  loaibienthe.update loaibienthe.index   --}}


@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Sửa loại biến thể</h4>
                <h6>Chỉnh sửa thông tin loại biến thể</h6>
            </div>
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
                    <a href="{{ route('loaibienthe.index') }}" class="btn btn-secondary">Hủy</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

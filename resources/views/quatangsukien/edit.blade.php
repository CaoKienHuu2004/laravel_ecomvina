@extends('layouts.app')

@section('title', 'Chỉnh sửa Quà Tặng Sự Kiện | Quản trị hệ thống Siêu Thị Vina')
{{-- bỏ slug -> label ko phải input --}}
@section('content')
<div class="page-wrapper">
  <div class="content container-fluid">
    <div class="page-header">
        <x-header.breadcrumb
            title="CHỈNH SỬA QUÀ TẶNG SỰ KIỆN"
            :links="[
                ['label' => 'Tổng quan', 'route' => 'trang-chu'],
                ['label' => 'Danh sách Quà Tặng Sự Kiện', 'route' => 'quatangsukien.index']
            ]"
            active="Chỉnh sửa Quà Tặng Sự Kiện"
        />
    </div>

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('quatangsukien.update', $quatang->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label for="tieude" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
        <input
          type="text"
          id="tieude"
          name="tieude"
          class="form-control @error('tieude') is-invalid @enderror"
          value="{{ old('tieude', $quatang->tieude) }}"
          required
          maxlength="255"
        >
        @error('tieude')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="slug" class="form-label">Slug</label>
        <input
          type="text"
          id="slug"
          name="slug"
          class="form-control @error('slug') is-invalid @enderror"
          value="{{ old('slug', $quatang->slug ?? 'chưa cập nhật')}}"
            readonly
            disabled
        >
        @error('tieude')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="id_bienthe" class="form-label">Biến thể sản phẩm <span class="text-danger">*</span></label>
        <select
          id="id_bienthe"
          name="id_bienthe"
          class="form-select @error('id_bienthe') is-invalid @enderror"
          required
        >
          <option value="">-- Chọn biến thể --</option>
          @foreach($bienthes as $bt)
            <option value="{{ $bt->id }}" {{ old('id_bienthe', $quatang->id_bienthe) == $bt->id ? 'selected' : '' }}>
              {{ $bt->id }} - {{ $bt->sanpham->ten ?? 'N/A' }} ({{ $bt->loaibienthe->ten ?? 'N/A' }})
            </option>
          @endforeach
        </select>
        @error('id_bienthe')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="chuongtrinh_id" class="form-label">Thuộc Chương Trình Sự Kiện <span class="text-danger">*</span></label>
        <select
            id="chuongtrinh_id"
            name="id_chuongtrinh"
            class="form-select @error('id_chuongtrinh') is-invalid @enderror"
            required
        >
            <option value="">-- Chọn chương trình --</option>
            @foreach ($chuongtrinhs as $ct)
            <option
                value="{{ $ct->id }}"
                data-img="{{ $ct->hinhanh }}"
                {{ old('id_chuongtrinh', $quatang->id_chuongtrinh) == $ct->id ? 'selected' : '' }}
            >
                {{ $ct->tieude }}
            </option>
            @endforeach
        </select>
        @error('id_chuongtrinh')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        </div>



      <div class="mb-3">
        <label for="dieukien" class="form-label">Điều kiện số lượng:  <span class="text-danger">*</span></label>
        <input
          type="number"
          id="dieukiensoluong"
          name="dieukiensoluong"
          class="form-control @error('dieukiensoluong') is-invalid @enderror"
          value="{{ old('dieukiensoluong', $quatang->dieukiensoluong) }}"
          max="999"
          min="0"
          step="1"
        >
        @error('dieukiensoluong')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="dieukien" class="form-label">Điều kiện giá trị (vnđ): </label>
        <input
          type="number"
          id="dieukiengiatri"
          name="dieukiengiatri"
          class="form-control @error('dieukiengiatri') is-invalid @enderror"
          value="{{ old('dieukiengiatri', $quatang->dieukiengiatri) }}"
          max="99999999999"
          min="0"
          step="1000"
        >
        @error('dieukiengiatri')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="thongtin" class="form-label">Thông tin</label>
        <textarea
          id="thongtin"
          name="thongtin"
          class="form-control @error('thongtin') is-invalid @enderror"
          rows="4"
        >{{ old('thongtin', $quatang->thongtin) }}</textarea>
        @error('thongtin')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label for="ngaybatdau" class="form-label">Ngày bắt đầu:  <span class="text-danger">*</span></label>
          <input
            type="date"
            id="ngaybatdau"
            name="ngaybatdau"
            class="form-control @error('ngaybatdau') is-invalid @enderror"
            value="{{ old('ngaybatdau', $quatang->ngaybatdau) }}"
          >
          @error('ngaybatdau')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-6">
          <label for="ngayketthuc" class="form-label">Ngày kết thúc:  <span class="text-danger">*</span></label>
          <input
            type="date"
            id="ngayketthuc"
            name="ngayketthuc"
            class="form-control @error('ngayketthuc') is-invalid @enderror"
            value="{{ old('ngayketthuc', $quatang->ngayketthuc) }}"
          >
          @error('ngayketthuc')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="mb-3">
        <label for="hinhanh" class="form-label">Hình ảnh</label>
        <input
          type="file"
          id="hinhanh"
          name="hinhanh"
          class="form-control @error('hinhanh') is-invalid @enderror"
          accept=".jpg,.jpeg,.png,.gif,.webp"
        >
        @error('hinhanh')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        @if($quatang->hinhanh)
          <div class="mt-2">
            <img src="{{ $quatang->hinhanh }}" alt="{{ $quatang->tieude }}" style="max-width: 200px; max-height: 100px; object-fit: contain;">
          </div>
        @endif
      </div>

      <div class="mb-3">
        <label for="trangthai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
        <select
          id="trangthai"
          name="trangthai"
          class="form-select @error('trangthai') is-invalid @enderror"
          required
        >
          <option value="">-- Chọn trạng thái --</option>
          @foreach($trangthais as $tt)
            <option value="{{ $tt }}" {{ old('trangthai', $quatang->trangthai) == $tt ? 'selected' : '' }}>
              {{ $tt }}
            </option>
          @endforeach
        </select>
        @error('trangthai')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <button type="submit" class="btn btn-primary">Cập nhật</button>
      <a href="{{ route('quatangsukien.index') }}" class="btn btn-secondary ms-2">Hủy</a>
    </form>
  </div>
</div>
@endsection
<script>
    $(document).ready(function() {
  function formatOption(option) {
    if (!option.id) return option.text;
    var imgUrl = $(option.element).data('img');
    if (imgUrl) {
      return $('<span><img src="' + imgUrl + '" style="width:20px; height:20px; margin-right:5px;" /> ' + option.text + '</span>');
    }
    return option.text;
  }

  $('#chuongtrinh_id').select2({
    templateResult: formatOption,
    templateSelection: formatOption,
  });
});
</script>

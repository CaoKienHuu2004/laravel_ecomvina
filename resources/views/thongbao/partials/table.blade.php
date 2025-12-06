<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table datanew">
                <thead>
                    <tr>
                        <th>Người nhận</th>
                        <th>Avatar</th>
                        <th>Tiêu đề</th>
                        <th>Nội dung</th>
                        <th>Liên kết</th>
                        <th>Loại thông báo</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($thongbaos as $tb)
                    <tr>
                        <td>
                            <strong>{{ $tb->nguoidung->hoten ?? 'Không xác định' }}</strong><br>
                            <small>SĐT: {{ $tb->nguoidung->sodienthoai ?? 'N/A' }}</small>
                        </td>
                        <td class="text-center">
                            @if($tb->nguoidung && $tb->nguoidung->avatar)
                                <img src="{{ $tb->nguoidung->avatar }}"
                                     style="width:45px;height:45px;border-radius:50%;object-fit:cover;">
                            @else
                                <img src="{{ asset('img/default_user.png') }}"
                                     style="width:45px;height:45px;border-radius:50%;object-fit:cover;">
                            @endif
                        </td>
                        <td>{!! wordwrap(($tb->tieude), 25, "<br>", true) !!}</td>
                        <td><small>{!! wordwrap(($tb->noidung), 36, "<br>", true) !!}</small></td>
                        <td>
                            @if($tb->lienket)
                                <a href="{{ $tb->lienket }}" target="_blank" class="text-primary">Mở liên kết</a>
                            @else
                                <span class="text-muted">Không có</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $tb->loaithongbao ?? 'Không xác định' }}</strong><br>
                        </td>
                        <td>
                            @if($tb->trangthai == 'Chưa đọc')
                                <span class="badge bg-danger">Chưa đọc</span>
                            @elseif($tb->trangthai == 'Đã đọc')
                                <span class="badge bg-success">Đã đọc</span>
                            @elseif($tb->trangthai == 'Tạm ẩn')
                                <span class="badge bg-secondary">Tạm ẩn</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('thongbao.show', $tb->id) }}" class="me-2" title="Xem chi tiết">
                                <img src="{{ asset('img/icons/eye.svg') }}">
                            </a>
                            <a href="{{ route('thongbao.edit', $tb->id) }}" class="me-2" title="Chỉnh sửa">
                                <img src="{{ asset('img/icons/edit.svg') }}">
                            </a>
                            <a href="#" title="Xóa" onclick="event.preventDefault();if(confirm('Bạn có chắc muốn xóa thông báo này?')){document.getElementById('delete-form-{{ $tb->id }}').submit();}">
                                <img src="{{ asset('img/icons/delete.svg') }}">
                            </a>
                            <form id="delete-form-{{ $tb->id }}" action="{{ route('thongbao.destroy', $tb->id) }}" method="POST" style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

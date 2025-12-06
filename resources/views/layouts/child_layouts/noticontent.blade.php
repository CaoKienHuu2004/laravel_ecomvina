
{{-- // các route sư dụng  thongbao.update-status thongbao.show --}}

<div class="noti-content">

    @php
        // Lấy danh sách thông báo chưa đọc của user đang đăng nhập
        $notis = auth()->user()->thongbao()
            ->where('trangthai', 'Chưa đọc')
            ->orderBy('id','desc')
            ->get();

        $count = $notis->count();
    @endphp

    <ul class="notification-list">

        <li class="notification-message">

            <p class="noti-details">
                Hiện có <span class="noti-title">{{ $count }}</span> thông báo Chưa đọc!
            </p>

            {{-- Nếu có thông báo chưa đọc --}}
            @if($count > 0)
                @foreach($notis as $noti)
                    <a href="javascript:void(0)"
                       class="noti-item"
                       data-id="{{ $noti->id }}"
                       data-url="{{ route('thongbao.update-status', $noti->id) }}"
                       data-redirect="{{ route('thongbao.show', $noti->id) }}">
                        <div class="media d-flex">
                            <div class="media-body flex-grow-1">
                                <span class="badge bg-secondary">Chưa đọc</span>
                                <p>{{ $noti->tieude }}</p>
                                <p>{{ $noti->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            @else
                <p>Không có thông báo chưa đọc.</p>
            @endif

        </li>

    </ul>
</div>

{{-- Trong public/js/child_layouts.js --}}
{{-- <script>
$(document).ready(function() {
    console.log('script noti-content đang chạy');

    $(document).on("click", ".noti-item", function () {
        let url = $(this).data("url");
        let redirectUrl = $(this).data("redirect");

        $.ajax({
            url: url,
            method: "PATCH",
            data: {
                _token: "{{ csrf_token() }}",
                trangthai: "Đã đọc"
            },
            success: function (res) {
                if (res.success) {
                    window.location.href = redirectUrl;
                } else {
                    alert("Cập nhật trạng thái thất bại!");
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert("Không thể cập nhật trạng thái!");
            }
        });
    });
});
</script> --}}

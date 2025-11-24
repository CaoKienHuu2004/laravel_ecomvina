$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on("click", ".noti-item", function () {
        let url = $(this).data("url");
        let redirectUrl = $(this).data("redirect");

        $.ajax({
            url: url,
            method: "PATCH",
            data: {
                trangthai: "Đã đọc"
            },
            success: function (res) {
                if (res.success) {
                    window.location.href = redirectUrl;
                } else {
                    alert("Cập nhật trạng thái thất bại: " + (res.message || ""));
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert("Không thể cập nhật trạng thái!");
            }
        });
    });
});

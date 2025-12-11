
document.addEventListener("DOMContentLoaded", function() {
    console.log("public/js/tranngchu.js script trang chu đang chạy");
    if ($("#sales_charts1").length > 0) {
        // ----------------------------------------------------
        // BƯỚC 1: TẠO DANH SÁCH 7 NGÀY ĐỘNG (7 NGÀY TRƯỚC VÀ HÔM NAY)
        // ----------------------------------------------------

        const today = new Date();
        const sevenDayCategories = [];

        // Lặp để lấy 7 ngày, bắt đầu từ (Hôm nay - 6 ngày) đến (Hôm nay)
        // i sẽ chạy từ -6 đến 0
        for (let i = -6; i <= 0; i++) {
            // Tạo một đối tượng Date mới từ ngày gốc
            const date = new Date(today);

            // Thiết lập ngày (cộng/trừ i ngày)
            // i = -6 là ngày sớm nhất, i = 0 là ngày hôm nay
            date.setDate(today.getDate() + i);

            // Định dạng ngày thành "DD/MM"
            const day = date.getDate().toString().padStart(2, '0');
            const month = (date.getMonth() + 1).toString().padStart(2, '0');

            const formattedDate = `${day}/${month}`;
            sevenDayCategories.push(formattedDate);
        }

        // ----------------------------------------------------
        // BƯỚC 2: KHỞI TẠO BIỂU ĐỒ VỚI DANH SÁCH MỚI
        // ----------------------------------------------------

        var options = {
            series: [
                // Đảm bảo data của bạn có 7 giá trị, với giá trị cuối cùng là của ngày hôm nay
                { name: "Doanh thu", data: [10, 45, 60, 70, 50, 45, 60] },
            ],
            colors: ["#28C76F"],
            chart: {
                type: "bar",
                height: 300,
                stacked: true,
                zoom: { enabled: true },
            },
            responsive: [
                {
                    breakpoint: 280,
                    options: { legend: { position: "bottom", offsetY: 0 } },
                },
            ],
            plotOptions: {
                bar: { horizontal: false, columnWidth: "50%" },
            },
            xaxis: {
                // SỬ DỤNG MẢNG CATEGORIES 7 NGÀY TRƯỚC
                categories: sevenDayCategories,
            },
            legend: { position: "right", offsetY: 40 },
            fill: { opacity: 1 },
        };

        var chart = new ApexCharts(
            document.querySelector("#sales_charts1"),
            options
        );
        chart.render();
    }

    if ($("#sales_charts2").length > 0) {

        // Mảng tên tháng tiếng Việt
        const monthNames = [
            "Thg 1", "Thg 2", "Thg 3", "Thg 4", "Thg 5", "Thg 6",
            "Thg 7", "Thg 8", "Thg 9", "Thg 10", "Thg 11", "Thg 12"
        ];

        // ----------------------------------------------------
        // BƯỚC 1: TẠO DANH SÁCH 4 TUẦN GẦN NHẤT
        //         VÀ GÁN TÊN THÁNG TƯƠNG ỨNG
        // ----------------------------------------------------

        const today = new Date();
        const fourWeekCategories = [];

        // Lặp để lấy 4 tuần, bắt đầu từ (Tuần hiện tại - 3) đến (Tuần hiện tại)
        // i sẽ chạy từ -3 đến 0 (4 items)
        for (let i = -3; i <= 0; i++) {
            const date = new Date(today);

            // Di chuyển đến một ngày trong tuần cần tính (ví dụ: ngày cuối tuần đó)
            date.setDate(today.getDate() + (i * 7));

            // Lấy tên tháng
            const monthIndex = date.getMonth();
            const monthName = monthNames[monthIndex];

            // Lấy ngày trong tháng để làm rõ hơn (ví dụ: 01 Thg 12)
            const day = date.getDate().toString().padStart(2, '0');

            // Định dạng thành "Ngày/Tháng" hoặc "Tuần x" tùy theo nhu cầu trực quan
            // Tôi chọn hiển thị ngày và tháng để biết tuần đó thuộc tháng nào
            const formattedLabel = `${day} ${monthName}`;

            fourWeekCategories.push(formattedLabel);
        }

        // ----------------------------------------------------
        // BƯỚC 2: KHỞI TẠO BIỂU ĐỒ VỚI DANH SÁCH NGÀY/THÁNG MỚI
        // ----------------------------------------------------

        var options = {
            series: [
                // Cần có 4 giá trị data để khớp với 4 categories
                { name: "Doanh thu", data: [50, 5, 10, 20] },
            ],
            colors: ["#28C76F"],
            chart: {
                type: "bar",
                height: 300,
                stacked: true,
                zoom: { enabled: true },
            },
            responsive: [
                {
                    breakpoint: 280,
                    options: { legend: { position: "bottom", offsetY: 0 } },
                },
            ],
            plotOptions: {
                bar: { horizontal: false, columnWidth: "50%" },
            },
            xaxis: {
                // SỬ DỤNG MẢNG CATEGORIES MỚI (4 TUẦN, GHI TÊN THÁNG)
                categories: fourWeekCategories,
                // title: { text: "4 Tuần Gần Nhất (Theo Ngày/Tháng)" }
            },
            legend: { position: "right", offsetY: 40 },
            fill: { opacity: 1 },
        };

        var chart = new ApexCharts(
            document.querySelector("#sales_charts2"),
            options
        );
        chart.render();
    }
    if ($("#sales_charts3").length > 0) {

        // Mảng tên 12 tháng
        const monthNames = [
            "Thg 1", "Thg 2", "Thg 3", "Thg 4", "Thg 5", "Thg 6",
            "Thg 7", "Thg 8", "Thg 9", "Thg 10", "Thg 11", "Thg 12"
        ];

        // ----------------------------------------------------
        // BƯỚC 1: TẠO DANH SÁCH CÁC THÁNG ĐÃ TRÔI QUA TRONG NĂM
        // ----------------------------------------------------

        // Lấy tháng hiện tại (0 = Tháng 1, 11 = Tháng 12)
        const currentMonthIndex = new Date().getMonth();

        // Lấy số lượng tháng đã trôi qua (ví dụ: nếu là tháng 1, length = 1; tháng 12, length = 12)
        const monthsPassed = currentMonthIndex + 1;

        // Cắt mảng tên tháng để chỉ hiển thị các tháng đã trôi qua
        const currentYearCategories = monthNames.slice(0, monthsPassed);

        // ----------------------------------------------------
        // BƯỚC 2: KHỞI TẠO BIỂU ĐỒ VỚI DANH SÁCH THÁNG MỚI
        // ----------------------------------------------------

        // LƯU Ý QUAN TRỌNG: Mảng data BẮT BUỘC phải có số lượng phần tử
        // BẰNG VỚI số lượng categories (monthsPassed)
        // Ví dụ, nếu hiện tại là tháng 1 (monthsPassed = 1), data chỉ cần 1 giá trị.
        var sampleData = [50, 45, 60, 70, 50, 45, 60, 70, 80, 75, 90, 100]; // 12 giá trị mẫu
        var dataForCurrentMonths = sampleData.slice(0, monthsPassed);


        var options = {
            series: [
                {
                    name: "Doanh thu",
                    data: dataForCurrentMonths // Sử dụng mảng data đã được cắt
                },
            ],
            colors: ["#28C76F"],
            chart: {
                type: "bar",
                height: 300,
                stacked: true,
                zoom: { enabled: true },
            },
            responsive: [
                {
                    breakpoint: 280,
                    options: { legend: { position: "bottom", offsetY: 0 } },
                },
            ],
            plotOptions: {
                bar: { horizontal: false, columnWidth: "50%" },
            },
            xaxis: {
                // SỬ DỤNG MẢNG CATEGORIES CÁC THÁNG ĐÃ TRÔI QUA
                categories: currentYearCategories,
                // title: { text: `Doanh thu năm ${new Date().getFullYear()}` }
            },
            legend: { position: "right", offsetY: 40 },
            fill: { opacity: 1 },
        };

        var chart = new ApexCharts(
            document.querySelector("#sales_charts3"),
            options
        );
        chart.render();
    }
});

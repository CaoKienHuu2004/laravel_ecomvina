<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>403 - Không có quyền</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        h1 {
            color: #e74c3c;
        }
        .buttons {
            margin-top: 20px;
        }
        a, form button {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            border: none;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }
        a:hover, form button:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <h1>403 - Bạn không có quyền truy cập</h1>
    <p>Vui lòng chọn thao tác bên dưới:</p>
    <div class="buttons">
        <a href="{{ url('/test-guest') }}">🏠 Về Trang Chủ</a>

        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit">🚪 Đăng Xuất</button>
        </form>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>401 - Unauthorized</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            color: #333;
            text-align: center;
            padding: 50px;
        }
        .error-box {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: inline-block;
        }
        h1 {
            font-size: 48px;
            margin-bottom: 10px;
            color: #e3342f;
        }
        p {
            font-size: 18px;
        }
        a {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            background: #3490dc;
            color: #fff;
            padding: 10px 20px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="error-box">
        <h1>401</h1>
        <p>Bạn không có quyền truy cập trang này.</p>
        <a href="{{ url('/') }}">Quay về Trang chủ</a>
    </div>
</body>
</html>

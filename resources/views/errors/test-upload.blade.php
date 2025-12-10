<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Test Upload</title>
</head>
<body>

<h2>Test Upload File</h2>

@if(session('error'))
    <p style="color:red;">⚠ {{ session('error') }}</p>
@endif

@if(session('success'))
    <p style="color:green;">✔ {{ session('success') }}</p>
    <p>Đường dẫn file: {{ session('path') }}</p>
    <p>URL public: <a href="{{ session('url') }}" target="_blank">{{ session('url') }}</a></p>
@endif

<form action="{{ route('test.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required>
    <button type="submit">Upload</button>
</form>

</body>
</html>


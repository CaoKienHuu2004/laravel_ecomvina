<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
      <meta name="description" content="POS - Bootstrap Admin Template">
      <meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, Projects">
      <meta name="author" content="Dreamguys - Bootstrap Admin Template">
      <meta name="robots" content="noindex, nofollow">
      <title>Đăng nhập - Ecomvina</title>

      <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/favicon.png') }}">

      <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/fontawesome/css/fontawesome.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/fontawesome/css/all.min.css') }}">
      <link rel="stylesheet" href="{{ asset('css/style.css') }}">
   </head>
   <body class="account-page">
      <div class="main-wrapper">
         <div class="account-content">
            <div class="login-wrapper">
               <div class="login-content">
                  <div class="login-userset">
                     <div class="login-logo">
                        <img src="{{ asset('img/logo_nguyenban.png') }}" alt="img" width="150" height="65">
                        {{-- <img src="{{ asset('img/logo.png') }}" alt="img"> --}}
                     </div>
                     <div class="login-userheading">
                        <h3>Đăng Nhập</h3>
                        <h4>Mời Nhập Tài Khoản</h4>
                     </div>
                     @if ($errors->has('login'))
                        <div class="alert alert-danger">
                            {{ $errors->first('login') }}
                        </div>
                    @endif
                     <form class="login-form" action="{{ route('xu-ly-dang-nhap')}}" method="POST" >
                        @csrf
                        <div class="form-login">
                           <label>Tên Đăng Nhập</label>
                           <div class="form-addons">
                              <input type="text" name="username" placeholder="Điền Tên Đăng Nhập hoặc Email">
                              <img src="{{ asset('img/icons/mail.svg') }}" alt="img">
                           </div>
                        </div>
                        <div class="form-login">
                           <label>Mật Khẩu</label>
                           <div class="pass-group">
                              <input type="password" name="password" class="pass-input" placeholder="Điền Mật Khẩu">
                              <span class="fas toggle-password fa-eye-slash"></span>
                           </div>
                        </div>
                        <div class="form-login">
                           <div class="alreadyuser">
                              {{-- // ------------------ Không có routes của NEXTJS, hoặc lấy theo domain ngoại, dang-nhap vì để tạm vây -------------- ///////// --}}
                              <h4><a href="{{ route('dang-nhap')}}" class="hover-a" style="color: #DE473F !important;">Quay lại trang chủ</a></h4>
                           </div>
                        </div>
                        <div class="form-login">
                           <button type="submit" class="btn btn-login" style="background-color: #DE473F !important;">Đăng Nhập</button>
                        </div>
                     </form>
                     <div class="signinform text-center  d-none">
                        <h4>Don’t have an account? <a href="signup.html" class="hover-a">Sign Up</a></h4>
                     </div>
                     <div class="form-setlogin  d-none">
                        <h4>Or sign up with</h4>
                     </div>
                     <div class="form-sociallink  d-none">
                        <ul>
                           <li>
                              <a href="javascript:void(0);">
                                 <img src="{{ asset('img/icons/google.png') }}" class="me-2" alt="google">
                                 Sign Up using Google
                              </a>
                           </li>
                           <li>
                              <a href="javascript:void(0);">
                                 <img src="{{ asset('img/icons/facebook.png') }}" class="me-2" alt="google">
                                 Sign Up using Facebook
                              </a>
                           </li>
                        </ul>
                     </div>
                  </div>
               </div>
               <div class="login-img">
                  <img src="{{ asset('img/login.jpg') }}" alt="img">
               </div>
            </div>
         </div>
      </div>

      <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
      <script src="{{ asset('js/feather.min.js') }}"></script>
      <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
      <script src="{{ asset('js/script.js') }}"></script>

   </body>
</html>

<script>
document.querySelector(".login-form").addEventListener("submit", function (e) {
    const input = document.querySelector("input[name='username']");
    const value = input.value.trim();

    // Kiểm tra trống
    if (!value) {
        alert("Bạn phải nhập tài khoản (username hoặc email).");
        e.preventDefault();
        return;
    }

    const isEmail = value.includes("@");

    // Regex email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // Regex username
    const usernameRegex = /^[A-Za-z0-9_]{6,20}$/;

    // Nếu là email
    if (isEmail) {
        if (value.length > 50) {
            alert("Email tối đa 50 ký tự.");
            e.preventDefault();
            return;
        }
        if (!emailRegex.test(value)) {
            alert("Email không hợp lệ!");
            e.preventDefault();
            return;
        }
    }
    // Nếu là username
    else {
        if (value.length > 20) {
            alert("Username tối đa 20 ký tự.");
            e.preventDefault();
            return;
        }
        if (!usernameRegex.test(value)) {
            alert("Username phải 6–20 ký tự và chỉ gồm chữ, số, dấu gạch dưới.");
            e.preventDefault();
            return;
        }
    }
});
</script>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - sidata</title>

  @vite(['resources/sass/app.scss', 'resources/js/app.js'])
   <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    body, html {
      height: 100%;
      margin: 0;
      font-family: "Rubik", sans-serif;
    }

    .login-container {
      height: 100vh;
    }

    .login-form-section {
      padding: 40px 60px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .login-form-section h2 {
      font-weight: 600;
      margin-bottom: 10px;
    }

    .login-form-section p {
      color: #6c757d;
      margin-bottom: 30px;
    }

    .login-image {
        background: #5c99ee;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
        border-top-left-radius: 20px;
        border-bottom-left-radius: 20px;
        overflow: hidden;
    }

    .login-image img {
      max-width: 80%;
      height: auto;
    }

    .form-control {
      padding: 10px 12px;
    }

    .btn-primary {
      background-color: #5c99ee;
      border-color: #5c99ee;
    }

    .btn-primary:hover {
      background-color: #4c8be0;
      border-color: #4c8be0;
    }

    .error-text {
      color: #d9534f;
      font-size: 14px;
      margin-top: 6px;
    }

    .form-check {
      display: flex;
      align-items: center;
      gap: 5px;
      margin-bottom: 20px;
    }

  </style>
</head>
<body>

<div class="container-fluid login-container">
  <div class="row h-100">

    <div class="col-md-6 login-form-section">
      <h2>Selamat Datang</h2>
      <p>Silakan login terlebih dahulu</p>

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input id="email" type="email"
                 class="form-control @error('email') is-invalid @enderror"
                 name="email" value="{{ old('email') }}" required autofocus>
          @error('email')
            <div class="error-text"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input id="password" type="password"
                 class="form-control @error('password') is-invalid @enderror"
                 name="password" required>
          @error('password')
            <div class="error-text"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
          @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-2">Login</button>

      </form>
    </div>

    <div class="col-md-6 login-image d-none d-md-flex">
      <img src="{{ asset('images/vector.png') }}" alt="Login Illustration">
    </div>

  </div>
</div>

</body>
</html>

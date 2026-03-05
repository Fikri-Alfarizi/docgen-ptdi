<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Document Generator PT DI</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .ptdi-logo {
            max-width: 150px;
            margin-bottom: 20px;
        }

        .bg-primary-ptdi {
            background-color: #003366 !important;
            /* Corporate Blue style attempt */
            color: white;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card login-card border-0">
                    <div class="card-body p-5 text-center">
                        <!-- Placeholder for PT DI Logo -->
                        <h3 class="fw-bold text-uppercase mb-1" style="color: #003366;">PT DI</h3>
                        <p class="text-muted mb-4">Aplikasi Document Generator</p>

                        @if($errors->any())
                            <div class="alert alert-danger p-2 mb-3 text-start small">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('login') }}" method="POST" class="text-start">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email') }}" required autofocus
                                    placeholder="Masukkan email karyawan...">
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label fw-bold">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required
                                    placeholder="Masukkan password...">
                            </div>
                            <button type="submit" class="btn bg-primary-ptdi w-100 py-2 fw-bold">LOGIN</button>
                        </form>

                        <div class="mt-4 text-muted small">
                            Belum punya akun? <a href="{{ route('register') }}"
                                class="fw-bold text-decoration-none">Daftar di sini</a><br>
                            &copy; {{ date('Y') }} PT Dirgantara Indonesia. All rights reserved.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
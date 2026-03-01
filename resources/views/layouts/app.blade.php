<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Dokumen - PT Dirgantara Indonesia</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons & Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #ffffff;
            font-size: 14px;
        }

        /* Top Navbar */
        .navbar-ptdi-top {
            background-color: #21255e;
            padding: 0;
            min-height: 50px;
        }

        .navbar-ptdi-top .nav-link {
            color: rgba(255, 255, 255, 0.85);
            font-size: 13px;
            font-weight: 500;
            text-transform: uppercase;
            padding: 15px 12px;
            transition: 0.2s;
        }

        .navbar-ptdi-top .nav-link:hover,
        .navbar-ptdi-top .nav-link.active {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .btn-grid-blue {
            background-color: #007bff;
            color: white;
            border-radius: 0;
            padding: 12px 15px;
            border: none;
            cursor: pointer;
        }

        .btn-grid-blue:hover {
            background-color: #0056b3;
        }

        .search-container {
            display: flex;
            align-items: center;
            height: 35px;
        }

        .search-container input {
            border: none;
            border-radius: 0;
            height: 100%;
            padding: 5px 10px;
            outline: none;
            width: 200px;
        }

        .btn-search-green {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 0;
            height: 100%;
            padding: 0 15px;
            font-size: 13px;
            font-weight: 500;
            transition: 0.2s;
        }

        .btn-search-green:hover {
            background-color: #218838;
        }

        /* Secondary Navbar */
        .navbar-ptdi-secondary {
            background-color: #ffffff;
            border-bottom: 1px solid #e0e0e0;
            padding: 5px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            font-size: 13px;
        }

        .navbar-ptdi-secondary .nav-link {
            color: #6c757d;
            padding: 10px 12px;
            font-weight: 400;
        }

        .navbar-ptdi-secondary .nav-link:hover {
            color: #333;
        }

        /* Main Content wrapper */
        .content-wrapper {
            padding: 30px 0;
            min-height: calc(100vh - 180px);
        }

        /* Footer scroll-to-top */
        .btn-scroll-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #12c554;
            color: white;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-decoration: none;
            transition: 0.3s;
            border: none;
            z-index: 1000;
        }

        .btn-scroll-top:hover {
            background-color: #0e9941;
            color: white;
            transform: translateY(-3px);
        }

        /* Dropdown arrow override */
        .dropdown-toggle::after {
            vertical-align: middle;
            font-size: 10px;
            margin-left: 5px;
        }
    </style>
    @stack('styles')
</head>

<body>

    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-ptdi-top">
        <div class="container-fluid px-2">
            <button class="navbar-toggler text-white border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#topNav">
                <i class="bi bi-list fs-3"></i>
            </button>

            <div class="collapse navbar-collapse" id="topNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#">BERANDA</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">DIREKTORI TELP.</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">DUKUNGAN TI</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}"
                            href="{{ route('user.dashboard') }}">DOKUMEN</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">JPK</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">GALERI</a>
                    </li>

                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu rounded-0 shadow border-0">
                                <li>
                                    <h6 class="dropdown-header">Akses: {{ ucfirst(Auth::user()->role) }}</h6>
                                </li>
                                @if(Auth::user()->role === 'admin')
                                    <li>
                                        <a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-cog me-2"></i>
                                            Panel Admin</a>
                                    </li>
                                @endif
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">LOGIN</a>
                        </li>
                    @endauth
                </ul>

                <!-- Grid Button & Search -->
                <div class="d-flex align-items-center">
                    <div class="btn-grid-blue me-3">
                        <i class="bi bi-grid-3x3-gap-fill"></i>
                    </div>
                    <form class="search-container me-2" action="#">
                        <input type="text" placeholder="Search">
                        <button class="btn-search-green" type="button"><i class="fas fa-search me-1"></i> CARI</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Secondary Navbar -->
    <nav class="navbar navbar-expand-lg navbar-ptdi-secondary d-none d-lg-block">
        <div class="container-fluid justify-content-center">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="#">Semua Materi</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Kebijakan Perusahaan</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Dokumen
                        Mutu</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Contoh 1</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="#">Dokumen K3LH</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Command Media Umum</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Quality
                        Operation</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Contoh 2</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="#">Tulisan Dinas</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Dokumen DOA</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Contoh 3</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="content-wrapper">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Scroll to Top Button -->
    <a href="#" class="btn-scroll-top" id="scrollTopBtn">
        <i class="fas fa-chevron-up"></i>
    </a>

    <!-- Footer -->
    <footer class="bg-light py-4 border-top mt-auto">
        <div class="container text-center text-muted" style="font-size: 13px;">
            Copyright &copy; {{ date('Y') }} PT Dirgantara Indonesia. All rights reserved. (Sistem Informasi Dokumen)
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <script>
        // Scroll to top
        $(window).scroll(function () {
            if ($(this).scrollTop() > 100) {
                $('#scrollTopBtn').fadeIn();
            } else {
                $('#scrollTopBtn').fadeOut();
            }
        });
        $('#scrollTopBtn').click(function (e) {
            e.preventDefault();
            $('html, body').animate({ scrollTop: 0 }, 'fast');
            return false;
        });
    </script>
    @stack('scripts')
</body>

</html>
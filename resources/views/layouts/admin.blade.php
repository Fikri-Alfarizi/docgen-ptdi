<!DOCTYPE html>
<html lang="id">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $site_settings['app_name'] ?? 'PT DI' }} | Admin Dashboard</title>

    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('staradmin/assets/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('staradmin/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('staradmin/assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('staradmin/assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('staradmin/assets/vendors/typicons/typicons.css') }}">
    <link rel="stylesheet" href="{{ asset('staradmin/assets/vendors/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('staradmin/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet"
        href="{{ asset('staradmin/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <!-- endinject -->

    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('staradmin/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('staradmin/assets/js/select.dataTables.min.css') }}">
    <!-- End plugin css for this page -->

    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('staradmin/assets/css/style.css') }}">
    <!-- endinject -->

    <link rel="shortcut icon"
        href="{{ !empty($site_settings['app_favicon']) ? asset('uploads/settings/' . $site_settings['app_favicon']) : asset('staradmin/assets/images/favicon.png') }}" />
    @stack('styles')
    @if(!empty($isPublic) && $isPublic)
        <style>
            .container-scroller {
                padding-top: 0 !important;
            }

            .page-body-wrapper {
                padding-top: 0 !important;
                min-height: 100vh !important;
            }

            .main-panel {
                width: 100% !important;
                margin-left: 0 !important;
            }

            .content-wrapper {
                padding: 20px !important;
                width: 100% !important;
                max-width: 100% !important;
            }

            .footer {
                width: 100% !important;
                margin-left: 0 !important;
            }
        </style>
    @endif
</head>

<body class="with-welcome-text">
    <div class="container-scroller">

        @if(empty($isPublic) || !$isPublic)
            <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
                <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                    <div class="me-3">
                        <button class="navbar-toggler navbar-toggler align-self-center" type="button"
                            data-bs-toggle="minimize">
                            <span class="icon-menu"></span>
                        </button>
                    </div>
                    <div>
                        <a class="navbar-brand brand-logo" href="{{ route('admin.dashboard') }}">
                            @if(isset($site_settings['app_logo']) && $site_settings['app_logo'])
                                <img src="{{ asset('uploads/settings/' . $site_settings['app_logo']) }}" alt="logo" />
                            @else
                                <img src="{{ asset('staradmin/assets/images/logo.svg') }}" alt="logo" />
                            @endif
                        </a>
                        <a class="navbar-brand brand-logo-mini" href="{{ route('admin.dashboard') }}">
                            @if(isset($site_settings['app_logo']) && $site_settings['app_logo'])
                                <img src="{{ asset('uploads/settings/' . $site_settings['app_logo']) }}" alt="logo" />
                            @else
                                <img src="{{ asset('staradmin/assets/images/logo-mini.svg') }}" alt="logo" />
                            @endif
                        </a>
                    </div>
                </div>
                <div class="navbar-menu-wrapper d-flex align-items-top">
                    <ul class="navbar-nav">
                        <li class="nav-item fw-semibold d-none d-lg-block ms-0">
                            <h1 class="welcome-text">Selamat Datang, <span
                                    class="text-black fw-bold">{{ Auth::check() ? Auth::user()->name : 'Administrator' }}</span>
                            </h1>
                            <h3 class="welcome-sub-text">Sistem Manajemen Dokumen
                                {{ $site_settings['company_name'] ?? 'PT Dirgantara Indonesia' }}
                            </h3>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <!-- Global Search -->
                        <li class="nav-item d-none d-lg-block">
                            <form class="search-form" action="{{ route('admin.search') }}" method="GET">
                                <i class="icon-search" style="cursor: pointer;" onclick="this.closest('form').submit()"></i>
                                <input type="search" name="query" class="form-control"
                                    placeholder="Cari data (Tekan Enter)..."
                                    title="Ketik untuk mencari Karyawan, Template, atau Riwayat Dokumen lalu Enter"
                                    autocomplete="off" value="{{ request('query') }}">
                            </form>
                        </li>
                        <!-- User Dropdown Profile -->
                        <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                            <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px; min-width: 40px;">
                                    <i class="mdi mdi-account text-white fs-6"></i>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                                <div class="dropdown-header text-center">
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto"
                                        style="width: 65px; height: 65px;">
                                        <i class="mdi mdi-account text-white fs-3"></i>
                                    </div>
                                    <p class="mb-1 mt-3 fw-semibold">
                                        {{ Auth::check() ? Auth::user()->name : 'Administrator' }}
                                    </p>
                                    <p class="fw-light text-muted mb-0">Role: Admin</p>
                                </div>
                                <a class="dropdown-item" href="#">
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2"
                                        style="width: 30px; height: 30px;">
                                        <i class="mdi mdi-account text-white fs-5"></i>
                                    </div> Profil Saya
                                </a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i
                                            class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out</button>
                                </form>
                            </div>
                        </li>
                    </ul>
                    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                        data-bs-toggle="offcanvas">
                        <span class="mdi mdi-menu"></span>
                    </button>
                </div>
            </nav>
        @endif
        <!-- partial -->

        <div class="container-fluid page-body-wrapper {{ !empty($isPublic) && $isPublic ? 'w-100 ps-0' : '' }}">

            <!-- partial:partials/_sidebar.html -->
            @if(empty($isPublic) || !$isPublic)
                <nav class="sidebar sidebar-offcanvas" id="sidebar">
                    <ul class="nav">
                        <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <i class="mdi mdi-grid-large menu-icon"></i>
                                <span class="menu-title">Dashboard</span>
                            </a>
                        </li>

                        <li class="nav-item nav-category">MANAJEMEN SISTEM</li>
                        <li class="nav-item {{ request()->routeIs('admin.templates') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.templates') }}">
                                <i class="mdi mdi-file-document-box-outline menu-icon"></i>
                                <span class="menu-title">Template Dokumen</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.users') }}">
                                <i class="mdi mdi-account-group-outline menu-icon"></i>
                                <span class="menu-title">Data Karyawan</span>
                            </a>
                        </li>

                        <li class="nav-item nav-category">PELAPORAN</li>
                        <li class="nav-item {{ request()->routeIs('admin.documents.history') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.documents.history') }}">
                                <i class="mdi mdi-history menu-icon"></i>
                                <span class="menu-title">Riwayat Cetak Global</span>
                            </a>
                        </li>

                        @if(!empty($site_settings['admin_settings_visible']) && $site_settings['admin_settings_visible'] == '1')
                            <li class="nav-item nav-category">SISTEM</li>
                            <li class="nav-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.settings') }}">
                                    <i class="mdi mdi-settings-outline menu-icon"></i>
                                    <span class="menu-title">Pengaturan Sistem</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </nav>
            @endif
            <!-- partial -->

            <div class="main-panel {{ !empty($isPublic) && $isPublic ? 'w-100' : '' }}">
                <div class="content-wrapper">
                    @yield('content')
                </div>
                <!-- content-wrapper ends -->

                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
                            {{ $site_settings['footer_text'] ?? 'Sistem Informasi Dokumen - PT Dirgantara Indonesia.' }}
                        </span>
                        <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">Copyright ©
                            {{ date('Y') }}. All rights reserved.</span>
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>

    <!-- Modal Bagikan -->
    <div class="modal fade" id="shareDashboardModal" tabindex="-1" aria-labelledby="shareDashboardModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title d-flex align-items-center" id="shareDashboardModalLabel">
                        <i class="mdi mdi-share-variant me-2 fs-4"></i> Bagikan Dashboard
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <p class="text-muted mb-4">Pilih platform atau salin tautan (Read-Only) untuk membagikan statistik
                        Dashboard saat ini.</p>

                    <div class="input-group mb-4">
                        <input type="text" class="form-control" id="shareUrlInput" readonly value="">
                        <button class="btn btn-outline-secondary" type="button" id="btnCopyShare" title="Salin Link">
                            <i class="mdi mdi-content-copy"></i>
                        </button>
                    </div>

                    <div class="d-flex justify-content-center gap-3">
                        <a href="#" id="shareWa" target="_blank"
                            class="btn btn-success rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px;" title="WhatsApp">
                            <i class="mdi mdi-whatsapp fs-3"></i>
                        </a>
                        <a href="#" id="shareFb" target="_blank"
                            class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px; background-color: #1877F2; border-color: #1877F2;"
                            title="Facebook">
                            <i class="mdi mdi-facebook fs-3 ms-1"></i>
                        </a>
                        <a href="#" id="shareTw" target="_blank"
                            class="btn btn-info rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px;" title="Twitter / X">
                            <i class="mdi mdi-twitter fs-3"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- container-scroller -->

    <!-- plugins:js -->
    <script src="{{ asset('staradmin/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('staradmin/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{ asset('staradmin/assets/vendors/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('staradmin/assets/vendors/progressbar.js/progressbar.min.js') }}"></script>
    <script src="{{ asset('staradmin/assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('staradmin/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('staradmin/assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('staradmin/assets/js/template.js') }}"></script>
    <script src="{{ asset('staradmin/assets/js/settings.js') }}"></script>
    <script src="{{ asset('staradmin/assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('staradmin/assets/js/todolist.js') }}"></script>
    <!-- endinject -->
    <script>
        // DataTables Global Configuration forexact match search issue fix
        $.extend(true, $.fn.dataTable.defaults, {
            "language": {
                search: ""
            }
        });

        // Share Modal Logic
        $(document).ready(function () {
            let randomToken = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
            const shareUrl = "{{ url('/share/dashboard') }}/" + randomToken;

            $('#shareUrlInput').val(shareUrl);

            $('#btnCopyShare').on('click', function () {
                const shareInput = document.getElementById('shareUrlInput');
                shareInput.select();
                shareInput.setSelectionRange(0, 99999); // For mobile devices
                document.execCommand('copy');
                alert('Link telah disalin ke clipboard!');
            });

            $('#shareWa').on('click', function (e) {
                e.preventDefault();
                const text = encodeURIComponent("Lihat statistik Dashboard PT Dirgantara Indonesia (Read-Only): " + $('#shareUrlInput').val());
                window.open(`https://api.whatsapp.com/send?text=${text}`, '_blank');
            });

            $('#shareFb').on('click', function (e) {
                e.preventDefault();
                const url = encodeURIComponent($('#shareUrlInput').val());
                window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
            });

            $('#shareTw').on('click', function (e) {
                e.preventDefault();
                const text = encodeURIComponent("Lihat statistik Dashboard PT Dirgantara Indonesia (Read-Only)");
                const url = encodeURIComponent($('#shareUrlInput').val());
                window.open(`https://twitter.com/intent/tweet?text=${text}&url=${url}`, '_blank');
            });
        });
    </script>
    <style>
        /* Optimization: Improve Icon Loading Performance */
        @font-face {
            font-family: "Material Design Icons";
            font-display: swap;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #ebedf2;
            padding: 0.4375rem 1.75rem 0.4375rem 0.75rem;
            font-size: 0.8125rem;
            font-weight: 400;
            line-height: 1;
            color: #212529;
            background-color: #fff;
            background-clip: padding-box;
            border-radius: 4px;
            box-shadow: none;
            outline: none;
        }

        .dataTables_wrapper .dataTables_length select:focus {
            border-color: #1F3BB3;
        }

        /* Fix for all dropdowns (form-select) for better contrast */
        .form-select,
        select.form-control {
            color: #1F3BB3 !important;
            /* Use theme primary for better visibility */
            background-color: #ffffff !important;
            border: 1px solid #ced4da !important;
            font-weight: 500 !important;
        }

        .form-select option {
            color: #212529 !important;
            background-color: #ffffff !important;
        }

        /* Optimization: Compact Tables */
        .select-table.compact-table th,
        .select-table.compact-table td {
            padding-top: 10px !important;
            padding-bottom: 10px !important;
            padding-left: 8px !important;
            padding-right: 8px !important;
            font-size: 0.8rem !important;
        }

        .select-table.compact-table h6 {
            font-size: 0.85rem !important;
            margin-bottom: 0 !important;
        }

        .select-table.compact-table p {
            font-size: 0.75rem !important;
        }

        .select-table.compact-table .badge {
            padding: 4px 8px !important;
            font-size: 0.7rem !important;
        }

        .select-table.compact-table .btn-sm {
            padding: 4px 8px !important;
        }

        @media print {
            body {
                background: #fff !important;
            }

            .navbar,
            .sidebar,
            .btn-wrapper,
            .nav-tabs,
            footer,
            .modal {
                display: none !important;
            }

            .main-panel {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                min-height: auto !important;
            }

            .content-wrapper {
                background: #fff !important;
                padding: 0 !important;
            }

            .card {
                border: 1px solid #ddd !important;
                box-shadow: none !important;
                break-inside: avoid;
                margin-bottom: 20px !important;
            }

            .chartjs-bar-wrapper {
                height: 250px !important;
            }

            /* Clean up Dashboard print grid */
            .row {
                display: flex !important;
                flex-wrap: wrap !important;
            }

            .col-lg-8,
            .col-lg-4,
            .col-12 {
                flex: 0 0 auto !important;
            }

            .col-lg-8 {
                width: 66.66666667% !important;
            }

            .col-lg-4 {
                width: 33.33333333% !important;
            }

            .col-12 {
                width: 100% !important;
            }

            /* Print tabs visibility */
            .tab-content>.tab-pane {
                display: block !important;
                opacity: 1 !important;
            }

            #more {
                display: none !important;
            }

            /* Hide quick actions in print */
        }
    </style>

    @stack('scripts')
    <!-- Shortcut Handler for Settings Visibility -->
    @if(Auth::check() && Auth::user()->role === 'admin')
        <script>
            document.addEventListener('keydown', function (e) {
                const shortcut = "{{ strtolower($site_settings['admin_settings_shortcut'] ?? 'ctrl+shift+f1') }}";
                const keys = shortcut.split('+');

                let ctrl = keys.includes('ctrl');
                let shift = keys.includes('shift');
                let alt = keys.includes('alt');
                let targetKey = keys[keys.length - 1].toLowerCase();

                // Special handling for F1-F12
                let pressedKey = e.key.toLowerCase();

                if (
                    e.ctrlKey === ctrl &&
                    e.shiftKey === shift &&
                    e.altKey === alt &&
                    pressedKey === targetKey
                ) {
                    e.preventDefault();

                    fetch("{{ route('admin.settings.toggle') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.location.reload();
                            }
                        })
                        .catch(error => console.error('Error toggling settings visibility:', error));
                }
            });
        </script>
    @endif
</body>

</html>
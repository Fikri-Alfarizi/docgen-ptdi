<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>404 - Halaman Tidak Ditemukan | DOCGEN PTDI</title>
    <link rel="stylesheet" href="{{ asset('staradmin/assets/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('staradmin/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('staradmin/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('staradmin/assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('staradmin/assets/images/favicon.png') }}" />
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center text-center error-page bg-primary">
                <div class="row flex-grow">
                    <div class="col-lg-7 mx-auto text-white">
                        <div class="row align-items-center d-flex flex-row">
                            <div class="col-lg-6 text-lg-right pr-lg-4">
                                <h1 class="display-1 mb-0">404</h1>
                            </div>
                            <div class="col-lg-6 error-page-divider text-lg-left pl-lg-4">
                                <h2>MAAF!</h2>
                                <h3 class="fw-light">Halaman yang Anda cari tidak ditemukan.</h3>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-12 text-center mt-xl-2">
                                <a class="text-white fw-medium" href="{{ url('/') }}">
                                    <i class="mdi mdi-arrow-left"></i> Kembali ke Dashboard
                                </a>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-12 mt-xl-2">
                                <p class="text-white fw-medium text-center">
                                    Sistem Informasi Dokumen - PT Dirgantara Indonesia.
                                    Copyright &copy; {{ date('Y') }}. All rights reserved.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('staradmin/assets/vendors/js/vendor.bundle.base.js') }}"></script>
</body>

</html>
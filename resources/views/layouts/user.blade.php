<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $site_settings['app_name'] ?? 'User Portal' }} |
        {{ $site_settings['company_name'] ?? 'PT Dirgantara Indonesia' }}
    </title>
    <link rel="shortcut icon"
        href="{{ !empty($site_settings['app_favicon']) ? asset('uploads/settings/' . $site_settings['app_favicon']) : asset('staradmin/assets/images/favicon.png') }}" />

    <!-- Fonts: Inter for modern look -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('staradmin/assets/vendors/mdi/css/materialdesignicons.min.css') }}">

    <style>
        :root {
            --primary-indigo: #0f172a;
            --accent-blue: #3b82f6;
            --sidebar-width: 230px;
            /* More compact */
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            /* Slightly darker background */
            color: #1e293b;
            overflow-x: hidden;
            min-height: 100vh;
            font-size: 14px;
            /* Smaller base font */
        }

        /* Sidebar Styling (Glassmorphism) */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: white;
            /* Changed to white */
            color: #1e293b;
            /* Dark text */
            z-index: 1000;
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #e2e8f0;
            /* Subtle merge border */
            box-shadow: none !important;
        }

        .sidebar-header {
            padding: 1.5rem 1rem;
            text-align: center;
            border-bottom: 1px solid #f1f5f9;
        }

        .sidebar-header .logo {
            font-weight: 800;
            font-size: 1.25rem;
            letter-spacing: 1px;
            color: var(--primary-indigo);
            /* Dark color */
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .nav-menu {
            flex: 1;
            padding: 2rem 0;
            list-style: none;
            margin: 0;
        }

        .nav-item {
            margin-bottom: 0.5rem;
            padding: 0 1rem;
        }

        .nav-link {
            color: #64748b !important;
            /* Muted dark */
            padding: 0.6rem 1rem !important;
            border-radius: 4px;
            display: flex !important;
            align-items: center;
            gap: 10px;
            transition: all 0.15s ease;
            font-weight: 500;
            text-decoration: none;
            font-size: 0.85rem;
        }

        .nav-link:hover {
            color: var(--accent-blue) !important;
            background: #f8fafc;
        }

        .nav-link.active {
            background: #eff6ff;
            /* Light blue background for active */
            color: var(--accent-blue) !important;
            border-right: 3px solid var(--accent-blue);
        }

        .nav-link i {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Top Header Area */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        /* Glass Cards */
        .glass-card {
            background: white;
            border-radius: 4px;
            /* Sharp */
            border: 1px solid #cbd5e1;
            padding: 1rem;
            box-shadow: none !important;
        }

        /* User Profile Dropdown */
        .user-dropdown-btn {
            background: white;
            border: 1px solid #cbd5e1;
            padding: 0.4rem 0.8rem;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.1s;
        }

        .user-dropdown-btn:hover {
            background: #f1f5f9;
        }

        .profile-pic {
            width: 30px;
            height: 30px;
            background: white;
            color: var(--accent-blue);
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.8rem;
        }

        /* Alerts Custom */
        .alert-custom {
            border-radius: 16px;
            border: none;
            padding: 1rem 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease forwards;
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }

        /* Drag & Drop Overlay */
        #global-drag-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.85);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(5px);
            transition: all 0.2s ease;
        }

        #global-drag-overlay.d-none {
            display: none !important;
        }

        .overlay-content {
            text-align: center;
            border: 3px dashed rgba(255, 255, 255, 0.5);
            border-radius: 20px;
            padding: 50px 100px;
            pointer-events: none;
            animation: pulse-border 2s infinite;
        }

        @keyframes pulse-border {
            0% {
                border-color: rgba(255, 255, 255, 0.5);
            }

            50% {
                border-color: rgba(255, 255, 255, 1);
                transform: scale(1.02);
            }

            100% {
                border-color: rgba(255, 255, 255, 0.5);
            }
        }
    </style>
    @stack('styles')
</head>

<body>

    <!-- Premium Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('user.dashboard') }}" class="logo">
                @if(isset($site_settings['app_logo']) && $site_settings['app_logo'])
                    <img src="{{ asset('uploads/settings/' . $site_settings['app_logo']) }}" alt="logo"
                        style="max-height: 40px; width: auto;">
                @else
                    <i class="mdi mdi-checkbox-multiple-marked-circle-outline"></i>
                    <span>{{ $site_settings['app_name'] ?? 'DOCGEN' }}</span>
                @endif
            </a>
        </div>

        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('user.dashboard') }}"
                    class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <i class="mdi mdi-view-dashboard-outline"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.documents.upload') }}"
                    class="nav-link {{ request()->routeIs('user.documents.upload') ? 'active' : '' }}">
                    <i class="mdi mdi-upload"></i>
                    <span>Upload Dokumen</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.documents') }}"
                    class="nav-link {{ request()->routeIs('user.documents') ? 'active' : '' }}">
                    <i class="mdi mdi-file-document-outline"></i>
                    <span>Dokumen</span>
                </a>
            </li>

            <li class="nav-item mt-auto border-top border-light pt-3 mx-3">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start text-danger">
                        <i class="mdi mdi-logout"></i>
                        <span>Keluar Sistem</span>
                    </button>
                </form>
            </li>
        </ul>
    </aside>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Top Header Overlay -->
        <header class="top-header">
            <div>
                <h4 class="fw-bold mb-0">Halo, {{ Auth::user()->name }}!</h4>
                <p class="text-muted small mb-0">NIK: {{ Auth::user()->nik ?? '-' }} • Unit:
                    {{ $site_settings['company_name'] ?? 'PTDI' }}
                </p>
            </div>

            <div class="dropdown">
                <div class="user-dropdown-btn dropdown-toggle shadow-sm" data-bs-toggle="dropdown">
                    <div class="profile-pic">{{ substr(Auth::user()->name, 0, 1) }}</div>
                    <div class="text-start d-none d-md-block">
                        <div class="fw-bold small" style="line-height: 1;">User Profile</div>
                        <div class="text-muted tiny" style="font-size: 10px;">{{ Auth::user()->role }}</div>
                    </div>
                </div>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 mt-2">
                    @if(Auth::user()->role === 'admin')
                        <li><a class="dropdown-item py-2" href="{{ route('dashboard') }}"><i
                                    class="mdi mdi-shield-crown-outline me-2"></i>Admin Panel</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                    @endif
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 text-danger"><i
                                    class="mdi mdi-power me-2"></i>Sign Out</button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        <!-- Dynamic Content -->
        <div class="animate-fade-in">
            @if(session('success'))
                <div class="alert alert-success alert-custom alert-dismissible fade show animate-fade-in" role="alert">
                    <i class="mdi mdi-check-circle-outline me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-custom alert-dismissible fade show animate-fade-in" role="alert">
                    <i class="mdi mdi-alert-circle-outline me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>

        <!-- Footer synchronized with Settings -->
        <footer class="mt-5 py-4 border-top">
            <div class="d-sm-flex justify-content-center justify-content-sm-between align-items-center">
                <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
                    {{ $site_settings['footer_text'] ?? 'Sistem Informasi Dokumen - PT Dirgantara Indonesia.' }}
                </span>
                <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">
                    Copyright © {{ date('Y') }}. All rights reserved.
                </span>
            </div>
        </footer>
    </main>

    <!-- Mobile Menu Toggle (Overlay) -->
    <button class="d-lg-none btn btn-primary position-fixed bottom-0 end-0 m-4 rounded-circle shadow-lg"
        style="width: 60px; height: 60px; z-index: 2000;"
        onclick="document.querySelector('.sidebar').classList.toggle('show')">
        <i class="mdi mdi-menu fs-4"></i>
    </button>

    <!-- Global Drag & Drop Overlay -->
    <div id="global-drag-overlay" class="d-none">
        <div class="overlay-content">
            <i class="mdi mdi-cloud-upload" style="font-size: 80px; color: white;"></i>
            <h2 class="text-white fw-bold mt-3">Drop file di sini untuk Upload</h2>
            <p class="text-white-50">Dukung PDF, Word, Excel, PowerPoint, ZIP, RAR</p>
        </div>
    </div>

    <!-- Global Quick Upload Modal -->
    <div class="modal fade" id="globalUploadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-premium">
                <div class="modal-header" style="background: var(--accent-blue); color: white; border: none;">
                    <h5 class="modal-title fw-bold">Selesaikan Upload Dokumen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('user.documents.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="alert alert-info py-2 small d-flex align-items-center mb-3"
                            id="globalDroppedFileName" style="border-radius: 8px;">
                            <i class="mdi mdi-file-check-outline fs-4 me-2 flex-shrink-0"></i>
                            <div class="w-100 overflow-hidden">
                                <span class="d-block text-muted" style="font-size: 10px;">File Terpilih</span>
                                <b class="text-dark d-block text-truncate" style="max-width: 100%;"
                                    title="">filename.pdf</b>
                            </div>
                        </div>

                        <!-- Hidden input to hold the dropped file -->
                        <input type="file" name="file_dokumen" id="globalFileInput" class="d-none" required>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Nama / Judul Dokumen <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="nama_dokumen" id="globalFileTitle" class="form-control"
                                placeholder="Contoh: Laporan Keuangan..." required maxlength="255">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Organisasi / Departemen (ORG) <span
                                    class="text-danger">*</span></label>
                            <select class="form-select form-control" name="org" required>
                                <option value="">-- Pilih Departemen --</option>
                                <option value="HR">HR - Human Resource</option>
                                <option value="UT">UT - Unit Teknik</option>
                                <option value="SK">SK - Sekretariat</option>
                                <option value="PTD">PTD - Produksi</option>
                                <option value="QA">QA - Quality Assurance</option>
                                <option value="FIN">FIN - Finance</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light px-4 py-3 border-top-0 justify-content-between">
                        <button type="button" class="btn btn-secondary" style="border-radius: 4px;"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn text-white fw-bold"
                            style="background: var(--accent-blue); border-radius: 4px;">
                            <i class="mdi mdi-cloud-upload me-1"></i> Upload Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Global JS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Global Drag & Drop Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let dragCounter = 0;
            const overlay = document.getElementById('global-drag-overlay');
            const fileInput = document.getElementById('globalFileInput');
            const fileTitle = document.getElementById('globalFileTitle');
            const fileNameDisplay = document.getElementById('globalDroppedFileName').querySelector('b');

            window.addEventListener('dragenter', function (e) {
                e.preventDefault();
                dragCounter++;
                overlay.classList.remove('d-none');
            });

            window.addEventListener('dragleave', function (e) {
                e.preventDefault();
                dragCounter--;
                if (dragCounter === 0) {
                    overlay.classList.add('d-none');
                }
            });

            window.addEventListener('dragover', function (e) {
                e.preventDefault();
            });

            window.addEventListener('drop', function (e) {
                e.preventDefault();
                dragCounter = 0;
                overlay.classList.add('d-none');

                if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                    const file = e.dataTransfer.files[0];

                    // Convert files to DataTransfer to set into input
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInput.files = dataTransfer.files;

                    // Update UI text
                    fileNameDisplay.textContent = file.name;
                    fileNameDisplay.title = file.name; // Add title for hover text

                    // Auto-fill title without extension and limit to 255 chars
                    let title = file.name.split('.').slice(0, -1).join('.');
                    fileTitle.value = (title || file.name).substring(0, 255);

                    // Show Modal
                    const uploadModal = new bootstrap.Modal(document.getElementById('globalUploadModal'));
                    uploadModal.show();
                }
            });
        });
    </script>

    @stack('modals')
    @stack('scripts')
</body>

</html>
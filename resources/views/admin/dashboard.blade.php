@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab"
                                aria-controls="overview" aria-selected="true">Overview</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#audiences" role="tab"
                                aria-selected="false">Aktivitas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#demographics" role="tab"
                                aria-selected="false">Laporan</a>
                        </li>
                        @if(empty($isPublic) || !$isPublic)
                        <li class="nav-item">
                            <a class="nav-link border-0" id="more-tab" data-bs-toggle="tab" href="#more" role="tab"
                                aria-selected="false">Lainnya</a>
                        </li>
                        @endif
                    </ul>
                    <div>
                        <div class="btn-wrapper">
                            @if(empty($isPublic) || !$isPublic)
                                <a href="#" class="btn btn-otline-dark align-items-center" id="btnShareDashboard"><i
                                        class="icon-share"></i>
                                    Bagikan</a>
                                <a href="#" class="btn btn-otline-dark" onclick="window.print(); return false;"><i
                                        class="icon-printer"></i> Cetak</a>
                                <a href="{{ route('admin.dashboard.export') }}" class="btn btn-primary text-white me-0"><i
                                        class="icon-download"></i> Ekspor PDF</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="tab-content tab-content-basic">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="statistics-details d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="statistics-title">Total Pengguna</p>
                                        <h3 class="rate-percentage">{{ $totalUsers }}</h3>
                                        <p class="text-success d-flex"><i class="mdi mdi-menu-up"></i><span>Status
                                                Aktif</span></p>
                                    </div>
                                    <div>
                                        <p class="statistics-title">Template Master</p>
                                        <h3 class="rate-percentage">{{ $totalTemplates }}</h3>
                                        <p class="text-success d-flex"><i class="mdi mdi-menu-up"></i><span>Tersedia</span>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="statistics-title">Dokumen Digenerate</p>
                                        <h3 class="rate-percentage">{{ $totalDocuments }}</h3>
                                        <p class="text-success d-flex"><i class="mdi mdi-menu-up"></i><span>Log Aktif</span>
                                        </p>
                                    </div>
                                    <div class="d-none d-md-block">
                                        <p class="statistics-title">Avg. Waktu Sistem</p>
                                        <h3 class="rate-percentage">0m:45s</h3>
                                        <p class="text-success d-flex"><i class="mdi mdi-menu-down"></i><span>Cepat</span>
                                        </p>
                                    </div>
                                    <div class="d-none d-md-block">
                                        <p class="statistics-title">Aktivitas Terbaru</p>
                                        <h3 class="rate-percentage">{{ $recentDocumentsCount }}</h3>
                                        <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>Hari ini</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-8 d-flex flex-column">
                                <div class="row flex-grow">
                                    <div class="col-12 grid-margin stretch-card">
                                        <div class="card card-rounded">
                                            <div class="card-body">
                                                <div class="d-sm-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h4 class="card-title card-title-dash">Performance Line Chart</h4>
                                                        <p class="card-subtitle card-subtitle-dash">Grafik Dokumen
                                                            Digenerate </p>
                                                            </div>
                                                            <div>
                                                                <div class="dropdown">
                                                                    <button
                                                                        class="btn btn-light dropdown-toggle toggle-dark btn-lg mb-0 me-0"
                                                                        type="button" id="periodDropdownButton"
                                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                                        aria-expanded="false"> Minggu Ini </button>
                                                                    <div class="dropdown-menu" aria-labelledby="periodDropdownButton">
                                                                        <h6 class="dropdown-header">Pilihan Periode</h6>
                                                                        <a class="dropdown-item period-select" href="#" data-period="minggu_ini">Minggu Ini</a>
                                                                        <a class="dropdown-item period-select" href="#" data-period="bulan_ini">Bulan Ini</a>
                                                                        <a class="dropdown-item period-select" href="#" data-period="bulan_lalu">Bulan Lalu</a>
                                                                        <a class="dropdown-item period-select" href="#" data-period="tahun_ini">Tahun Ini</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-sm-flex align-items-center mt-1 justify-content-between">
                                                            <div class="d-sm-flex align-items-center mt-4 justify-content-between">
                                                                <h2 class="me-2 fw-bold" id="chartTotalDocs">{{ $chartData['minggu_ini']['total'] ?? 0 }}</h2>
                                                                <h4 class="me-2">DOCS</h4>
                                                                <h4 class="text-success" id="chartPercentage"></h4>
                                                            </div>
                                                        </div>
                                                        <div class="chartjs-bar-wrapper mt-3">
                                                            <canvas id="marketingOverview"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 d-flex flex-column">
                                        <div class="row flex-grow">
                                            <div class="col-12 grid-margin stretch-card">
                                                <div class="card card-rounded card-dark-blue border-0 shadow-sm"
                                                    style="background: url('{{ asset('staradmin/assets/images/dashboard/shape-4.svg') }}') center / cover no-repeat; background-color:#1F3BB3 !important;">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                                            <h4 class="card-title card-title-dash mb-0 text-white">Status Server</h4>
                                                            <div class="badge badge-success"><i class="mdi mdi-check-circle-outline text-white"></i></div>
                                                        </div>
                                                        <h5 class="text-white mt-4 fw-light">Kondisi Sistem</h5>
                                                        <h2 class="text-white mt-1 fw-bold">AKTIF & STABIL</h2>
                                                        <div class="mt-4 pt-3 text-center">
                                                            <button class="btn btn-success rounded-pill px-4 fw-bold shadow" data-bs-toggle="modal" data-bs-target="#systemLogModal">
                                                                <i class="mdi mdi-shield-check-outline text-white me-2"></i>Lihat Log Sistem
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB AKTIVITAS -->
                            <div class="tab-pane fade" id="audiences" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="row">
                                    <div class="col-12 grid-margin stretch-card">
                                        <div class="card card-rounded">
                                            <div class="card-body">
                                                <div class="d-sm-flex justify-content-between align-items-start mb-4">
                                                    <div>
                                                        <h4 class="card-title card-title-dash">Aktivitas Pencetakan Terbaru</h4>
                                                        <p class="card-subtitle card-subtitle-dash">Menampilkan 10 dokumen terakhir yang dicetak</p>
                                                    </div>
                                                    @if(empty($isPublic) || !$isPublic)
                                                    <a href="{{ route('admin.documents.history') }}" class="btn btn-primary btn-sm text-white mb-0 me-0"><i class="mdi mdi-open-in-new"></i> Lihat Semua</a>
                                                    @endif
                                                </div>
                                                <div class="table-responsive mt-1">
                                                    <table class="table select-table">
                                                        <thead>
                                                            <tr>
                                                                <th>Pegawai</th>
                                                                <th>Dokumen</th>
                                                                <th>Waktu</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($recentDocs as $doc)
                                                                <tr>
                                                                    <td>
                                                                        <h6>{{ $doc->user->name ?? 'Terhapus' }}</h6>
                                                                        <p>{{ $doc->user->nik ?? '-' }}</p>
                                                                    </td>
                                                                    <td>
                                                                        @php
                                                                            $extBadge = strtolower(pathinfo($doc->file_path, PATHINFO_EXTENSION));
                                                                            $iconClass = 'mdi-file-document-outline';
                                                                            $textClass = 'text-secondary';

                                                                            if (in_array($extBadge, ['doc', 'docx'])) {
                                                                                $iconClass = 'mdi-file-word';
                                                                                $textClass = 'text-primary';
                                                                            } elseif ($extBadge === 'pdf') {
                                                                                $iconClass = 'mdi-file-pdf';
                                                                                $textClass = 'text-danger';
                                                                            } elseif (in_array($extBadge, ['xls', 'xlsx'])) {
                                                                                $iconClass = 'mdi-file-excel';
                                                                                $textClass = 'text-success';
                                                                            }
                                                                        @endphp
                                                                        <div class="d-flex align-items-center">
                                                                            <i class="mdi {{ $iconClass }} {{ $textClass }} me-2" style="font-size: 20px;"></i>
                                                                            <span class="fw-bold">{{ mb_strtoupper($doc->jenis_dokumen) }}</span>
                                                                        </div>
                                                                    </td>
                                                                    <td>{{ $doc->created_at->diffForHumans() }}</td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="3" class="text-center py-4 text-muted">Belum ada aktivitas.</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB LAPORAN -->
                            <div class="tab-pane fade" id="demographics" role="tabpanel" aria-labelledby="contact-tab">
                                <div class="row">
                                    <div class="col-12 grid-margin stretch-card">
                                        <div class="card card-rounded">
                                            <div class="card-body">
                                                <div class="d-sm-flex justify-content-between align-items-start mb-4">
                                                    <div>
                                                        <h4 class="card-title card-title-dash">Laporan Template Populer</h4>
                                                        <p class="card-subtitle card-subtitle-dash">Top 5 tipe dokumen yang paling sering digenerate</p>
                                                    </div>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Nama Template / Dokumen</th>
                                                                <th>Total Di-generate</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($templateStats as $index => $stat)
                                                                <tr>
                                                                    <td>{{ $index + 1 }}</td>
                                                                    <td class="fw-bold">{{ mb_strtoupper($stat->jenis_dokumen) }}</td>
                                                                    <td>
                                                                        <div class="d-flex align-items-center">
                                                                            <h5 class="mb-0 me-2">{{ $stat->total }}</h5>
                                                                            <span class="text-success"><i class="mdi mdi-arrow-up"></i></span>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="3" class="text-center py-4 text-muted">Belum ada data template.</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if(empty($isPublic) || !$isPublic)
                            <!-- TAB LAINNYA -->
                            <div class="tab-pane fade" id="more" role="tabpanel" aria-labelledby="more-tab">
                                <div class="row">
                                    <div class="col-12 grid-margin stretch-card">
                                        <div class="card card-rounded">
                                            <div class="card-body text-center py-5">
                                                <i class="mdi mdi-cogs text-primary mb-3" style="font-size: 60px;"></i>
                                                <h4 class="card-title card-title-dash">Tindakan Cepat (Quick Actions)</h4>
                                                <p class="card-subtitle card-subtitle-dash mb-4">Akses menu manajemen administrasi lainnya.</p>

                                                <div class="d-flex justify-content-center gap-3">
                                                    <a href="{{ route('admin.templates') }}" class="btn btn-outline-primary btn-lg"><i class="mdi mdi-file-document-box-multiple-outline me-2"></i>Kelola Template</a>
                                                    <a href="{{ route('admin.users') }}" class="btn btn-outline-info btn-lg"><i class="mdi mdi-account-multiple-outline me-2"></i>Kelola Pegawai</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                    </div>
                </div>

                <!-- Modal Log Sistem -->
            <div class="modal fade" id="systemLogModal" tabindex="-1" aria-labelledby="systemLogModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header bg-dark text-white">
                            <h5 class="modal-title d-flex align-items-center" id="systemLogModalLabel">
                                <i class="mdi mdi-console-line me-2 fs-4"></i> Log Kejadian Sistem (System Logs)
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body bg-light p-0">
                            <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                                @forelse($systemLogs ?? [] as $log)
                                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="bg-{{ $log['color'] }} text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="mdi {{ $log['icon'] }}"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-bold">{{ $log['title'] }}</h6>
                                                <p class="mb-0 text-muted small">{{ $log['message'] }}</p>
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($log['time'])->diffForHumans() }}</small>
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-muted">
                                        <i class="mdi mdi-sleep" style="font-size: 40px;"></i>
                                        <p class="mt-2">Belum ada aktivitas terekam.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        <div class="modal-footer bg-light border-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
@endsection

@push('styles')
    <style>
        @media print {
            .navbar, .sidebar, .btn-wrapper, .nav-tabs {
                display: none !important;
            }
            .main-panel {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .content-wrapper {
                background: #fff !important;
            }
        }
    </style>
@endpush

    @push('scripts')
        <script src="{{ asset('staradmin/assets/js/dashboard.js') }}"></script>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                if ($("#marketingOverview").length) {
                    var canvas = document.getElementById('marketingOverview');
                    var chartStatus = Chart.getChart(canvas);

                    if (chartStatus != undefined) {
                        chartStatus.destroy();
                    }

                    var ctx = canvas.getContext('2d');
                    
                    // Create gradient similar to original template
                    var graphGradient = ctx.createLinearGradient(5, 0, 5, 100);
                    graphGradient.addColorStop(0, 'rgba(26, 115, 232, 0.18)');
                    graphGradient.addColorStop(1, 'rgba(26, 115, 232, 0.02)');

                    var rawChartData = @json($chartData);
                    var currentPeriod = 'minggu_ini'; 
                    var currentData = rawChartData[currentPeriod] || { labels: [], data: [] };

                    var myChart = new Chart(ctx, {
                        type: 'line', // WAVY LINE CHART
                        data: {
                            labels: currentData.labels,
                            datasets: [{
                                label: 'Total Generated',
                                data: currentData.data,
                                backgroundColor: graphGradient,
                                borderColor: [
                                    '#1F3BB3',
                                ],
                                borderWidth: 1.5,
                                fill: true,
                                pointBorderWidth: 1,
                                pointRadius: [4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4],
                                pointHoverRadius: [2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2],
                                pointBackgroundColor: ['#1F3BB3', '#1F3BB3', '#1F3BB3', '#1F3BB3','#1F3BB3', '#1F3BB3', '#1F3BB3', '#1F3BB3','#1F3BB3', '#1F3BB3', '#1F3BB3', '#1F3BB3', '#1F3BB3', '#1F3BB3', '#1F3BB3'],
                                pointBorderColor: ['#fff','#fff','#fff','#fff','#fff','#fff','#fff','#fff','#fff','#fff','#fff','#fff','#fff','#fff','#fff'],
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            elements: {
                                line: {
                                    tension: 0.4, // Wavy line!
                                }
                            },
                            scales: {
                                y: {
                                    border: { display: false },
                                    grid: { display: true, color:"#F0F0F0", drawBorder: false },
                                    ticks: { beginAtZero: false, autoSkip: true, maxTicksLimit: 4, color:"#6B778C", font: { size: 10 } }
                                },
                                x: {
                                    border: { display: false },
                                    grid: { display: false, drawBorder: false },
                                    ticks: { beginAtZero: false, autoSkip: true, maxTicksLimit: 7, color:"#6B778C", font: { size: 10 } }
                                }
                            },
                            plugins: {
                                legend: { display: false }
                            }
                        }
                    });

                    // Event Listener untuk Dropdown Periode Chart
                    document.querySelectorAll('.period-select').forEach(function(item) {
                        item.addEventListener('click', function(e) {
                            e.preventDefault();
                            var periodVal = this.getAttribute('data-period');
                            var periodText = this.innerText;
                            var selectedData = rawChartData[periodVal];

                            if (selectedData) {
                                // Ubah text tombol dropdown
                                document.getElementById('periodDropdownButton').innerText = periodText;

                                // Ubah total display
                                document.getElementById('chartTotalDocs').innerText = selectedData.total;

                                // Animasi perubahan data chart
                                myChart.data.labels = selectedData.labels;
                                myChart.data.datasets[0].data = selectedData.data;
                                myChart.update();
                            }
                        });
                    });
                }

                // Logika Tombol Bagikan Dashboard
                var btnShare = document.getElementById("btnShareDashboard");
                if (btnShare) {
                    btnShare.addEventListener("click", function(e) {
                        e.preventDefault();
                        
                        // Menampilkan modal share yang ada di layout admin.blade.php
                        $('#shareDashboardModal').modal('show');
                    });
                }
            });
        </script>
    @endpush
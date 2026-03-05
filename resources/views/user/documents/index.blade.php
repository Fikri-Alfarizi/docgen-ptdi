@extends('layouts.user')

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <style>
        .history-card-flat {
            background: white;
            border-radius: 4px;
            border: 1px solid #cbd5e1;
            overflow: hidden;
            box-shadow: none !important;
        }

        .history-table th {
            background: #f8fafc;
            text-transform: uppercase;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            color: #475569;
            padding: 0.8rem 1rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .history-table td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        /* DataTables Custom Polish */
        .dataTables_wrapper .row {
            margin: 0;
            padding: 0 1rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.page-item.active .page-link {
            background-color: var(--accent-blue);
            border-color: var(--accent-blue);
        }

        .dataTables_length select {
            border-radius: 4px;
            padding: 4px 8px;
            border: 1px solid #cbd5e1;
            margin: 0 5px;
        }

        .dataTables_length {
            padding: 1rem 1rem 0.5rem 1rem;
            color: #475569;
            font-size: 0.85rem;
        }

        .dataTables_info {
            padding: 1rem !important;
            color: #64748b !important;
            font-size: 0.85rem;
        }

        .dataTables_paginate {
            padding: 1rem !important;
        }

        .doc-icon-circle {
            width: 38px;
            height: 38px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .btn-action-history {
            width: 32px;
            height: 32px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.1s;
            border: 1px solid #cbd5e1;
            background: white;
            color: #64748b;
            text-decoration: none;
        }

        .btn-action-history:hover {
            background: var(--accent-blue);
            color: white;
            border-color: var(--accent-blue);
            transform: translateY(-2px);
        }

        .btn-preview {
            color: #3b82f6;
        }

        .btn-download {
            color: #10b981;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* Modal Premium */
        .modal-premium {
            border-radius: 4px;
            border: none;
            box-shadow: none !important;
        }

        .modal-premium .modal-header {
            background: var(--primary-indigo);
            color: white;
            border: none;
            padding: 1rem 1.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 mt-2 gap-3">
        <div>
            <h5 class="fw-bold mb-0">Arsip Dokumen Saya</h5>
            <p class="text-muted tiny mb-0">Kelola dan cari dokumen yang telah Anda unggah.</p>
        </div>
        <div class="d-flex flex-column flex-md-row gap-2 align-items-md-center w-100 justify-content-end">
            <div class="d-flex gap-2">
                <select id="filterFormat" class="form-select form-select-sm border-secondary-subtle shadow-sm"
                    style="width: 140px; border-radius: 4px;">
                    <option value="">Semua Format</option>
                    <option value="PDF">PDF</option>
                    <option value="DOCX">DOCX</option>
                    <option value="XLSX">Excel</option>
                    <option value="PPTX">PowerPoint</option>
                    <option value="ZIP">ZIP/RAR</option>
                </select>
                <select id="filterOrg" class="form-select form-select-sm border-secondary-subtle shadow-sm"
                    style="width: 160px; border-radius: 4px;">
                    <option value="">Semua Departemen</option>
                    <option value="HR">HR - Human Resource</option>
                    <option value="UT">UT - Unit Teknik</option>
                    <option value="SK">SK - Sekretariat</option>
                    <option value="PTD">PTD - Produksi</option>
                    <option value="QA">QA - Quality Assurance</option>
                    <option value="FIN">FIN - Finance</option>
                </select>
            </div>
            <div class="position-relative">
                <i class="fa-solid fa-search position-absolute text-muted"
                    style="top:50%; transform:translateY(-50%); left:12px;"></i>
                <input type="text" id="searchDoc"
                    class="form-control form-control-sm ps-5 shadow-sm border-secondary-subtle"
                    placeholder="Cari dokumen..."
                    style="width: 220px; border-radius: 4px; padding-top: 5px; padding-bottom: 5px;">
            </div>
            <a href="{{ route('user.documents.upload') }}"
                class="btn btn-primary btn-sm rounded-1 px-3 py-1 fw-bold shadow-sm d-flex align-items-center justify-content-center text-nowrap"
                style="background: var(--accent-blue); height: 31px;">
                <i class="fa-solid fa-upload me-1"></i> Upload
            </a>
        </div>
    </div>

    <div class="history-card-flat mt-3 pb-3">
        <div class="table-responsive">
            <table class="table history-table mb-0" id="documentsTable">
                <thead>
                    <tr>
                        <th class="ps-4">Dokumen & ID</th>
                        <th>Format</th>
                        <th>Org</th>
                        <th>Rev</th>
                        <th>Waktu Generate</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                        @php
                            $ext = strtolower(pathinfo($doc->file_path, PATHINFO_EXTENSION));
                            $icon = 'fa-file-lines';
                            $color = 'text-secondary';
                            $bg = 'bg-light';
                            if (in_array($ext, ['doc', 'docx'])) {
                                $icon = 'fa-file-word';
                                $color = 'text-primary';
                                $bg = 'bg-primary-subtle';
                            } elseif ($ext === 'pdf') {
                                $icon = 'fa-file-pdf';
                                $color = 'text-danger';
                                $bg = 'bg-danger-subtle';
                            } elseif (in_array($ext, ['xls', 'xlsx'])) {
                                $icon = 'fa-file-excel';
                                $color = 'text-success';
                                $bg = 'bg-success-subtle';
                            }
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="doc-icon-circle {{ $bg }} {{ $color }} me-3">
                                        <i class="fa-solid {{ $icon }}"></i>
                                    </div>
                                    <div class="overflow-hidden" style="max-width: 300px;">
                                        <h6 class="fw-bold mb-0 text-truncate" title="{{ mb_strtoupper($doc->jenis_dokumen) }}">
                                            {{ mb_strtoupper($doc->jenis_dokumen) }}
                                        </h6>
                                        <p class="text-muted tiny mb-0">Register: #{{ str_pad($doc->id, 5, '0', STR_PAD_LEFT) }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge bg-light text-dark border">
                                    <i class="fa-solid {{ $icon }} {{ $color }}"></i> {{ strtoupper($ext) }}
                                </span>
                            </td>
                            <td>
                                <div class="badge bg-primary-subtle text-primary fw-bold px-2 py-1" style="font-size: 0.75rem;">
                                    {{ $doc->org ?? '-' }}
                                </div>
                            </td>
                            <td>
                                <div class="badge bg-warning-subtle text-dark border border-warning-subtle fw-bold px-2 py-1"
                                    style="font-size: 0.75rem;">
                                    Rev: {{ $doc->rev ?? '0' }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column text-muted small">
                                    <span class="fw-medium text-dark">{{ $doc->created_at->format('d M Y') }}</span>
                                    <span>{{ $doc->created_at->format('H:i') }} WIB</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn-action-history text-warning" title="Upload Ulang / Edit"
                                        data-bs-toggle="modal" data-bs-target="#editModal{{ $doc->id }}">
                                        <i class="fa-solid fa-upload fs-5"></i>
                                    </button>
                                    <button type="button" class="btn-action-history btn-preview" title="Pratinjau Cepat"
                                        data-bs-toggle="modal" data-bs-target="#previewModal{{ $doc->id }}">
                                        <i class="fa-solid fa-eye fs-5"></i>
                                    </button>
                                    <a href="{{ route('documents.download', $doc->id) }}"
                                        class="btn-action-history btn-download" title="Unduh File">
                                        <i class="fa-solid fa-download fs-5"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="py-5">
                                    <i class="fa-solid fa-clock-rotate-left text-muted opacity-25" style="font-size: 4rem;"></i>
                                    <h6 class="mt-3 fw-bold text-muted">Belum Ada Riwayat</h6>
                                    <p class="text-muted small">Dokumen yang anda buat akan muncul secara kronologis di sini.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('modals')
        @foreach($documents as $doc)
            @php
                $ext = strtolower(pathinfo($doc->file_path, PATHINFO_EXTENSION));
                $icon = 'fa-file-lines';
                $color = 'text-secondary';
                if (in_array($ext, ['doc', 'docx'])) {
                    $icon = 'fa-file-word';
                    $color = 'text-primary';
                } elseif ($ext === 'pdf') {
                    $icon = 'fa-file-pdf';
                    $color = 'text-danger';
                } elseif (in_array($ext, ['xls', 'xlsx'])) {
                    $icon = 'fa-file-excel';
                    $color = 'text-success';
                }
            @endphp
            <!-- Modal Preview Premium -->
            <div class="modal fade" id="previewModal{{ $doc->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content modal-premium">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">
                                <i class="fa-solid fa-file-magnifying-glass me-2"></i> Pratinjau Dokumen
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-0" style="height: 75vh; position: relative;">
                            @if(strtolower($ext) === 'pdf')
                                <iframe src="{{ route('documents.preview', $doc->id) }}" width="100%" height="100%"
                                    style="border: none;"></iframe>
                            @else
                                <div class="d-flex flex-column align-items-center justify-content-center h-100 bg-white">
                                    <div class="p-5 rounded-circle mb-4 bg-light shadow-none">
                                        <i class="fa-solid {{ $icon }} {{ $color }}" style="font-size: 80px;"></i>
                                    </div>
                                    <h5 class="fw-bold mb-2">Format .{{ strtoupper($ext) }}</h5>
                                    <p class="text-muted text-center px-5 mb-4">Pratinjau langsung hanya tersedia untuk format
                                        PDF.<br>Silakan unduh dokumen untuk melihat isi.</p>
                                    <a href="{{ route('documents.download', $doc->id) }}" class="btn btn-primary px-5 py-2 fw-bold"
                                        style="background: var(--accent-blue); border-radius: 4px;">
                                        <i class="fa-solid fa-download me-2"></i> UNDUH DOKUMEN ORIGINAL
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer bg-light px-4 py-3 border-top-0">
                            <div class="me-auto">
                                <p class="mb-0 fw-bold small">{{ mb_strtoupper($doc->jenis_dokumen) }}</p>
                                <span class="tiny text-muted">ID: {{ str_pad($doc->id, 8, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <button type="button" class="btn btn-secondary px-4 py-2" style="border-radius: 4px;"
                                data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Upload Ulang / Edit -->
            <div class="modal fade" id="editModal{{ $doc->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content modal-premium">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Edit Dokumen: #{{ str_pad($doc->id, 5, '0', STR_PAD_LEFT) }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('user.documents.update', $doc->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted">Nama / Judul Dokumen</label>
                                    <input type="text" name="nama_dokumen" class="form-control" value="{{ $doc->jenis_dokumen }}"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted">Organisasi / Departemen (ORG)</label>
                                    <select class="form-select form-control" name="org" required>
                                        <option value="HR" {{ $doc->org == 'HR' ? 'selected' : '' }}>HR - Human Resource</option>
                                        <option value="UT" {{ $doc->org == 'UT' ? 'selected' : '' }}>UT - Unit Teknik</option>
                                        <option value="SK" {{ $doc->org == 'SK' ? 'selected' : '' }}>SK - Sekretariat</option>
                                        <option value="PTD" {{ $doc->org == 'PTD' ? 'selected' : '' }}>PTD - Produksi</option>
                                        <option value="QA" {{ $doc->org == 'QA' ? 'selected' : '' }}>QA - Quality Assurance</option>
                                        <option value="FIN" {{ $doc->org == 'FIN' ? 'selected' : '' }}>FIN - Finance</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted">Upload File Baru (Abaikan jika tidak
                                        diubah)</label>
                                    <input type="file" name="file_dokumen" class="form-control"
                                        accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar">
                                    <div class="form-text text-danger mt-1" style="font-size: 0.75rem;">* File baru otomatis
                                        menambah angka Revisi (Rev)</div>
                                </div>
                            </div>
                            <div class="modal-footer bg-light px-4 py-3 border-top-0 justify-content-between">
                                <button type="button" class="btn btn-secondary" style="border-radius: 4px;"
                                    data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-warning fw-bold text-dark" style="border-radius: 4px;">Update
                                    Dokumen</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endpush

    @push('scripts')
        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

        <script>
            $(document).ready(function () {
                var $table = $('#documentsTable');
                var isRealData = $table.find('tbody tr td[colspan]').length === 0;

                if (isRealData) {
                    var table = $table.DataTable({
                        "pageLength": 10,
                        "lengthMenu": [
                            [10, 25, 50, 100, -1],
                            [10, 25, 50, 100, "Semua"]
                        ],
                        "language": {
                            "lengthMenu": "_MENU_ per halaman",
                            "zeroRecords": "Tidak ada dokumen yang ditemukan",
                            "info": "Menampilkan halaman _PAGE_ dari _PAGES_ (_TOTAL_ dokumen)",
                            "infoEmpty": "Tidak ada data tersedia",
                            "infoFiltered": "(difilter dari _MAX_ total dokumen)",
                            "paginate": {
                                "first": "Awal",
                                "last": "Akhir",
                                "next": "Lanjut",
                                "previous": "Kembali"
                            }
                        },
                        "columnDefs": [
                            { "orderable": false, "targets": [1, 5] } // Nonaktifkan sorting di kolom format & aksi
                        ],
                        "order": [[4, "desc"]], // Default sorting by Waktu Generate (kolom ke-5) descending
                        "dom": '<"d-flex justify-content-between align-items-center"l>rt<"d-flex justify-content-between align-items-center flex-wrap"ip><"clear">' // Sembunyikan search box default DataTables
                    });

                    // Filter Custom: Format (Kolom index ke-1)
                    $('#filterFormat').on('change', function() {
                        let val = $(this).val();
                        // Gunakan regex yang mencari kata (mis: PDF) di dalam tag HTML
                        table.column(1).search(val ? val : '', true, false).draw();
                    });

                    // Filter Custom: Departemen / ORG (Kolom index ke-2)
                    $('#filterOrg').on('change', function() {
                        let val = $(this).val();
                        // Gunakan pencarian parsial (tanpa regex strict ^$) karena ada spasi/HTML
                        table.column(2).search(val ? val : '', true, false).draw();
                    });

                    // Sambungkan custom search box dengan DataTables search
                    $("#searchDoc").on("keyup", function () {
                        table.search(this.value).draw();
                    });
                } else {
                    // Sembunyikan search bar, filter dan pagination jika data kosong
                    $("#searchDoc, #filterFormat, #filterOrg").prop("disabled", true);
                }
            });
        </script>
    @endpush
@endsection
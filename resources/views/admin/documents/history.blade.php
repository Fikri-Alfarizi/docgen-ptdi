@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                    <div>
                        <h1 class="m-0 fw-bold">Riwayat Cetak Dokumen (Global)</h1>
                    </div>
                    <div>
                        <div class="dropdown">
                            <button class="btn btn-outline-dark btn-lg mb-0 me-0 dropdown-toggle" type="button"
                                id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-download"></i> Unduh Log
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="exportDropdown">
                                <li><a class="dropdown-item"
                                        href="{{ route('admin.documents.export', ['limit' => 10]) }}">Unduh 10 Data
                                        Terakhir</a></li>
                                <li><a class="dropdown-item"
                                        href="{{ route('admin.documents.export', ['limit' => 25]) }}">Unduh 25 Data
                                        Terakhir</a></li>
                                <li><a class="dropdown-item"
                                        href="{{ route('admin.documents.export', ['limit' => 50]) }}">Unduh 50 Data
                                        Terakhir</a></li>
                                <li><a class="dropdown-item"
                                        href="{{ route('admin.documents.export', ['limit' => 100]) }}">Unduh 100 Data
                                        Terakhir</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-primary fw-bold"
                                        href="{{ route('admin.documents.export', ['limit' => 'all']) }}">Unduh Semua
                                        Data</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row flex-grow">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card card-rounded">
                            <div class="card-body">
                                <div class="d-sm-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h4 class="card-title card-title-dash mb-1">Log Aktivitas Generasi Dokumen</h4>
                                        <p class="card-subtitle card-subtitle-dash mb-0">Daftar semua dokumen yang dicetak
                                            oleh seluruh karyawan</p>
                                    </div>
                                    <div class="d-flex flex-column flex-md-row gap-2 mt-3 mt-sm-0 align-items-md-center">
                                        <div class="d-flex gap-2">
                                            <select id="filterFormat"
                                                class="form-select form-select-sm border-secondary-subtle shadow-sm"
                                                style="width: 140px; border-radius: 4px;">
                                                <option value="">Semua Format</option>
                                                <option value="PDF">PDF</option>
                                                <option value="DOCX">DOCX</option>
                                                <option value="XLSX">Excel</option>
                                                <option value="PPTX">PowerPoint</option>
                                                <option value="ZIP">ZIP/RAR</option>
                                            </select>
                                            <select id="filterOrg"
                                                class="form-select form-select-sm border-secondary-subtle shadow-sm"
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
                                            <i class="mdi mdi-magnify position-absolute text-muted"
                                                style="top:50%; transform:translateY(-50%); left:12px; font-size:18px;"></i>
                                            <input type="text" id="searchDoc"
                                                class="form-control form-control-sm ps-5 shadow-sm border-secondary-subtle"
                                                placeholder="Cari riwayat..."
                                                style="width: 200px; border-radius: 4px; padding-top: 5px; padding-bottom: 5px;">
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive mt-1">
                                    <table class="table select-table compact-table">
                                        <thead>
                                            <tr>
                                                <th>ID Dokumen</th>
                                                <th>Nama Karyawan</th>
                                                <th>NIK Karyawan</th>
                                                <th>File - Nama Dokumen</th>
                                                <th>ORG</th>
                                                <th>Rev</th>
                                                <th>Waktu (Timestamp)</th>
                                                <th>Aksi File</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($documents as $doc)
                                                <tr>
                                                    <td>
                                                        <div class="badge badge-opacity-warning">#{{ $doc->id }}</div>
                                                    </td>
                                                    <td>
                                                        <h6>{{ $doc->user->name ?? 'Unknown (Terhapus)' }}</h6>
                                                    </td>
                                                    <td><code>{{ $doc->user->nik ?? '-' }}</code></td>
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
                                                            } elseif (in_array($extBadge, ['ppt', 'pptx'])) {
                                                                $iconClass = 'mdi-file-powerpoint';
                                                                $textClass = 'text-warning';
                                                            }
                                                        @endphp
                                                        <div class="d-flex align-items-center overflow-hidden"
                                                            style="max-width: 250px;">
                                                            <i class="mdi {{ $iconClass }} {{ $textClass }} me-2 flex-shrink-0"
                                                                style="font-size: 20px;"></i>
                                                            <span class="fw-bold text-truncate"
                                                                title="{{ mb_strtoupper($doc->jenis_dokumen) }}">{{ mb_strtoupper($doc->jenis_dokumen) }}</span>
                                                            <span
                                                                class="d-none">{{ strtoupper((in_array($extBadge, ['rar', 'zip']) ? 'ZIP' : $extBadge)) }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="badge badge-opacity-primary">{{ $doc->org ?? '-' }}</div>
                                                    </td>
                                                    <td>
                                                        <div class="badge badge-opacity-warning">Rev: {{ $doc->rev ?? '0' }}
                                                        </div>
                                                    </td>
                                                    <td>{{ $doc->created_at->format('d/m/Y - H:i:s') }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @php
                                                                $ext = pathinfo($doc->file_path, PATHINFO_EXTENSION);
                                                            @endphp
                                                            <button type="button" class="btn btn-sm btn-outline-info me-2"
                                                                title="Lihat Preview" data-bs-toggle="modal"
                                                                data-bs-target="#previewModal{{ $doc->id }}">
                                                                <i class="mdi mdi-eye"></i> Cek File
                                                            </button>
                                                            <form action="{{ route('admin.documents.destroy', $doc->id) }}"
                                                                method="POST" class="d-inline-block"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data log & file dokumen ini secara permanen?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                    title="Hapus Riwayat & File"><i
                                                                        class="mdi mdi-delete"></i></button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-5 text-muted">
                                                        <i class="mdi mdi-history" style="font-size: 40px; opacity:0.5"></i><br>
                                                        Belum ada aktivitas mencetak dokumen dari karyawan manapun.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning border-0 shadow-sm mt-3" role="alert">
                    <h5 class="fw-bold"><i class="mdi mdi-alert-circle-outline"></i> Catatan Keamanan & Audit</h5>
                    Halaman ini menampilkan <strong>seluruh riwayat aktivitas pencetakan dokumen</strong> oleh
                    seluruh karyawan PT Dirgantara Indonesia. Apabila data seorang karyawan dihapus dari sistem,
                    maka seluruh catatan riwayat dokumen milik karyawan tersebut akan secara otomatis terhapus
                    sesuai dengan kebijakan integritas data (<i>ON DELETE CASCADE</i>) yang diterapkan pada basis data.
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Preview Document (Loop) -->
    @foreach($documents as $doc)
        <div class="modal fade" id="previewModal{{ $doc->id }}" tabindex="-1" aria-labelledby="previewModalLabel{{ $doc->id }}"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="previewModalLabel{{ $doc->id }}">Preview: {{ $doc->jenis_dokumen }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0" style="height: 65vh;">
                        @php
                            $ext = pathinfo($doc->file_path, PATHINFO_EXTENSION);
                        @endphp

                        @if(strtolower($ext) == 'pdf')
                            <iframe src="{{ route('documents.preview', $doc->id) }}" width="100%" height="100%"
                                style="border: none;"></iframe>
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center h-100 bg-light">
                                <i class="mdi mdi-file-document-outline text-secondary mb-3" style="font-size: 80px;"></i>
                                <h5 class="text-muted mb-2">Live Preview tidak tersedia untuk ekstensi .{{ strtoupper($ext) }}</h5>
                                <p class="text-muted text-center px-4">Browser hanya mendukung preview langsung untuk format PDF.
                                    Silakan unduh file untuk memproses atau melihat kontennya.</p>
                                <a href="{{ route('documents.download', $doc->id) }}" class="btn btn-primary mt-3 text-white">
                                    <i class="mdi mdi-download me-2"></i>Unduh Dokumen
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        @if(strtolower($ext) == 'pdf')
                            <a href="{{ route('documents.download', $doc->id) }}" class="btn btn-primary text-white">
                                <i class="mdi mdi-download me-2"></i>Unduh Asli
                            </a>
                        @endif
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @push('scripts')
        <script>
            $(document).ready(function () {
                if ($('.select-table').length) {
                    $('.select-table').each(function () {
                        var $table = $(this);
                        var rowCount = $table.find('tbody tr').length;
                        var isRealData = $table.find('tbody tr td[colspan]').length === 0;

                        if (rowCount > 0 && isRealData && !$.fn.DataTable.isDataTable(this)) {
                            var table = $table.DataTable({
                                "aLengthMenu": [
                                    [10, 25, 50, -1],
                                    [10, 25, 50, "All"]
                                ],
                                "iDisplayLength": 10,
                                "language": {
                                    "search": "",
                                    "zeroRecords": "Tidak ada riwayat yang ditemukan",
                                    "infoEmpty": "Tidak ada data tersedia",
                                    "infoFiltered": "(difilter dari _MAX_ total log)"
                                },
                                "order": [[6, "desc"]], // Sort by Waktu column by default
                                "dom": '<"d-flex justify-content-between align-items-center mb-2"l>rt<"d-flex justify-content-between align-items-center flex-wrap"ip><"clear">'
                            });

                            // Filter Custom: Format (Kolom index ke-3)
                            $('#filterFormat').on('change', function () {
                                let val = $(this).val();
                                table.column(3).search(val ? val : '', true, false).draw();
                            });

                            // Filter Custom: Departemen / ORG (Kolom index ke-4)
                            $('#filterOrg').on('change', function () {
                                let val = $(this).val();
                                table.column(4).search(val ? val : '', true, false).draw();
                            });

                            // Custom search global
                            $("#searchDoc").on("keyup", function () {
                                table.search(this.value).draw();
                            });
                        } else {
                            $("#searchDoc, #filterFormat, #filterOrg").prop("disabled", true);
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
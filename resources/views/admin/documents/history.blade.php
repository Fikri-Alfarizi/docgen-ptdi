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
                        <a href="{{ route('admin.documents.export') }}" class="btn btn-outline-dark btn-lg mb-0 me-0"
                            title="Export Log">
                            <i class="mdi mdi-download"></i> Unduh Log
                        </a>
                    </div>
                </div>

                <div class="row flex-grow">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card card-rounded">
                            <div class="card-body">
                                <div class="d-sm-flex justify-content-between align-items-start">
                                    <div>
                                        <h4 class="card-title card-title-dash">Log Aktivitas Generasi Dokumen</h4>
                                        <p class="card-subtitle card-subtitle-dash">Daftar semua dokumen yang dicetak oleh
                                            seluruh karyawan</p>
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
                                                        <div class="d-flex align-items-center">
                                                            <i class="mdi {{ $iconClass }} {{ $textClass }} me-2"
                                                                style="font-size: 20px;"></i>
                                                            <span
                                                                class="fw-bold">{{ mb_strtoupper($doc->jenis_dokumen) }}</span>
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
                    <h5 class="fw-bold"><i class="mdi mdi-alert-circle-outline"></i> Peringatan Audit!</h5>
                    Di sini Anda berkuasa melihat **seluruh kegiatan pencetakan** oleh seluruh karyawan PT DI. Jika seorang
                    Karyawan
                    dihapus dari sistem, maka laporan milik mereka di sini akan otomatis hilang sesuai kaidah keamanan <i>ON
                        DELETE CASCADE</i> yang ada di tabel Database.
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
                            $table.DataTable({
                                "aLengthMenu": [
                                    [10, 25, 50, -1],
                                    [10, 25, 50, "All"]
                                ],
                                "iDisplayLength": 10,
                                "language": {
                                    search: ""
                                },
                                "order": [[4, "desc"]] // Sort by Waktu column by default
                            });
                        }
                    });
                    $('.dataTables_filter input').attr('placeholder', 'Cari riwayat...');
                }
            });
        </script>
    @endpush
@endsection
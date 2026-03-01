@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                    <div>
                        <h1 class="m-0 fw-bold">Hasil Pencarian: "{{ $keyword }}"</h1>
                    </div>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active ps-0" id="users-tab" data-bs-toggle="tab" href="#users" role="tab"
                            aria-controls="users" aria-selected="true">
                            Karyawan ({{ $users->count() }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="templates-tab" data-bs-toggle="tab" href="#templates" role="tab"
                            aria-controls="templates" aria-selected="false">
                            Template Master ({{ $templates->count() }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="documents-tab" data-bs-toggle="tab" href="#documents" role="tab"
                            aria-controls="documents" aria-selected="false">
                            Riwayat Dokumen ({{ $documents->count() }})
                        </a>
                    </li>
                </ul>

                <div class="tab-content tab-content-basic">
                    <!-- Tab Karyawan -->
                    <div class="tab-pane fade show active" id="users" role="tabpanel" aria-labelledby="users-tab">
                        <div class="card card-rounded">
                            <div class="card-body">
                                <h4 class="card-title card-title-dash mb-4">Hasil Karyawan / Pengguna</h4>
                                <div class="table-responsive">
                                    <table class="table table-search-results compact-table" id="table-search-users">
                                        <thead>
                                            <tr>
                                                <th>Nama Pegawai</th>
                                                <th>NIK</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($users as $user)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3"
                                                                style="width: 40px; height: 40px;">
                                                                <i class="mdi mdi-account text-secondary fs-4"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-1">{{ $user->name }}</h6>
                                                                <p class="mb-0 text-muted">ID: {{ $user->id }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $user->nik ?? '-' }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        @if($user->role == 'admin')
                                                            <div class="badge badge-opacity-danger">Administrator</div>
                                                        @else
                                                            <div class="badge badge-opacity-info">Pegawai/User</div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">Tidak ada data karyawan ditemukan.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Template -->
                    <div class="tab-pane fade" id="templates" role="tabpanel" aria-labelledby="templates-tab">
                        <div class="card card-rounded">
                            <div class="card-body">
                                <h4 class="card-title card-title-dash mb-4">Hasil Template Dokumen</h4>
                                <div class="table-responsive">
                                    <table class="table table-search-results compact-table" id="table-search-templates">
                                        <thead>
                                            <tr>
                                                <th>Nama Template</th>
                                                <th>Nomor</th>
                                                <th>Org</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($templates as $template)
                                                <tr>
                                                    <td>
                                                        <h6>{{ $template->nama_template }}</h6>
                                                    </td>
                                                    <td>{{ $template->nomor ?? '-' }}</td>
                                                    <td>
                                                        <div class="badge badge-opacity-primary">{{ $template->org ?? '-' }}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted">Tidak ada template ditemukan.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Documents -->
                    <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                        <div class="card card-rounded">
                            <div class="card-body">
                                <h4 class="card-title card-title-dash mb-4">Hasil Riwayat Dokumen</h4>
                                <div class="table-responsive">
                                    <table class="table table-search-results compact-table" id="table-search-documents">
                                        <thead>
                                            <tr>
                                                <th>File - Nama Dokumen</th>
                                                <th>Dicetak Oleh</th>
                                                <th>Waktu (Timestamp)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($documents as $doc)
                                                <tr>
                                                    <td>
                                                        @php
                                                            $extBadge = $doc->ext ?? 'docx';
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
                                                            <i class="mdi {{ $iconClass }} {{ $textClass }} me-2"
                                                                style="font-size: 20px;"></i>
                                                            <span
                                                                class="fw-bold">{{ mb_strtoupper($doc->jenis_dokumen ?? $doc->nama_template) }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <h6>{{ $doc->user_name ?? ($doc->user->name ?? 'Unknown') }}</h6>
                                                        <code>{{ $doc->user_nik ?? ($doc->user->nik ?? '-') }}</code>
                                                    </td>
                                                    <td>{{ $doc->created_at ? $doc->created_at->format('d/m/Y - H:i:s') : '-' }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted">Tidak ada riwayat dokumen ditemukan.</td>
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
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('.table-search-results').each(function () {
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
                            }
                        });
                    }
                });

                $('.dataTables_filter input')
                    .attr('placeholder', 'Saring hasil di tab ini (Live)...');
            });
        </script>
    @endpush
@endsection
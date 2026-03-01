@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                    <div>
                        <h1 class="m-0 fw-bold">Manajemen Template Dokumen</h1>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary btn-lg text-white mb-0 me-0" data-bs-toggle="modal"
                            data-bs-target="#modal-upload-template">
                            <i class="mdi mdi-upload"></i> Upload Template Baru
                        </button>
                    </div>
                </div>

                <div class="row flex-grow">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card card-rounded">
                            <div class="card-body">
                                <div class="d-sm-flex justify-content-between align-items-start">
                                    <div>
                                        <h4 class="card-title card-title-dash">Katalog Template</h4>
                                        <p class="card-subtitle card-subtitle-dash">Daftar master dokumen siap generate</p>
                                    </div>
                                </div>

                                <div class="table-responsive mt-1">
                                    <table class="table select-table compact-table">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Template</th>
                                                <th>Nomor</th>
                                                <th>Org</th>
                                                <th>Rev</th>
                                                <th style="width: 200px;">File System Path</th>
                                                <th>Tgl Upload</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($templates as $key => $template)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>
                                                        <h6>{{ $template->nama_template }}</h6>
                                                    </td>
                                                    <td>{{ $template->nomor ?? '-' }}</td>
                                                    <td>
                                                        <div class="badge badge-opacity-primary">{{ $template->org ?? '-' }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="badge badge-opacity-warning">Rev:
                                                            {{ $template->rev ?? '0' }}</div>
                                                    </td>
                                                    <td><code class="text-muted" style="display: inline-block; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $template->file_path }}">{{ $template->file_path }}</code></td>
                                                    <td>{{ $template->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <button type="button" class="btn btn-sm btn-outline-warning me-2"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal-edit-template-{{ $template->id }}"
                                                                title="Edit Template">
                                                                <i class="mdi mdi-pencil"></i> Edit
                                                            </button>
                                                            <form action="{{ route('admin.templates.destroy', $template->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Yakin ingin menghapus template ini?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                    title="Hapus Template">
                                                                    <i class="mdi mdi-delete"></i> Hapus
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center py-5 text-muted">
                                                        <i class="mdi mdi-file-document-outline"
                                                            style="font-size: 40px; opacity:0.5"></i><br>
                                                        Belum ada template master yang diunggah.
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

                <!-- Modal Edit Template (Loop) -->
                @foreach($templates as $template)
                    <div class="modal fade" id="modal-edit-template-{{ $template->id }}"
                        tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title fw-bold">Edit Template:
                                        {{ $template->nomor }}</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('admin.templates.update', $template->id) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group border-bottom pb-3">
                                            <label class="fw-bold" for="nama_template">Nama Jenis
                                                Dokumen (Judul)</label>
                                            <input type="text" class="form-control"
                                                name="nama_template"
                                                value="{{ $template->nama_template }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="fw-bold" for="org">Organisasi / Departemen
                                                (ORG)</label>
                                            <select class="form-select form-control" name="org"
                                                required>
                                                <option value="HR" {{ $template->org == 'HR' ? 'selected' : '' }}>HR - Human Resource</option>
                                                <option value="UT" {{ $template->org == 'UT' ? 'selected' : '' }}>UT - Unit Teknik</option>
                                                <option value="SK" {{ $template->org == 'SK' ? 'selected' : '' }}>SK - Sekretariat</option>
                                                <option value="PTD" {{ $template->org == 'PTD' ? 'selected' : '' }}>PTD - Produksi</option>
                                                <option value="QA" {{ $template->org == 'QA' ? 'selected' : '' }}>QA - Quality Assurance
                                                </option>
                                                <option value="FIN" {{ $template->org == 'FIN' ? 'selected' : '' }}>FIN - Finance</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="fw-bold" for="file_template">Upload File
                                                Baru (Abaikan jika tetap)</label>
                                            <input type="file" class="form-control form-control-sm"
                                                name="file_template"
                                                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                                            <small class="text-danger mt-2 d-block">* Jika Anda
                                                mengupload file baru, nilai
                                                <b>Rev (Revisi)</b> akan otomatis bertambah.</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-light"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="submit"
                                            class="btn btn-warning text-dark fw-bold"><i
                                                class="mdi mdi-content-save"></i> Update
                                            Template</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Modal Upload -->
                <div class="modal fade" id="modal-upload-template" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title fw-bold">Upload Template Baru</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('admin.templates.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group border-bottom pb-3">
                                        <label class="fw-bold" for="nama_template">Nama Jenis Dokumen (Judul)</label>
                                        <input type="text" class="form-control" id="nama_template" name="nama_template"
                                            placeholder="Contoh: KEBIJAKAN PERUSAHAAN..." required>
                                        <small class="text-muted">Nama ini akan muncul sebagai judul dokumen di tabel
                                            dashboard karyawan.</small>
                                    </div>
                                    <div class="form-group">
                                        <label class="fw-bold" for="org">Organisasi / Departemen (ORG)</label>
                                        <select class="form-select form-control" id="org" name="org" required>
                                            <option value="">-- Pilih Departemen --</option>
                                            <option value="HR">HR - Human Resource</option>
                                            <option value="UT">UT - Unit Teknik</option>
                                            <option value="SK">SK - Sekretariat</option>
                                            <option value="PTD">PTD - Produksi</option>
                                            <option value="QA">QA - Quality Assurance</option>
                                            <option value="FIN">FIN - Finance</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="fw-bold" for="file_template">File Dokumen Asli</label>
                                        <input type="file" class="form-control" id="file_template" name="file_template"
                                            accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" required>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary text-white"><i
                                            class="mdi mdi-content-save"></i> Simpan Template</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info border-0 shadow-sm mt-3" role="alert">
                    <h5 class="fw-bold"><i class="mdi mdi-information-outline"></i> Sistem Manajemen Dokumen (DMS)</h5>
                    Upload file dokumen pendukung operasi perusahaan dengan standar kode departemen (ORG). Anda
                    diperbolehkan
                    mengupload berbagai format file (PDF, Docx, Excel, PPT). Saat ada dokumen versi terbaru, cukup tekan
                    <b>Edit</b> dan upload ulang file-nya. Sistem akan otomatis melakukan pencatatan angka Revisi (Rev).
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                if ($('.select-table').length) {
                    $('.select-table').each(function() {
                        var $table = $(this);
                        // Only init if there are real data rows (not just the empty message row with colspan)
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
                    $('.dataTables_filter input').attr('placeholder', 'Cari template...');
                }
            });
        </script>
    @endpush
@endsection
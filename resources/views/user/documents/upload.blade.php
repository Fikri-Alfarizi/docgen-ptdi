@extends('layouts.user')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 mt-2">
        <div>
            <h5 class="fw-bold mb-0">Upload Dokumen Baru</h5>
            <p class="text-muted tiny mb-0">Silakan isi detail dan unggah file dokumen Anda ke sistem.</p>
        </div>
        <a href="{{ route('user.documents') }}" class="btn btn-outline-secondary btn-sm fw-bold">
            <i class="mdi mdi-arrow-left me-1"></i> Kembali ke Arsip
        </a>
    </div>

    <div class="card glass-card border-0 shadow-sm mt-3 w-100">
        <div class="card-body p-4 p-md-5">
            <div class="row">
                <div
                    class="col-lg-4 d-flex flex-column align-items-center justify-content-center mb-4 mb-lg-0 border-end pe-lg-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle mb-3"
                        style="width: 120px; height: 120px;">
                        <i class="mdi mdi-cloud-upload" style="font-size: 3.5rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Form Upload</h5>
                    <p class="text-muted small text-center px-3">
                        Dokumen yang diunggah akan otomatis tersimpan dalam Arsip Dokumen Anda, aman dan mudah dicari.
                    </p>
                </div>

                <div class="col-lg-8 ps-lg-5">
                    <form action="{{ route('user.documents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Nama / Judul Dokumen</label>
                            <input type="text" name="nama_dokumen" class="form-control form-control-lg bg-light border-0"
                                required placeholder="Contoh: Laporan Bulanan Januari...">
                            <div class="form-text mt-2"><i class="mdi mdi-information-outline"></i> Masukkan nama dokumen
                                yang jelas agar mudah dicari kelak.</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Organisasi / Departemen (ORG)</label>
                            <select class="form-select form-control form-control-lg bg-light border-0" name="org" required>
                                <option value="">-- Pilih Departemen --</option>
                                <option value="HR">HR - Human Resource</option>
                                <option value="UT">UT - Unit Teknik</option>
                                <option value="SK">SK - Sekretariat</option>
                                <option value="PTD">PTD - Produksi</option>
                                <option value="QA">QA - Quality Assurance</option>
                                <option value="FIN">FIN - Finance</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">File Dokumen</label>
                            <div class="input-group">
                                <input type="file" name="file_dokumen"
                                    class="form-control form-control-lg bg-light border-0" required
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar" id="fileUpload">
                            </div>
                            <div class="form-text mt-2 text-primary">
                                <i class="mdi mdi-check-circle-outline"></i> Maksimal ukuran: 30MB.<br>
                                <i class="mdi mdi-check-circle-outline"></i> Format didukung: PDF, Word, Excel, PowerPoint,
                                ZIP, RAR.
                            </div>
                        </div>

                        <div class="mt-5 pt-3 border-top">
                            <button type="submit"
                                class="btn btn-primary px-5 py-3 fw-bold w-100 d-flex justify-content-center align-items-center"
                                style="background: var(--accent-blue); border: none; font-size: 1.1rem; border-radius: 8px;">
                                <i class="mdi mdi-cloud-upload-outline me-2 fs-4"></i> UNGGAH SEKARANG
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
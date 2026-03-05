@extends('layouts.user')

@push('styles')
    <style>
        .stat-card {
            background: white;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-color: #cbd5e1;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="mdi mdi-file-document-outline"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $myDocumentsCount }}</h3>
                    <p class="text-muted mb-0 small">Total Dokumen Anda</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="stat-card" style="cursor: pointer;"
                onclick="window.location.href='{{ route('user.documents.upload') }}'">
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="mdi mdi-upload"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Upload Baru</h6>
                    <p class="text-muted mb-0 small">Mulai unggah dokumen</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card glass-card">
        <div class="card-body">
            <h5 class="card-title fw-bold mb-4">Aktivitas Terakhir</h5>

            @if($recentDocs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-borderless align-middle min-w-100">
                        <thead class="border-bottom">
                            <tr>
                                <th class="text-muted small fw-semibold pb-3">Nama Dokumen</th>
                                <th class="text-muted small fw-semibold pb-3 text-end">Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentDocs as $doc)
                                <tr>
                                    <td class="pt-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded p-2 me-3 text-secondary">
                                                <i class="mdi mdi-file-outline fs-5"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-medium">{{ mb_strtoupper($doc->jenis_dokumen) }}</h6>
                                                <span class="small text-muted">ID:
                                                    {{ str_pad($doc->id, 5, '0', STR_PAD_LEFT) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pt-3 text-end">
                                        <span class="small text-muted">{{ $doc->created_at->diffForHumans() }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="mdi mdi-text-box-remove-outline text-muted opacity-25" style="font-size: 4rem;"></i>
                    <h6 class="mt-3 fw-bold text-muted">Belum Ada Aktivitas</h6>
                    <p class="text-muted small">Anda belum melakukan unggah dokumen.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
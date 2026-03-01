@extends('layouts.user')

@push('styles')
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
    <div class="d-flex align-items-center justify-content-between mb-4 mt-2">
        <div>
            <h5 class="fw-bold mb-0">Arsip Dokumen Saya</h5>
            <p class="text-muted tiny mb-0">Kelola dan unduh hasil generate dokumen Anda secara mandiri.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('user.dashboard') }}" class="btn btn-primary rounded-1 px-3 py-2 fw-bold shadow-none"
                style="background: var(--accent-blue); font-size: 0.85rem;">
                <i class="fa-solid fa-plus me-1"></i> Generate Baru
            </a>
        </div>
    </div>

    <div class="history-card-flat mt-3">
        <div class="table-responsive">
            <table class="table history-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Dokumen & ID</th>
                        <th>Format</th>
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
                                    <div>
                                        <h6 class="fw-bold mb-0">{{ mb_strtoupper($doc->jenis_dokumen) }}</h6>
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
                                <div class="d-flex flex-column text-muted small">
                                    <span class="fw-medium text-dark">{{ $doc->created_at->format('d M Y') }}</span>
                                    <span>{{ $doc->created_at->format('H:i') }} WIB</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
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
                                    <a href="{{ route('documents.download', $doc->id) }}"
                                        class="btn btn-primary px-5 py-2 fw-bold" style="background: var(--accent-blue); border-radius: 4px;">
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
                            <button type="button" class="btn btn-secondary px-4 py-2" style="border-radius: 4px;" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endpush
@endsection
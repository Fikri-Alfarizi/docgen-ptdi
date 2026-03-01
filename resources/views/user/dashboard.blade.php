@extends('layouts.user')

@push('styles')
    <style>
        /* Category Component */
        .category-scroll {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            padding-bottom: 1rem;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .category-scroll::-webkit-scrollbar { display: none; }

        .category-pill {
            padding: 6px 15px;
            background: white;
            border: 1px solid #cbd5e1;
            border-radius: 4px; /* Sharp */
            white-space: nowrap;
            cursor: pointer;
            transition: all 0.1s;
            font-weight: 500;
            font-size: 0.8rem;
            color: #64748b;
        }
        .category-pill.active {
            background: var(--accent-blue);
            color: white;
            border-color: var(--accent-blue);
        }

        /* View Toggle Buttons */
        .view-toggle {
            display: flex;
            background: #e2e8f0;
            padding: 2px;
            border-radius: 4px;
            gap: 2px;
        }
        .toggle-btn {
            border: none;
            background: transparent;
            padding: 4px 10px;
            border-radius: 2px;
            color: #64748b;
            cursor: pointer;
            transition: all 0.1s;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .toggle-btn.active {
            background: white;
            color: var(--accent-blue);
            box-shadow: none; /* No shadow as requested */
        }

        /* Table View (Default) */
        .template-table-container {
            background: white;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            overflow: hidden;
        }
        .template-table th {
            background: #f8fafc;
            padding: 0.8rem 1rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
        }
        .template-table td {
            padding: 0.6rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        /* Grid View */
        .template-card {
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 1.25rem;
            background: white;
            transition: all 0.15s;
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
        }
        .template-card:hover {
            border-color: var(--accent-blue);
            transform: translateY(-2px);
        }
        .icon-box-large {
            width: 45px;
            height: 45px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            font-size: 1.4rem;
        }

        /* Search Bar */
        .search-wrapper-v2 {
            position: relative;
            flex: 1;
        }
        .search-wrapper-v2 input {
            padding: 0.6rem 1rem 0.6rem 2.5rem;
            border-radius: 4px;
            border: 1px solid #cbd5e1;
            width: 100%;
            outline: none;
            transition: all 0.15s;
            font-size: 0.85rem;
        }
        .search-wrapper-v2 input:focus {
            border-color: var(--accent-blue);
        }
        .search-wrapper-v2 i {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .btn-use-template {
            border-radius: 4px;
            font-weight: 600;
            padding: 0.4rem 1rem;
            font-size: 0.8rem;
        }

        /* Modal fixes */
        .modal-premium { border-radius: 4px; border: none; }
        .modal-premium .modal-header { background: var(--primary-indigo); color: white; border: none; padding: 1rem 1.5rem; }
        .modal-premium .modal-body { padding: 1.5rem; }
        .modal-premium .form-control { border-radius: 4px; font-size: 0.9rem; padding: 0.6rem 0.8rem; }
    </style>
@endpush

@section('content')
    <div class="row align-items-center mb-4 g-3">
        <div class="col-xl-5 col-lg-4">
            <div class="search-wrapper-v2">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="templateSearch" placeholder="Cari memo, surat, atau formulir...">
            </div>
        </div>
        <div class="col-xl-5 col-lg-5">
            <div class="category-scroll mb-0">
                <div class="category-pill active" onclick="filterCategory('all', this)">Semua</div>
                <div class="category-pill" onclick="filterCategory('kebijakan', this)">Kebijakan</div>
                <div class="category-pill" onclick="filterCategory('mutu', this)">Mutu</div>
                <div class="category-pill" onclick="filterCategory('k3lh', this)">K3LH</div>
                <div class="category-pill" onclick="filterCategory('umum', this)">Umum</div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 d-flex justify-content-lg-end">
            <div class="view-toggle">
                <button class="toggle-btn active" id="btnTableView" title="Tampilan Tabel">
                    <i class="fa-solid fa-list-ul"></i>
                </button>
                <button class="toggle-btn" id="btnGridView" title="Tampilan Grid">
                    <i class="fa-solid fa-grip-vertical"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Table View (Default) -->
    <div id="tableViewContainer" class="template-table-container animate-fade-in">
        <div class="table-responsive">
            <table class="table template-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Nama Template</th>
                        <th>Nomor Dokumen</th>
                        <th>Organisasi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="templateTableBody">
                    @forelse($templates as $template)
                        @php
                            $ext = strtolower(pathinfo($template->file_path, PATHINFO_EXTENSION));
                            $icon = 'fa-file-lines';
                            $color = 'text-secondary';
                            if (in_array($ext, ['doc', 'docx'])) { $icon = 'fa-file-word'; $color = 'text-primary'; }
                            elseif ($ext === 'pdf') { $icon = 'fa-file-pdf'; $color = 'text-danger'; }
                            elseif (in_array($ext, ['xls', 'xlsx'])) { $icon = 'fa-file-excel'; $color = 'text-success'; }
                            elseif (in_array($ext, ['ppt', 'pptx'])) { $icon = 'fa-file-powerpoint'; $color = 'text-warning'; }
                        @endphp
                        <tr class="template-item" data-title="{{ strtolower($template->nama_template) }}" data-org="{{ strtolower($template->org) }}">
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid {{ $icon }} {{ $color }} fe-4 me-3" style="font-size: 1.4rem;"></i>
                                    <div>
                                        <h6 class="fw-bold mb-0">{{ mb_strtoupper($template->nama_template) }}</h6>
                                        <span class="tiny text-muted">Format: {{ strtoupper($ext) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted">{{ $template->nomor ?? '-' }}</td>
                            <td><span class="badge bg-light text-dark border-0">{{ $template->org ?? 'GENERAL' }}</span></td>
                            <td class="text-center">
                                <button class="btn btn-outline-primary btn-use-template btn-sm" data-bs-toggle="modal" data-bs-target="#genModal{{ $template->id }}">
                                    Gunakan
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-5 text-muted">Tidak ada template.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Grid View (Hidden by default) -->
    <div id="gridViewContainer" class="row g-4 d-none animate-fade-in">
        @foreach($templates as $template)
            @php
                $ext = strtolower(pathinfo($template->file_path, PATHINFO_EXTENSION));
                $icon = 'fa-file-lines'; $bg = 'bg-light'; $color = 'text-secondary';
                if (in_array($ext, ['doc', 'docx'])) { $icon = 'fa-file-word'; $bg = 'bg-primary-subtle'; $color = 'text-primary'; }
                elseif ($ext === 'pdf') { $icon = 'fa-file-pdf'; $bg = 'bg-danger-subtle'; $color = 'text-danger'; }
                elseif (in_array($ext, ['xls', 'xlsx'])) { $icon = 'fa-file-excel'; $bg = 'bg-success-subtle'; $color = 'text-success'; }
            @endphp
            <div class="col-xl-3 col-lg-4 col-md-6 template-item" data-title="{{ strtolower($template->nama_template) }}" data-org="{{ strtolower($template->org) }}">
                <div class="template-card">
                    <div class="icon-box-large {{ $bg }} {{ $color }}">
                        <i class="fa-solid {{ $icon }}"></i>
                    </div>
                    <h6 class="fw-bold mb-1 text-truncate" title="{{ $template->nama_template }}">{{ mb_strtoupper($template->nama_template) }}</h6>
                    <p class="text-muted tiny mb-3">Unit: {{ $template->org ?? 'General' }}</p>
                    <button class="btn btn-primary btn-use-template w-100" data-bs-toggle="modal" data-bs-target="#genModal{{ $template->id }}">
                        Ganti & Gunakan
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modals (Re-used for both views) -->
    @push('modals')
        @foreach($templates as $template)
            <div class="modal fade" id="genModal{{ $template->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content modal-premium">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Konfigurasi Dokumen</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('user.documents.generate') }}" method="POST">
                            @csrf
                            <input type="hidden" name="template_id" value="{{ $template->id }}">
                            <div class="modal-body p-4">
                                <h6 class="fw-bold text-center mb-4 text-primary">{{ $template->nama_template }}</h6>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">NAMA PEGAWAI</label>
                                    <input type="text" class="form-control" name="nama" value="{{ Auth::user()->name }}"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">NIK / ID</label>
                                    <input type="text" class="form-control" name="nik" value="{{ Auth::user()->nik }}"
                                        required maxlength="16">
                                </div>
                                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mt-2" style="border-radius: 4px;">
                                    <i class="fa-solid fa-bolt me-2"></i> GENERATE DOKUMEN
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endpush
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // View Toggle Logic
            $('#btnTableView').click(function() {
                $('.toggle-btn').removeClass('active');
                $(this).addClass('active');
                $('#gridViewContainer').addClass('d-none');
                $('#tableViewContainer').removeClass('d-none');
            });

            $('#btnGridView').click(function() {
                $('.toggle-btn').removeClass('active');
                $(this).addClass('active');
                $('#tableViewContainer').addClass('d-none');
                $('#gridViewContainer').removeClass('d-none');
            });

            // Search Logic
            $('#templateSearch').on('keyup', function() {
                let value = $(this).val().toLowerCase();
                $(".template-item").filter(function() {
                    $(this).toggle($(this).attr('data-title').indexOf(value) > -1)
                });
            });
        });

        function filterCategory(cat, el) {
            $('.category-pill').removeClass('active');
            $(el).addClass('active');
            
            if (cat === 'all') {
                $('.template-item').show();
            } else {
                $('.template-item').hide();
                $(`.template-item[data-org*="${cat.toLowerCase()}"]`).show();
            }
        }
    </script>
@endpush
@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card card-rounded">
                <div class="card-body">
                    <div class="d-sm-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h4 class="card-title card-title-dash">Pengaturan Sistem</h4>
                            <p class="card-subtitle card-subtitle-dash">Kelola identitas, logo, dan informasi dasar
                                aplikasi.
                            </p>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Identitas Section -->
                            <div class="col-md-6 border-right">
                                <h5 class="mb-4 text-primary"><i class="mdi mdi-information-outline me-2"></i>Identitas
                                    Aplikasi</h5>
                                <div class="form-group">
                                    <label for="app_name">Nama Aplikasi / Judul Tab</label>
                                    <input type="text" class="form-control" name="app_name" id="app_name"
                                        value="{{ $settings['app_name'] ?? '' }}" required>
                                    <small class="text-muted">Akan muncul di judul tab browser.</small>
                                </div>
                                <div class="form-group">
                                    <label for="company_name">Nama Instansi / Perusahaan</label>
                                    <input type="text" class="form-control" name="company_name" id="company_name"
                                        value="{{ $settings['company_name'] ?? '' }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="footer_text">Teks Footer</label>
                                    <textarea class="form-control" name="footer_text" id="footer_text"
                                        rows="3">{{ $settings['footer_text'] ?? '' }}</textarea>
                                </div>
                            </div>

                            <!-- Branding Section -->
                            <div class="col-md-6">
                                <h5 class="mb-4 text-primary"><i class="mdi mdi-palette me-2"></i>Branding & Logo</h5>

                                <div class="form-group">
                                    <label>Logo Aplikasi</label>
                                    <input type="file" name="app_logo" class="file-upload-default" accept="image/*">
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled
                                            placeholder="Upload Logo (PNG/SVG)">
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-primary px-4 py-2" type="button"
                                                style="border-radius: 0 4px 4px 0;">Pilih File</button>
                                        </span>
                                    </div>
                                    <div class="mt-2 bg-light p-3 text-center rounded">
                                        @if(isset($settings['app_logo']) && $settings['app_logo'])
                                            <img src="{{ asset('uploads/settings/' . $settings['app_logo']) }}"
                                                alt="Logo Current" style="max-height: 50px;">
                                        @else
                                            <img src="{{ asset('staradmin/assets/images/logo.svg') }}" alt="Default Logo"
                                                style="max-height: 50px;">
                                            <p class="text-muted tiny mt-1">Logo Default Aktif</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <label>Favicon (Icon Tab)</label>
                                    <input type="file" name="app_favicon" class="file-upload-default"
                                        accept="image/x-icon,image/png">
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled
                                            placeholder="Upload Favicon (ICO/PNG)">
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-primary px-4 py-2" type="button"
                                                style="border-radius: 0 4px 4px 0;">Pilih File</button>
                                        </span>
                                    </div>
                                    <div class="mt-2 bg-light p-3 text-center rounded">
                                        @if(isset($settings['app_favicon']) && $settings['app_favicon'])
                                            <img src="{{ asset('uploads/settings/' . $settings['app_favicon']) }}"
                                                alt="Favicon Current" style="height: 32px; width: 32px;">
                                        @else
                                            <img src="{{ asset('staradmin/assets/images/favicon.png') }}" alt="Default Favicon"
                                                style="height: 32px; width: 32px;">
                                            <p class="text-muted tiny mt-1">Favicon Default Aktif</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Security & Access Section -->
                        <div class="row">
                            <div class="col-12">
                                <h5 class="mb-4 text-danger"><i class="mdi mdi-shield-lock-outline me-2"></i>Keamanan &
                                    Akses Menu</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="admin_settings_visible">Tampilkan Menu Pengaturan di Sidebar</label>
                                            <select
                                                class="form-select border-{{ ($settings['admin_settings_visible'] ?? '1') == '1' ? 'success' : 'danger' }}"
                                                name="admin_settings_visible" id="admin_settings_visible">
                                                <option value="1" {{ ($settings['admin_settings_visible'] ?? '1') == '1' ? 'selected' : '' }}>Tampilkan (Rekomendasi)</option>
                                                <option value="0" {{ ($settings['admin_settings_visible'] ?? '1') == '0' ? 'selected' : '' }}>Sembunyikan (Aman)</option>
                                            </select>
                                            <small class="text-muted">Jika disembunyikan, gunakan shortcut untuk menampilkan
                                                kembali.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="admin_settings_shortcut">Kombinasi Shortcut (Keyboard)</label>
                                            <input type="text" class="form-control" name="admin_settings_shortcut"
                                                id="admin_settings_shortcut"
                                                value="{{ $settings['admin_settings_shortcut'] ?? 'ctrl+shift+f1' }}"
                                                placeholder="contoh: ctrl+shift+f1">
                                            <small class="text-muted">Gunakan format pemisah '+' (contoh: ctrl+shift+x).
                                                Default: <b>ctrl+shift+f1</b></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary btn-lg px-5 py-3 fw-bold shadow-sm">
                                <i class="mdi mdi-content-save-outline me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function ($) {
            'use strict';
            $(function () {
                $('.file-upload-browse').on('click', function () {
                    var file = $(this).parent().parent().parent().find('.file-upload-default');
                    file.trigger('click');
                });
                $('.file-upload-default').on('change', function () {
                    $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
                });
            });
        })(jQuery);
    </script>
@endpush
@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                    <div>
                        <h1 class="m-0 fw-bold">Manajemen Karyawan / Pengguna</h1>
                    </div>
                    <div>
                        <!-- Tombol Tambah Akun dihilangkan (Registrasi via Register Page) -->
                    </div>
                </div>

                <div class="row flex-grow">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card card-rounded">
                            <div class="card-body">
                                <div class="d-sm-flex justify-content-between align-items-start">
                                    <div>
                                        <h4 class="card-title card-title-dash">Daftar Akun Terdaftar</h4>
                                        <p class="card-subtitle card-subtitle-dash">Kelola akses karyawan ke dalam sistem
                                        </p>
                                    </div>
                                </div>

                                <div class="table-responsive mt-1">
                                    <table class="table select-table compact-table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nama Pegawai</th>
                                                <th>NIK</th>
                                                <th>Email Akses</th>
                                                <th>Role</th>
                                                <th>Terdaftar Pada</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($users as $user)
                                                <tr>
                                                    <td>
                                                        <div class="badge badge-opacity-success">{{ $user->id }}</div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center me-3"
                                                                style="width: 40px; height: 40px; border: 1px solid #ebedf2;">
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
                                                    <td>{{ $user->created_at->format('d M Y') }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <button type="button" class="btn btn-sm btn-outline-warning me-2"
                                                                title="Edit Data" data-bs-toggle="modal"
                                                                data-bs-target="#editUserModal{{ $user->id }}"><i
                                                                    class="mdi mdi-pencil"></i></button>
                                                            @if($user->id !== Auth::id())
                                                                <form action="{{ route('admin.users.destroy', $user->id) }}"
                                                                    method="POST" class="d-inline-block"
                                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                        title="Hapus Akun"><i class="mdi mdi-delete"></i></button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-5 text-muted">
                                                        <i class="mdi mdi-account-group-outline"
                                                            style="font-size: 40px; opacity:0.5"></i><br>
                                                        Belum ada pengguna yang terdaftar di sistem.
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

            </div>
        </div>
    </div>

    <!-- Form Tambah Akun Di-disable / Integrasi Registrasi Publik -->

    <!-- Modal Edit Akun (Loop) -->
    @foreach($users as $user)
        <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1"
            aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">Edit Akun Pegawai</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">NIK</label>
                                <input type="text" class="form-control" name="nik" value="{{ $user->nik }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password (Opsional)</label>
                                <input type="password" class="form-control" name="password"
                                    placeholder="Kosongkan jika tidak diubah">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Role Akses</label>
                                <select class="form-select" name="role" required>
                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Pegawai/User</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary text-white">Simpan Perubahan</button>
                        </div>
                    </form>
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
                                }
                            });
                        }
                    });
                    $('.dataTables_filter input').attr('placeholder', 'Cari pegawai...');
                }
            });
        </script>
    @endpush
@endsection
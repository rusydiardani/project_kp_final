@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">Manajemen Pengguna</h5>
                    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm rounded-pill">
                        <i class="bi bi-plus-lg me-1"></i> Tambah User Baru
                    </a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Tanggal Dibuat</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                    style="width: 35px; height: 35px;">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $user->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->role == 'admin')
                                                <span class="badge bg-danger rounded-pill">Admin</span>
                                            @else
                                                <span class="badge bg-info text-dark rounded-pill">Petugas</span>
                                            @endif
                                        </td>
                                        <td class="small text-muted">{{ $user->created_at->format('d M Y') }}</td>
                                        <td class="text-end">
                                            <div class="btn-group">
                                                <a href="{{ route('users.edit', $user->id) }}"
                                                    class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                @if(auth()->id() !== $user->id)
                                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="bi bi-people fs-1 d-block mb-3"></i>
                                            Belum ada data user.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
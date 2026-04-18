@extends('layouts.app')

@section('content')
    <div class="row mb-4">
        <!-- Form Input Data Baru -->
        <div class="col-md-12">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="bi bi-plus-circle me-2"></i> Input Data Pengambilan Baru
                </div>
                <div class="card-body">
                    <form action="{{ route('services.store') }}" method="POST" class="row g-3">
                        @csrf
                        <input type="hidden" name="submission_date" value="{{ date('Y-m-d') }}">

                        <div class="col-md-3">
                            <label class="form-label small fw-bold">NIK</label>
                            <input type="text" name="nik" class="form-control" placeholder="16 Digit NIK" minlength="16"
                                maxlength="16" required>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label small fw-bold">Nama Pemohon</label>
                            <input type="text" name="applicant_name" class="form-control" placeholder="Nama Lengkap"
                                required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-save me-1"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-list-task me-2"></i> Daftar Antrian / Data KTP</h5>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.location.reload();">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </button>
        </div>
        <div class="card-body">
            <!-- Filter Section -->
            <form action="{{ route('services.index') }}" method="GET" class="row g-2 mb-3 align-items-center">
                <div class="col-auto">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Belum Diambil</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Sudah Diambil
                        </option>
                    </select>
                </div>
                <div class="col-auto d-flex align-items-center gap-2">
                    <input type="date" name="start_date" class="form-control form-control-sm" 
                           value="{{ request('start_date', date('Y-m-d')) }}" title="Tanggal Awal">
                    <span>-</span>
                    <input type="date" name="end_date" class="form-control form-control-sm" 
                           value="{{ request('end_date', date('Y-m-d')) }}" title="Tanggal Akhir">
                </div>
                <div class="col-auto flex-grow-1">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Cari NIK / Nama..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-secondary"><i class="bi bi-search"></i> Cari</button>
                    @if(request()->query())
                        <a href="{{ route('services.index') }}" class="btn btn-sm btn-outline-secondary ms-1" title="Reset Filter"><i class="bi bi-x-circle"></i> Reset</a>
                    @endif
                </div>
            </form>

            <!-- Form untuk Bulk Delete -->
            @if(auth()->user()->role === 'admin')
                <form id="bulkDeleteForm" action="{{ route('services.bulkDelete') }}" method="POST" class="d-none mb-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-sm btn-danger shadow-sm" onclick="confirmBulkDelete()">
                        <i class="bi bi-trash"></i> Hapus <span id="selectedCount">0</span> Data Terpilih
                    </button>
                </form>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            @if(auth()->user()->role === 'admin')
                                <th style="width: 40px;" class="text-center">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </th>
                            @endif
                            <th>Pemohon</th>
                            <th>Petugas Input</th>
                            <th>Tgl Dicetak</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($services as $s)
                        <tr>
                            @if(auth()->user()->role === 'admin')
                                <td class="text-center">
                                    <input class="form-check-input row-checkbox" type="checkbox" name="ids[]" value="{{ $s->id }}" form="bulkDeleteForm">
                                </td>
                            @endif
                            <td>
                                <div class="fw-bold">{{ $s->applicant_name }}</div>
                                <small class="text-muted">{{ $s->nik }}</small>
                            </td>
                            <td>
                                <div class="small fw-bold">{{ $s->user ? $s->user->name : '-' }}</div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($s->submission_date)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $s->status == 'completed' ? 'success' : 'warning' }}">
                                    {{ $s->status == 'completed' ? 'Sudah Diambil' : 'Belum Diambil' }}
                                </span>
                            </td>
                            <td class="text-end">
                                @if($s->status !== 'completed')
                                    <button type="button" class="btn btn-sm btn-success text-nowrap" data-bs-toggle="modal" data-bs-target="#pickupModal{{ $s->id }}">
                                        <i class="bi bi-check-lg me-1"></i> Ambil
                                    </button>


                                @else
                                    <span class="text-success small fw-bold">
                                        <i class="bi bi-check-all"></i> Selesai
                                    </span>
                                @endif
                                <button type="button" class="btn btn-sm btn-outline-primary ms-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $s->id }}" title="Edit Data">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                @if(auth()->user()->role === 'admin')
                                    <form action="{{ route('services.destroy', $s->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger ms-1" title="Hapus Data">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === 'admin' ? '6' : '5' }}" class="text-center py-4 text-muted">Belum ada data layanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $services->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
            </div> <!-- End responsive -->
        </div>
    </div>

    <!-- Pickup Modals -->
    @foreach($services as $s)
        @if($s->status !== 'completed')
            <!-- Modal Ambil -->
            <div class="modal fade" id="pickupModal{{ $s->id }}" tabindex="-1" aria-labelledby="pickupModalLabel{{ $s->id }}" aria-hidden="true">
                <div class="modal-dialog text-start">
                    <div class="modal-content">
                        <form action="{{ route('services.pickup', $s->id) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="pickupModalLabel{{ $s->id }}">Konfirmasi Pengambilan KTP</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">No. Telp Pengambil</label>
                                    <input type="text" name="taker_phone" class="form-control" placeholder="08xxxxxxxxx" pattern="[0-9]+" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input rep-checkbox" id="isRep{{ $s->id }}" name="is_representative" value="1" data-target="repFields{{ $s->id }}">
                                    <label class="form-check-label" for="isRep{{ $s->id }}">Diwakilkan orang lain?</label>
                                </div>
                                <div id="repFields{{ $s->id }}" style="display: none;" class="p-3 bg-light rounded border">
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">NIK Pengambil (Wakil)</label>
                                        <input type="text" name="taker_nik" class="form-control form-control-sm rep-input" placeholder="16 Digit NIK">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success">Simpan & Selesai</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal{{ $s->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $s->id }}" aria-hidden="true">
            <div class="modal-dialog text-start">
                <div class="modal-content">
                    <form action="{{ route('services.update', $s->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header bg-light">
                            <h5 class="modal-title" id="editModalLabel{{ $s->id }}"><i class="bi bi-pencil-square me-2"></i>Edit Data Layanan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @if(auth()->user()->role === 'petugas')
                                <div class="alert alert-warning small">
                                    <i class="bi bi-exclamation-circle me-1"></i> Mode Perbaikan Typo: Anda hanya dapat mengubah NIK dan Nama.
                                </div>
                            @endif
                            <div class="mb-3">
                                <label class="form-label fw-bold small">NIK Pemohon</label>
                                <input type="text" name="nik" class="form-control" value="{{ $s->nik }}" minlength="16" maxlength="16" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Nama Pemohon</label>
                                <input type="text" name="applicant_name" class="form-control" value="{{ $s->applicant_name }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Script to toggle representative fields per modal & Bulk Delete -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Bulk delete logic
            const selectAll = document.getElementById('selectAll');
            const rowCheckboxes = document.querySelectorAll('.row-checkbox');
            const bulkDeleteForm = document.getElementById('bulkDeleteForm');
            const selectedCount = document.getElementById('selectedCount');

            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    rowCheckboxes.forEach(cb => cb.checked = this.checked);
                    updateBulkDeleteUI();
                });

                rowCheckboxes.forEach(cb => {
                    cb.addEventListener('change', updateBulkDeleteUI);
                });
            }

            function updateBulkDeleteUI() {
                if(!bulkDeleteForm) return;
                const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
                if (checkedCount > 0) {
                    bulkDeleteForm.classList.remove('d-none');
                    bulkDeleteForm.classList.add('d-block');
                    selectedCount.textContent = checkedCount;
                } else {
                    bulkDeleteForm.classList.remove('d-block');
                    bulkDeleteForm.classList.add('d-none');
                }
            }

            window.confirmBulkDelete = function() {
                if(confirm('Yakin ingin menghapus semua data yang dipilih?')) {
                    bulkDeleteForm.submit();
                }
            }

            const checkboxes = document.querySelectorAll('.rep-checkbox');
            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    const targetId = this.getAttribute('data-target');
                    const targetDiv = document.getElementById(targetId);
                    const inputs = targetDiv.querySelectorAll('.rep-input');
                    
                    if(this.checked) {
                        targetDiv.style.display = 'block';
                        inputs.forEach(input => input.setAttribute('required', 'required'));
                    } else {
                        targetDiv.style.display = 'none';
                        inputs.forEach(input => {
                            input.removeAttribute('required');
                            input.value = ''; // clear value
                        });
                    }
                });
            });
        });
    </script>
@endsection
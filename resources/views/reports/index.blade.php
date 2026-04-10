@extends('layouts.app')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <h4 class="fw-bold mb-3"><i class="bi bi-file-earmark-bar-graph me-2 text-primary"></i> Laporan Pengambilan KTP</h4>
            
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <!-- Filter & Export Section -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                        <form action="{{ route('reports.index') }}" method="GET" class="d-flex flex-wrap gap-2 align-items-center">
                            <!-- Date Filters -->
                            <div class="input-group input-group-sm" style="width: auto;">
                                <span class="input-group-text bg-light"><i class="bi bi-calendar3"></i></span>
                                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" title="Tanggal Awal">
                                <span class="input-group-text bg-light">-</span>
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" title="Tanggal Akhir">
                            </div>

                            <!-- Search -->
                            <div class="input-group input-group-sm" style="width: auto;">
                                <select name="pickup_method" class="form-select" style="min-width: 160px;" title="Metode Ambil">
                                    <option value="">Semua</option>
                                    <option value="ybs" {{ request('pickup_method') == 'ybs' ? 'selected' : '' }}>YBS</option>
                                    <option value="diwakilkan" {{ request('pickup_method') == 'diwakilkan' ? 'selected' : '' }}>Diwakilkan</option>
                                </select>
                                <input type="text" name="search" class="form-control" placeholder="Cari Nama/NIK..." value="{{ request('search') }}" style="min-width: 200px;">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>
                            </div>
                            
                            @if(request('start_date') || request('end_date') || request('search') || request('pickup_method'))
                                <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset Filter"><i class="bi bi-x-circle"></i> Reset</a>
                            @endif
                        </form>

                        <!-- Export Button -->
                        <a href="{{ route('reports.export', request()->query()) }}" class="btn btn-sm btn-success shadow-sm">
                            <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                        </a>
                    </div>
                    
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
                                    <th>Nama Pemohon</th>
                                    <th>Petugas Penyerah</th>
                                    <th>Tgl & Jam Diambil</th>
                                    <th>No. Telp Pengambil</th>
                                    <th class="text-center">Status Pengambilan</th>
                                    <th>NIK Wakil</th>
                                    @if(auth()->user()->role === 'admin')
                                        <th class="text-end">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $r)
                                    <tr>
                                        @if(auth()->user()->role === 'admin')
                                            <td class="text-center">
                                                <input class="form-check-input row-checkbox" type="checkbox" name="ids[]" value="{{ $r->id }}" form="bulkDeleteForm">
                                            </td>
                                        @endif
                                        <td>
                                            <div class="fw-bold">{{ $r->applicant_name }}</div>
                                            <small class="text-muted">{{ $r->nik }}</small>
                                        </td>
                                        <td>
                                            <div class="small fw-bold text-success">{{ $r->releasedBy ? $r->releasedBy->name : '-' }}</div>
                                        </td>
                                        <td>
                                            @if($r->picked_up_at)
                                                <div>{{ \Carbon\Carbon::parse($r->picked_up_at)->translatedFormat('d M Y') }}</div>
                                                <small class="text-secondary">{{ \Carbon\Carbon::parse($r->picked_up_at)->format('H:i') }} WIB</small>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($r->taker_phone)
                                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', $r->taker_phone) }}" target="_blank" class="text-decoration-none" title="Hubungi via WhatsApp">
                                                    {{ $r->taker_phone }} <i class="bi bi-whatsapp text-success fs-6 ms-1"></i>
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($r->taker_nik)
                                                <span class="badge bg-warning text-dark"><i class="bi bi-person-badge"></i> Diwakilkan</span>
                                            @else
                                                <span class="badge bg-info"><i class="bi bi-person"></i> YBS</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($r->taker_nik)
                                                <span class="fw-medium font-monospace">{{ $r->taker_nik }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        @if(auth()->user()->role === 'admin')
                                            <td class="text-end">
                                                <form action="{{ route('services.destroy', $r->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus rekaman data ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm" title="Hapus Data">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->role === 'admin' ? '8' : '6' }}" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                            Belum ada rekaman pengambilan KTP.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $reports->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@if(auth()->user()->role === 'admin')
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
            if(confirm('Yakin ingin menghapus semua data laporan yang dipilih?')) {
                bulkDeleteForm.submit();
            }
        }
    });
</script>
@endif

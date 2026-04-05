@extends('layouts.app')

@section('content')
<style>
    /* Premium Animations & Styles that make it 'pop' */
    .hero-banner {
        background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
        border-radius: 1rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(13, 110, 253, 0.2);
    }
    .hero-banner::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        animation: pulse 8s infinite alternate ease-in-out;
    }
    @keyframes pulse {
        0% { transform: scale(1) translate(0, 0); }
        100% { transform: scale(1.5) translate(-20px, 30px); }
    }
    
    .stat-card-modern {
        border: none;
        border-radius: 1rem;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        overflow: hidden;
        background: white;
    }
    .stat-card-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    .stat-icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .glass-list {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.5);
    }
    .hover-row:hover {
        background-color: rgba(13, 110, 253, 0.05);
        cursor: default;
    }
</style>

<!-- Hero Section -->
<div class="hero-banner p-4 p-md-5 text-white mb-5 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div style="z-index: 1;">
        <h2 class="fw-bold mb-2">Selamat Datang, {{ explode(' ', $user->name)[0] }}! <i class="bi bi-stars text-warning ms-1"></i></h2>
        <p class="mb-0 opacity-75 fs-5">Berikut adalah ringkasan operasional harian Disdukcapil.</p>
    </div>
    <div style="z-index: 1;">
        <a href="{{ route('services.index') }}" class="btn btn-light rounded-pill px-4 fw-bold shadow-sm text-primary">
            <i class="bi bi-plus-circle me-1"></i> Input KTP Baru
        </a>
    </div>
</div>

<!-- Stats Section -->
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card stat-card-modern shadow-sm h-100 p-3">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon-wrapper bg-primary bg-opacity-10 text-primary me-4">
                    <i class="bi bi-folder2-open"></i>
                </div>
                <div>
                    <h6 class="text-uppercase text-muted fw-bold small mb-1">Total KTP Masuk</h6>
                    <h2 class="fw-bold mb-0 text-dark">{{ number_format($stats['total']) }}</h2>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0 pb-2 px-4">
                <small class="text-muted"><i class="bi bi-info-circle me-1"></i> Akumulasi seluruh data</small>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card-modern shadow-sm h-100 p-3">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon-wrapper bg-success bg-opacity-10 text-success me-4">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div>
                    <h6 class="text-uppercase text-muted fw-bold small mb-1">Sudah Diambil</h6>
                    <h2 class="fw-bold mb-0 text-success">{{ number_format($stats['completed']) }}</h2>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0 pb-2 px-4">
                <small class="text-muted"><i class="bi bi-check-all text-success me-1"></i> Sukses diserahkan</small>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card-modern shadow-sm h-100 p-3">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon-wrapper bg-danger bg-opacity-10 text-danger me-4">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div>
                    <h6 class="text-uppercase text-muted fw-bold small mb-1">Menunggu Diambil</h6>
                    <h2 class="fw-bold mb-0 text-danger">{{ number_format($stats['pending']) }}</h2>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0 pb-2 px-4">
                <small class="text-muted"><i class="bi bi-exclamation-triangle text-warning me-1"></i> Berada di Dinas</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Chart Section -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-graph-up-arrow text-primary me-2"></i>Tren Permohonan (7 Hari)</h5>
                <p class="text-muted small mt-1 mb-0">Perbandingan KTP yang diambil vs belum selama sepekan terakhir.</p>
            </div>
            <div class="card-body px-4 pb-4">
                <canvas id="serviceChart" height="110"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-lightning-charge text-warning me-2"></i>Aktivitas Terakhir</h5>
                <span class="badge bg-light text-primary border rounded-pill px-3">Live</span>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush glass-list">
                    @forelse($recentPickups as $latest)
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-4 hover-row border-0 border-bottom">
                            <div>
                                <h6 class="mb-1 fw-bold text-dark">{{ $latest->applicant_name }}</h6>
                                <div class="text-muted small">
                                    <i class="bi bi-person-badge text-secondary me-1"></i> {{ substr($latest->nik, 0, 6) }}**********
                                </div>
                                <div class="text-muted small mt-1">
                                    <i class="bi bi-person-check text-success me-1"></i> Diserahkan: {{ $latest->releasedBy ? $latest->releasedBy->name : 'Sistem' }}
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 mb-1">
                                    <i class="bi bi-check"></i> Selesai
                                </span><br>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    {{ \Carbon\Carbon::parse($latest->picked_up_at)->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                            <p class="mb-0">Belum ada KTP yang diambil <br>dalam waktu dekat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            @if(count($recentPickups) > 0)
            <div class="card-footer bg-white text-center py-3 border-0">
                <a href="{{ route('reports.index') }}" class="text-decoration-none text-primary fw-bold small">
                    Lihat Semua Riwayat <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Subtle animation for cards on load
        const cards = document.querySelectorAll('.stat-card-modern, .card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100 * index);
        });

        // Chart Initialization
        const ctx = document.getElementById('serviceChart').getContext('2d');
        const serviceChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [
                    {
                        label: 'Sudah Diambil',
                        data: {!! json_encode($chartDataCompleted) !!},
                        backgroundColor: 'rgba(25, 135, 84, 0.9)',
                        borderColor: '#198754',
                        borderWidth: 0,
                        borderRadius: 6,
                        barPercentage: 0.6
                    },
                    {
                        label: 'Menunggu',
                        data: {!! json_encode($chartDataPending) !!},
                        backgroundColor: 'rgba(220, 53, 69, 0.8)',
                        borderColor: '#dc3545',
                        borderWidth: 0,
                        borderRadius: 6,
                        barPercentage: 0.6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { family: "'Inter', sans-serif", size: 12 }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        titleColor: '#000',
                        bodyColor: '#333',
                        borderColor: 'rgba(0,0,0,0.1)',
                        borderWidth: 1,
                        padding: 12,
                        boxPadding: 6,
                        usePointStyle: true
                    }
                },
                scales: {
                    x: {
                        stacked: false,
                        grid: { display: false }
                    },
                    y: {
                        stacked: false,
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.05)',
                            drawBorder: false
                        },
                        ticks: { precision: 0 }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
            }
        });
    });
</script>
@endsection
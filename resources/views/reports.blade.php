@extends('layouts.app')

@section('content')
<div class="page-content">
    <!-- Top Stats -->
    <div class="top-stats">
        <div class="top-stat-card highlight">
            <div class="top-stat-label">TOTAL ASET</div>
            <div class="top-stat-value">Rp {{ number_format($totalAssets ?? $balance, 0) }}</div>
            <div class="top-stat-change">+12%</div>
        </div>
        <div class="top-stat-card">
            <div class="top-stat-label">PENGELUARAN TERBESAR</div>
            <div class="top-stat-value" style="font-size: 18px;">Properti</div>
            <div class="top-stat-sub">35% dari total</div>
        </div>
        <div class="top-stat-card">
            <div class="top-stat-label">RASIO TABUNGAN</div>
            <div class="top-stat-value" style="color: var(--success-green);">42.5%</div>
            <div class="top-stat-sub" style="color: var(--success-green);">Sehat</div>
        </div>
        <div class="top-stat-card">
            <div class="top-stat-label">DIVIDEN ESTIMASI</div>
            <div class="top-stat-value">Rp {{ number_format($estimatedDividend ?? 0, 0) }}</div>
            <div class="top-stat-sub">/ bulan</div>
        </div>
    </div>
    
    <!-- Charts Grid -->
    <div class="reports-grid">
        <div class="card">
            <div class="card-header">
                <h3>Pertumbuhan Total Aset</h3>
                <p style="font-size: 13px; color: var(--text-muted);">Visualisasi akumulasi kekayaan 12 bulan terakhir</p>
                <div style="display: flex; gap: 8px;">
                    <button class="status-badge active">1Y</button>
                    <button class="status-badge">5Y</button>
                    <button class="status-badge">All</button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-wrapper">
                    <canvas id="assetGrowthChart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3>Alokasi Pengeluaran</h3>
                <p style="font-size: 13px; color: var(--text-muted);">Distribusi biaya per kategori utama</p>
            </div>
            <div class="card-body">
                <div class="donut-chart-container">
                    <div class="donut-chart">
                        <canvas id="expenseAllocationChart"></canvas>
                        <div class="donut-center">
                            <div class="donut-center-value">100%</div>
                            <div class="donut-center-label">TERALOKASI</div>
                        </div>
                    </div>
                    <div class="donut-legend">
                        <div class="legend-item">
                            <div class="legend-left">
                                <div class="legend-dot" style="background: var(--primary-blue);"></div>
                                <span class="legend-label">Properti & Kebutuhan</span>
                            </div>
                            <span class="legend-value">35%</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-left">
                                <div class="legend-dot" style="background: var(--success-green);"></div>
                                <span class="legend-label">Investasi</span>
                            </div>
                            <span class="legend-value">28%</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-left">
                                <div class="legend-dot" style="background: #E5E7EB;"></div>
                                <span class="legend-label">Gaya Hidup</span>
                            </div>
                            <span class="legend-value">22%</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-left">
                                <div class="legend-dot" style="background: var(--purple);"></div>
                                <span class="legend-label">Lainnya</span>
                            </div>
                            <span class="legend-value">15%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Transaction Analysis -->
    <div class="card transaction-analysis">
        <div class="card-header">
            <h3>Analisis Transaksi Terkini</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: 2fr 3fr 1fr; padding: 0 0 12px; font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                <span>KATEGORI</span>
                <span>DESKRIPSI</span>
                <span>TANGGAL</span>
            </div>
            
            @forelse($recentTransactions as $transaction)
            <div class="analysis-row">
                <div class="analysis-category">
                    <div class="analysis-icon" style="background: {{ $transaction->type === 'income' ? 'var(--success-green-light)' : '#FEF3C7' }}; color: {{ $transaction->type === 'income' ? 'var(--success-green)' : '#F59E0B' }};">
                        <i class="fas {{ $transaction->type === 'income' ? 'fa-chart-line' : 'fa-utensils' }}"></i>
                    </div>
                    <span style="font-weight: 500;">{{ $transaction->category->name ?? 'Uncategorized' }}</span>
                </div>
                <div>
                    <div class="analysis-desc">{{ $transaction->description ?? $transaction->category->name ?? '-' }}</div>
                    <div class="analysis-location">{{ $transaction->type === 'income' ? 'Revenue' : 'Expense' }}</div>
                </div>
                <div class="analysis-date">{{ $transaction->transaction_date->format('d M Y') }}</div>
            </div>
            @empty
            <div style="text-align: center; padding: 40px; color: var(--text-muted);">
                No transactions to analyze yet.
            </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Asset Growth Chart
    const assetCtx = document.getElementById('assetGrowthChart').getContext('2d');
    const monthlyData = @json($monthlyData);
    
    // Calculate cumulative balance
    let cumulative = 0;
    const cumulativeData = monthlyData.map(item => {
        cumulative += item.net;
        return cumulative;
    });
    
    new Chart(assetCtx, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => item.month.substring(0, 3).toUpperCase()),
            datasets: [{
                data: cumulativeData,
                borderColor: '#4A90E2',
                backgroundColor: function(context) {
                    const chart = context.chart;
                    const {ctx, chartArea} = chart;
                    if (!chartArea) return null;
                    const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                    gradient.addColorStop(0, 'rgba(74, 144, 226, 0.2)');
                    gradient.addColorStop(1, 'rgba(74, 144, 226, 0)');
                    return gradient;
                },
                fill: true,
                tension: 0.4,
                pointRadius: 0,
                pointHoverRadius: 6,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#F3F4F6' },
                    ticks: {
                        font: { size: 11 },
                        color: '#9CA3AF',
                        callback: function(value) {
                            return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                        }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { size: 11 },
                        color: '#9CA3AF'
                    }
                }
            }
        }
    });
    
    // Expense Allocation Donut Chart
    const donutCtx = document.getElementById('expenseAllocationChart').getContext('2d');
    new Chart(donutCtx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [35, 28, 22, 15],
                backgroundColor: ['#4A90E2', '#10B981', '#E5E7EB', '#8B5CF6'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.parsed + '%';
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection
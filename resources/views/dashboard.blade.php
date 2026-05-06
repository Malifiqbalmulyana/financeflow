@extends('layouts.app')

@section('content')
<div class="page-content">
    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <div class="card-header">
                <span class="card-label">TOTAL SALDO</span>
                <div class="card-icon blue">
                    <i class="fas fa-university"></i>
                </div>
            </div>
            <div class="card-amount">Rp {{ number_format($balance, 0) }}</div>
            <div class="card-trend positive">
                <i class="fas fa-arrow-up"></i>
                <span>+2.4% bln ini</span>
            </div>
        </div>
        
        <div class="summary-card">
            <div class="card-header">
                <span class="card-label">PENDAPATAN</span>
                <div class="card-icon green">
                    <i class="fas fa-arrow-down"></i>
                </div>
            </div>
            <div class="card-amount">Rp {{ number_format($totalIncome, 0) }}</div>
            <div class="card-trend positive">
                <span>Target: Rp 20.000.000</span>
            </div>
        </div>
        
        <div class="summary-card">
            <div class="card-header">
                <span class="card-label">PENGELUARAN</span>
                <div class="card-icon red">
                    <i class="fas fa-arrow-up"></i>
                </div>
            </div>
            <div class="card-amount">Rp {{ number_format($totalExpenses, 0) }}</div>
            <div class="card-trend negative">
                <i class="fas fa-arrow-up"></i>
                <span>-8% dari bulan lalu</span>
            </div>
        </div>
    </div>
    
    <!-- Main Grid -->
    <div class="dashboard-grid">
        <!-- Cash Flow Chart -->
        <div class="card">
            <div class="card-header">
                <h3>Arus Kas Bulanan</h3>
                <div style="display: flex; gap: 16px; font-size: 13px;">
                    <span style="display: flex; align-items: center; gap: 6px;">
                        <span style="width: 8px; height: 8px; background: var(--primary-blue); border-radius: 50%;"></span>
                        Pemasukan
                    </span>
                    <span style="display: flex; align-items: center; gap: 6px;">
                        <span style="width: 8px; height: 8px; background: #E5E7EB; border-radius: 50%;"></span>
                        Pengeluaran
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="cashFlowChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Recent Transactions -->
        <div class="card">
            <div class="card-header">
                <h3>Transaksi Terakhir</h3>
                <a href="{{ route('transactions.index') }}" class="view-all">Lihat Semua →</a>
            </div>
            <div class="card-body">
                <ul class="transaction-list">
                    @forelse($recentTransactions as $transaction)
                    <li class="transaction-item">
                        <div class="transaction-icon {{ strtolower($transaction->category->name ?? 'food') }}">
                            @if($transaction->type === 'income')
                                <i class="fas fa-money-bill-wave"></i>
                            @else
                                <i class="fas fa-utensils"></i>
                            @endif
                        </div>
                        <div class="transaction-details">
                            <div class="transaction-title">{{ $transaction->category->name ?? 'Uncategorized' }}</div>
                            <div class="transaction-meta">{{ $transaction->description ?? 'No description' }}</div>
                        </div>
                        <div class="transaction-amount {{ $transaction->type }}">
                            @if($transaction->type === 'income')
                                +Rp {{ number_format($transaction->amount, 0) }}
                            @else
                                -Rp {{ number_format($transaction->amount, 0) }}
                            @endif
                        </div>
                    </li>
                    @empty
                    <li class="transaction-item" style="justify-content: center; color: var(--text-muted);">
                        No transactions yet
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Savings Target -->
    <div class="savings-card">
        <div class="savings-header">
            <div class="savings-icon">🌱</div>
            <div>
                <div class="savings-title">Target Tabungan: Dana Darurat</div>
                <div class="savings-subtitle">Anda telah mencapai {{ $savingsPercentage }}% dari target dana darurat sebesar Rp {{ number_format($savingsTarget, 0) }}. Tinggal sedikit lagi!</div>
            </div>
        </div>
        <div class="progress-bar">
            <div class="progress-fill" style="width: {{ $savingsPercentage }}%;"></div>
        </div>
        <div class="progress-text">PROGRESS: {{ $savingsPercentage }}%</div>
    </div>
</div>

@push('scripts')
<script>
    const ctx = document.getElementById('cashFlowChart').getContext('2d');
    const monthlyData = @json($monthlyData);
    
    const months = monthlyData.map(item => item.month.substring(0, 3).toUpperCase());
    const incomes = monthlyData.map(item => item.income);
    const expenses = monthlyData.map(item => item.expenses);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Pemasukan',
                    data: incomes,
                    borderColor: '#4A90E2',
                    backgroundColor: 'rgba(74, 144, 226, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    borderWidth: 2
                },
                {
                    label: 'Pengeluaran',
                    data: expenses,
                    borderColor: '#E5E7EB',
                    backgroundColor: 'rgba(229, 231, 235, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#F3F4F6'
                    },
                    ticks: {
                        font: {
                            size: 11
                        },
                        color: '#9CA3AF'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        },
                        color: '#9CA3AF'
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection
@extends('layouts.app')

@section('content')
<div class="page-content">
    <div class="page-header">
        <h1>Transactions</h1>
        <p>Review and manage your detailed financial activities.</p>
    </div>
    
    <div class="transactions-header">
        <div class="filter-bar">
            <div class="filter-group">
                <label>DATE RANGE</label>
                <input type="date" class="filter-input" placeholder="mm/dd/yyyy">
                <span style="color: var(--text-muted);">to</span>
                <input type="date" class="filter-input" placeholder="mm/dd/yyyy">
            </div>
            <div class="filter-group">
                <label>CATEGORY</label>
                <select class="filter-select">
                    <option>All Categories</option>
                    @foreach($categories ?? [] as $cat)
                        <option>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>STATUS</label>
                <div class="status-badges">
                    <button class="status-badge active">All</button>
                    <button class="status-badge">Completed</button>
                    <button class="status-badge">Pending</button>
                </div>
            </div>
            <div style="margin-left: auto; display: flex; gap: 12px;">
                <button class="btn-export" style="color: var(--text-secondary);">
                    <i class="fas fa-download"></i> Export CSV
                </button>
                <a href="{{ route('transactions.create') }}" class="btn-add">
                    <i class="fas fa-plus"></i> Tambah Transaksi
                </a>
            </div>
        </div>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>DATE</th>
                    <th>CATEGORY</th>
                    <th>DESCRIPTION</th>
                    <th>STATUS</th>
                    <th style="text-align: right;">AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->transaction_date->format('M d, Y') }}</td>
                    <td>
                        <div class="table-category">
                            <div class="table-category-icon" style="background: {{ $transaction->type === 'income' ? 'var(--success-green-light)' : '#FEF3C7' }}; color: {{ $transaction->type === 'income' ? 'var(--success-green)' : '#F59E0B' }};">
                                <i class="fas {{ $transaction->type === 'income' ? 'fa-money-bill-wave' : 'fa-utensils' }}"></i>
                            </div>
                            <span>{{ $transaction->category->name ?? 'Uncategorized' }}</span>
                        </div>
                    </td>
                    <td>{{ $transaction->description ?? '-' }}</td>
                    <td>
                        <span class="table-status paid">PAID</span>
                    </td>
                    <td style="text-align: right;">
                        <span class="table-amount {{ $transaction->type }}">
                            @if($transaction->type === 'income')
                                +Rp {{ number_format($transaction->amount, 2) }}
                            @else
                                -Rp {{ number_format($transaction->amount, 2) }}
                            @endif
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-muted);">
                        No transactions found. Start by adding your first transaction!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($transactions->hasPages())
        <div class="table-pagination">
            <div class="pagination-info">
                Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of {{ $transactions->total() }} transactions
            </div>
            <div class="pagination-buttons">
                {{ $transactions->links() }}
            </div>
        </div>
        @endif
    </div>
    
    <!-- Stats Footer -->
    <div class="stats-footer">
        <div class="stat-card">
            <div class="stat-label">HIGHEST SPEND</div>
            <div class="stat-value negative">
                -Rp {{ number_format($highestExpense ?? 0, 0) }}
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-label">TOTAL NET FLOW</div>
            <div class="stat-value positive">
                +Rp {{ number_format($netFlow ?? 0, 0) }}
            </div>
        </div>
        <div class="stat-card" style="background: var(--primary-blue-light);">
            <div class="stat-label">TREND INSIGHT</div>
            <div style="font-size: 13px; color: var(--text-secondary); margin-top: 4px;">
                Your marketing spend is 12% lower
            </div>
        </div>
    </div>
</div>
@endsection
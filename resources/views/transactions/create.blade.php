@extends('layouts.app')

@section('content')
<div class="page-content">
    <div class="page-header">
        <h1>Add Transaction</h1>
        <p>Record your income or expense</p>
    </div>
    
    <div style="max-width: 600px;">
        <div class="card">
            <div class="card-header">
                <h3>Transaction Details</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('transactions.store') }}" method="POST">
                    @csrf
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px;">Type</label>
                        <div style="display: flex; gap: 12px;">
                            <label style="flex: 1; padding: 16px; border: 2px solid var(--border-color); border-radius: 10px; cursor: pointer; text-align: center; transition: all 0.2s;" 
                                   onmouseover="this.style.borderColor='var(--success-green)'" 
                                   onmouseout="this.style.borderColor='var(--border-color)'">
                                <input type="radio" name="type" value="income" style="display: none;" onchange="this.closest('label').style.borderColor='var(--success-green)'">
                                <i class="fas fa-arrow-down" style="font-size: 24px; color: var(--success-green); margin-bottom: 8px; display: block;"></i>
                                <span style="font-weight: 600;">Income</span>
                            </label>
                            <label style="flex: 1; padding: 16px; border: 2px solid var(--border-color); border-radius: 10px; cursor: pointer; text-align: center; transition: all 0.2s;"
                                   onmouseover="this.style.borderColor='var(--danger-red)'" 
                                   onmouseout="this.style.borderColor='var(--border-color)'">
                                <input type="radio" name="type" value="expense" style="display: none;" onchange="this.closest('label').style.borderColor='var(--danger-red)'">
                                <i class="fas fa-arrow-up" style="font-size: 24px; color: var(--danger-red); margin-bottom: 8px; display: block;"></i>
                                <span style="font-weight: 600;">Expense</span>
                            </label>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px;">Category</label>
                        <select name="category_id" class="filter-select" style="width: 100%; padding: 12px;" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px;">Amount</label>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-weight: 600;">Rp</span>
                            <input type="number" name="amount" step="0.01" min="0.01" class="filter-input" 
                                   style="width: 100%; padding: 12px 16px 12px 40px; font-size: 16px;" 
                                   placeholder="0" required>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px;">Description</label>
                        <textarea name="description" class="filter-input" 
                                  style="width: 100%; padding: 12px; resize: vertical; min-height: 80px;" 
                                  placeholder="Enter details..."></textarea>
                    </div>
                    
                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px;">Date</label>
                        <input type="date" name="transaction_date" class="filter-input" 
                               style="width: 100%; padding: 12px;" 
                               value="{{ date('Y-m-d') }}" required>
                    </div>
                    
                    <div style="display: flex; gap: 12px;">
                        <button type="submit" class="btn-add" style="flex: 1; justify-content: center;">
                            <i class="fas fa-check"></i> Save Transaction
                        </button>
                        <a href="{{ route('transactions.index') }}" class="btn-export" style="text-decoration: none;">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
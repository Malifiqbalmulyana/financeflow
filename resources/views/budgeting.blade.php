@extends('layouts.app')

@section('content')
<div class="page-content">
    <div class="page-header">
        <h1>Budget Management</h1>
        <p>Track and optimize your monthly spending goals.</p>
    </div>
    
    <div style="display: flex; justify-content: flex-end; margin-bottom: 24px;">
        <button class="btn-edit-limit" style="width: auto; padding: 10px 20px;">
            <i class="fas fa-sliders-h"></i> Adjust Period
        </button>
    </div>
    
    <div class="budget-grid">
        @foreach($budgetCategories as $budget)
        <div class="budget-card">
            <div class="menu-dots">
                <i class="fas fa-ellipsis-v"></i>
            </div>
            <div class="budget-category">
                <div class="budget-icon" style="background: {{ $budget['color_bg'] }}; color: {{ $budget['color'] }};">
                    <i class="fas {{ $budget['icon'] }}"></i>
                </div>
                <div>
                    <div class="budget-name">{{ $budget['name'] }}</div>
                    <div class="budget-subtitle">{{ $budget['subtitle'] }}</div>
                </div>
            </div>
            
            <div class="budget-amounts">
                <span class="budget-spent">Rp {{ number_format($budget['spent'], 0) }}</span>
                <span class="budget-limit">/ Rp {{ number_format($budget['limit'], 0) }}</span>
            </div>
            
            <div class="budget-percentage {{ $budget['status'] }}">
                {{ $budget['percentage'] }}% Used
            </div>
            
            <div class="budget-progress">
                <div class="budget-progress-fill {{ $budget['status'] }}" style="width: {{ $budget['percentage'] }}%;"></div>
            </div>
            
            <button class="btn-edit-limit">
                {{ $budget['percentage'] > 100 ? 'Reallocate' : 'Edit Limit' }}
                @if($budget['percentage'] > 100)
                <i class="fas fa-exclamation-circle" style="color: var(--danger-red);"></i>
                @endif
            </button>
        </div>
        @endforeach
        
        <!-- Add New Category Card -->
        <a href="{{ route('categories.create') }}" style="text-decoration: none;">
            <div class="add-category-card">
                <div class="add-category-icon">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="add-category-text">Tambah Kategori Baru</div>
                <div class="add-category-subtext">Tetapkan batas belanja lainnya</div>
            </div>
        </a>
    </div>
    
    <!-- Smart Insights -->
    <div class="card" style="margin-top: 24px; background: var(--text-primary); color: white;">
        <div class="card-body" style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fas fa-lightbulb" style="font-size: 20px;"></i>
                <span style="font-weight: 600;">SMART INSIGHTS</span>
            </div>
            <span style="font-size: 14px; color: #9CA3AF;">Your dining budget is 15% higher than last month</span>
        </div>
    </div>
</div>
@endsection
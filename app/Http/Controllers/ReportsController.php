<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get recent transactions
        $recentTransactions = $user->transactions()
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->limit(10)
            ->get();
        
        // Calculate total assets (simplified)
        $totalIncome = $user->transactions()->where('type', 'income')->sum('amount');
        $totalExpenses = $user->transactions()->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpenses;
        $totalAssets = $balance + 50000000; // Example base amount
        
        // Estimated dividend (example calculation)
        $estimatedDividend = $totalAssets * 0.005; // 0.5% monthly
        
        // Monthly data for charts
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            
            $transactions = $user->transactions()
                ->whereYear('transaction_date', $month->year)
                ->whereMonth('transaction_date', $month->month)
                ->get();
                
            $income = $transactions->where('type', 'income')->sum('amount');
            $expenses = $transactions->where('type', 'expense')->sum('amount');
            
            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'income' => $income,
                'expenses' => $expenses,
                'net' => $income - $expenses
            ];
        }
        
        return view('reports', compact(
            'recentTransactions',
            'totalAssets',
            'estimatedDividend',
            'monthlyData'
        ));
    }
}
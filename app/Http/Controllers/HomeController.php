<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get transactions for current month
        $transactions = $user->transactions()
            ->whereYear('transaction_date', Carbon::now()->year)
            ->whereMonth('transaction_date', Carbon::now()->month)
            ->get();
        
        // Calculate totals
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpenses = $transactions->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpenses;
        
        // Get recent transactions
        $recentTransactions = $user->transactions()
            ->orderBy('transaction_date', 'desc')
            ->limit(5)
            ->get();
        
        // Prepare data for charts
        $monthlyData = $this->getMonthlyData();
        
        // Savings target (example: 10 million)
        $savingsTarget = 10000000;
        $savingsPercentage = $savingsTarget > 0 ? min(100, round(($balance / $savingsTarget) * 100)) : 0;
        
        return view('dashboard', compact(
            'totalIncome',
            'totalExpenses',
            'balance',
            'recentTransactions',
            'monthlyData',
            'savingsTarget',
            'savingsPercentage'
        ));
    }
    
    private function getMonthlyData()
    {
        $user = Auth::user();
        
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            
            $transactions = $user->transactions()
                ->whereYear('transaction_date', $month->year)
                ->whereMonth('transaction_date', $month->month)
                ->get();
                
            $income = $transactions->where('type', 'income')->sum('amount');
            $expenses = $transactions->where('type', 'expense')->sum('amount');
            
            $data[] = [
                'month' => $month->format('M Y'),
                'income' => $income,
                'expenses' => $expenses,
                'net' => $income - $expenses
            ];
        }
        
        return $data;
    }
}
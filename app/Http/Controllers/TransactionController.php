<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Auth::user()->transactions()
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->paginate(15);
        
        $categories = Auth::user()->categories;
        
        // Calculate stats
        $highestExpense = Auth::user()->transactions()
            ->where('type', 'expense')
            ->max('amount') ?? 0;
        
        $totalIncome = Auth::user()->transactions()
            ->where('type', 'income')
            ->sum('amount');
        
        $totalExpenses = Auth::user()->transactions()
            ->where('type', 'expense')
            ->sum('amount');
        
        $netFlow = $totalIncome - $totalExpenses;
        
        return view('transactions.index', compact(
            'transactions', 
            'categories',
            'highestExpense',
            'netFlow'
        ));
    }

    public function create()
    {
        $categories = Auth::user()->categories;
        return view('transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:income,expense',
            'description' => 'nullable|string|max:255',
            'transaction_date' => 'required|date'
        ]);

        Transaction::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'type' => $request->type,
            'description' => $request->description,
            'transaction_date' => $request->transaction_date
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaction added successfully!');
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);
        $categories = Auth::user()->categories;
        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);
        
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:income,expense',
            'description' => 'nullable|string|max:255',
            'transaction_date' => 'required|date'
        ]);

        $transaction->update([
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'type' => $request->type,
            'description' => $request->description,
            'transaction_date' => $request->transaction_date
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully!');
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully!');
    }
}
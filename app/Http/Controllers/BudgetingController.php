<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BudgetingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $categories = $user->categories()->where('type', 'expense')->get();
        
        // Define budget limits (you can make this configurable later)
        $budgetLimits = [
            'Food' => 2000000,
            'Transportation' => 1500000,
            'Entertainment' => 1000000,
            'Shopping' => 2000000,
            'Utilities' => 1500000,
        ];
        
        $budgetCategories = [];
        
        foreach ($categories as $category) {
            $spent = $user->transactions()
                ->where('category_id', $category->id)
                ->where('type', 'expense')
                ->whereYear('transaction_date', Carbon::now()->year)
                ->whereMonth('transaction_date', Carbon::now()->month)
                ->sum('amount');
            
            $limit = $budgetLimits[$category->name] ?? 1000000;
            $percentage = $limit > 0 ? round(($spent / $limit) * 100) : 0;
            
            $status = 'normal';
            if ($percentage > 100) $status = 'over';
            elseif ($percentage > 75) $status = 'warning';
            
            // Define icons and colors
            $iconMap = [
                'Food' => ['icon' => 'fa-utensils', 'color' => '#F59E0B', 'bg' => '#FEF3C7'],
                'Transportation' => ['icon' => 'fa-car', 'color' => '#3B82F6', 'bg' => '#DBEAFE'],
                'Entertainment' => ['icon' => 'fa-film', 'color' => '#EF4444', 'bg' => '#FEE2E2'],
                'Shopping' => ['icon' => 'fa-shopping-bag', 'color' => '#8B5CF6', 'bg' => '#EDE9FE'],
                'Utilities' => ['icon' => 'fa-bolt', 'color' => '#F59E0B', 'bg' => '#FEF3C7'],
            ];
            
            $iconData = $iconMap[$category->name] ?? ['icon' => 'fa-tag', 'color' => '#6B7280', 'bg' => '#F3F4F6'];
            
            $budgetCategories[] = [
                'name' => $category->name,
                'subtitle' => $this->getSubtitle($category->name),
                'spent' => $spent,
                'limit' => $limit,
                'percentage' => $percentage,
                'status' => $status,
                'icon' => $iconData['icon'],
                'color' => $iconData['color'],
                'color_bg' => $iconData['bg'],
            ];
        }
        
        return view('budgeting', compact('budgetCategories'));
    }
    
    private function getSubtitle($name)
    {
        $subtitles = [
            'Food' => 'Dine out & Groceries',
            'Transportation' => 'Commute & Travel',
            'Entertainment' => 'Cinema & Streaming',
            'Shopping' => 'Lifestyle & Hobby',
            'Utilities' => 'Bills & Services',
        ];
        return $subtitles[$name] ?? 'General';
    }
}
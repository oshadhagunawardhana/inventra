<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
{
    $totalCategories = Category::count();
    $totalProducts = Product::count();
    $totalStock = Product::sum('quantity');
    $lowStockProducts = Product::whereColumn('quantity', '<=', 'low_stock_level')->count();

    $totalSales = \App\Models\Sale::count();
    $totalRevenue = \App\Models\Sale::sum('total_amount');

    $recentSales = \App\Models\Sale::latest()
        ->take(5)
        ->get();

    $lowStockList = Product::whereColumn('quantity', '<=', 'low_stock_level')
        ->orderBy('quantity')
        ->take(5)
        ->get();

    $startDate = now('Asia/Colombo')
    ->subDays(6)
    ->startOfDay();

$salesForChart = \App\Models\Sale::where(
    'created_at',
    '>=',
    $startDate->copy()->utc()
)->get();

$salesByDate = $salesForChart
    ->groupBy(function ($sale) {
        return $sale->created_at
            ->timezone('Asia/Colombo')
            ->format('Y-m-d');
    })
    ->map(function ($sales) {
        return $sales->sum('total_amount');
    });

$chartLabels = collect();
$chartData = collect();

for ($i = 0; $i < 7; $i++) {
    $date = $startDate->copy()->addDays($i);

    $dateKey = $date->format('Y-m-d');

    $chartLabels->push($date->format('M d'));
    $chartData->push($salesByDate->get($dateKey, 0));
}

    return view('dashboard', compact(
        'totalCategories',
        'totalProducts',
        'totalStock',
        'lowStockProducts',
        'totalSales',
        'totalRevenue',
        'recentSales',
        'lowStockList',
        'chartLabels',
        'chartData'
    ));
}
}

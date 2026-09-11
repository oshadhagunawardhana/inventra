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

    return view('dashboard', compact(
        'totalCategories',
        'totalProducts',
        'totalStock',
        'lowStockProducts',
        'totalSales',
        'totalRevenue',
        'recentSales',
        'lowStockList'
    ));
}
}

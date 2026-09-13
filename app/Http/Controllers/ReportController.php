<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Keep original values for the date inputs
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Convert Sri Lanka dates to UTC for database filtering
        $startUtc = null;
        $endUtc = null;

        if ($startDate) {
            $startUtc = Carbon::createFromFormat(
                'Y-m-d',
                $startDate,
                'Asia/Colombo'
            )->startOfDay()->utc();
        }

        if ($endDate) {
            $endUtc = Carbon::createFromFormat(
                'Y-m-d',
                $endDate,
                'Asia/Colombo'
            )->endOfDay()->utc();
        }

        // Sales Report
        $salesQuery = Sale::query();

        if ($startUtc) {
            $salesQuery->where('created_at', '>=', $startUtc);
        }

        if ($endUtc) {
            $salesQuery->where('created_at', '<=', $endUtc);
        }

        $sales = $salesQuery
            ->orderBy('created_at', 'desc')
            ->get();

        // Totals
        $totalSales = $sales->count();
        $totalRevenue = $sales->sum('total_amount');

        // Best Selling Products
        $bestSellingQuery = SaleItem::with('product')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->selectRaw(
                'sale_items.product_id, SUM(sale_items.quantity) as total_quantity'
            );

        if ($startUtc) {
            $bestSellingQuery->where(
                'sales.created_at',
                '>=',
                $startUtc
            );
        }

        if ($endUtc) {
            $bestSellingQuery->where(
                'sales.created_at',
                '<=',
                $endUtc
            );
        }

        $bestSellingProducts = $bestSellingQuery
            ->groupBy('sale_items.product_id')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();

        // Current Low Stock Products
        $lowStockProducts = Product::whereColumn(
            'quantity',
            '<=',
            'low_stock_level'
        )
            ->orderBy('quantity', 'asc')
            ->get();

        return view('reports.index', compact(
            'sales',
            'totalSales',
            'totalRevenue',
            'bestSellingProducts',
            'lowStockProducts',
            'startDate',
            'endDate'
        ));
    }
}

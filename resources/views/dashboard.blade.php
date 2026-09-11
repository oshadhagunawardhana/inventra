@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<h2 class="mb-4">Inventra Dashboard</h2>

<div class="row g-4">

<div class="col-md-3">
    <div class="card stat-card">
        <div class="card-body">
            <h6 class="text-muted">Total Sales</h6>
            <h2>{{ $totalSales }}</h2>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">

    <div class="col-md-7">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Recent Sales</h5>

                    <a href="{{ route('sales.index') }}"
                       class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Invoice</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($recentSales as $sale)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('sales.show', $sale) }}">
                                            {{ $sale->invoice_number }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $sale->customer_name ?? 'Walk-in Customer' }}
                                    </td>
                                    <td>
                                        Rs. {{ number_format($sale->total_amount, 2) }}
                                    </td>
                                    <td>
                                        {{ $sale->created_at->format('Y-m-d') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        No sales found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card stat-card">
            <div class="card-body">
                <h5 class="mb-3">Low Stock Products</h5>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Stock</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($lowStockList as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->quantity }}</td>
                                    <td>
                                        <span class="badge bg-danger">
                                            Low Stock
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        No low stock products.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="col-md-3">
    <div class="card stat-card">
        <div class="card-body">
            <h6 class="text-muted">Total Revenue</h6>
            <h2>Rs. {{ number_format($totalRevenue, 2) }}</h2>
        </div>
    </div>
</div>

    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="text-muted">Total Categories</h6>
                <h2>{{ $totalCategories }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="text-muted">Total Products</h6>
                <h2>{{ $totalProducts }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="text-muted">Total Stock</h6>
                <h2>{{ $totalStock }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="text-muted">Low Stock Products</h6>
                <h2>{{ $lowStockProducts }}</h2>
            </div>
        </div>
    </div>

</div>

@endsection
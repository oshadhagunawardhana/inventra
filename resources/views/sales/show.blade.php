@extends('layouts.app')

@section('title', 'Invoice')

@section('content')

<style>
    @media print {
        .sidebar,
        .btn {
            display: none !important;
        }

        .col-md-10 {
            width: 100% !important;
        }

        body {
            background: white !important;
        }

        .card {
            box-shadow: none !important;
            border: none !important;
        }
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Invoice</h2>
        <p class="text-muted mb-0">{{ $sale->invoice_number }}</p>
    </div>

    <div>
        <button onclick="window.print()" class="btn btn-success">
            Print Invoice
        </button>

        <a href="{{ route('sales.index') }}" class="btn btn-secondary">
            Back
        </a>
    </div>
</div>
<div class="card stat-card">
    <div class="card-body">

        <div class="row mb-4">
            <div class="col-md-6">
                <strong>Customer:</strong>
                {{ $sale->customer_name ?? 'Walk-in Customer' }}
            </div>

            <div class="col-md-3">
                <strong>Payment:</strong>
                {{ ucfirst(str_replace('_', ' ', $sale->payment_method)) }}
            </div>

            <div class="col-md-3">
                <strong>Date:</strong>
                {{ $sale->created_at->format('Y-m-d H:i') }}
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Unit Price</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($sale->items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->product->sku }}</td>
                            <td>Rs. {{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rs. {{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-end">
            <h3>
                Total: Rs. {{ number_format($sale->total_amount, 2) }}
            </h3>
        </div>

    </div>
</div>

@endsection
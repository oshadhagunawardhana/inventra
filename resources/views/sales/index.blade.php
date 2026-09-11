@extends('layouts.app')

@section('title', 'Sales')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Sales</h2>

    <a href="{{ route('sales.create') }}" class="btn btn-primary">
        + New Sale
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card stat-card">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($sales as $sale)

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $sale->invoice_number }}</td>
                        <td>{{ $sale->customer_name ?? 'Walk-in Customer' }}</td>
                        <td>Rs. {{ number_format($sale->total_amount, 2) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $sale->payment_method)) }}</td>
                        <td>{{ $sale->created_at->format('Y-m-d H:i') }}</td>

                        <td>
    <a href="{{ route('sales.show', $sale) }}"
       class="btn btn-sm btn-primary">
        View
    </a>

    <form
        action="{{ route('sales.destroy', $sale) }}"
        method="POST"
        class="d-inline"
        onsubmit="return confirm('Delete this sale and restore stock?')"
    >
        @csrf
        @method('DELETE')

        <button class="btn btn-sm btn-danger">
            Delete
        </button>
    </form>
</td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            No sales found.
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection
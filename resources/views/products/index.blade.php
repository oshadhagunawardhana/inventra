@extends('layouts.app')

@section('title', 'Products')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Products</h2>

    <a href="{{ route('products.create') }}" class="btn btn-primary">
        + Add Product
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
                        <th>Product</th>
                        <th>Category</th>
                        <th>SKU</th>
                        <th>Cost Price</th>
                        <th>Selling Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($products as $product)

                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            <strong>{{ $product->name }}</strong>
                        </td>

                        <td>
                            {{ $product->category->name }}
                        </td>

                        <td>
                            {{ $product->sku }}
                        </td>

                        <td>
                            Rs. {{ number_format($product->cost_price, 2) }}
                        </td>

                        <td>
                            Rs. {{ number_format($product->selling_price, 2) }}
                        </td>

                        <td>
                            {{ $product->quantity }}
                        </td>

                        <td>
                            @if($product->quantity <= $product->low_stock_level)
                                <span class="badge bg-danger">
                                    Low Stock
                                </span>
                            @else
                                <span class="badge bg-success">
                                    In Stock
                                </span>
                            @endif
                        </td>

                        <td>
                            <a
                                href="{{ route('products.edit', $product) }}"
                                class="btn btn-sm btn-warning"
                            >
                                Edit
                            </a>

                            <form
                                action="{{ route('products.destroy', $product) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Are you sure you want to delete this product?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-sm btn-danger"
                                >
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="9" class="text-center text-muted">
                            No products found.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection
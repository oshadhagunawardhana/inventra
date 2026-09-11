@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Product</h2>

    <a href="{{ route('products.index') }}" class="btn btn-secondary">
        Back
    </a>
</div>

<div class="card stat-card">
    <div class="card-body">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">Category</label>

                    <select
                        name="category_id"
                        id="category_id"
                        class="form-select"
                        required
                    >
                        @foreach($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Product Name</label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control"
                        value="{{ old('name', $product->name) }}"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label for="sku" class="form-label">SKU</label>

                    <input
                        type="text"
                        id="sku"
                        name="sku"
                        class="form-control"
                        value="{{ old('sku', $product->sku) }}"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label for="quantity" class="form-label">Quantity</label>

                    <input
                        type="number"
                        id="quantity"
                        name="quantity"
                        class="form-control"
                        min="0"
                        value="{{ old('quantity', $product->quantity) }}"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label for="cost_price" class="form-label">Cost Price (Rs.)</label>

                    <input
                        type="number"
                        id="cost_price"
                        name="cost_price"
                        class="form-control"
                        step="0.01"
                        min="0"
                        value="{{ old('cost_price', $product->cost_price) }}"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label for="selling_price" class="form-label">Selling Price (Rs.)</label>

                    <input
                        type="number"
                        id="selling_price"
                        name="selling_price"
                        class="form-control"
                        step="0.01"
                        min="0"
                        value="{{ old('selling_price', $product->selling_price) }}"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label for="low_stock_level" class="form-label">Low Stock Level</label>

                    <input
                        type="number"
                        id="low_stock_level"
                        name="low_stock_level"
                        class="form-control"
                        min="0"
                        value="{{ old('low_stock_level', $product->low_stock_level) }}"
                        required
                    >
                </div>

                <div class="col-12 mb-3">
                    <label for="description" class="form-label">Description</label>

                    <textarea
                        id="description"
                        name="description"
                        class="form-control"
                        rows="4"
                    >{{ old('description', $product->description) }}</textarea>
                </div>

            </div>

            <button type="submit" class="btn btn-warning">
                Update Product
            </button>

        </form>

    </div>
</div>

@endsection
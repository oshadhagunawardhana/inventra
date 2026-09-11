@extends('layouts.app')

@section('title', 'New Sale')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>New Sale</h2>

    <a href="{{ route('sales.index') }}" class="btn btn-secondary">
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

        <form action="{{ route('sales.store') }}" method="POST">
            @csrf

            <div class="row mb-3">

                <div class="col-md-6">
                    <label class="form-label">Customer Name</label>

                    <input
                        type="text"
                        name="customer_name"
                        class="form-control"
                        value="{{ old('customer_name') }}"
                        placeholder="Leave blank for walk-in customer"
                    >
                </div>

                <div class="col-md-6">
                    <label class="form-label">Payment Method</label>

                    <select name="payment_method" class="form-select" required>
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                </div>

            </div>

            <hr>

            <h5 class="mb-3">Products</h5>

            <div id="productRows">

                <div class="row product-row align-items-end mb-3">

                    <div class="col-md-6">
                        <label class="form-label">Product</label>

                        <select
                            name="product_id[]"
                            class="form-select product-select"
                            required
                        >
                            <option value="">Select Product</option>

                            @foreach($products as $product)
                                <option
                                    value="{{ $product->id }}"
                                    data-price="{{ $product->selling_price }}"
                                    data-stock="{{ $product->quantity }}"
                                >
                                    {{ $product->name }}
                                    — Rs. {{ number_format($product->selling_price, 2) }}
                                    — Stock: {{ $product->quantity }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Quantity</label>

                        <input
                            type="number"
                            name="quantity[]"
                            class="form-control quantity-input"
                            value="1"
                            min="1"
                            required
                        >
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Subtotal</label>

                        <input
                            type="text"
                            class="form-control subtotal-display"
                            value="Rs. 0.00"
                            readonly
                        >
                    </div>

                    <div class="col-md-1">
                        <button
                            type="button"
                            class="btn btn-danger remove-product"
                        >
                            ×
                        </button>
                    </div>

                </div>

            </div>

            <button
                type="button"
                id="addProduct"
                class="btn btn-outline-primary mb-4"
            >
                + Add Product
            </button>

            <div class="text-end mb-3">
                <h4>
                    Total:
                    <span id="grandTotal">Rs. 0.00</span>
                </h4>
            </div>

            <button type="submit" class="btn btn-success">
                Complete Sale
            </button>

        </form>

    </div>
</div>

<script>
    const productRows = document.getElementById('productRows');
    const addProductButton = document.getElementById('addProduct');
    const grandTotal = document.getElementById('grandTotal');

    function updateTotals() {
        let total = 0;

        document.querySelectorAll('.product-row').forEach(row => {
            const productSelect = row.querySelector('.product-select');
            const quantityInput = row.querySelector('.quantity-input');
            const subtotalDisplay = row.querySelector('.subtotal-display');

            const selectedOption =
                productSelect.options[productSelect.selectedIndex];

            const price = parseFloat(selectedOption?.dataset.price || 0);
            const quantity = parseInt(quantityInput.value || 0);

            const subtotal = price * quantity;

            subtotalDisplay.value =
                'Rs. ' + subtotal.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

            total += subtotal;
        });

        grandTotal.textContent =
            'Rs. ' + total.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
    }

    addProductButton.addEventListener('click', function () {
        const firstRow = document.querySelector('.product-row');
        const newRow = firstRow.cloneNode(true);

        newRow.querySelector('.product-select').selectedIndex = 0;
        newRow.querySelector('.quantity-input').value = 1;
        newRow.querySelector('.subtotal-display').value = 'Rs. 0.00';

        productRows.appendChild(newRow);

        updateTotals();
    });

    productRows.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-product')) {

            const rows = document.querySelectorAll('.product-row');

            if (rows.length > 1) {
                event.target.closest('.product-row').remove();
                updateTotals();
            }
        }
    });

    productRows.addEventListener('change', updateTotals);
    productRows.addEventListener('input', updateTotals);
</script>

@endsection
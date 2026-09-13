@extends('layouts.app')

@section('content')

<style>
    .sale-form-card {
        background: #ffffff;
        border-radius: 18px;
        border-top: 5px solid #0d6efd;
        padding: 22px;
        box-shadow: 0 5px 18px rgba(0, 0, 0, 0.04);
    }

    .sale-form-card .form-label {
        font-weight: 500;
        margin-bottom: 8px;
    }

    .sale-form-card .form-control,
    .sale-form-card .form-select {
        min-height: 42px;
    }

    .product-table thead th {
        background: #f8f9fa;
        font-weight: 600;
        padding: 12px;
    }

    .product-table td {
        padding: 10px;
        vertical-align: middle;
    }

    .grand-total-box {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 16px 20px;
    }
</style>


<div class="d-flex justify-content-between align-items-center mb-4">

    <h2 class="mb-0">
        New Sale
    </h2>

    <a
        href="{{ route('sales.index') }}"
        class="btn btn-secondary"
    >
        Back
    </a>

</div>


@if ($errors->any())

    <div class="alert alert-danger mb-4">
        <ul class="mb-0">

            @foreach ($errors->all() as $error)
                <li>
                    {{ $error }}
                </li>
            @endforeach

        </ul>
    </div>

@endif


<form
    action="{{ route('sales.store') }}"
    method="POST"
    id="saleForm"
>

    @csrf


    <div class="sale-form-card">

        {{-- Customer + Payment --}}
        <div class="row g-4 mb-4">

            <div class="col-md-6">

                <label class="form-label">
                    Customer
                </label>

                <select
                    name="customer_id"
                    class="form-select"
                >

                    <option value="">
                        Walk-in Customer
                    </option>

                    @foreach ($customers as $customer)

                        <option
                            value="{{ $customer->id }}"
                            {{ old('customer_id') == $customer->id ? 'selected' : '' }}
                        >
                            {{ $customer->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div class="col-md-6">

                <label class="form-label">
                    Payment Method
                </label>

                <select
                    name="payment_method"
                    class="form-select"
                    required
                >

                    <option
                        value="cash"
                        {{ old('payment_method', 'cash') === 'cash' ? 'selected' : '' }}
                    >
                        Cash
                    </option>

                    <option
                        value="card"
                        {{ old('payment_method') === 'card' ? 'selected' : '' }}
                    >
                        Card
                    </option>

                    <option
                        value="bank_transfer"
                        {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}
                    >
                        Bank Transfer
                    </option>

                </select>

            </div>

        </div>


        {{-- Products Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">

            <h5 class="mb-0">
                Products
            </h5>

            <button
                type="button"
                class="btn btn-primary"
                id="addProductBtn"
                {{ $products->isEmpty() ? 'disabled' : '' }}
            >
                <i class="bi bi-plus-lg me-1"></i>
                Add Product
            </button>

        </div>


        @if($products->isEmpty())

            <div class="alert alert-warning">
                No products are currently in stock.
            </div>

        @else

            <div class="table-responsive">

                <table class="table table-bordered product-table">

                    <thead>

                        <tr>
                            <th>
                                Product
                            </th>

                            <th style="width: 130px;">
                                Quantity
                            </th>

                            <th style="width: 150px;">
                                Price
                            </th>

                            <th style="width: 170px;">
                                Subtotal
                            </th>

                            <th style="width: 90px;">
                                Action
                            </th>
                        </tr>

                    </thead>


                    <tbody id="productRows">
                    </tbody>

                </table>

            </div>

        @endif


        {{-- Bottom --}}
        <div class="row mt-4">

            <div class="col-md-6">
            </div>


            <div class="col-md-6">

                <div class="grand-total-box">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <span class="fw-semibold">
                            Grand Total
                        </span>

                        <h3 class="mb-0">
                            Rs.
                            <span id="grandTotal">
                                0.00
                            </span>
                        </h3>

                    </div>


                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                        {{ $products->isEmpty() ? 'disabled' : '' }}
                    >
                        <i class="bi bi-check-circle me-1"></i>
                        Complete Sale
                    </button>

                </div>

            </div>

        </div>

    </div>

</form>


<template id="productOptionsTemplate">

    <option value="">
        Select Product
    </option>

    @foreach ($products as $product)

        <option
            value="{{ $product->id }}"
            data-price="{{ $product->selling_price }}"
            data-stock="{{ $product->quantity }}"
        >
            {{ $product->name }}
            - {{ $product->sku }}
            - Stock: {{ $product->quantity }}
        </option>

    @endforeach

</template>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const productRows =
        document.getElementById('productRows');

    const addProductBtn =
        document.getElementById('addProductBtn');

    const grandTotal =
        document.getElementById('grandTotal');

    const optionsTemplate =
        document.getElementById(
            'productOptionsTemplate'
        );


    if (!productRows || !optionsTemplate) {
        return;
    }


    function formatMoney(value) {

        return Number(value).toLocaleString(
            'en-US',
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        );
    }


    function updateGrandTotal() {

        let total = 0;

        document
            .querySelectorAll('.sale-product-row')
            .forEach(function (row) {

                total += parseFloat(
                    row.dataset.subtotal || 0
                );
            });

        grandTotal.textContent =
            formatMoney(total);
    }


    function updateRemoveButtons() {

        const rows =
            document.querySelectorAll(
                '.sale-product-row'
            );

        rows.forEach(function (row) {

            const button =
                row.querySelector(
                    '.remove-product-btn'
                );

            if (button) {
                button.disabled =
                    rows.length === 1;
            }

        });
    }


    function updateRow(row) {

        const select =
            row.querySelector(
                '.product-select'
            );

        const quantityInput =
            row.querySelector(
                '.quantity-input'
            );

        const priceDisplay =
            row.querySelector(
                '.price-display'
            );

        const subtotalDisplay =
            row.querySelector(
                '.subtotal-display'
            );


        const selectedOption =
            select.options[
                select.selectedIndex
            ];


        const price =
            parseFloat(
                selectedOption?.dataset.price || 0
            );


        const stock =
            parseInt(
                selectedOption?.dataset.stock || 0
            );


        let quantity =
            parseInt(
                quantityInput.value || 1
            );


        if (select.value) {

            quantityInput.max = stock;

            if (quantity > stock) {

                quantity = stock;

                quantityInput.value =
                    stock;
            }

            if (quantity < 1) {

                quantity = 1;

                quantityInput.value =
                    1;
            }

        } else {

            quantityInput.removeAttribute(
                'max'
            );
        }


        const subtotal =
            price * quantity;


        priceDisplay.textContent =
            'Rs. ' + formatMoney(price);


        subtotalDisplay.textContent =
            'Rs. ' + formatMoney(subtotal);


        row.dataset.subtotal =
            subtotal;


        updateGrandTotal();
    }


    function addProductRow(
        selectedProduct = '',
        selectedQuantity = 1
    ) {

        const row =
            document.createElement('tr');


        row.className =
            'sale-product-row';


        row.dataset.subtotal =
            0;


        row.innerHTML = `

            <td>

                <select
                    name="product_id[]"
                    class="form-select product-select"
                    required
                >
                </select>

            </td>


            <td>

                <input
                    type="number"
                    name="quantity[]"
                    class="form-control quantity-input"
                    min="1"
                    value="${selectedQuantity}"
                    required
                >

            </td>


            <td>

                <span class="price-display">
                    Rs. 0.00
                </span>

            </td>


            <td>

                <strong class="subtotal-display">
                    Rs. 0.00
                </strong>

            </td>


            <td class="text-center">

                <button
                    type="button"
                    class="btn btn-outline-danger btn-sm remove-product-btn"
                >
                    <i class="bi bi-trash"></i>
                </button>

            </td>

        `;


        const select =
            row.querySelector(
                '.product-select'
            );


        select.innerHTML =
            optionsTemplate.innerHTML;


        if (selectedProduct) {

            select.value =
                String(selectedProduct);
        }


        productRows.appendChild(row);


        select.addEventListener(
            'change',
            function () {

                updateRow(row);

            }
        );


        row.querySelector(
            '.quantity-input'
        ).addEventListener(
            'input',
            function () {

                updateRow(row);

            }
        );


        row.querySelector(
            '.remove-product-btn'
        ).addEventListener(
            'click',
            function () {

                row.remove();

                updateRemoveButtons();

                updateGrandTotal();

            }
        );


        updateRow(row);

        updateRemoveButtons();
    }


    if (addProductBtn) {

        addProductBtn.addEventListener(
            'click',
            function () {

                addProductRow();

            }
        );
    }


    const oldProductIds =
        @json(old('product_id', []));


    const oldQuantities =
        @json(old('quantity', []));


    if (
        Array.isArray(oldProductIds) &&
        oldProductIds.length > 0
    ) {

        oldProductIds.forEach(
            function (productId, index) {

                addProductRow(
                    productId,
                    oldQuantities[index] || 1
                );

            }
        );

    } else {

        addProductRow();

    }

});
</script>

@endsection
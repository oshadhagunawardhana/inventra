@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2 class="mb-0">
        Sales
    </h2>

    <a
        href="{{ route('sales.create') }}"
        class="btn btn-primary"
    >
        <i class="bi bi-plus-lg me-1"></i>
        New Sale
    </a>

</div>


@if(session('success'))

    <div class="alert alert-success">
        {{ session('success') }}
    </div>

@endif


@if(session('low_stock_products'))

    <div
        id="lowStockAlert"
        class="alert low-stock-alert"
        role="alert"
    >
        <i class="bi bi-exclamation-triangle-fill me-2"></i>

        <strong>
            Low Stock!
        </strong>

        {{ implode(', ', session('low_stock_products')) }}

        reached the low stock level.
    </div>

@endif


@if(session('out_of_stock_products'))

    <div
        id="outOfStockAlert"
        class="alert out-of-stock-alert"
        role="alert"
    >
        <i class="bi bi-x-octagon-fill me-2"></i>

        <strong>
            Out of Stock!
        </strong>

        {{ implode(', ', session('out_of_stock_products')) }}

        is now out of stock.
    </div>

@endif


<div class="card stat-card">

    <div class="card-body">


        <form
            action="{{ route('sales.index') }}"
            method="GET"
            id="salesSearchForm"
            class="mb-4"
        >

            <div class="position-relative">

                <input
                    type="text"
                    name="search"
                    id="salesSearch"
                    class="form-control"
                    placeholder="Search by invoice, customer or payment method..."
                    value="{{ $search ?? '' }}"
                    autocomplete="off"
                >

                <div
                    id="salesSuggestions"
                    class="list-group position-absolute w-100 shadow-sm"
                    style="z-index: 1000; display: none;"
                ></div>

            </div>

        </form>


        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead>

                    <tr>
                        <th>#</th>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Payment</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th style="width: 200px;">
                            Actions
                        </th>
                    </tr>

                </thead>


                <tbody>

                    @forelse($sales as $sale)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>


                            <td>

                                <a
                                    href="{{ route('sales.show', $sale) }}"
                                    class="fw-semibold"
                                >
                                    {{ $sale->invoice_number }}
                                </a>

                            </td>


                            <td>
                                {{ $sale->customer_name ?? 'Walk-in Customer' }}
                            </td>


                            <td>
                                {{ ucwords(str_replace('_', ' ', $sale->payment_method)) }}
                            </td>


                            <td>
                                Rs.
                                {{ number_format($sale->total_amount, 2) }}
                            </td>


                            <td>
                                {{
                                    $sale->created_at
                                        ->timezone('Asia/Colombo')
                                        ->format('Y-m-d h:i A')
                                }}
                            </td>


                            <td>

                                <a
                                    href="{{ route('sales.show', $sale) }}"
                                    class="btn btn-primary btn-sm"
                                >
                                    <i class="bi bi-eye"></i>
                                    View
                                </a>


                                <form
                                    action="{{ route('sales.destroy', $sale) }}"
                                    method="POST"
                                    class="d-inline"

                                    data-confirm
                                    data-confirm-title="Delete Sale?"
                                    data-confirm-message="This sale will be deleted and the sold stock will be restored. Are you sure you want to continue?"
                                    data-confirm-button="Delete Sale"
                                    data-confirm-type="danger"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                    >
                                        <i class="bi bi-trash"></i>
                                        Delete
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center text-muted py-4"
                            >
                                No sales found.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const input =
        document.getElementById(
            'salesSearch'
        );

    const suggestionBox =
        document.getElementById(
            'salesSuggestions'
        );

    const form =
        document.getElementById(
            'salesSearchForm'
        );

    const suggestions =
        @json($searchSuggestions ?? []);


    input.addEventListener(
        'input',
        function () {

            const value =
                this.value
                    .trim()
                    .toLowerCase();


            suggestionBox.innerHTML = '';


            if (value === '') {

                suggestionBox.style.display =
                    'none';


                if (
                    new URLSearchParams(
                        window.location.search
                    ).has('search')
                ) {

                    window.location.href =
                        "{{ route('sales.index') }}";
                }

                return;
            }


            const filtered =
                suggestions.filter(
                    function (item) {

                        return (
                            (item.invoice || '')
                                .toLowerCase()
                                .includes(value)
                            ||
                            (item.customer || '')
                                .toLowerCase()
                                .includes(value)
                            ||
                            (item.payment || '')
                                .toLowerCase()
                                .includes(value)
                        );
                    }
                ).slice(0, 6);


            if (filtered.length === 0) {

                suggestionBox.style.display =
                    'none';

                return;
            }


            filtered.forEach(
                function (item) {

                    const button =
                        document.createElement(
                            'button'
                        );

                    button.type = 'button';

                    button.className =
                        'list-group-item list-group-item-action';


                    const payment =
                        (item.payment || '')
                            .replaceAll('_', ' ');


                    button.innerHTML =
                        '<strong>' +
                        item.invoice +
                        '</strong>' +
                        '<br>' +
                        '<small class="text-muted">' +
                        item.customer +
                        ' • ' +
                        payment +
                        '</small>';


                    button.addEventListener(
                        'click',
                        function () {

                            input.value =
                                item.invoice;

                            suggestionBox.style.display =
                                'none';

                            form.submit();
                        }
                    );


                    suggestionBox.appendChild(
                        button
                    );
                }
            );


            suggestionBox.style.display =
                'block';

        }
    );


    document.addEventListener(
        'click',
        function (event) {

            if (
                !input.contains(event.target) &&
                !suggestionBox.contains(
                    event.target
                )
            ) {

                suggestionBox.style.display =
                    'none';
            }
        }
    );

});
</script>

@endsection
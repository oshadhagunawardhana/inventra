@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2 class="mb-0">
        Customers
    </h2>

    <a
        href="{{ route('customers.create') }}"
        class="btn btn-primary"
    >
        <i class="bi bi-plus-lg me-1"></i>
        Add Customer
    </a>

</div>


@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif


<div class="card stat-card">

    <div class="card-body">


        <form
            action="{{ route('customers.index') }}"
            method="GET"
            id="customerSearchForm"
            class="mb-4"
        >

            <div class="position-relative">

                <input
                    type="text"
                    name="search"
                    id="customerSearch"
                    class="form-control"
                    placeholder="Search by name, phone or email..."
                    value="{{ $search ?? '' }}"
                    autocomplete="off"
                >

                <div
                    id="customerSuggestions"
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
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th style="width: 190px;">
                            Actions
                        </th>
                    </tr>

                </thead>


                <tbody>

                    @forelse($customers as $customer)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td class="fw-semibold">
                                {{ $customer->name }}
                            </td>

                            <td>
                                {{ $customer->phone ?: '—' }}
                            </td>

                            <td>
                                {{ $customer->email ?: '—' }}
                            </td>

                            <td>
                                {{ $customer->address ?: '—' }}
                            </td>

                            <td>

                                <a
                                    href="{{ route('customers.edit', $customer) }}"
                                    class="btn btn-warning btn-sm"
                                >
                                    <i class="bi bi-pencil-square"></i>
                                    Edit
                                </a>


                                <form
                                    action="{{ route('customers.destroy', $customer) }}"
                                    method="POST"
                                    class="d-inline"

                                    data-confirm
                                    data-confirm-title="Delete Customer?"
                                    data-confirm-message="Are you sure you want to delete {{ $customer->name }}?"
                                    data-confirm-button="Delete Customer"
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
                                colspan="6"
                                class="text-center text-muted py-4"
                            >
                                No customers found.
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
            'customerSearch'
        );

    const suggestionBox =
        document.getElementById(
            'customerSuggestions'
        );

    const form =
        document.getElementById(
            'customerSearchForm'
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
                        "{{ route('customers.index') }}";
                }

                return;
            }


            const filtered =
                suggestions.filter(
                    function (item) {

                        return (
                            (item.name || '')
                                .toLowerCase()
                                .includes(value)
                            ||
                            (item.phone || '')
                                .toLowerCase()
                                .includes(value)
                            ||
                            (item.email || '')
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


                    button.innerHTML =
                        '<strong>' +
                        item.name +
                        '</strong>' +
                        '<br>' +
                        '<small class="text-muted">' +
                        (item.phone || '') +
                        (
                            item.email
                                ? ' • ' +
                                  item.email
                                : ''
                        ) +
                        '</small>';


                    button.addEventListener(
                        'click',
                        function () {

                            input.value =
                                item.name;

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
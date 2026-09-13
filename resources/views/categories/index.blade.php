@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Categories</h2>

    <a href="{{ route('categories.create') }}"
       class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>
        Add Category
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
            action="{{ route('categories.index') }}"
            method="GET"
            id="categorySearchForm"
            class="mb-4"
        >
            <div class="position-relative">

                <input
                    type="text"
                    name="search"
                    id="categorySearch"
                    class="form-control"
                    placeholder="Search by name or description..."
                    value="{{ $search ?? '' }}"
                    autocomplete="off"
                >

                <div
                    id="categorySuggestions"
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
                        <th>Category Name</th>
                        <th>Description</th>
                        <th style="width: 190px;">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($categories as $category)

                        <tr>
                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td class="fw-semibold">
                                {{ $category->name }}
                            </td>

                            <td>
                                {{ $category->description ?: '—' }}
                            </td>

                            <td>

                                <a
                                    href="{{ route('categories.edit', $category) }}"
                                    class="btn btn-warning btn-sm"
                                >
                                    <i class="bi bi-pencil-square"></i>
                                    Edit
                                </a>


                                <form
                                    action="{{ route('categories.destroy', $category) }}"
                                    method="POST"
                                    class="d-inline"

                                    data-confirm
                                    data-confirm-title="Delete Category?"
                                    data-confirm-message="Are you sure you want to delete {{ $category->name }}?"
                                    data-confirm-button="Delete Category"
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
                                colspan="4"
                                class="text-center text-muted py-4"
                            >
                                No categories found.
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
        document.getElementById('categorySearch');

    const suggestionBox =
        document.getElementById('categorySuggestions');

    const form =
        document.getElementById('categorySearchForm');

    const suggestions =
        @json($searchSuggestions ?? []);


    input.addEventListener('input', function () {

        const value =
            this.value.trim().toLowerCase();

        suggestionBox.innerHTML = '';


        if (value === '') {

            suggestionBox.style.display = 'none';

            if (
                new URLSearchParams(
                    window.location.search
                ).has('search')
            ) {
                window.location.href =
                    "{{ route('categories.index') }}";
            }

            return;
        }


        const filtered =
            suggestions.filter(function (item) {

                return (
                    item.name
                        .toLowerCase()
                        .includes(value)
                    ||
                    (item.description || '')
                        .toLowerCase()
                        .includes(value)
                );
            }).slice(0, 6);


        if (filtered.length === 0) {

            suggestionBox.style.display = 'none';

            return;
        }


        filtered.forEach(function (item) {

            const button =
                document.createElement('button');

            button.type = 'button';

            button.className =
                'list-group-item list-group-item-action';

            button.innerHTML =
                '<strong>' + item.name + '</strong>' +
                (
                    item.description
                        ? '<br><small class="text-muted">' +
                          item.description +
                          '</small>'
                        : ''
                );


            button.addEventListener(
                'click',
                function () {

                    input.value = item.name;

                    suggestionBox.style.display =
                        'none';

                    form.submit();
                }
            );


            suggestionBox.appendChild(button);

        });


        suggestionBox.style.display = 'block';

    });


    document.addEventListener(
        'click',
        function (event) {

            if (
                !input.contains(event.target) &&
                !suggestionBox.contains(event.target)
            ) {
                suggestionBox.style.display = 'none';
            }
        }
    );

});
</script>

@endsection
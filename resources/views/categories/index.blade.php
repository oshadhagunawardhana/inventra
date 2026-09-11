@extends('layouts.app')

@section('title', 'Categories')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Categories</h2>

    <a href="{{ route('categories.create') }}" class="btn btn-primary">
        + Add Category
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
                        <th>Category Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($categories as $category)

                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            <strong>{{ $category->name }}</strong>
                        </td>

                        <td>
                            {{ $category->description ?? '-' }}
                        </td>

                        <td>
                            <a
                                href="{{ route('categories.edit', $category) }}"
                                class="btn btn-sm btn-warning"
                            >
                                Edit
                            </a>

                            <form
                                action="{{ route('categories.destroy', $category) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Are you sure you want to delete this category?')"
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
                        <td colspan="4" class="text-center text-muted">
                            No categories found.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection
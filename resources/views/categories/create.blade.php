@extends('layouts.app')

@section('title', 'Add Category')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Add Category</h2>

    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
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

        <form action="{{ route('categories.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Category Name</label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control"
                    value="{{ old('name') }}"
                    placeholder="Enter category name"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>

                <textarea
                    id="description"
                    name="description"
                    class="form-control"
                    rows="4"
                    placeholder="Enter category description"
                >{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                Save Category
            </button>
        </form>

    </div>
</div>

@endsection
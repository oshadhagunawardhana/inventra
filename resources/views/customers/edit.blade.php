@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Customer</h2>

    <a href="{{ route('customers.index') }}" class="btn btn-secondary">
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

        <form action="{{ route('customers.update', $customer) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Customer Name</label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control"
                        value="{{ old('name', $customer->name) }}"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">Phone</label>

                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        class="form-control"
                        value="{{ old('phone', $customer->phone) }}"
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email', $customer->email) }}"
                    >
                </div>

                <div class="col-12 mb-3">
                    <label for="address" class="form-label">Address</label>

                    <textarea
                        id="address"
                        name="address"
                        class="form-control"
                        rows="3"
                    >{{ old('address', $customer->address) }}</textarea>
                </div>

            </div>

            <button type="submit" class="btn btn-warning">
                Update Customer
            </button>

        </form>

    </div>
</div>

@endsection
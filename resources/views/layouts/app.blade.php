<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Inventra')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f7fb;
        }

        .sidebar {
            min-height: 100vh;
            background: #212529;
        }

        .sidebar a {
            color: #ddd;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
            border-radius: 6px;
            margin-bottom: 5px;
        }

        .sidebar a:hover {
            background: #343a40;
            color: white;
        }

        .stat-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        }

      
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <div class="col-md-2 sidebar p-3">
           <div class="d-flex align-items-center mb-4">
    <img
        src="{{ asset('images/inventra-logo.png') }}"
        alt="Inventra Logo"
        width="46"
        height="46"
        class="me-2"
    >

    <div>
        <h4 class="text-white mb-0">Inventra</h4>
        <small class="text-secondary">Inventory & Sales System</small>
    </div>
</div>

            <a href="{{ route('dashboard') }}">📊 Dashboard</a>
            <a href="{{ route('categories.index') }}">📁 Categories</a>
            <a href="{{ route('products.index') }}">📦 Products</a>
            <a href="{{ route('sales.index') }}">🧾 Sales</a>
        </div>

        <div class="col-md-10 p-4">
            @yield('content')
        </div>

    </div>
</div>

</body>
</html>
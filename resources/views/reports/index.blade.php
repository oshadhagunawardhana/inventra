@extends('layouts.app')

@section('content')

<style>

    .report-card {
        background: #ffffff;

        border-radius: 18px;

        border-top: 5px solid #0d6efd;

        padding: 22px;

        box-shadow:
            0 6px 20px
            rgba(0, 0, 0, 0.05);
    }


    .report-summary-card {
        background: #ffffff;

        border-radius: 16px;

        padding: 20px;

        box-shadow:
            0 5px 18px
            rgba(0, 0, 0, 0.05);

        border:
            1px solid
            rgba(13, 110, 253, 0.08);
    }


    .report-print-header {
        display: none;
    }


    /* ===============================
       PRINT - A4
    =============================== */

    @media print {

        @page {
            size: A4 portrait;
            margin: 12mm;
        }


        body {
            background: white !important;

            color: #000 !important;

            margin: 0 !important;

            padding: 0 !important;
        }


        .sidebar,
        .no-print,
        .report-filter {
            display: none !important;
        }


        .main-content {
            margin: 0 !important;

            padding: 0 !important;

            width: 100% !important;

            min-height: auto !important;
        }


        .report-print-area {
            width: 100%;

            max-width: 186mm;

            margin: 0 auto;

            background: white;

            color: black;

            box-shadow: none !important;
        }


        .report-print-header {
            display: flex !important;

            justify-content: space-between;

            align-items: center;

            border-bottom:
                2px solid #000;

            padding-bottom: 10px;

            margin-bottom: 18px;
        }


        .report-print-brand {
            display: flex;

            align-items: center;

            gap: 10px;
        }


        .report-print-logo {
            width: 46px;

            height: 46px;

            object-fit: contain;
        }


        .report-print-title {
            font-size: 22px;

            font-weight: 700;

            margin: 0;
        }


        .report-print-subtitle {
            font-size: 11px;

            margin-top: 2px;
        }


        .report-print-meta {
            text-align: right;

            font-size: 9px;

            line-height: 1.5;
        }


        .report-summary-row {
            display: flex !important;

            gap: 10px;

            margin-bottom: 18px;
        }


        .report-summary-card {
            flex: 1;

            padding: 10px;

            border:
                1px solid #aaa;

            border-radius: 5px;

            box-shadow: none !important;

            background: white !important;
        }


        .report-summary-card small {
            font-size: 9px;
        }


        .report-summary-card h2 {
            font-size: 17px;

            margin: 3px 0 0;
        }


        .report-card,
        .card,
        .stat-card {
            border: none !important;

            box-shadow: none !important;

            background: white !important;

            padding: 0 !important;

            margin-bottom: 16px !important;

            transform: none !important;
        }


        .report-section-title {
            font-size: 14px;

            font-weight: 700;

            margin-bottom: 7px;

            padding-bottom: 4px;

            border-bottom:
                1px solid #999;
        }


        table {
            width: 100% !important;

            border-collapse: collapse !important;

            font-size: 9px !important;
        }


        table th {
            background:
                #efefef !important;

            color: #000 !important;

            border:
                1px solid #aaa !important;

            padding: 5px !important;

            font-weight: 700;
        }


        table td {
            border:
                1px solid #bbb !important;

            padding: 5px !important;

            color: #000 !important;
        }


        table a {
            color: #000 !important;

            text-decoration: none !important;
        }


        tr {
            page-break-inside: avoid;
        }


        .report-section {
            page-break-inside: avoid;

            margin-top: 18px;
        }


        .badge {
            border:
                1px solid #777 !important;

            color: #000 !important;

            background: transparent !important;
        }

    }

</style>



{{-- ===============================
     TOP BAR
================================ --}}

<div class="d-flex justify-content-between align-items-center mb-4 no-print">

    <div>

        <h2 class="mb-1">
            Reports
        </h2>

        <p class="text-muted mb-0">
            Sales and inventory reports
        </p>

    </div>


    <button
        type="button"
        onclick="window.print()"
        class="btn btn-primary"
    >
        <i class="bi bi-printer me-1"></i>
        Print Report
    </button>

</div>



{{-- ===============================
     FILTER
================================ --}}

<div class="report-card report-filter mb-4 no-print">

    <form
        action="{{ route('reports.index') }}"
        method="GET"
    >

        <div class="row g-3 align-items-end">


            <div class="col-md-4">

                <label class="form-label">
                    From Date
                </label>

                <input
                    type="date"
                    name="start_date"
                    class="form-control"
                    value="{{ $startDate }}"
                >

            </div>


            <div class="col-md-4">

                <label class="form-label">
                    To Date
                </label>

                <input
                    type="date"
                    name="end_date"
                    class="form-control"
                    value="{{ $endDate }}"
                >

            </div>


            <div class="col-md-4">

                <div class="d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-funnel me-1"></i>
                        Filter Report
                    </button>


                    <a
                        href="{{ route('reports.index') }}"
                        class="btn btn-secondary"
                    >
                        Reset
                    </a>

                </div>

            </div>


        </div>

    </form>

</div>



{{-- ===============================
     PRINT AREA
================================ --}}

<div class="report-print-area">


    {{-- PRINT HEADER --}}

    <div class="report-print-header">

        <div class="report-print-brand">

            <img
                src="{{ asset('images/inventra-logo.png') }}"
                alt="Inventra"
                class="report-print-logo"
            >


            <div>

                <h1 class="report-print-title">
                    Inventra
                </h1>

                <div class="report-print-subtitle">
                    Sales & Inventory Report
                </div>

            </div>

        </div>


        <div class="report-print-meta">

            <div>
                <strong>
                    Generated:
                </strong>

                {{
                    now('Asia/Colombo')
                        ->format('Y-m-d h:i A')
                }}
            </div>


            @if($startDate || $endDate)

                <div>

                    <strong>
                        Period:
                    </strong>

                    {{ $startDate ?: 'Beginning' }}

                    to

                    {{ $endDate ?: 'Today' }}

                </div>

            @else

                <div>
                    <strong>
                        Period:
                    </strong>

                    All Sales
                </div>

            @endif

        </div>

    </div>



    {{-- ===============================
         SUMMARY
    =============================== --}}

    <div class="row g-4 mb-4 report-summary-row">

        <div class="col-md-6">

            <div class="report-summary-card h-100">

                <small class="text-muted">
                    Total Sales
                </small>

                <h2 class="mb-0 mt-2">
                    {{ $totalSales }}
                </h2>

            </div>

        </div>


        <div class="col-md-6">

            <div class="report-summary-card h-100">

                <small class="text-muted">
                    Total Revenue
                </small>

                <h2 class="mb-0 mt-2">

                    Rs.
                    {{ number_format($totalRevenue, 2) }}

                </h2>

            </div>

        </div>

    </div>



    {{-- ===============================
         SALES REPORT
    =============================== --}}

    <div class="report-card mb-4 report-section">

        <h4 class="report-section-title mb-3">
            Sales Report
        </h4>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>
                        <th>#</th>

                        <th>
                            Invoice
                        </th>

                        <th>
                            Customer
                        </th>

                        <th>
                            Payment
                        </th>

                        <th>
                            Total
                        </th>

                        <th>
                            Date
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
                                >
                                    {{ $sale->invoice_number }}
                                </a>

                            </td>


                            <td>
                                {{
                                    $sale->customer_name
                                    ?? 'Walk-in Customer'
                                }}
                            </td>


                            <td>

                                {{
                                    ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $sale->payment_method
                                        )
                                    )
                                }}

                            </td>


                            <td>

                                Rs.
                                {{
                                    number_format(
                                        $sale->total_amount,
                                        2
                                    )
                                }}

                            </td>


                            <td>

                                {{
                                    $sale->created_at
                                        ->timezone('Asia/Colombo')
                                        ->format('Y-m-d h:i A')
                                }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center text-muted py-4"
                            >
                                No sales found for this period.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>



    {{-- ===============================
         BEST SELLING PRODUCTS
    =============================== --}}

    <div class="report-card mb-4 report-section">

        <h4 class="report-section-title mb-3">
            Best Selling Products
        </h4>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>
                        <th>#</th>

                        <th>
                            Product
                        </th>

                        <th>
                            Quantity Sold
                        </th>
                    </tr>

                </thead>


                <tbody>

                    @forelse($bestSellingProducts as $item)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>


                            <td>

                                {{
                                    $item->product->name
                                    ?? 'Product'
                                }}

                            </td>


                            <td>
                                {{ $item->total_quantity }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="3"
                                class="text-center text-muted py-4"
                            >
                                No product sales found.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>



    {{-- ===============================
         LOW STOCK PRODUCTS
    =============================== --}}

    <div class="report-card report-section">

        <h4 class="report-section-title mb-3">
            Low Stock Products
        </h4>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>
                        <th>#</th>

                        <th>
                            Product
                        </th>

                        <th>
                            SKU
                        </th>

                        <th>
                            Current Stock
                        </th>

                        <th>
                            Low Stock Level
                        </th>

                        <th>
                            Status
                        </th>
                    </tr>

                </thead>


                <tbody>

                    @forelse($lowStockProducts as $product)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>


                            <td>
                                {{ $product->name }}
                            </td>


                            <td>
                                {{ $product->sku }}
                            </td>


                            <td>
                                {{ $product->quantity }}
                            </td>


                            <td>
                                {{ $product->low_stock_level }}
                            </td>


                            <td>

                                @if($product->quantity == 0)

                                    <span class="badge bg-danger">
                                        Out of Stock
                                    </span>

                                @else

                                    <span
                                        class="badge"
                                        style="
                                            background: #f59e0b;
                                            color: white;
                                        "
                                    >
                                        Low Stock
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center text-muted py-4"
                            >
                                No low stock products.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


</div>

@endsection
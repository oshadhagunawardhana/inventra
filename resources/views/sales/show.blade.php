@extends('layouts.app')

@section('content')

<style>
    .invoice-card {
        background: #ffffff;
        border-radius: 18px;
        border-top: 5px solid #0d6efd;
        padding: 25px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
    }

    .invoice-logo {
        width: 60px;
        height: 60px;
        object-fit: contain;
    }

    .invoice-info-box {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 15px;
    }

    .invoice-table th {
        background: #f8f9fa;
    }

    .invoice-total {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 18px;
    }

    /* Receipt-only styles */
    .receipt-print {
        display: none;
    }


    /* ===============================
       PRINT - 80MM RECEIPT
    =============================== */

    @media print {

        @page {
            size: 80mm auto;
            margin: 0;
        }

        body {
            margin: 0 !important;
            padding: 0 !important;
            background: #ffffff !important;
        }

        .sidebar,
        .no-print,
        .screen-invoice {
            display: none !important;
        }

        .main-content {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            min-height: auto !important;
        }

        .receipt-print {
            display: block !important;

            width: 72mm;

            margin: 0 auto;

            padding: 4mm 3mm;

            background: #ffffff;

            color: #000000;

            font-family:
                "Courier New",
                monospace;

            font-size: 10px;

            box-shadow: none !important;

            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .receipt-header {
            text-align: center;
        }

        .receipt-logo {
            width: 38px;
            height: 38px;

            object-fit: contain;

            margin-bottom: 3px;
        }

        .receipt-business-name {
            font-size: 17px;

            font-weight: 700;

            margin: 0;
        }

        .receipt-subtitle {
            font-size: 9px;

            margin: 2px 0;
        }

        .receipt-line {
            border-top:
                1px dashed #000;

            margin: 7px 0;
        }

        .receipt-info {
            font-size: 9px;

            line-height: 1.55;
        }

        .receipt-table {
            width: 100%;

            border-collapse: collapse;

            font-size: 9px;
        }

        .receipt-table th {
            padding: 4px 1px;

            border-bottom:
                1px dashed #000;

            text-align: left;
        }

        .receipt-table td {
            padding: 4px 1px;

            vertical-align: top;
        }

        .receipt-table .qty {
            width: 10mm;

            text-align: center;
        }

        .receipt-table .amount {
            width: 20mm;

            text-align: right;
        }

        .receipt-total-row {
            display: flex;

            justify-content: space-between;

            font-size: 13px;

            font-weight: 700;

            margin-top: 6px;
        }

        .receipt-footer {
            text-align: center;

            font-size: 8px;

            line-height: 1.5;

            margin-top: 10px;
        }
    }
</style>


{{-- ===============================
     SCREEN VERSION
================================ --}}

<div class="screen-invoice">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="mb-1">
                Invoice
            </h2>

            <p class="text-muted mb-0">
                {{ $sale->invoice_number }}
            </p>
        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route('sales.index') }}"
                class="btn btn-secondary no-print"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>


            <button
                type="button"
                onclick="window.print()"
                class="btn btn-primary no-print"
            >
                <i class="bi bi-printer me-1"></i>
                Print Receipt
            </button>

        </div>

    </div>


    <div class="invoice-card">

        <div class="d-flex justify-content-between align-items-start mb-4">

            <div class="d-flex align-items-center">

                <img
                    src="{{ asset('images/inventra-logo.png') }}"
                    alt="Inventra"
                    class="invoice-logo me-3"
                >

                <div>
                    <h3 class="mb-1">
                        Inventra
                    </h3>

                    <div class="text-muted">
                        Inventory & Sales Management System
                    </div>
                </div>

            </div>


            <div class="text-end">

                <div class="fw-bold">
                    {{ $sale->invoice_number }}
                </div>

                <div class="text-muted">
                    {{
                        $sale->created_at
                            ->timezone('Asia/Colombo')
                            ->format('Y-m-d h:i A')
                    }}
                </div>

            </div>

        </div>


        <hr>


        <div class="row g-3 mb-4">

            <div class="col-md-6">

                <div class="invoice-info-box h-100">

                    <h6 class="fw-bold mb-3">
                        Customer Details
                    </h6>

                    <div>
                        <strong>Name:</strong>

                        {{
                            $sale->customer?->name
                            ?? $sale->customer_name
                            ?? 'Walk-in Customer'
                        }}
                    </div>


                    @if($sale->customer?->phone)
                        <div>
                            <strong>Phone:</strong>
                            {{ $sale->customer->phone }}
                        </div>
                    @endif


                    @if($sale->customer?->email)
                        <div>
                            <strong>Email:</strong>
                            {{ $sale->customer->email }}
                        </div>
                    @endif


                    @if($sale->customer?->address)
                        <div>
                            <strong>Address:</strong>
                            {{ $sale->customer->address }}
                        </div>
                    @endif

                </div>

            </div>


            <div class="col-md-6">

                <div class="invoice-info-box h-100">

                    <h6 class="fw-bold mb-3">
                        Payment Details
                    </h6>

                    <div>
                        <strong>Payment Method:</strong>

                        {{
                            ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $sale->payment_method
                                )
                            )
                        }}
                    </div>


                    <div>
                        <strong>Date:</strong>

                        {{
                            $sale->created_at
                                ->timezone('Asia/Colombo')
                                ->format('Y-m-d')
                        }}
                    </div>


                    <div>
                        <strong>Time:</strong>

                        {{
                            $sale->created_at
                                ->timezone('Asia/Colombo')
                                ->format('h:i A')
                        }}
                    </div>

                </div>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table invoice-table align-middle">

                <thead>

                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th class="text-end">
                            Subtotal
                        </th>
                    </tr>

                </thead>


                <tbody>

                    @foreach($sale->items as $item)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ $item->product->name ?? 'Product' }}
                            </td>

                            <td>
                                {{ $item->quantity }}
                            </td>

                            <td>
                                Rs.
                                {{ number_format($item->unit_price, 2) }}
                            </td>

                            <td class="text-end fw-semibold">
                                Rs.
                                {{ number_format($item->subtotal, 2) }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        <div class="row justify-content-end mt-4">

            <div class="col-md-5">

                <div class="invoice-total">

                    <div class="d-flex justify-content-between align-items-center">

                        <span class="fw-semibold">
                            Grand Total
                        </span>

                        <h3 class="mb-0">
                            Rs.
                            {{ number_format($sale->total_amount, 2) }}
                        </h3>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>



{{-- ===============================
     80MM PRINT RECEIPT
================================ --}}

<div class="receipt-print">

    <div class="receipt-header">

        <img
            src="{{ asset('images/inventra-logo.png') }}"
            alt="Inventra"
            class="receipt-logo"
        >

        <div class="receipt-business-name">
            INVENTRA
        </div>

        <div class="receipt-subtitle">
            Inventory & Sales System
        </div>

        <div class="receipt-subtitle">
            SALES RECEIPT
        </div>

    </div>


    <div class="receipt-line"></div>


    <div class="receipt-info">

        <div>
            <strong>Invoice:</strong>
            {{ $sale->invoice_number }}
        </div>

        <div>
            <strong>Date:</strong>
            {{
                $sale->created_at
                    ->timezone('Asia/Colombo')
                    ->format('Y-m-d h:i A')
            }}
        </div>

        <div>
            <strong>Customer:</strong>

            {{
                $sale->customer?->name
                ?? $sale->customer_name
                ?? 'Walk-in Customer'
            }}
        </div>

        <div>
            <strong>Payment:</strong>

            {{
                ucwords(
                    str_replace(
                        '_',
                        ' ',
                        $sale->payment_method
                    )
                )
            }}
        </div>

    </div>


    <div class="receipt-line"></div>


    <table class="receipt-table">

        <thead>

            <tr>
                <th>Item</th>

                <th class="qty">
                    Qty
                </th>

                <th class="amount">
                    Amount
                </th>
            </tr>

        </thead>


        <tbody>

            @foreach($sale->items as $item)

                <tr>

                    <td>
                        {{ $item->product->name ?? 'Product' }}
                    </td>

                    <td class="qty">
                        {{ $item->quantity }}
                    </td>

                    <td class="amount">
                        {{ number_format($item->subtotal, 2) }}
                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>


    <div class="receipt-line"></div>


    <div class="receipt-total-row">

        <span>
            TOTAL
        </span>

        <span>
            Rs.
            {{ number_format($sale->total_amount, 2) }}
        </span>

    </div>


    <div class="receipt-line"></div>


    <div class="receipt-footer">

        Thank you for your purchase!

        <br>

        Please come again.

        <br><br>

        Powered by Inventra

    </div>

</div>

@endsection
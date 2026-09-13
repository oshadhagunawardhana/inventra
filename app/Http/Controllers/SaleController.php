<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $sales = Sale::with(['customer', 'items.product'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('invoice_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('payment_method', 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'asc')
            ->get();

        $searchSuggestions = Sale::orderBy('id', 'asc')
            ->get()
            ->map(function ($sale) {
                return [
                    'invoice' => $sale->invoice_number,
                    'customer' => $sale->customer_name ?? 'Walk-in Customer',
                    'payment' => $sale->payment_method,
                ];
            });

        return view('sales.index', compact(
            'sales',
            'search',
            'searchSuggestions'
        ));
    }

    public function create()
    {
        $products = Product::where('quantity', '>', 0)
            ->orderBy('name')
            ->get();

        $customers = Customer::orderBy('name')
            ->get();

        return view('sales.create', compact(
            'products',
            'customers'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => [
                'nullable',
                'exists:customers,id',
            ],

            'payment_method' => [
    'required',
    'in:cash,card,bank_transfer',
],

            'product_id' => [
                'required',
                'array',
                'min:1',
            ],

            'product_id.*' => [
                'required',
                'exists:products,id',
            ],

            'quantity' => [
                'required',
                'array',
                'min:1',
            ],

            'quantity.*' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $lowStockProducts = [];
        $outOfStockProducts = [];

        DB::transaction(function () use (
            $validated,
            &$lowStockProducts,
            &$outOfStockProducts
        ) {
            $customer = null;

            if (!empty($validated['customer_id'])) {
                $customer = Customer::find(
                    $validated['customer_id']
                );
            }

            do {
                $invoiceNumber =
                    'INV-' .
                    now('Asia/Colombo')->format('YmdHis') .
                    '-' .
                    random_int(100, 999);
            } while (
                Sale::where(
                    'invoice_number',
                    $invoiceNumber
                )->exists()
            );

            $sale = Sale::create([
                'invoice_number' => $invoiceNumber,

                'customer_id' => $customer?->id,

                'customer_name' => $customer?->name,

                'total_amount' => 0,

                'payment_method' =>
                    $validated['payment_method'],
            ]);

            $totalAmount = 0;

            foreach (
                $validated['product_id']
                as $index => $productId
            ) {
                $quantity =
                    (int) $validated['quantity'][$index];

                $product = Product::where(
                    'id',
                    $productId
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($quantity > $product->quantity) {
                    throw ValidationException::withMessages([
                        'quantity' =>
                            "Not enough stock for {$product->name}. " .
                            "Available: {$product->quantity}",
                    ]);
                }

                $unitPrice =
                    (float) $product->selling_price;

                $subtotal =
                    $unitPrice * $quantity;

                $sale->items()->create([
                    'product_id' => $product->id,

                    'quantity' => $quantity,

                    'unit_price' => $unitPrice,

                    'subtotal' => $subtotal,
                ]);

                $product->decrement(
                    'quantity',
                    $quantity
                );

                $product->refresh();

                if ($product->quantity === 0) {
                    $outOfStockProducts[] =
                        $product->name;
                } elseif (
                    $product->quantity <=
                    $product->low_stock_level
                ) {
                    $lowStockProducts[] =
                        $product->name;
                }

                $totalAmount += $subtotal;
            }

            $sale->update([
                'total_amount' => $totalAmount,
            ]);
        });

        return redirect()
            ->route('sales.index')
            ->with(
                'success',
                'Sale created successfully!'
            )
            ->with(
                'low_stock_products',
                array_values(
                    array_unique($lowStockProducts)
                )
            )
            ->with(
                'out_of_stock_products',
                array_values(
                    array_unique($outOfStockProducts)
                )
            );
    }

    public function show(Sale $sale)
    {
        $sale->load([
            'customer',
            'items.product',
        ]);

        return view(
            'sales.show',
            compact('sale')
        );
    }

    public function destroy(Sale $sale)
    {
        DB::transaction(function () use ($sale) {
            $sale->load('items');

            foreach ($sale->items as $item) {
                $product = Product::where(
                    'id',
                    $item->product_id
                )
                    ->lockForUpdate()
                    ->first();

                if ($product) {
                    $product->increment(
                        'quantity',
                        $item->quantity
                    );
                }
            }

            $sale->delete();
        });

        return redirect()
            ->route('sales.index')
            ->with(
                'success',
                'Sale deleted successfully!'
            );
    }
}
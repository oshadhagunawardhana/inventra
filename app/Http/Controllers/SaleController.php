<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('items.product')
            ->orderBy('id', 'asc')
            ->get();

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::where('quantity', '>', 0)
            ->orderBy('name')
            ->get();

        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'customer_name' => ['nullable', 'string', 'max:255'],
        'payment_method' => ['required', 'in:cash,card,bank_transfer'],
        'product_id' => ['required', 'array', 'min:1'],
        'product_id.*' => ['required', 'exists:products,id'],
        'quantity' => ['required', 'array', 'min:1'],
        'quantity.*' => ['required', 'integer', 'min:1'],
    ]);

    if (count($validated['product_id']) !== count($validated['quantity'])) {
        return back()
            ->withErrors(['product_id' => 'Invalid sale items.'])
            ->withInput();
    }

    DB::transaction(function () use ($validated) {

        $sale = Sale::create([
            'invoice_number' => 'INV-' . now()->format('YmdHis') . '-' . random_int(100, 999),
            'customer_name' => $validated['customer_name'] ?? null,
            'total_amount' => 0,
            'payment_method' => $validated['payment_method'],
        ]);

        $totalAmount = 0;

        foreach ($validated['product_id'] as $index => $productId) {

            $quantity = $validated['quantity'][$index];

            $product = Product::where('id', $productId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($quantity > $product->quantity) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'quantity' => "Not enough stock for {$product->name}. Available: {$product->quantity}",
                ]);
            }

            $unitPrice = $product->selling_price;
            $subtotal = $unitPrice * $quantity;

            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
            ]);

            $product->decrement('quantity', $quantity);

            $totalAmount += $subtotal;
        }

        $sale->update([
            'total_amount' => $totalAmount,
        ]);
    });

    return redirect()
        ->route('sales.index')
        ->with('success', 'Sale recorded successfully.');
}

    public function show(Sale $sale)
{
    $sale->load('items.product');

    return view('sales.show', compact('sale'));
}

    public function destroy(Sale $sale)
    {
        DB::transaction(function () use ($sale) {

            $sale->load('items');

            foreach ($sale->items as $item) {
                Product::where('id', $item->product_id)
                    ->increment('quantity', $item->quantity);
            }

            $sale->delete();
        });

        return redirect()
            ->route('sales.index')
            ->with('success', 'Sale deleted and stock restored.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Topping;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PosController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->where('is_active', true)
            ->with('variants')
            ->orderBy('name')
            ->get();

        $toppings = Topping::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('pos.index', compact('products', 'toppings'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'items' => json_decode((string) $request->input('items'), true) ?? [],
        ]);

        $validated = $request->validate([
            'customer_name' => ['nullable', 'string', 'max:255'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.toppings' => ['nullable', 'array'],
            'items.*.toppings.*.topping_id' => ['required', 'integer', 'exists:toppings,id'],
            'items.*.toppings.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $order = DB::transaction(function () use ($validated, $request) {
            $variants = ProductVariant::whereIn(
                'id',
                collect($validated['items'])->pluck('product_variant_id')
            )->get()->keyBy('id');

            $toppingIds = collect($validated['items'])
                ->flatMap(fn ($item) => collect($item['toppings'] ?? [])->pluck('topping_id'));
            $toppings = Topping::whereIn('id', $toppingIds)->get()->keyBy('id');

            $order = Order::create([
                'user_id' => $request->user()->id,
                'customer_name' => $validated['customer_name'] ?? null,
                'total' => 0,
                'status' => 'unpaid',
            ]);

            $total = 0;

            foreach ($validated['items'] as $itemData) {
                $variant = $variants->get($itemData['product_variant_id']);
                $quantity = $itemData['quantity'];
                $subtotal = $variant->price * $quantity;

                $orderItem = $order->items()->create([
                    'product_variant_id' => $variant->id,
                    'quantity' => $quantity,
                    'unit_price' => $variant->price,
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;

                foreach ($itemData['toppings'] ?? [] as $toppingData) {
                    $topping = $toppings->get($toppingData['topping_id']);
                    $toppingQuantity = $toppingData['quantity'];
                    $toppingSubtotal = $topping->price * $toppingQuantity * $quantity;

                    $orderItem->toppings()->create([
                        'topping_id' => $topping->id,
                        'quantity' => $toppingQuantity * $quantity,
                        'unit_price' => $topping->price,
                        'subtotal' => $toppingSubtotal,
                    ]);

                    $total += $toppingSubtotal;
                }
            }

            $order->update(['total' => $total]);

            return $order;
        });

        return redirect()->route('pos.index')->with('success', "Order #{$order->id} saved. Mark it as paid from History once the customer pays.");
    }

    public function history(Request $request): View
    {
        $orders = Order::with('items.productVariant.product')
            ->where('user_id', $request->user()->id)
            ->whereDate('created_at', today())
            ->latest()
            ->get();

        return view('pos.history', compact('orders'));
    }

    public function pay(Request $request, Order $order): RedirectResponse
    {
        if ($order->status === 'paid') {
            return redirect()->route('pos.history')->with('error', "Order #{$order->id} is already paid.");
        }

        $validated = $request->validate([
            'paid_amount' => ['required', 'numeric', 'min:'.$order->total],
        ]);

        $order->update([
            'paid_amount' => $validated['paid_amount'],
            'change_amount' => $validated['paid_amount'] - $order->total,
            'status' => 'paid',
        ]);

        return redirect()->route('pos.history')->with('success', "Order #{$order->id} marked as paid. Change: ".number_format($order->change_amount, 0, ',', '.'));
    }
}

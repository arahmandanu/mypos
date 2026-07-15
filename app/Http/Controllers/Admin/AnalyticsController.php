<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function sales(Request $request): View
    {
        $from = $request->date('from') ?? Carbon::now()->startOfMonth();
        $to = $request->date('to') ?? Carbon::now()->endOfDay();

        $orders = Order::with('user')
            ->whereBetween('created_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->latest()
            ->get();

        $totalRevenue = $orders->sum('total');
        $orderCount = $orders->count();
        $averageOrderValue = $orderCount > 0 ? $totalRevenue / $orderCount : 0;

        return view('admin.analytics.sales', compact(
            'orders', 'totalRevenue', 'orderCount', 'averageOrderValue', 'from', 'to'
        ));
    }

    public function topProducts(Request $request): View
    {
        $from = $request->date('from') ?? Carbon::now()->startOfMonth();
        $to = $request->date('to') ?? Carbon::now()->endOfDay();

        $topProducts = OrderItem::query()
            ->join('product_variants', 'product_variants.id', '=', 'order_items.product_variant_id')
            ->join('products', 'products.id', '=', 'product_variants.product_id')
            ->whereBetween('order_items.created_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->selectRaw('products.id, products.name, SUM(order_items.quantity) as total_quantity, SUM(order_items.subtotal) as total_revenue')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit(20)
            ->get();

        return view('admin.analytics.top-products', compact('topProducts', 'from', 'to'));
    }
}

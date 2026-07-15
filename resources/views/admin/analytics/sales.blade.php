<x-admin-layout :title="'Sales Analytics - MyPOS Admin'">

    <form method="GET" class="mb-6 flex flex-wrap items-end gap-3 rounded-xl bg-white p-4 shadow-sm">
        <div>
            <label class="block text-sm font-medium text-gray-700">From</label>
            <input type="date" name="from" value="{{ $from->toDateString() }}"
                class="mt-1 rounded-md border border-gray-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">To</label>
            <input type="date" name="to" value="{{ $to->toDateString() }}"
                class="mt-1 rounded-md border border-gray-300 px-3 py-2 text-sm">
        </div>
        <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            Filter
        </button>
    </form>

    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <p class="text-sm text-gray-500">Total Revenue</p>
            <p class="mt-1 text-2xl font-semibold text-gray-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <p class="text-sm text-gray-500">Orders</p>
            <p class="mt-1 text-2xl font-semibold text-gray-800">{{ $orderCount }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <p class="text-sm text-gray-500">Average Order Value</p>
            <p class="mt-1 text-2xl font-semibold text-gray-800">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Order #</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Cashier</th>
                    <th class="px-4 py-3">Customer</th>
                    <th class="px-4 py-3">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($orders as $order)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-800">#{{ $order->id }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $order->user->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $order->customer_name ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-500">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-400">No orders in this range.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>

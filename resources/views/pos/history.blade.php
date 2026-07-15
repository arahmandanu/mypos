<x-layout :title="'Today\'s History - MyPOS'">
    <div class="mx-auto max-w-4xl px-4 py-6" x-data="{ selectedOrderId: null }">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Today's History</h1>
            <a href="{{ route('pos.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                ← Back to POS
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-md bg-green-50 p-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-md bg-red-50 p-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-xl bg-white shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Order #</th>
                        <th class="px-4 py-3">Time</th>
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">Items</th>
                        <th class="px-4 py-3">Total</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($orders as $order)
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-800">#{{ $order->id }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $order->created_at->format('H:i') }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $order->customer_name ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-500">
                                {{ $order->items->map(fn ($item) => "{$item->quantity}x {$item->productVariant->product->name}")->implode(', ') }}
                            </td>
                            <td class="px-4 py-3 text-gray-500">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                @if ($order->status === 'paid')
                                    <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs text-green-700">Paid</span>
                                @else
                                    <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs text-amber-700">Unpaid</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if ($order->status === 'unpaid')
                                    <button type="button" @click="selectedOrderId = {{ $order->id }}"
                                        class="rounded-md bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-emerald-700">
                                        Checkout
                                    </button>
                                @else
                                    <span class="text-xs text-gray-400">
                                        Paid Rp {{ number_format($order->paid_amount, 0, ',', '.') }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-gray-400">No orders yet today.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @foreach ($orders->where('status', 'unpaid') as $order)
            <div x-show="selectedOrderId === {{ $order->id }}" x-cloak
                class="fixed inset-0 flex items-center justify-center bg-black/40 px-4">
                <div class="w-full max-w-sm rounded-xl bg-white p-6 shadow-lg" x-data="{ amount: {{ $order->total }} }">
                    <h2 class="mb-1 text-lg font-semibold text-gray-800">Checkout Order #{{ $order->id }}</h2>
                    <p class="mb-4 text-sm text-gray-500">{{ $order->customer_name ?? 'Walk-in customer' }}</p>

                    <div class="mb-4 flex justify-between text-sm">
                        <span class="text-gray-500">Total</span>
                        <span class="font-medium text-gray-800">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>

                    <form method="POST" action="{{ route('pos.orders.pay', $order) }}">
                        @csrf
                        @method('PUT')

                        <label class="mb-1 block text-sm text-gray-600">Amount received</label>
                        <input type="number" name="paid_amount" x-model.number="amount" step="0.01"
                            min="{{ $order->total }}" required
                            class="mb-3 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">

                        <div class="mb-4 flex justify-between text-sm">
                            <span class="text-gray-500">Change</span>
                            <span class="font-medium text-gray-800"
                                x-text="`Rp ${Math.max(0, amount - {{ $order->total }}).toLocaleString('id-ID')}`"></span>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit"
                                class="flex-1 rounded-md bg-emerald-600 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                                Confirm Payment
                            </button>
                            <button type="button" @click="selectedOrderId = null"
                                class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</x-layout>

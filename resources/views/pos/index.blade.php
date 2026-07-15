<x-layout :title="'Cashier POS - MyPOS'">
    <div class="mx-auto max-w-6xl px-4 py-6" x-data="posCart()">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Cashier POS</h1>
            <div class="flex items-center gap-4">
                <a href="{{ route('pos.history') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                    History
                </a>
                @can('manage products')
                    <a href="{{ route('admin.products.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                        Settings
                    </a>
                @endcan
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm text-gray-500 hover:text-gray-700">Logout ({{ auth()->user()->name }})</button>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-md bg-green-50 p-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-md bg-red-50 p-3 text-sm text-red-700">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-4">
                @foreach ($products as $product)
                    <div class="flex gap-4 rounded-xl bg-white p-4 shadow-sm">
                        <img src="{{ $product->image_url ?? asset('images/product-placeholder.svg') }}"
                            alt="{{ $product->name }}" class="h-16 w-16 flex-shrink-0 rounded-lg object-cover bg-gray-100">

                        <div class="flex-1">
                            <h2 class="mb-3 font-medium text-gray-800">{{ $product->name }}</h2>
                            <div class="flex flex-wrap gap-2">
                            @foreach ($product->variants as $variant)
                                <button type="button"
                                    @click="selectVariant({{ $variant->id }}, '{{ $product->name }}', '{{ $variant->size }}', {{ $variant->price }})"
                                    class="rounded-md border px-3 py-2 text-sm hover:border-indigo-500 hover:bg-indigo-50"
                                    :class="draft.product_variant_id === {{ $variant->id }} ? 'border-indigo-500 bg-indigo-50' : 'border-gray-300'">
                                    {{ ucfirst($variant->size) }} — Rp {{ number_format($variant->price, 0, ',', '.') }}
                                </button>
                            @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="space-y-4">
                <div class="rounded-xl bg-white p-4 shadow-sm" x-show="draft.product_variant_id">
                    <h3 class="mb-2 font-medium text-gray-800">
                        Adding: <span x-text="draft.name"></span> (<span x-text="draft.size"></span>)
                    </h3>

                    <label class="mb-2 block text-sm text-gray-600">Quantity</label>
                    <input type="number" min="1" x-model.number="draft.quantity"
                        class="mb-3 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">

                    <p class="mb-2 text-sm font-medium text-gray-600">Toppings</p>
                    <div class="mb-3 space-y-1">
                        @foreach ($toppings as $topping)
                            <label class="flex items-center justify-between text-sm">
                                <span class="flex items-center gap-2">
                                    <input type="checkbox" :value="{{ $topping->id }}"
                                        :checked="draft.toppings.some(t => t.topping_id === {{ $topping->id }})"
                                        @change="toggleTopping({{ $topping->id }}, '{{ $topping->name }}', {{ $topping->price }})"
                                        class="rounded border-gray-300">
                                    {{ $topping->name }}
                                </span>
                                <span class="text-gray-400">Rp {{ number_format($topping->price, 0, ',', '.') }}</span>
                            </label>
                        @endforeach
                    </div>

                    <button type="button" @click="addToCart()"
                        class="w-full rounded-md bg-indigo-600 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        Add to Cart
                    </button>
                </div>

                <div class="rounded-xl bg-white p-4 shadow-sm">
                    <h3 class="mb-3 font-medium text-gray-800">Cart</h3>

                    <template x-if="cart.length === 0">
                        <p class="text-sm text-gray-400">No items yet.</p>
                    </template>

                    <ul class="mb-3 space-y-2">
                        <template x-for="(line, index) in cart" :key="index">
                            <li class="border-b pb-2 text-sm">
                                <div class="flex items-center justify-between">
                                    <span x-text="`${line.quantity}x ${line.name} (${line.size})`"></span>
                                    <button type="button" class="text-red-500" @click="removeLine(index)">✕</button>
                                </div>
                                <template x-for="t in line.toppings" :key="t.topping_id">
                                    <div class="pl-4 text-xs text-gray-500" x-text="`+ ${t.name}`"></div>
                                </template>
                                <div class="pl-4 text-xs font-medium text-gray-600" x-text="`Rp ${lineTotal(line).toLocaleString('id-ID')}`"></div>
                            </li>
                        </template>
                    </ul>

                    <div class="mb-3 flex justify-between font-medium text-gray-800">
                        <span>Total</span>
                        <span x-text="`Rp ${cartTotal().toLocaleString('id-ID')}`"></span>
                    </div>

                    <form method="POST" action="{{ route('pos.orders.store') }}" @submit="prepareSubmit">
                        @csrf
                        <label class="mb-1 block text-sm text-gray-600">Customer name (optional)</label>
                        <input type="text" name="customer_name" x-model="customerName"
                            class="mb-3 w-full rounded-md border border-gray-300 px-3 py-2 text-sm" placeholder="Walk-in customer">

                        <p class="mb-3 text-xs text-gray-400">
                            Payment isn't collected yet — mark this order as paid from History once the customer pays.
                        </p>

                        <input type="hidden" name="items" :value="JSON.stringify(itemsPayload())">

                        <button type="submit" :disabled="cart.length === 0"
                            class="w-full rounded-md bg-emerald-600 py-2 text-sm font-medium text-white hover:bg-emerald-700 disabled:opacity-50">
                            Save Order
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function posCart() {
            return {
                draft: { product_variant_id: null, name: '', size: '', price: 0, quantity: 1, toppings: [] },
                cart: [],
                customerName: '',

                selectVariant(id, name, size, price) {
                    this.draft = { product_variant_id: id, name, size, price, quantity: 1, toppings: [] };
                },

                toggleTopping(id, name, price) {
                    const existing = this.draft.toppings.findIndex(t => t.topping_id === id);
                    if (existing >= 0) {
                        this.draft.toppings.splice(existing, 1);
                    } else {
                        this.draft.toppings.push({ topping_id: id, name, price, quantity: 1 });
                    }
                },

                addToCart() {
                    if (!this.draft.product_variant_id) return;
                    this.cart.push(JSON.parse(JSON.stringify(this.draft)));
                    this.draft = { product_variant_id: null, name: '', size: '', price: 0, quantity: 1, toppings: [] };
                },

                removeLine(index) {
                    this.cart.splice(index, 1);
                },

                lineTotal(line) {
                    const toppingsTotal = line.toppings.reduce((sum, t) => sum + t.price * t.quantity, 0);
                    return (line.price + toppingsTotal) * line.quantity;
                },

                cartTotal() {
                    return this.cart.reduce((sum, line) => sum + this.lineTotal(line), 0);
                },

                itemsPayload() {
                    return this.cart.map(line => ({
                        product_variant_id: line.product_variant_id,
                        quantity: line.quantity,
                        toppings: line.toppings.map(t => ({ topping_id: t.topping_id, quantity: t.quantity })),
                    }));
                },

                prepareSubmit(event) {
                    if (this.cart.length === 0) {
                        event.preventDefault();
                    }
                },
            };
        }
    </script>
</x-layout>

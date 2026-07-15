<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin - MyPOS' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="flex min-h-screen">
        @php
            $navGroups = [
                'Master Product' => [
                    ['route' => 'admin.products.index', 'match' => 'admin.products.*', 'label' => 'Product', 'icon' => 'cube'],
                    ['route' => 'admin.variants.index', 'match' => 'admin.variants.*', 'label' => 'Variant', 'icon' => 'layers'],
                    ['route' => 'admin.toppings.index', 'match' => 'admin.toppings.*', 'label' => 'Topping', 'icon' => 'sparkles'],
                ],
                'Master User' => [
                    ['route' => 'admin.users.index', 'match' => 'admin.users.*', 'label' => 'User', 'icon' => 'users'],
                ],
                'Analytic' => [
                    ['route' => 'admin.analytics.sales', 'match' => 'admin.analytics.sales', 'label' => 'Sales', 'icon' => 'chart'],
                    ['route' => 'admin.analytics.top-products', 'match' => 'admin.analytics.top-products', 'label' => 'Top Product Sold', 'icon' => 'trophy'],
                ],
            ];

            $icons = [
                'cube' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />',
                'layers' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l-4.5 2.25 9.75 4.875 9.75-4.875-4.5-2.25m-15 6l9.75 4.875L21 13.5M6.75 4.5l-4.5 2.25 9.75 4.875 9.75-4.875-4.5-2.25-5.25 2.625-5.25-2.625Z" />',
                'sparkles' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z" />',
                'users' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />',
                'chart' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />',
                'trophy' => '<path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.25 9.71 2.25 12 2.25c2.291 0 4.545 0 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-2.472 0" />',
            ];
        @endphp

        <aside class="w-64 flex-shrink-0 bg-slate-900 text-slate-300">
            <div class="flex items-center gap-2 px-5 py-5">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600 text-sm font-bold text-white">M</div>
                <span class="text-lg font-semibold text-white">MyPOS Admin</span>
            </div>

            <nav class="px-3 pb-6 text-sm">
                <a href="{{ route('pos.index') }}"
                    class="mb-4 flex items-center gap-2 rounded-lg px-3 py-2 text-slate-400 hover:bg-slate-800 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to Cashier
                </a>

                @foreach ($navGroups as $group => $items)
                    <div class="mt-5 first:mt-0">
                        <p class="px-3 pb-2 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ $group }}</p>
                        <div class="space-y-0.5">
                            @foreach ($items as $item)
                                @php $active = request()->routeIs($item['match']); @endphp
                                <a href="{{ route($item['route']) }}"
                                    class="flex items-center gap-2.5 rounded-lg border-l-2 px-3 py-2 transition-colors
                                        {{ $active ? 'border-indigo-500 bg-slate-800 text-white' : 'border-transparent text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 flex-shrink-0">
                                        {!! $icons[$item['icon']] !!}
                                    </svg>
                                    {{ $item['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </nav>
        </aside>

        <div class="flex flex-1 flex-col">
            <header class="flex items-center justify-between border-b border-gray-200 bg-white px-8 py-4">
                <h1 class="text-lg font-semibold text-gray-800">{{ preg_replace('/\s*-\s*MyPOS Admin$/', '', $title ?? 'Admin') }}</h1>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-500">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="rounded-md border border-gray-300 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50">
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            <main class="flex-1 px-8 py-6">
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

                @if ($errors->any())
                    <div class="mb-4 rounded-md bg-red-50 p-3 text-sm text-red-700">
                        <ul class="list-inside list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>

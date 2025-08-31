<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Invent-App</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @if (Route::has('login'))
            <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
                @else
                    <a href="{{ route('login') }}"
                        class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log
                        in</a>
                @endauth
            </div>
        @endif

        <main class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Invent App</h1>
                    <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">Latest products from the inventory</p>
                </div>

                @if ($products->isEmpty())
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 text-center">
                        <p class="text-gray-600 dark:text-gray-300">No products available yet.</p>
                        <p class="mt-4"><a href="{{ route('products.index') }}"
                                class="text-blue-600 hover:underline">Go to products</a></p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach ($products as $p)
                            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden flex flex-col">
                                @if ($p->product_image)
                                    <img src="{{ asset('storage/' . $p->product_image) }}" alt="{{ $p->product_name }}"
                                        class="w-full h-40 object-cover">
                                @else
                                    <div
                                        class="w-full h-40 bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500">
                                        No image
                                    </div>
                                @endif
                                <div class="p-4 flex-1 flex flex-col">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $p->product_name }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-300 mt-1">Code:
                                        {{ $p->product_code }}</p>
                                    <div class="mt-2 flex items-center justify-between">
                                        <span
                                            class="text-sm text-gray-600 dark:text-gray-300">{{ $p->category?->category_name ?? 'Uncategorized' }}</span>
                                        <span class="text-sm text-gray-600 dark:text-gray-300">Stock:
                                            {{ $p->product_stock ?? '—' }}</span>
                                    </div>
                                    <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">{{ $p->location }}</p>
                                    <div class="mt-4">
                                        <a href="{{ route('products.index') }}"
                                            class="inline-block px-3 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">View
                                            products</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </main>
    </div>
</body>

</html>

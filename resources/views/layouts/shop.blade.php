<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nexora')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900">
    <header class="bg-white border-b sticky top-0 z-10">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
           <div class="flex items-center justify-between w-full">
    <a href="{{ route('shop.index') }}" class="text-xl font-bold">Nexora</a>

    <div class="flex items-center gap-6">
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="text-sm text-gray-600 hover:text-black flex items-center gap-1">
                {{ LaravelLocalization::getCurrentLocaleNative() }}
                <span class="text-xs">▾</span>
            </button>
            <div x-show="open" @click.outside="open = false" x-cloak
                 class="absolute right-0 mt-2 bg-white border rounded-lg shadow-lg py-1 w-32 z-20">
                @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    <a href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ LaravelLocalization::getCurrentLocale() === $localeCode ? 'font-semibold' : '' }}">
                        {{ $properties['native'] }}
                    </a>
                @endforeach
            </div>
        </div>

        <a href="{{ route('cart.show') }}" class="text-sm text-gray-600 hover:text-black relative">
            {{ __('shop.my_cart') }}
            @if ($cartCount > 0)
                <span class="absolute -top-2 -right-3 bg-black text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">
                    {{ $cartCount }}
                </span>
            @endif
        </a>
    </div>
</div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-8">
        @if (session('success'))
            <div class="mb-4 bg-green-100 text-green-700 text-sm px-4 py-2 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 bg-red-100 text-red-700 text-sm px-4 py-2 rounded">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
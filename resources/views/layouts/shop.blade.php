<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nexora')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAFAF8] text-[#14171F]">
    <header class="bg-[#FAFAF8]/90 backdrop-blur border-b border-[#E4E2DA] sticky top-0 z-30">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="font-display text-xl font-semibold tracking-tight">
                Nexora
            </a>

            <nav class="hidden md:flex items-center gap-8 text-sm font-medium">
                <a href="{{ route('home') }}" class="hover:text-[#FF6A1A] transition-colors">{{ __('layout.nav_home') }}</a>
                <a href="{{ route('shop.catalog') }}" class="hover:text-[#FF6A1A] transition-colors">{{ __('layout.nav_catalog') }}</a>
            </nav>

            <div class="flex items-center gap-5">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="text-sm text-gray-600 hover:text-[#14171F] flex items-center gap-1">
                        {{ LaravelLocalization::getCurrentLocaleNative() }}
                        <span class="text-xs">▾</span>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-cloak
                         class="absolute right-0 mt-2 bg-white border border-[#E4E2DA] rounded-lg shadow-lg py-1 w-32 z-20">
                        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <a href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 {{ LaravelLocalization::getCurrentLocale() === $localeCode ? 'font-semibold' : '' }}">
                                {{ $properties['native'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('cart.show') }}" class="text-sm font-medium hover:text-[#FF6A1A] transition-colors relative">
                    {{ __('shop.my_cart') }}
                    @if ($cartCount > 0)
                        <span class="absolute -top-2 -right-3 bg-[#FF6A1A] text-white text-xs w-5 h-5 flex items-center justify-center rounded-full font-mono-data">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
            </div>
        </div>
    </header>

    @if (session('success'))
        <div class="max-w-6xl mx-auto px-4 pt-4">
            <div class="bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-2 rounded-lg">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="max-w-6xl mx-auto px-4 pt-4">
            <div class="bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-2 rounded-lg">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    <footer class="bg-[#14171F] text-[#F4F3EF] mt-24">
        <div class="max-w-6xl mx-auto px-4 py-16 grid grid-cols-1 md:grid-cols-4 gap-10">
            <div>
                <p class="font-display text-lg font-semibold mb-3">Nexora</p>
                <p class="text-sm text-gray-400 leading-relaxed">
                    {{ __('layout.footer_tagline') }}
                </p>
            </div>

            <div>
                <p class="text-sm font-medium text-gray-300 mb-3">{{ __('layout.footer_shop') }}</p>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">{{ __('layout.nav_home') }}</a></li>
                    <li><a href="{{ route('shop.catalog') }}" class="hover:text-white transition-colors">{{ __('layout.nav_catalog') }}</a></li>
                    <li><a href="{{ route('cart.show') }}" class="hover:text-white transition-colors">{{ __('shop.my_cart') }}</a></li>
                </ul>
            </div>

            <div>
                <p class="text-sm font-medium text-gray-300 mb-3">{{ __('layout.footer_info') }}</p>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="{{ route('legal.notice') }}" class="hover:text-white transition-colors">{{ __('layout.legal_notice') }}</a></li>
                    <li><a href="{{ route('legal.terms') }}" class="hover:text-white transition-colors">{{ __('layout.legal_terms') }}</a></li>
                    <li><a href="{{ route('legal.privacy') }}" class="hover:text-white transition-colors">{{ __('layout.legal_privacy') }}</a></li>
                </ul>
            </div>

            <div>
                <p class="text-sm font-medium text-gray-300 mb-3">{{ __('layout.footer_language') }}</p>
                <ul class="space-y-2 text-sm text-gray-400">
                    @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <li>
                            <a href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                               class="hover:text-white transition-colors {{ LaravelLocalization::getCurrentLocale() === $localeCode ? 'text-white' : '' }}">
                                {{ $properties['native'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10">
            <p class="max-w-6xl mx-auto px-4 py-6 text-xs text-gray-500">
                © {{ date('Y') }} Nexora. {{ __('layout.footer_rights') }}
            </p>
        </div>
    </footer>
</body>
</html>
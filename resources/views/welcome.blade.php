@extends('layouts.shop')

@section('title', 'Nexora — Électronique sélectionnée')

@section('content')
    {{-- Héros --}}
    <section class="max-w-6xl mx-auto px-4 pt-16 pb-20 grid grid-cols-1 md:grid-cols-5 gap-12 items-center">
        <div class="md:col-span-3 animate-hero">
            <h1 class="font-display text-4xl md:text-5xl font-semibold leading-[1.1] tracking-tight">
                {!! __('home.hero_title') !!}
            </h1>
            <p class="mt-6 text-gray-600 text-lg leading-relaxed max-w-md">
                {{ __('home.hero_subtitle') }}
            </p>
            <div class="mt-8 flex flex-wrap items-center gap-4">
                <a href="{{ route('shop.catalog') }}"
                   class="bg-[#14171F] text-white px-6 py-3 rounded-lg font-medium hover:bg-[#FF6A1A] transition-colors">
                    {{ __('home.explore_catalog') }}
                </a>
                <span class="text-sm text-gray-500 font-mono-data">
                    {{ trans_choice('home.products_available|home.product_available', $productCount, ['count' => $productCount]) }}
                </span>
            </div>
        </div>

        <div class="md:col-span-2 animate-hero">
            <div class="relative bg-white border border-[#E4E2DA] rounded-2xl p-8 bracket-corner">
                <p class="font-mono-data text-xs text-gray-400 mb-6">{{ __('home.shop_preview') }}</p>

                <div class="space-y-5">
                    <div class="flex items-baseline justify-between">
                        <span class="text-sm text-gray-500">{{ __('home.active_catalog') }}</span>
                        <span class="font-mono-data text-lg font-medium">{{ $productCount }}</span>
                    </div>
                    <div class="flex items-baseline justify-between">
                        <span class="text-sm text-gray-500">{{ __('home.categories') }}</span>
                        <span class="font-mono-data text-lg font-medium">{{ $categories->count() }}</span>
                    </div>
                    @if ($reviewsCount > 0)
                        <div class="flex items-baseline justify-between">
                            <span class="text-sm text-gray-500">{{ __('home.average_rating') }}</span>
                            <span class="font-mono-data text-lg font-medium">{{ number_format($averageRating, 1) }} / 5</span>
                        </div>
                    @endif
                    <div class="flex items-baseline justify-between pt-4 border-t border-[#E4E2DA]">
                        <span class="text-sm text-gray-500">{{ __('home.payment') }}</span>
                        <span class="text-sm font-medium text-[#0F8B8D]">{{ __('home.secured_by_stripe') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Catégories --}}
    @if ($categories->isNotEmpty())
        <section class="max-w-6xl mx-auto px-4 py-16">
            <h2 class="font-display text-2xl font-semibold mb-8">{{ __('home.explore_by_category') }}</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                @foreach ($categories as $category)
                    <a href="{{ route('shop.catalog', ['category' => $category->slug]) }}"
                       class="group relative bg-white border border-[#E4E2DA] rounded-xl p-6 bracket-corner opacity-0 hover:border-[#FF6A1A]/40 transition-colors [animation:fade-up_0.5s_ease_forwards]"
                       style="animation-delay: {{ $loop->index * 0.06 }}s">
                        <p class="font-medium">{{ $category->name }}</p>
                        <p class="font-mono-data text-xs text-gray-400 mt-2">
                            {{ trans_choice('home.products_count|home.product_count', $category->products_count, ['count' => $category->products_count]) }}
                        </p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Produits en vedette --}}
    @if ($featuredProducts->isNotEmpty())
        <section class="max-w-6xl mx-auto px-4 py-16">
            <div class="flex items-end justify-between mb-8">
                <h2 class="font-display text-2xl font-semibold">{{ __('home.recently_added') }}</h2>
                <a href="{{ route('shop.catalog') }}" class="text-sm font-medium text-[#0F8B8D] hover:underline">
                    {{ __('home.view_full_catalog') }}
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($featuredProducts as $product)
                    <a href="{{ route('shop.show', $product) }}"
                       class="block bg-white rounded-xl border border-[#E4E2DA] p-4 hover:shadow-lg hover:-translate-y-1 transition-all">
                        <div class="aspect-square bg-gray-50 rounded-lg mb-3 flex items-center justify-center text-gray-300 text-sm">
                            {{ __('shop.image_placeholder') }}
                        </div>
                        <h3 class="font-medium text-sm">{{ $product->translated_name }}</h3>
                        <p class="font-mono-data text-base font-medium mt-1">{{ number_format($product->price, 2) }} €</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Bandeau confiance --}}
    <section class="bg-[#14171F] text-white mt-8">
        <div class="max-w-6xl mx-auto px-4 py-14 grid grid-cols-1 sm:grid-cols-3 gap-8 text-center sm:text-left">
            <div>
                <p class="font-display text-lg font-semibold">{{ __('home.secure_payment_title') }}</p>
                <p class="text-sm text-gray-400 mt-1">{{ __('home.secure_payment_text') }}</p>
            </div>
            <div>
                <p class="font-display text-lg font-semibold">{{ __('home.tracked_delivery_title') }}</p>
                <p class="text-sm text-gray-400 mt-1">{{ __('home.tracked_delivery_text') }}</p>
            </div>
            <div>
                <p class="font-display text-lg font-semibold">{{ __('home.returns_title') }}</p>
                <p class="text-sm text-gray-400 mt-1">{{ __('home.returns_text') }}</p>
            </div>
        </div>
    </section>

    {{-- CTA finale --}}
    <section class="max-w-6xl mx-auto px-4 py-20 text-center">
        <h2 class="font-display text-3xl font-semibold">{{ __('home.cta_title') }}</h2>
        <a href="{{ route('shop.catalog') }}"
           class="inline-block mt-6 bg-[#FF6A1A] text-white px-8 py-3 rounded-lg font-medium hover:bg-[#14171F] transition-colors">
            {{ __('home.explore_catalog') }}
        </a>
    </section>
@endsection
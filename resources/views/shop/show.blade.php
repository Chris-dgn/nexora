@extends('layouts.shop')

@section('title', $product->name . ' - Nexora')

@section('content')
    <div class="mb-6">
        <a href="{{ route('shop.index') }}" class="text-sm text-gray-500 hover:text-black">
            {{ __('shop.back_to_catalog') }}
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        <div class="aspect-square bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
            {{ __('shop.image_placeholder') }}
        </div>

        <div>
            <h1 class="text-2xl font-bold">{{ $product->name }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $product->category->name }}</p>

            <p class="text-3xl font-semibold mt-4">{{ number_format($product->price, 2) }} €</p>
            @if ($product->compare_at_price)
                <p class="text-gray-400 line-through text-sm">{{ number_format($product->compare_at_price, 2) }} €</p>
            @endif

            <div class="mt-4">
                @if ($product->stock === 0)
                    <span class="inline-block bg-red-100 text-red-700 text-sm px-3 py-1 rounded">
                        {{ __('shop.out_of_stock') }}
                    </span>
                @elseif ($product->stock < 5)
                    <span class="inline-block bg-orange-100 text-orange-700 text-sm px-3 py-1 rounded">
                        {{ __('shop.low_stock_urgent', ['count' => $product->stock]) }}
                    </span>
                @else
                    <span class="inline-block bg-green-100 text-green-700 text-sm px-3 py-1 rounded">
                        {{ __('shop.in_stock') }}
                    </span>
                @endif
            </div>

            <p class="mt-6 text-gray-700 leading-relaxed">
                {{ $product->description ?? __('shop.no_description') }}
            </p>

            @if ($product->stock > 0)
                <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-8">
                    @csrf
                    <button
                        type="submit"
                        class="w-full bg-black text-white py-3 rounded-lg font-medium hover:bg-gray-800"
                    >
                        {{ __('shop.add_to_cart') }}
                    </button>
                </form>
            @else
                <button
                    disabled
                    class="mt-8 w-full bg-gray-300 text-white py-3 rounded-lg font-medium cursor-not-allowed"
                >
                    {{ __('shop.unavailable') }}
                </button>
            @endif

            <div class="mt-6 text-xs text-gray-500 space-y-1">
                <p>{{ __('shop.secure_payment') }}</p>
                <p>{{ __('shop.delivery_time') }}</p>
                <p>{{ __('shop.free_returns') }}</p>
            </div>
        </div>
    </div>
@endsection
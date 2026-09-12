@extends('layouts.shop')

@section('title', 'Mon panier - Nexora')

@section('content')
    <h1 class="text-2xl font-bold mb-6">{{ __('shop.cart') }}</h1>

    @forelse ($cart->items as $item)
        <div class="flex items-center justify-between border-b py-4">
            <div>
                <p class="font-medium">{{ $item->product->name }}</p>
                <p class="text-sm text-gray-500">{{ number_format($item->product->price, 2) }} € / unité</p>
            </div>

            <div class="flex items-center gap-4">
                <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center gap-2">
                    @csrf
                    @method('PATCH')
                    <input
                        type="number"
                        name="quantity"
                        value="{{ $item->quantity }}"
                        min="1"
                        max="{{ $item->product->stock }}"
                        class="w-16 border rounded px-2 py-1 text-sm"
                    >
                    <button type="submit" class="text-sm text-gray-600 hover:text-black">
                        {{ __('shop.update') }}
                    </button>
                </form>

                <p class="font-semibold w-20 text-right">
                    {{ number_format($item->product->price * $item->quantity, 2) }} €
                </p>

                <form action="{{ route('cart.remove', $item) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm text-red-600 hover:underline">
                        {{ __('shop.remove') }}
                    </button>
                </form>
            </div>
        </div>
    @empty
        <p class="text-gray-500">{{ __('shop.empty_cart') }}</p>
        <a href="{{ route('shop.index') }}" class="inline-block mt-4 text-sm underline">
            {{ __('shop.return_to_catalog') }}
        </a>
    @endforelse

    @if ($cart->items->isNotEmpty())
        <div class="mt-6 flex justify-between items-center">
            <p class="text-lg font-semibold">
                {{ __('shop.total', ['amount' => number_format($cart->items->sum(fn ($item) => $item->product->price * $item->quantity), 2) . ' €']) }}
            </p>
            <form action="{{ route('checkout.start') }}" method="POST">
                @csrf
                <button type="submit" class="bg-black text-white px-6 py-3 rounded-lg font-medium">
                    {{ __('shop.checkout') }}
                </button>
            </form>
        </div>
    @endif
@endsection
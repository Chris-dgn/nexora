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
            <h1 class="text-2xl font-bold">{{ $product->translated_name }}</h1>
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
                {{ $product->translated_description ?? __('shop.no_description') }}
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

    <div class="mt-16 border-t pt-10">
    <h2 class="text-xl font-bold mb-4">Avis clients</h2>

    @if ($product->reviews_count > 0)
        <div class="flex items-center gap-2 mb-6">
            <span class="text-2xl font-bold">{{ number_format($product->average_rating, 1) }}</span>
            <div class="text-yellow-500">
                @for ($i = 1; $i <= 5; $i++)
                    {{ $i <= round($product->average_rating) ? '★' : '☆' }}
                @endfor
            </div>
            <span class="text-sm text-gray-500">({{ $product->reviews_count }} avis)</span>
        </div>
    @else
        <p class="text-gray-500 mb-6">Aucun avis pour le moment. Sois le premier à donner ton avis !</p>
    @endif

    <div class="space-y-6 mb-10">
        @foreach ($product->approvedReviews as $review)
            <div class="border-b pb-4">
                <div class="flex items-center gap-2">
                    <div class="text-yellow-500 text-sm">
                        @for ($i = 1; $i <= 5; $i++)
                            {{ $i <= $review->rating ? '★' : '☆' }}
                        @endfor
                    </div>
                    <span class="text-sm font-medium">{{ $review->author_name }}</span>
                </div>
                @if ($review->comment)
                    <p class="text-sm text-gray-600 mt-1">{{ $review->comment }}</p>
                @endif
            </div>
        @endforeach
    </div>

    <div class="bg-gray-50 rounded-lg p-6">
        <h3 class="font-semibold mb-4">Laisser un avis</h3>

        <form action="{{ route('reviews.store', $product) }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1">Ton nom</label>
                <input type="text" name="author_name" required maxlength="255"
                       class="w-full border rounded px-3 py-2 text-sm" value="{{ old('author_name') }}">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Note</label>
                <select name="rating" required class="border rounded px-3 py-2 text-sm">
                    <option value="">Choisir...</option>
                    @for ($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                            {{ $i }} étoile{{ $i > 1 ? 's' : '' }}
                        </option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Commentaire (optionnel)</label>
                <textarea name="comment" rows="3" maxlength="2000"
                          class="w-full border rounded px-3 py-2 text-sm">{{ old('comment') }}</textarea>
            </div>

            @error('author_name')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
            @error('rating')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            <button type="submit" class="bg-black text-white px-6 py-2 rounded-lg text-sm font-medium">
                Envoyer mon avis
            </button>
        </form>
    </div>
</div>
@endsection
@extends('layouts.shop')

@section('title', 'Catalogue - Nexora')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <aside class="md:col-span-1">
            <h2 class="font-semibold mb-3">{{ __('shop.categories') }}</h2>
            <ul class="space-y-2 text-sm">
                <li>
                    <a href="{{ route('shop.index') }}" class="text-gray-600 hover:text-black">
                        {{ __('shop.all_categories') }}
                    </a>
                </li>
                @foreach ($categories as $category)
                    <li>
                        <a href="{{ route('shop.index', ['category' => $category->slug]) }}"
                           class="text-gray-600 hover:text-black">
                            {{ $category->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>

        <div class="md:col-span-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($products as $product)
                    <a href="{{ route('shop.show', $product) }}"
                       class="block bg-white rounded-lg border p-4 hover:shadow-md transition">
                        <div class="aspect-square bg-gray-100 rounded mb-3 flex items-center justify-center text-gray-400">
                            {{ __('shop.image_placeholder') }}
                        </div>
                        <h3 class="font-medium text-sm">{{ $product->translated_name }}</h3>
                        <p class="text-lg font-semibold mt-1">{{ number_format($product->price, 2) }} €</p>
                        @if ($product->stock === 0)
                            <span class="text-xs text-red-600">{{ __('shop.out_of_stock') }}</span>
                        @elseif ($product->stock < 5)
                            <span class="text-xs text-orange-600">{{ __('shop.low_stock', ['count' => $product->stock]) }}</span>
                        @endif
                    </a>
                @empty
                    <p class="text-gray-500 col-span-full">{{ __('shop.no_products') }}</p>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection
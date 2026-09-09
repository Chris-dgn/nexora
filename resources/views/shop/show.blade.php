@extends('layouts.shop')

@section('title', $product->name . ' - Nexora')

@section('content')
    <div class="mb-6">
        <a href="{{ route('shop.index') }}" class="text-sm text-gray-500 hover:text-black">
            ← Retour au catalogue
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        <div class="aspect-square bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
            Image
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
                        Rupture de stock
                    </span>
                @elseif ($product->stock < 5)
                    <span class="inline-block bg-orange-100 text-orange-700 text-sm px-3 py-1 rounded">
                        Plus que {{ $product->stock }} en stock — commandez vite
                    </span>
                @else
                    <span class="inline-block bg-green-100 text-green-700 text-sm px-3 py-1 rounded">
                        En stock
                    </span>
                @endif
            </div>

            <p class="mt-6 text-gray-700 leading-relaxed">
                {{ $product->description ?? 'Aucune description disponible pour ce produit.' }}
            </p>

            <button
                @if ($product->stock === 0) disabled @endif
                class="mt-8 w-full bg-black text-white py-3 rounded-lg font-medium disabled:bg-gray-300 disabled:cursor-not-allowed"
            >
                @if ($product->stock === 0)
                    Indisponible
                @else
                    Ajouter au panier
                @endif
            </button>

            <div class="mt-6 text-xs text-gray-500 space-y-1">
                <p>✓ Paiement sécurisé par Stripe</p>
                <p>✓ Livraison sous 3 à 5 jours ouvrés</p>
                <p>✓ Retours gratuits sous 30 jours</p>
            </div>
        </div>
    </div>
@endsection
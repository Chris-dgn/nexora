<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function show(Request $request): View
    {
        $cart = $this->getOrCreateCart($request);
        $cart->load('items.product');

        return view('shop.cart', [
            'cart' => $cart,
        ]);
    }

    public function add(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);

        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);
        $quantity = $validated['quantity'] ?? 1;

        if ($product->stock < $quantity) {
            return back()->with('error', 'Stock insuffisant pour ce produit.');
        }

        $cart = $this->getOrCreateCart($request);

        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $newQuantity = min($item->quantity + $quantity, $product->stock);
            $item->update(['quantity' => $newQuantity]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => min($quantity, $product->stock),
            ]);
        }

        return back()->with('success', 'Produit ajouté au panier.');
    }

    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        $this->authorizeCartItem($request, $cartItem);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $quantity = min($validated['quantity'], $cartItem->product->stock);
        $cartItem->update(['quantity' => $quantity]);

        return back()->with('success', 'Panier mis à jour.');
    }

    public function remove(Request $request, CartItem $cartItem): RedirectResponse
    {
        $this->authorizeCartItem($request, $cartItem);

        $cartItem->delete();

        return back()->with('success', 'Produit retiré du panier.');
    }

    private function getOrCreateCart(Request $request): Cart
    {
        if ($request->user()) {
            return Cart::firstOrCreate(['user_id' => $request->user()->id]);
        }

        return Cart::firstOrCreate(['session_id' => $request->session()->getId()]);
    }

    private function authorizeCartItem(Request $request, CartItem $cartItem): void
    {
        $cart = $this->getOrCreateCart($request);

        abort_unless($cartItem->cart_id === $cart->id, 403);
    }
}
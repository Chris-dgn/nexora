<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    public function checkout(Request $request): RedirectResponse
    {
        $cart = $this->getCart($request);
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.show')->with('error', 'Ton panier est vide.');
        }

        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->stock) {
                return redirect()->route('cart.show')
                    ->with('error', "Stock insuffisant pour {$item->product->name}.");
            }
        }

        $order = DB::transaction(function () use ($cart, $request) {
            $order = Order::create([
                'user_id' => $request->user()?->id,
                'status' => 'pending',
                'total' => $cart->items->sum(fn ($item) => $item->product->price * $item->quantity),
                'currency' => 'EUR',
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->product->price,
                ]);
            }

            return $order;
        });

        $order->load('items.product');

        Stripe::setApiKey(config('services.stripe.secret'));

        $lineItems = $order->items->map(fn ($item) => [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => $item->product->name,
                ],
                'unit_amount' => (int) round($item->unit_price * 100),
            ],
            'quantity' => $item->quantity,
        ])->toArray();

      $session = StripeSession::create([
    'mode' => 'payment',
    'line_items' => $lineItems,
    'customer_email' => $request->user()?->email,
    'success_url' => route('checkout.success').'?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => route('cart.show'),
    'metadata' => [
        'order_id' => $order->id,
    ],
]);

        $order->update(['payment_id' => $session->id]);

        return redirect($session->url);
    }

    public function success(Request $request): RedirectResponse
    {
        return redirect()->route('shop.index')
            ->with('success', 'Merci pour ta commande ! Un email de confirmation te sera envoyé sous peu.');
    }

    private function getCart(Request $request): Cart
    {
        if ($request->user()) {
            return Cart::firstOrCreate(['user_id' => $request->user()->id]);
        }

        return Cart::firstOrCreate(['session_id' => $request->session()->getId()]);
    }
}
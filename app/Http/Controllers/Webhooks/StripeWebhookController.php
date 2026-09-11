<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmed;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use UnexpectedValueException;

class StripeWebhookController extends Controller
{
    public function handle(Request $request): Response
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (UnexpectedValueException|SignatureVerificationException $e) {
            Log::warning('Stripe webhook signature invalide.', ['error' => $e->getMessage()]);

            return response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $order = Order::where('payment_id', $session->id)->first();

            if (! $order) {
                Log::warning('Webhook Stripe : commande introuvable.', ['session_id' => $session->id]);

                return response('Order not found', 200);
            }

            if ($order->status === 'paid') {
                return response('Already processed', 200);
            }

            DB::transaction(function () use ($order, $session) {
                $order->update([
                    'status' => 'paid',
                    'customer_email' => $order->customer_email ?? $session->customer_details?->email,
                ]);

                foreach ($order->items as $item) {
                    $item->product->decrement('stock', $item->quantity);
                }

                $order->user?->cart?->items()->delete();
            });

            $order->refresh();

            if ($order->customer_email) {
                Mail::to($order->customer_email)->send(new OrderConfirmed($order));
            } else {
                Log::warning('Impossible d\'envoyer la confirmation : aucun email trouvé.', ['order_id' => $order->id]);
            }

            Log::info('Commande confirmée via webhook Stripe.', ['order_id' => $order->id]);
        }

        return response('Webhook handled', 200);
    }
}
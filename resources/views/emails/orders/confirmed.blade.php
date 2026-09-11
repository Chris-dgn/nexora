<x-mail::message>
# Merci pour ta commande !

Bonjour,

Ta commande **#{{ $order->id }}** a bien été confirmée et le paiement a été validé avec succès.

## Récapitulatif

<x-mail::table>
| Produit | Quantité | Prix |
| :------ | :------: | ---: |
@foreach ($order->items as $item)
| {{ $item->product->name }} | {{ $item->quantity }} | {{ number_format($item->unit_price, 2) }} € |
@endforeach
</x-mail::table>

**Total payé : {{ number_format($order->total, 2) }} €**

## Livraison estimée

Ta commande sera livrée aux alentours du **{{ $estimatedDelivery }}**.

<x-mail::button :url="route('shop.index')">
Continuer mes achats
</x-mail::button>

Merci de ta confiance,<br>
{{ config('app.name') }}
</x-mail::message>
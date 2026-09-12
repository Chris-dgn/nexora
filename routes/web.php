<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Webhooks\StripeWebhookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\HomeController;



Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/catalogue', [ShopController::class, 'index'])->name('shop.catalog');
    Route::get('/produits/{product:slug}', [ShopController::class, 'show'])->name('shop.show');

    Route::get('/panier', [CartController::class, 'show'])->name('cart.show');
    Route::post('/panier/ajouter/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/panier/items/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/panier/items/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');

    Route::post('/commander', [CheckoutController::class, 'checkout'])->name('checkout.start');
    Route::get('/commande/succes', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::post('/produits/{product:slug}/avis', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/mentions-legales', [LegalController::class, 'legalNotice'])->name('legal.notice');
    Route::get('/cgv', [LegalController::class, 'terms'])->name('legal.terms');
    Route::get('/confidentialite', [LegalController::class, 'privacy'])->name('legal.privacy');
});

Route::post('/webhooks/stripe', [StripeWebhookController::class, 'handle'])->name('webhooks.stripe');
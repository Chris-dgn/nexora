<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [ShopController::class, 'index'])->name('shop.index');
Route::get('/produits/{product:slug}', [ShopController::class, 'show'])->name('shop.show');

Route::get('/panier', [CartController::class, 'show'])->name('cart.show');
Route::post('/panier/ajouter/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/panier/items/{cartItem}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/panier/items/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
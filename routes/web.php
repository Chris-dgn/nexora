<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [ShopController::class, 'index'])->name('shop.index');
Route::get('/produits/{product:slug}', [ShopController::class, 'show'])->name('shop.show');
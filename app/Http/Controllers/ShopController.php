<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->where('is_active', true)
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->whereHas('category', function ($q) use ($request) {
                    $q->where('slug', $request->input('category'));
                });
            })
            ->latest()
            ->paginate(12);

        $categories = Category::whereNull('parent_id')->get();

        return view('shop.index', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        return view('shop.show', [
            'product' => $product,
        ]);
    }
}
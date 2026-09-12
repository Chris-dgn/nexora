<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::whereNull('parent_id')
            ->withCount('products')
            ->get();

        $featuredProducts = Product::query()
            ->with('translations')
            ->where('is_active', true)
            ->latest()
            ->take(4)
            ->get();

        $productCount = Product::where('is_active', true)->count();

        $averageRating = \App\Models\Review::where('is_approved', true)->avg('rating');
        $reviewsCount = \App\Models\Review::where('is_approved', true)->count();

        return view('welcome', [
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
            'productCount' => $productCount,
            'averageRating' => $averageRating,
            'reviewsCount' => $reviewsCount,
        ]);
    }
}
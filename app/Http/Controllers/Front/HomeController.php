<?php

namespace App\Http\Controllers\Front;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        // with() egar load which mean get products and categories with the one select from DB
        $products = Product::with('category')->active()->latest()->take(8)->get();
        return view('front.home',compact('products'));
    }
}

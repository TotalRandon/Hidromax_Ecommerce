<?php

namespace App\Http\Controllers;

use App\Models\Product;

class FrontController extends Controller
{
    public function index()
    {
        $products = Product::where('is_featured', 'Yes')->orderBy ('id', 'DESC')->where('status', 1)->take(8)->get();
        $data['featuredProducts'] = $products;
        
        $latestProducts = Product::orderBy ('id', 'DESC')->where('status', 1)->take(8)->get();
        $data['latestProducts'] = $latestProducts;


        return view('front.home', $data);
    }
}

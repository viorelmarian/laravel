<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class BackController extends Controller
{
    public function products() {
        $query = Product::query();
        $products = $query->get();
        if (null !== request()->input('logout')) {
            session()->forget('logged');
        }
        return view('back.products', ['products' => $products]);
    }
}

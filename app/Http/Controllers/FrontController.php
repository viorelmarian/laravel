<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index() {
        $query = Product::query();

        if (request()->input('id')) {
            $cart = session()->has('cart') ? session()->get('cart') : [];
            $cart[] = request()->input('id');
            session()->put('cart', $cart);
        }

        if (session()->has('cart')) {
            $query->whereNotIn('id', session()->get('cart'));
        }

        return view('front.index', ['products' => $query->get()]);
    }
}

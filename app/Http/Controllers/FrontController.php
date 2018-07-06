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
        } else {
            $cart = session()->has('cart') ? session()->get('cart') : [];
            session()->put('cart', $cart);
        }

        if (session()->has('cart')) {
            $query->whereNotIn('id', session()->get('cart'));
        }
        return view('front.index', ['products' => $query->get()]);
    }

    public function cart() {
        $query = Product::query();

        if (session()->has('cart')) {
            $query->whereIn('id', session()->get('cart'));
        }
        
        if (request()->input('id') && request()->input('id') == 'all') { 
            $cart = [];
            session()->put('cart', $cart);
            return back();
        } elseif (request()->input('id')) {
            $key = array_search(request()->input('id'), session()->get('cart'));
            session()->forget('cart.' . $key);
            return back();
        }

        if(request()->input('name') && request()->input('contact') && request()->input('comments')) {
            $to = __('viorel.omv@gmail.com');
            $subject = __('Ordered Products');
            $message = '<html><body>';
            $message .= '<p><b>Name: </b></p>' . strip_tags(request()->input('name')) . '<br>' .
                        '<p><b>Contact: </b></p>' . strip_tags(request()->input('contact')) . '<br>' .
                        '<p><b>Comments: </b></p>' . strip_tags(request()->input('comments'));
            foreach ($query->get() as $product) {
                $message .= 
                '<br><br>
                <img src="' . asset('storage/'. $product['image']) . '" alt="">
                <h1 align="top">' . $product["title"] . '</h1>
                <p>' . $product["description"] . '</p>
                <p>' . __('Price: ') . $product["price"] . __('$') . '</p>
                <hr>';
            }
            $message .= '<body><html>';
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            mail($to, $subject, $message, $headers);
            $cart = [];
            session()->put('cart', $cart);
            return back();
        }
        return view('front.cart', ['products' => $query->get()]);
    }
}

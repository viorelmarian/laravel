<?php

namespace App\Http\Controllers;


use App\Product;
use App\Mail\orderShipped;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
 

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

        
        if (request()->input('id') && request()->input('id') == 'all') { 
            $cart = [];
            session()->put('cart', $cart);
        } elseif (request()->input('id')) {
            $key = array_search(request()->input('id'), session()->get('cart'));
            session()->forget('cart.' . $key);
        }

        if (session()->has('cart')) {
            $query->whereIn('id', session()->get('cart'));
        }

        $errors = [
            'name' => '',
            'contact' =>'',
            'comments' =>''
        ];
        
        $formInfo = [
            'name' => '',
            'contact' =>'',
            'comments' =>''
        ];
        if(null!== request()->input('checkout')) {
            $formInfo['name'] = strip_tags(request()->input('name'));
            $formInfo['contact'] = strip_tags(request()->input('contact'));
            $formInfo['comments'] = strip_tags(request()->input('comments'));
            if(request()->input('name') && request()->input('contact') && request()->input('comments')) {
                
                $manager_email = "viorel.omv@gmail.com";
                $products = $query->get();
                Mail::to("viorel.omv@gmail.com")->send(new orderShipped($products, $formInfo));
                $cart = [];
                session()->put('cart', $cart);
                return back();
            } else {

                if(empty(request()->input('name'))) {
                    $errors['name'] = "Name is required.";
                }
                if(empty(request()->input('contact'))) {
                    $errors['contact'] = "Contact Information is required.";
                }
                if(empty(request()->input('comments'))) {
                    $errors['comments'] = "Add a comment.";
                }
            }
        }
        
        return view('front.cart', ['products' => $query->get(), 'errors' => $errors, 'formInfo' => $formInfo]);
    }
}

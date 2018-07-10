<?php

namespace App\Http\Controllers;


use App\Product;
use App\Mail\orderShipped;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Validator;

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
        }

        $query->whereNotIn('id', $cart);

        return view('front.index', ['products' => $query->get()]);
    }

    public function cart(Request $request) {
        $query = Product::query();
        
        if (request()->input('id') && request()->input('id') == 'all') { 
            $cart = [];
            session()->put('cart', $cart);
        } elseif (request()->input('id')) {
            $key = array_search(request()->input('id'), session()->get('cart'));
            if ($key !== false) {
                session()->forget('cart.' . $key);
            }
        }

        if(null!== request()->input('checkout')) {
            $formInfo = Validator::make($request->all(), [
                'name' => 'required',
                'contact' => 'required',
                'comments' => 'required',
            ]);
    
            if ($formInfo->fails()) {
                return redirect('/cart.php')
                            ->withErrors($formInfo)
                            ->withInput();
            }

            $formInfo = $request->validate([
                'name' => 'required',
                'contact' => 'required',
                'comments' => 'required',
            ]);
            $query->whereIn('id', session()->get('cart'));
            $products = $query->get();
            Mail::to(env('MANAGER_EMAIL'))->send(new orderShipped($products, $formInfo));
            $cart = [];
            session()->put('cart', $cart);
            
        }
        $query->whereIn('id', session()->has('cart') ? session()->get('cart') : []);
        return view('front.cart', ['products' => $query->get()]);
    }

    public function login(Request $request) {
        
        if(null !== request()->input('login')) {
            $credentials = Validator::make($request->all(), [
                'username' => 'required',
                'password' => 'required'
            ]);
            if ($credentials->fails()) {
                return redirect('/login.php')
                            ->withErrors($credentials)
                            ->withInput();
            }
            if (
                request()->input('username') == env('ADMIN_USERNAME') && 
                request()->input('username') == env('ADMIN_PASSWORD')
            ) {
                session()->put('logged','ok');
                return redirect('/products.php');
                exit();
            }
        } 
        return view('front.login');
    }
}

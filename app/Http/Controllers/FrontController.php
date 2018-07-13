<?php

namespace App\Http\Controllers;


use App\Product;
use App\Mail\orderShipped;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Input;
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

        if (request()->ajax()) {
            return $query->get();
        }

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

            $query->whereIn('id', session()->get('cart'));
            $products = $query->get();

            $formInfo = Validator::make($request->all(), [
                'name' => 'required',
                'contact' => 'required',
                'comments' => 'required',
            ]);
    
            if ($formInfo->fails()) {

                if (request()->ajax()) {
                    $response = [
                        'products' => $query->get(),
                        'errors' => $formInfo->errors()
                    ];
                    return json_encode($response);
                }

                return redirect('/cart')
                            ->withErrors($formInfo)
                            ->withInput();
                exit();
            }
            Mail::to(env('MANAGER_EMAIL'))->send(new orderShipped($products, $request));
            $cart = [];
            session()->put('cart', $cart);
            
        }
        $query->whereIn('id', session()->has('cart') ? session()->get('cart') : []);
        
        if (request()->ajax()) {
            $response = [
                'products' => $query->get(),
                'errors' => []
            ];
            return json_encode($response);
        }

        return view('front.cart', ['products' => $query->get()]);
    }

    public function login(Request $request) {
        
        if(null !== request()->input('login')) {
            $credentials = Validator::make($request->all(), [
                'username' => 'required',
                'password' => 'required'
            ]);
            if ($credentials->fails()) {

                if (request()->ajax()) {
                    $response = [
                        'errors' => $credentials->errors(),
                    ];
                    return json_encode($response);
                }
                return redirect('/login')
                            ->withErrors($credentials)
                            ->withInput();
            } elseif (
                request()->input('username') == env('ADMIN_USERNAME') && 
                request()->input('password') == env('ADMIN_PASSWORD')
            ) {
                session()->put('logged','ok');
                if (request()->ajax()) {
                    $response = [
                        
                        'login' => "success",
                        'errors' => [],
                    ];
                    return json_encode($response);
                }
                return redirect('/products');
            } elseif (request()->ajax()) {
                $response = [
                    'errors' => [
                        'credentials' => "Wrong credentials."
                    ],
                    
                ];
                return json_encode($response);
            }
        } 
        if (request()->ajax()) {
            $response = [
                'errors' => 'no error',
                'login' => 'denied'
            ];
            return json_encode($response);
        }
        return view('front.login');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Product;
use Validator;

class BackController extends Controller
{
    public function logout() {
        session()->forget('logged');
       
        return redirect('/login');
    }

    public function products() {
        $query = Product::query();

        if (null !== request()->input('id')) {
            $product = Product::find(request()->input('id'));
            if ($product) {
                unlink('storage/' . $product->image);
                $product->delete();
            }
        }
        $products = $query->get();
        if (request()->ajax()) {
            return $products;
        }
        return view('back.products', ['products' => $products]);
    }

    public function product(Request $request) {
        if (request()->has('id')) {
            $product = Product::find(request()->input('id'));
        } else {
            $product = new Product();
        }
        
        if (null !== request()->input('save')) {
            $productValidator = Validator::make(request()->all(), [
                'title' => 'required',
                'description' => 'required',
                'price' => 'required|numeric',
                'image' => (!request()->has('id') ? 'required|' : '') . 'image'
            ]);

            if ($productValidator->fails()) {
                if (request()->ajax()) {
                    $response = [
                        'errors' => $productValidator->errors()
                    ];
                    return json_encode($response);
                }
                return redirect('/product' . (request()->has('id') ? '?id=' . request()->input('id') : ''))
                    ->withErrors($productValidator)
                    ->withInput();
            }

            if ($product->getKey()) {
                $oldImage = $product->image;
                if (request()->file('image')) {
                    $newImage = basename(request()->file('image')->store('public'));
                    unlink('storage/' . $oldImage);
                } else {
                    $newImage = $oldImage;
                }
            } else {
                if (request()->file('image')) { 
                    $newImage = basename(request()->file('image')->store('public'));
                }
            }

            $product->title = request()->input('title');
            $product->description = request()->input('description');
            $product->price = request()->input('price');
            $product->image = $newImage;

            $product->save();

            if (request()->ajax()) {
                return ['success' => true];
            }
            return redirect('/products');
        }

        if (request()->ajax()) {
            return $product;
        }
        
        return view('back.product', ['productInfo' => $product->toArray()]);
    }
}

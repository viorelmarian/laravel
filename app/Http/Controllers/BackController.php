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
            if (request()->ajax()) {
                return json_encode('logged-out');
            }
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
        $query = Product::query();
        if (request()->has('id')) {
            $product = Product::find(request()->input('id'));
        } else {
            $product = new Product();
        }

        $productInfo = $product->toArray();
        if (null !== request()->input('save')) {
            $productInfo = Validator::make(request()->all(), [
                'title' => 'required',
                'description' => 'required',
                'price' => 'required',
                'image' => (!request()->has('id') ? 'required|' : '') . 'image'
            ]);

            if ($productInfo->fails()) {
                return redirect('/product' . (request()->has('id') ? '?id=' . request()->input('id') : ''))
                    ->withErrors($productInfo)
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

            return redirect('/products');
        }

        if (request()->ajax()) {
            return $query->get();
        }
        return view('back.product', ['productInfo' => $productInfo]);
    }
}

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
            return redirect('/login.php');
    }
    public function products() {
        $query = Product::query();

        if (null !== request()->input('id')) {
            $oldImage = DB::select ('select image from products where id= ?', [request()->input('id')])[0]->image;
            $deleted = DB::delete('delete from products where id= ?', [request()->input('id')]);
            unlink('storage/' . $oldImage);
        }
        $products = $query->get();
        return view('back.products', ['products' => $products]);
    }

    public function product(Request $request) {
        $query = Product::query();
        if (request()->has('id')) {
            $product = DB::select('select * from products where id = ?', [request()->input('id')]);
            $productInfo = (array)$product[0];
        } else {
            $productInfo = [
                'title' => '',
                'description' => '',
                'price' => '',
                'image' => '',
            ];
        }
        if (null !== request()->input('save')) {
            $productInfo = Validator::make(request()->all(), [
                'title' => 'required',
                'description' => 'required',
                'price' => 'required',
                'image' => (!request()->has('id') ? 'required|' : '') . 'image'
            ]);
            if ($productInfo->fails()) {
                return redirect('/product.php' . (request()->has('id') ? '?id=' . request()->input('id') : ''))
                            ->withErrors($productInfo)
                            ->withInput();
            }
            if (request()->has('id')) {
                $oldImage = DB::select ('select image from products where id= ?', [request()->input('id')])[0]->image;
                if (request()->file('image')) {
                    $newImage = basename(request()->file('image')->store('public'));
                    unlink('storage/' . $oldImage);
                } else {
                    $newImage = $oldImage;
                }
                $affected = DB::update('UPDATE `products` SET `title`=?,`description`=?,`price`=?,`image`=? WHERE `id`=?',
                                        [request()->title, request()->description, request()->price, $newImage, request()->id]);
                return redirect('/products.php');
            } else {
                if (request()->file('image')) { 
                    $newImage = basename(request()->file('image')->store('public'));
                }
                $inserted = DB::insert('INSERT INTO `products`(`title`, `description`, `price`,`image`) VALUES (?, ?, ?, ?)',
                                        [request()->title, request()->description, request()->price, $newImage]);
                return redirect('/products.php');
            }
        }
        return view('back.product', ['productInfo' => $productInfo]);
    }
}

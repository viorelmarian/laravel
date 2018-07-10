<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Product;
use Validator;
use Storage;

class BackController extends Controller
{
    public function products() {
        $query = Product::query();

        if (null !== request()->input('logout')) {
            session()->forget('logged');
            return redirect('/login.php');
            exit();
        }

        if (null !== request()->input('id')) {
            $imgPath = DB::select ('select image from products where id= ?', [request()->input('id')]);
            $deleted = DB::delete('delete from products where id= ?', [request()->input('id')]);
            Storage::delete($imgPath[0]->image);
        }



        $products = $query->get();
        return view('back.products', ['products' => $products]);
    }

    public function product(Request $request) {
        $query = Product::query();
        if (request()->has('id')) {
            $product = DB::select('select * from products where id = ?', [request()->get('id')]);
            $productInfo = [
                'title' => $product[0]->title,
                'description' => $product[0]->description,
                'price' => $product[0]->price,
                'image' => $product[0]->image,
            ];
        } else {
            $productInfo = [
                'title' => '',
                'description' => '',
                'price' => '',
                'image' => '',
            ];
        }
        if (null !== request()->input('save')) {
            if(request()->has('id')) {
                $path = $request->file('image')->store('public');
                $path = explode('/',$path);
                $affected = DB::update('UPDATE `products` SET `title`=?,`description`=?,`price`=?,`image`=? WHERE `id`=?',
                                        [$request->title, $request->description, $request->price, $path[1], $request->id]);
                return redirect('/products.php');
            } else {
                $path = $request->file('image')->store('public');
                $path = explode('/',$path);
                $inserted = DB::insert('INSERT INTO `products`(`title`, `description`, `price`,`image`) VALUES (?, ?, ?, ?)',
                                        [$request->title, $request->description, $request->price, $path[1]]);
                return redirect('/products.php');
            }
        }
        return view('back.product', ['productInfo' => $productInfo]);
    }
}

<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/','FrontController@index');
Route::get('/index','FrontController@index');
Route::get('/login', 'FrontController@login');
Route::get('/logout', 'BackController@logout');
Route::post('/login', 'FrontController@login');
Route::get('/cart', 'FrontController@cart');
Route::post('/cart', 'FrontController@cart');
Route::get('/products', 'BackController@products')->middleware('authentication');
Route::get('/product', 'BackController@product')->middleware('authentication');
Route::post('/product', 'BackController@product')->middleware('authentication');
Route::get('/spa', function() {
    return view('spa');
});
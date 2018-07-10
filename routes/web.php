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
Route::get('/login.php', 'FrontController@login');
Route::post('/login.php', 'FrontController@login');
Route::get('/cart.php', 'FrontController@cart');
Route::post('/cart.php', 'FrontController@cart');
Route::get('/products.php', 'BackController@products')->middleware('authentication');
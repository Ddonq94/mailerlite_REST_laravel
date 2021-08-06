<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/','App\Http\Controllers\UsersController@index');
Route::get('Users','App\Http\Controllers\UsersController@create');

Route::get('Users/{id}/subscribers','App\Http\Controllers\SubscribersController@index');
Route::get('Users/subscribers','App\Http\Controllers\SubscribersController@load');


Route::post('Subscribers','App\Http\Controllers\SubscribersController@store')->name('Subscribers.store');
Route::post('Subscribers/update','App\Http\Controllers\SubscribersController@update')->name('Subscribers.update');
Route::post('Subscribers/delete','App\Http\Controllers\SubscribersController@destroy')->name('Subscribers.delete');

Route::resource('Users','App\Http\Controllers\UsersController');
// Route::resource('Subscribers','App\Http\Controllers\SubscribersController');


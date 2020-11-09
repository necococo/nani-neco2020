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

Route::get('/', 'ImagesController@index');
Route::get('/usage', function () {
    return view('usage');
})->name('usage');

Route::resource('images', 'ImagesController', ['only' => ['index', 'create', 'store', 'show', 'destroy']]);

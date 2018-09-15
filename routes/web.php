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

Route::get('/', function () {
    return view('welcome');
});

// For Excel
Route::get('recordIndex', 'CarController@recordIndex')->name('recordIndex');
Route::post('import', 'CarController@import')->name('import');
Route::get('listall', 'CarController@listall')->name('listall');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

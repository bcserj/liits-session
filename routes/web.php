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

Route::get('/', function () {
    return view('welcome');
})->middleware('request_counter_ip:5,5');

Route::get('/test', function () {
    return view('welcome');
})->middleware('request_counter_ip:2,10');

Route::get('/home', function () {
    return 'Test';
})->middleware('request_counter:3,5');

Route::get('/home2', function () {
    return 'Test';
})->middleware('request_counter:5,10');

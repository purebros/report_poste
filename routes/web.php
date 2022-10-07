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

Route::get('/cprovider/cdeliver', 'App\Http\Controllers\MTController@sendMt')->name('cprovider.cdeliver');
Route::get('/send/mt', 'App\Http\Controllers\MTController@sendMt')->name('send.mt');
Route::get('/notification/mt', 'App\Http\Controllers\NotificationController@mt')->name('notification.mt');
Route::get('/notification/mo', 'App\Http\Controllers\NotificationController@mo')->name('notification.mo');

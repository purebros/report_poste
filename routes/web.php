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

Route::get('/cprovider/cdeliver', 'MTController@sendMt')->name('cprovider.cdeliver');
Route::get('/send/mt', 'MTController@sendMt')->name('send.mt');
Route::get('/notification/mt', 'NotificationController@mt')->name('notification.mt');
Route::get('/notification/mo', 'NotificationController@mo')->name('notification.mo');

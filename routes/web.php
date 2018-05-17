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
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('site.index');


Route::resource('/alias', 'AliasController');
Route::resource('/domain', 'DomainController');
Route::resource('/mailbox', 'MailboxController');
Route::resource('/throttle', 'ThrottleController');

Route::get('/mailbox/{mailbox}/change_password', 'MailboxController@change_password_show')->name('mailbox.change_password');
Route::post('/mailbox/change_password_store', 'MailboxController@change_password_store')->name('mailbox.change_password_store');

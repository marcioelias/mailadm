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

// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
//$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//$this->post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('/', 'HomeController@index')->name('site.index');

Route::middleware('auth:web')->group(function() {
    Route::get('/perfil', 'UserController@profile')->name('user.profile');
    Route::get('/alterar_senha', 'UserController@showChangePassword')->name('user.form.change.password');
    Route::put('/alterar_senha/{user}', 'UserController@changePassword')->name('user.change.password');

    Route::resource('/alias', 'AliasController')->except('show');
    Route::resource('/domain', 'DomainController')->except('show', 'edit', 'update');
    Route::resource('/mailbox', 'MailboxController')->except('show');
    Route::resource('/throttle', 'ThrottleController')->except('show');
    Route::resource('/user', 'UserController')->except('show');
    Route::resource('/role', 'RolesController')->except('show');

    Route::get('/mailbox/{mailbox}/change_password', 'MailboxController@change_password_show')->name('mailbox.change_password');
    Route::post('/mailbox/change_password_store', 'MailboxController@change_password_store')->name('mailbox.change_password_store');

    Route::get('mailbox/json', 'MailboxController@getMailboxesJson')->name('mailbox.json');
});
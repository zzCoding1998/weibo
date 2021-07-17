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

Route::get('/', 'StaticPagesController@home')->name('home');
Route::get('/about', 'StaticPagesController@about')->name('about');
Route::get('/help', 'StaticPagesController@help')->name('help');

Route::get('/signup','UsersController@create')->name('signup');
Route::resource('users','UsersController');
Route::get('/signup/confirm/{token}','UsersController@confirmSignupEmail')->name('signup_confirm');

Route::get('/login','SessionsController@create')->name('login');
Route::post('/login','SessionsController@store')->name('login');
Route::delete('/logout','SessionsController@destroy')->name('logout');

Route::get('/password/email','PasswordsController@emailForm')->name('password.email_form');
Route::post('/password/email','PasswordsController@sendResetEmail')->name('password.email');
Route::get('/password/reset/{token}','PasswordsController@resetForm')->name('password.reset_form');
Route::post('/password/reset','PasswordsController@reset')->name('password.reset');

Route::post('/status/store','StatusesController@store')->name('statuses.store');
Route::delete('/status/delete/{status}','StatusesController@destroy')->name('statuses.destroy');




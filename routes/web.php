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


Route::group(['middleware' => ['customAuth']], function () {
    /*Route::get('/', function () {
        if(!session()->has('data'))
        {
            return redirect('login');
        }
        return view('welcome');
    });    */
    Route::get('/index','Users@index');  
    Route::get('/customer','Users@customer');  

});


Route::view('/', 'admin.login');
Route::view('/login', 'admin.login');
Route::post('login', 'Login@index');

Route::get('logout', function () {
    session()->forget('data');
    return redirect('/login');
});
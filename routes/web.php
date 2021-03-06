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

Route::get('/', 'Auth\LoginController@showLoginForm');

Auth::routes(['register' => FALSE]);

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function () {

    Route::post('home/bookyear', 'HomeController@setBookYear')->name('home.setBookYear');

    Route::get('receipt/search', 'ReceiptController@index')->name('receipt.search');
    Route::resource('receipt', 'ReceiptController');

    Route::resource('group', 'GroupController');

    Route::resource('debtor', 'DebtorController');

    Route::get('income/search', 'IncomeController@index')->name('income.search');
    Route::resource('income', 'IncomeController');

    Route::resource('invoice', 'InvoiceController');
    Route::get('invoice/search', 'InvoiceController@index')->name('invoice.search');
    Route::get('invoice/{invoice}/download', 'InvoiceController@download')->name('invoice.download');

});

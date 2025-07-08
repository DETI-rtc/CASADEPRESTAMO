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
    return view('auth.login');
})->name('basepath');



//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::get('/factura', [App\Http\Controllers\HomeController::class, 'factura'])->name('factura');
// Route::get('/convertir', [App\Http\Controllers\HomeController::class, 'convertir'])->name('convertir');
Route::get('calcular','FueraController@calculomonto1');
Route::get('delista1','FueraController@delista1');
Route::get('calctcea1','CuotafueraController@calctcea');
Route::get('nuevafactura','ComprobanteController@cuotasVencidasCompro1');
Route::get('cronograma/{id}','CreditosController@show')->middleware('auth:sanctum');
Route::get('{any}', function () {
    return view('home');
})->where('any', '.*')->middleware('auth:sanctum');

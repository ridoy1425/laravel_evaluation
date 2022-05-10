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
use App\Models\TestModel;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', function () {
    dd(Auth::routes());
});

Auth::routes();

// Route::middleware(['web', 'admin'])->group(function () {
    
// });
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/addProducts', [App\Http\Controllers\HomeController::class, 'addProducts']);
Route::get('/productSearch', [App\Http\Controllers\HomeController::class, 'productSearch']);
Route::get('/product_delete', [App\Http\Controllers\HomeController::class, 'product_delete']);
Route::get('/subCatSearch', [App\Http\Controllers\HomeController::class, 'subCatSearch']);



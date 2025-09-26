<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FrontPageController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('menu', [FrontPageController::class, 'menuCategory'])->name('menu');
Route::prefix('categories')->group(function () {
    //Route::get('/', [FrontPageController::class, 'categoryList'])->name('categories.index');
    Route::get('/{slug}', [FrontPageController::class, 'categoryProductList'])->name('categories.show');
});
Route::get('products/{slug}', [FrontPageController::class, 'productDetails'])->name('products.details');

Route::get('home-product', [FrontPageController::class, 'homeProductList'])->name('home-product');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


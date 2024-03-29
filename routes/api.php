<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/article',[ArticleController::class,'index']);
Route::post('/article',[ArticleController::class,'ArticleUpload']);
Route::get('/article/{id}',[ArticleController::class,'ArticleDetail']);
Route::post('/article/{id}',[ArticleController::class,'ArticleEdit']);
Route::delete('/article/{id}', [ArticleController::class, 'ArticleDelete']);
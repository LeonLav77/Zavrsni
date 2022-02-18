<?php

use App\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

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

Route::get('/card/{id}', [TransactionController::class, 'card']);
Route::get('/test', function () {
    return response()->json(Card::All());
});
Route::post('/test', function () {
    return response()->json('Hello World');
});
Route::post('/createCard', [TransactionController::class, 'createCard']);

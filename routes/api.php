<?php

use Illuminate\Http\Request;

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


Route::prefix('/matchMove')->middleware(['AuthenticateClientRequest'])->group(function () {

    Route::get('/Dashboard', 'MatchMoveController@dashboard');
});


Route::prefix('/admin')->middleware(['AuthenticateClientRequest'])->group(function () {
    
    Route::post('/CreateToken', 'TokenController@createToken');
    Route::get('/GetAllToken', 'AdminController@getAllToken');
    Route::patch('/RevokeToken', 'AdminController@revokeToken');
    
});

Route::prefix('/open-call')->group(function () {
    Route::get('/ValidateToken', 'TokenController@validateToken');
});

Route::get('/admin-v2/login', 'AdminController@login');
<?php

use App\Http\Controllers\API\ChoiceController;
use App\Http\Controllers\API\DivisionController;
use App\Http\Controllers\API\PollController;
use App\Http\Controllers\API\VoteController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/signup', [AuthController::class, 'signup']);

    Route::group(['middleware' => 'jwt.verify:admin,user'], function() {
        Route::post('/me', [AuthController::class, 'me'])->name('profile');
        Route::post('/reset_password', [AuthController::class, 'reset_password'])->name('reset_password');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});

Route::group(['middleware' => 'jwt.verify:admin'], function () {
    Route::post('/poll', [MainController::class, 'store_poll']);
    Route::delete('/poll/{id}', [MainController::class, 'delete_poll']);

    Route::get('/poll/trash', [MainController::class, 'show_poll_trash']);
    Route::post('/poll/restore/{id}', [MainController::class, 'restore_poll_id']);
    Route::post('/poll/force/{id}', [MainController::class, 'force_delete_poll']);

    Route::post('/user', [UserController::class, 'store']);
});

Route::group(['middleware' => 'jwt.verify:admin,user'], function () {
    Route::get('/poll', [MainController::class, 'get_poll']);
    Route::get('/poll/{id}', [MainController::class, 'get_poll_id']);
});

Route::group(['middleware' => 'jwt.verify:user'], function () {
    Route::post('/poll/{poll_id}/vote/{choice_id}', [MainController::class, 'vote']);
});

Route::group(['prefix' => 'v1', 'middleware' => 'jwt.verify:admin'], function() {
    Route::apiResource('division', DivisionController::class);
    Route::apiResource('choice', ChoiceController::class);
    Route::apiResource('poll', PollController::class);
    Route::apiResource('vote', VoteController::class);
});

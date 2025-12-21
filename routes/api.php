<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ====> Start V1 API Routes <====
Route::group(['prefix' => 'v1'], function () {

    //authentication routes
    //log in the user
    Route::post('login', [AuthController::class, 'login'])->name('v1.login');

    //register a new user in to system
    Route::post('register', [AuthController::class, 'register'])->name('v1.register');

    //====> Start Authenticated Routes <====
    Route::middleware('auth:sanctum')->group(function () {

        //returns the auth user
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

    });
    //====> End Authenticated Routes <====

});
// ====> End V1 API Routes <====

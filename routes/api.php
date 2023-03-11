<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['throttle:5,1', 'guest']], function () {
    Route::post('/register', [AuthenticationController::class, 'register']);
    Route::post('/login', [AuthenticationController::class, 'login']);
    Route::post('/send-password-reset-link', [UserController::class, 'sendPasswordResetLink']);
    Route::put('/reset-password', [UserController::class, 'resetPassword']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/refresh-token', function (Request $request) {
        $request->user()->tokens()->delete();
        return response()->json([
            'token' => $request->user()->createToken($request->user() . '-' . time())
                ->plainTextToken,
            'user' => $request->user()
        ]);
    });
    Route::put('/user', [UserController::class, 'update']);
    Route::post('/logout', [AuthenticationController::class, 'logout']);
});

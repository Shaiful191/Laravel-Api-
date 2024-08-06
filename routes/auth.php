<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//Password Reset:
Route::post('/password/email', [ResetPasswordController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->middleware('signed')->name('password.reset');


Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    
    //Verify email:
    Route::post('/email/verify/send', [VerifyEmailController::class, 'sendMail']);
    Route::post('/email/verify', [VerifyEmailController::class, 'verify'])->middleware('signed')->name('verify_email');

});

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\ListMessageWithMeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $arrUser = $request->user()->getUserWithShortName();
    return response()->json($arrUser);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('user/user-online', 'getUserOnline'); 
        Route::get('user/all', 'fetchUser'); 
        Route::put('user/{id}', 'update'); 
        Route::post('upload_avatar', 'uploadAvatar'); 
     });
    Route::get('auth/logout', [AuthController::class, 'logout']);

    Route::controller(ListMessageWithMeController::class)->group(function () {
       Route::post('list-message', 'store'); 
       Route::get('list-message-with-me', 'listWithMe'); 
    });

    Route::controller(MessageController::class)->group(function () {
        Route::get('fetchMessages', 'fetchMessages'); 
        Route::post('sendMessage', 'store'); 
        Route::post('sendMessageFile', 'storeFile'); 
        Route::post('removeMessage', 'removeMessage'); 
    });
});

Route::controller(MessageController::class)->group(function () {
    Route::get('download/{list_message_id}/{path}', 'downloadFile'); 
});

Route::prefix('auth')->group(function (){
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::get('/config', [ConfigController::class, 'get']);
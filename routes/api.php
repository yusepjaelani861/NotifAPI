<?php

use App\Http\Controllers\API\NotifController;
use App\Http\Controllers\API\sendMessage;
use App\Http\Controllers\API\WhatsappController;
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

Route::post('/sendmessage', [WhatsappController::class, 'sendMessage']);
Route::post('/sendfile', [WhatsappController::class, 'sendFile']);
Route::post('/send', [NotifController::class, 'send']);
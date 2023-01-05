<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PHPUnit\TextUI\XmlConfiguration\Group;
use App\Http\Controllers\ApiController;

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

Route::prefix('/v1')->group(function () {
    Route::get('/cats_api', [ApiController::class, 'api_index']);               //from api endpoint
    Route::get('/cats_api/{id}', [ApiController::class, 'api_show']);

    Route::get('/cats', [ApiController::class, 'index']);                       //from DB
    Route::get('/cats/{id}', [ApiController::class, 'show']);
    Route::post('/cats/store', [ApiController::class, 'store']);

    Route::get('/cats/{id}/edit', [ApiController::class, 'edit']);
    Route::put('/cats/{id}/update', [ApiController::class, 'update']);

    Route::delete('/cats/{id}/delete', [ApiController::class, 'destroy']);
});

Route::prefix('/pub')->group(function () {
    Route::get('/astro', [ApiController::class, 'astro']);
});

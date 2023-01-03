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

    Route::get('/cats', [ApiController::class, 'catsAPI']);             //from api endpoint
    Route::get('/cats/{id}', [ApiController::class, 'show']);

    Route::get('/getCat', [ApiController::class, 'get']);              //from DB
    Route::post('/cats/store', [ApiController::class, 'store']);
});

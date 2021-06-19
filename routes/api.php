<?php

require_once base_path("routes/RoutePartials/adminRoutes.php");
require_once base_path("routes/RoutePartials/cityRoutes.php");
require_once base_path("routes/RoutePartials/professionRoutes.php");
require_once base_path("routes/RoutePartials/userRoutes.php");

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

// route for logging user
Route::post('login', [App\Http\Controllers\PassportAuthController::class, 'login']);
// route for logout user
Route::post('logout', [App\Http\Controllers\PassportAuthController::class, 'logout']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

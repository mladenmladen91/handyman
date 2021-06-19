<?php
Route::group(
    ['prefix' => 'user/'],
    function () {
        Route::group(
            ['middleware' => 'auth:api'],
            function () {
                Route::post('changeProfile', [App\Http\Controllers\UserController::class, 'changeProfile']);
            }
        );
       
    }
);

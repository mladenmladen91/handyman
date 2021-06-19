<?php
Route::group(
    ['prefix' => 'user/'],
    function () {
        Route::group(
            ['middleware' => ['auth:api', 'admin']],
            function () {
                Route::post('addUser', [App\Http\Controllers\UserController::class, 'addUser']);
                Route::post('updateUser', [App\Http\Controllers\UserController::class, 'updateUser']);
                Route::post('deleteUser', [App\Http\Controllers\UserController::class, 'deleteUser']);
                Route::post('getUsers', [App\Http\Controllers\UserController::class, 'getUsers']);
            }
        );
       
    }
);

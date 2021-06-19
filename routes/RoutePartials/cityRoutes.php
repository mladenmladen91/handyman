<?php
Route::group(
    ['prefix' => 'city/'],
    function () {
        Route::group(
            ['middleware' => ['auth:api', 'admin']],
            function () {
                Route::post('addCity', [App\Http\Controllers\CityController::class, 'addCity']);
                Route::post('updateCity', [App\Http\Controllers\CityController::class, 'updateCity']);
                Route::post('deleteCity', [App\Http\Controllers\CityController::class, 'deleteCity']);
            }
        );

        Route::get('getCities', [App\Http\Controllers\CityController::class, 'getCities']);
       
    }

    
);

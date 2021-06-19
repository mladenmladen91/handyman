<?php
Route::group(
    ['prefix' => 'profession/'],
    function () {
        Route::group(
            ['middleware' => ['auth:api', 'admin']],
            function () {
                Route::post('addProfession', [App\Http\Controllers\ProfessionController::class, 'addProfession']);
                Route::post('updateProfession', [App\Http\Controllers\ProfessionController::class, 'updateProfession']);
                Route::post('deleteProfession', [App\Http\Controllers\ProfessionController::class, 'deleteProfession']);
            }
        );

        Route::get('getProfessions', [App\Http\Controllers\ProfessionController::class, 'getProfessions']);
       
    }

    
);

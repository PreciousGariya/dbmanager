<?php

use PreciousGariya\DbManager\Http\Controllers\DbManagerDBController;

Route::group(['prefix' => 'db-manager', 'as' => 'db-manager.','middleware' => 'web'], function () {
        Route::controller(DbManagerDBController::class)->group(function () {
            // Route::post('/orders', 'store');
            Route::get('/', 'index')->name('index');
            Route::get('/step-1',  'step_one')->name('step_1');
            Route::any('/configuration', 'step_one_post')->name('step_1_post');
            Route::post('/setup/step-2', 'step_two')->name('step_2');


            Route::get('/step_final', function () {
                return view('db_manager::include.step_final');
            })->name('step_final');
        });
    // Route::get('/test', function () {
    //     return view('DBManager::welcome');
    // });
});

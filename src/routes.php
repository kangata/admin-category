<?php

$admin = [
    'prefix' => 'admin',
    'namespace' => 'QuetzalArc\Admin\Category',
];

Route::group($admin, function() {
    Route::get('/categories', 'CategoryController@index');
    Route::post('/categories', 'CategoryController@store');
    Route::get('/categories/{id}/edit', 'CategoryController@edit');
    Route::patch('/categories/{id}', 'CategoryController@update');
    Route::delete('/categories/{id}', 'CategoryController@delete');
});
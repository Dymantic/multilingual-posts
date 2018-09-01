<?php

Route::group(['prefix' => 'multilingual-posts', 'namespace' => 'Dymantic\MultilingualPosts'], function() {
    Route::post('/', 'PostsController@store');
    Route::post('{postId}', 'PostsController@update');
});
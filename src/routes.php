<?php

Route::group(['prefix' => 'multilingual-posts', 'namespace' => 'Dymantic\MultilingualPosts'], function() {
    Route::post('posts', 'PostsController@store');
    Route::post('posts/{postId}', 'PostsController@update');
    Route::delete('posts/{postId}', 'PostsController@destroy');

    Route::post('published-posts', 'PublishedPostsController@store');
    Route::delete('published-posts/{postId}', 'PublishedPostsController@destroy');

    Route::post('posts/{postId}/title-image', 'PostTitleImageController@store');

    Route::post('posts/{postId}/images', 'PostImagesController@store');
});
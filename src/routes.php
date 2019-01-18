<?php

Route::group(['prefix' => 'multilingual-posts', 'middleware' => ['auth'], 'namespace' => 'Dymantic\MultilingualPosts'], function() {
    Route::get('posts', 'PostsController@index');
    Route::post('posts', 'PostsController@store');
    Route::get('posts/{postId}', 'PostsController@show');
    Route::post('posts/{postId}', 'PostsController@update');
    Route::delete('posts/{postId}', 'PostsController@destroy');

    Route::post('published-posts', 'PublishedPostsController@store');
    Route::delete('published-posts/{postId}', 'PublishedPostsController@destroy');

    Route::post('posts/{postId}/title-image', 'PostTitleImageController@store');
    Route::post('posts/{postId}/images', 'PostImagesController@store');

    Route::post('categories', 'CategoriesController@store');
    Route::post('categories/{categoryId}', 'CategoriesController@update');
    Route::delete('categories/{categoryId}', 'CategoriesController@destroy');
});
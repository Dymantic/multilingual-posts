<?php

namespace Dymantic\MultilingualPosts;

use Illuminate\Support\ServiceProvider;

class MultilingualPostsServiceProvider extends ServiceProvider
{
    public function boot() {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        $this->publishes([
            __DIR__.'/../config/multilingual-posts.php.php' => config_path('multilingual-posts.php'),
        ], 'config');

        if (! class_exists('CreateMultilingualPostsTable')) {
            $this->publishes([
                __DIR__.'/../database/migrations/create_multilingual_posts_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_multilingual_posts_table.php'),
            ], 'migrations');
        }

        if (! class_exists('CreateMultilingualCategoriesTable')) {
            $this->publishes([
                __DIR__.'/../database/migrations/create_multilingual_categories_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_multilingual_categories_table.php'),
            ], 'migrations');
        }

        if (! class_exists('CreateMultilingualCategoryPostTable')) {
            $this->publishes([
                __DIR__.'/../database/migrations/create_multilingual_category_post_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_multilingual_category_post_table.php'),
            ], 'migrations');
        }

    }

    public function register()
    {
        $this->app->bind(MediaBroker::class, function() {
            $class = config('multilingual-posts.media-broker') ?? NullMediaBroker::class;
            return $this->app->make($class);
        });
    }
}
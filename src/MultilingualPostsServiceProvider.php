<?php

namespace Dymantic\MultilingualPosts;

use Illuminate\Support\ServiceProvider;

class MultilingualPostsServiceProvider extends ServiceProvider
{
    public function boot() {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        $this->publishes([
            __DIR__.'/../config/medialibrary.php' => config_path('multilingual-posts.php'),
        ], 'config');

        if (! class_exists('CreateMultilingualPostsTable')) {
            $this->publishes([
                __DIR__.'/../database/migrations/create_multilingual_posts_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_multilingual_posts_table.php'),
            ], 'migrations');
        }
    }

    public function register()
    {

    }
}
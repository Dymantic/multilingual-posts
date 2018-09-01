<?php

namespace Dymantic\MultilingualPosts;

use Illuminate\Support\ServiceProvider;

class MultilingualPostsServiceProvider extends ServiceProvider
{
    public function boot() {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
    }

    public function register()
    {

    }
}
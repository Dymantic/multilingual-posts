<?php

namespace Dymantic\MultilingualPosts\Tests;

use Cviebrock\EloquentSluggable\ServiceProvider;
use Dymantic\MultilingualPosts\ArticlesServiceProvider;
use Dymantic\MultilingualPosts\MultilingualPostsServiceProvider;
use File;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Exceptions\Handler;
use Orchestra\Testbench\TestCase as Orchestra;
use \Spatie\MediaLibrary\MediaLibraryServiceProvider;
use Spatie\Translatable\TranslatableServiceProvider;

abstract class TestCase extends Orchestra
{

    public function setUp() :void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    public function asLoggedInUser()
    {
        $this->actingAs(TestUser::first());

        return $this;
    }

    public function asGuestUser()
    {
        return $this;
    }

    protected function assertDatabaseHasWithTranslations($table, $data)
    {
        $translated = collect($data)->flatMap(function ($value, $key) {
            return is_array($value) ? [$key => json_encode($value)] : [$key => $value];
        })->all();

        return $this->assertDatabaseHas($table, $translated);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            MultilingualPostsServiceProvider::class,
            ServiceProvider::class,
            TranslatableServiceProvider::class,
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $this->initializeDirectory(__DIR__ . '/temp');

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('filesystems.disks.media', [
            'driver' => 'local',
            'root'   => __DIR__ . '/temp/media',
        ]);



        $app->bind('path.public', function () {
            return __DIR__ . '/temp';
        });

        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('test_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('password');
        });

        $app['db']->connection()->getSchemaBuilder()->create('nameless_authors', function (Blueprint $table) {
            $table->increments('id');
        });

        $app['db']->connection()->getSchemaBuilder()->create('multilingual_posts_media_models', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('post_id');
            $table->nullableTimestamps();
        });


        TestUser::create(['name' => 'test user', 'email' => 'test@example.com', 'password' => 'password']);

        include_once __DIR__ . '/../database/migrations/create_multilingual_posts_table.php.stub';
        include_once __DIR__ . '/../database/migrations/create_multilingual_categories_table.php.stub';
        include_once __DIR__ . '/../database/migrations/create_multilingual_category_post_table.php.stub';

        (new \CreateMultilingualPostsTable())->up();
        (new \CreateMultilingualCategoriesTable())->up();
        (new \CreateMultilingualCategoryPostTable())->up();
    }

    protected function initializeDirectory($directory)
    {
        if (File::isDirectory($directory)) {
            File::deleteDirectory($directory);
        }
        File::makeDirectory($directory);
    }
}
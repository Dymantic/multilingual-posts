<?php


namespace Dymantic\MultilingualPosts\Tests\feature;


use Dymantic\MultilingualPosts\MediaLibraryMediaBroker;
use Dymantic\MultilingualPosts\TestMediaBroker;
use Dymantic\MultilingualPosts\Tests\TestCase;

class LoadMediaManagerServiceProviderTest extends TestCase
{
    /**
     *@test
     */
    public function load_the_correct_media_manager_specified_in_config()
    {
        config(['multilingual-posts.media-broker' => TestMediaBroker::class]);

        $broker = app(\Dymantic\MultilingualPosts\MediaBroker::class);

        $this->assertInstanceOf(TestMediaBroker::class, $broker);
        $this->assertInstanceOf(\Dymantic\MultilingualPosts\MediaBroker::class, $broker);
    }

    /**
     *@test
     */
    public function defaults_to_media_library_media_broker()
    {
        config(['multilingual-posts.media-broker' => null]);

        $broker = app(\Dymantic\MultilingualPosts\MediaBroker::class);

        $this->assertInstanceOf(MediaLibraryMediaBroker::class, $broker);
        $this->assertInstanceOf(\Dymantic\MultilingualPosts\MediaBroker::class, $broker);
    }
}
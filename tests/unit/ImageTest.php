<?php


namespace Dymantic\MultilingualPosts\Tests\unit;


use Dymantic\MultilingualPosts\Image;
use Dymantic\MultilingualPosts\Post;
use Dymantic\MultilingualPosts\TestMediaBroker;
use Dymantic\MultilingualPosts\Tests\TestCase;
use Illuminate\Http\UploadedFile;

class ImageTest extends TestCase
{

    /**
     *@test
     */
    public function get_full_url_of_image_using_app_url()
    {
        config(['multilingual-posts.media-broker' => TestMediaBroker::class]);
        config(['app.url' => 'https://test.test']);

        $post = Post::create(['title' => 'test title']);
        $image = $post->attachImage(UploadedFile::fake()->image('testpic.png'));

        $this->assertEquals('https://test.test/' . $image->getUrl(), $image->getFullUrl());
    }
}
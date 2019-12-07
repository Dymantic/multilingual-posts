<?php


namespace Dymantic\MultilingualPosts\Tests\unit;


use Dymantic\MultilingualPosts\Image;
use Dymantic\MultilingualPosts\Post;
use Dymantic\MultilingualPosts\TestMediaBroker;
use Dymantic\MultilingualPosts\Tests\TestCase;
use Illuminate\Http\UploadedFile;

class PostImagesTest extends TestCase
{
    /**
     *@test
     */
    public function attach_image_to_post_body()
    {
        config(['multilingual-posts.media-broker' => TestMediaBroker::class]);

        $post = Post::create(['title' => 'test title']);

        $image = $post->attachImage(UploadedFile::fake()->image('testpic.png'));

        $this->assertInstanceOf(Image::class, $image);
    }

    /**
     *@test
     */
    public function set_title_image_on_post()
    {
        config(['multilingual-posts.media-broker' => TestMediaBroker::class]);
        $post = Post::create(['title' => 'test title']);

        $image = $post->setTitleImage(UploadedFile::fake()->image('testpic.png'));

        $this->assertInstanceOf(Image::class, $image);
    }

    /**
     *@test
     */
    public function get_title_image()
    {
        config(['multilingual-posts.media-broker' => TestMediaBroker::class]);
        $post = Post::create(['title' => 'test title']);
        $post->setTitleImage(UploadedFile::fake()->image('testpic.png'));

        $image = $post->titleImage();

        $this->assertInstanceOf(Image::class, $image);
    }
}
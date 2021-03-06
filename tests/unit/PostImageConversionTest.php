<?php


namespace Dymantic\MultilingualPosts\Tests\unit;


use Dymantic\MultilingualPosts\Post;
use Dymantic\MultilingualPosts\PostImageConversion;
use Dymantic\MultilingualPosts\Tests\TestCase;

class PostImageConversionTest extends TestCase
{
    /**
     * @test
     */
    public function a_web_conversion_is_correctly_formed()
    {
        $conversion = new PostImageConversion([
            'name'         => 'web',
            'manipulation' => 'crop',
            'width'        => 1600,
            'height'       => 1000,
            'title'        => false,
            'post'         => true
        ]);

        $this->assertEquals('web', $conversion->name);
        $this->assertEquals(1600, $conversion->width);
        $this->assertEquals(1000, $conversion->height);
        $this->assertEquals([Post::BODY_IMAGES], $conversion->collections);
        $this->assertFalse($conversion->optimize);
    }

    /**
     * @test
     */
    public function a_thumb_conversion_is_correctly_formed()
    {
        $conversion = new PostImageConversion([
            'name'         => 'thumb',
            'manipulation' => 'fit',
            'width'        => 400,
            'height'       => 400,
            'title'        => true,
            'post'         => true,
            'optimize' => false,
        ]);

        $this->assertEquals('thumb', $conversion->name);
        $this->assertEquals(400, $conversion->width);
        $this->assertEquals(400, $conversion->height);
        $this->assertEquals([Post::TITLE_IMAGES, Post::BODY_IMAGES], $conversion->collections);
        $this->assertFalse($conversion->optimize);
    }

    /**
     * @test
     */
    public function a_banner_conversion_is_correctly_formed()
    {
        $conversion = new PostImageConversion([
            'name'         => 'banner',
            'manipulation' => 'crop',
            'width'        => 2000,
            'height'       => 1000,
            'title'        => true,
            'post'         => false,
            'optimize' => true,
        ]);

        $this->assertEquals('banner', $conversion->name);
        $this->assertEquals(2000, $conversion->width);
        $this->assertEquals(1000, $conversion->height);
        $this->assertEquals([Post::TITLE_IMAGES], $conversion->collections);
        $this->assertTrue($conversion->optimize);
    }
}
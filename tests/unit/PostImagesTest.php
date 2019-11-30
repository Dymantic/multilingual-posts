<?php


namespace Dymantic\MultilingualPosts\Tests\unit;


use Dymantic\MultilingualPosts\Post;
use Dymantic\MultilingualPosts\Tests\UsesModels;
use Dymantic\MultilingualPosts\Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\Models\Media;

class PostImagesTest extends TestCase
{
    use UsesModels;

    /**
     *@test
     */
    public function image_can_be_attached_to_a_post()
    {
        $post = $this->makePost();

        $image = $post->attachImage(UploadedFile::fake()->image('testpic.png'));

        $this->assertInstanceOf(Media::class, $image);
        $this->assertCount(1, $post->fresh()->getMedia(Post::BODY_IMAGES));
    }

    /**
     *@test
     */
    public function conversions_are_generated_for_an_attached_image()
    {
        $post = $this->makePost();
        $image = $post->attachImage(UploadedFile::fake()->image('testpic.png'))->fresh();

        $this->assertTrue($image->hasGeneratedConversion('web'), 'web conversion not generated');
        $this->assertTrue($image->hasGeneratedConversion('thumb'), 'thumb conversion not generated');
    }

    /**
     *@test
     */
    public function standard_conversions_are_generated_if_no_config()
    {
        config(['multilingual-posts.conversions' => null]);

        $post = $this->makePost();
        $image = $post->attachImage(UploadedFile::fake()->image('testpic.png', 3000, 2000))->fresh();

        $this->assertTrue($image->hasGeneratedConversion('web'), 'web conversion not generated');
        $this->assertTrue($image->hasGeneratedConversion('thumb'), 'thumb conversion not generated');

        $thumbSize = getimagesize($image->getPath('thumb'));
        $this->assertEquals(400, $thumbSize[0]);
        $this->assertEquals(300, $thumbSize[1]);

        $webSize = getimagesize($image->getPath('web'));
        $this->assertEquals(800, $webSize[0]);
        $this->assertEquals(533, $webSize[1]);

    }

    /**
     *@test
     */
    public function conversions_can_be_generated_from_config()
    {
        config(['multilingual-posts.conversions' => [
            ['name' => 'web', 'manipulation' => 'crop', 'width' => 1600, 'height' => 1000, 'title' => false, 'post' => true],
            ['name' => 'thumb', 'manipulation' => 'fit', 'width' => 400, 'height' => 400, 'title' => false, 'post' => true],
        ]]);

        $post = $this->makePost();
        $image = $post->attachImage(UploadedFile::fake()->image('testpic.png', 3000, 2000))->fresh();

        $this->assertTrue($image->hasGeneratedConversion('web'), 'web conversion not generated');
        $this->assertTrue($image->hasGeneratedConversion('thumb'), 'thumb conversion not generated');

        $webSize = getimagesize($image->getPath('web'));

        $this->assertEquals(1600, $webSize[0]);
        $this->assertEquals(1000, $webSize[1]);

        $thumbSize = getimagesize($image->getPath('thumb'));

        $this->assertEquals(400, $thumbSize[0]);
        $this->assertEquals(267, $thumbSize[1]);
    }
}
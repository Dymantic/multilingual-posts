<?php


namespace Dymantic\MultilingualPosts\Tests\unit;


use Dymantic\MultilingualPosts\Post;
use Dymantic\MultilingualPosts\Tests\MakesPosts;
use Dymantic\MultilingualPosts\Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\Models\Media;

class PostImagesTest extends TestCase
{
    use MakesPosts;

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
}
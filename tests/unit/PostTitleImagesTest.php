<?php


namespace Dymantic\MultilingualPosts\Tests\unit;


use Dymantic\MultilingualPosts\Post;
use Dymantic\MultilingualPosts\Tests\MakesPosts;
use Dymantic\MultilingualPosts\Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\Models\Media;

class PostTitleImagesTest extends TestCase
{
    use MakesPosts;

    /**
     *@test
     */
    public function a_title_image_can_be_set_on_a_post()
    {
        $post = $this->makePost();

        $image = $post->setTitleImage(UploadedFile::fake()->image('testpic.png'));

        $this->assertInstanceOf(Media::class, $image);
        $this->assertCount(1, $post->fresh()->getMedia(Post::TITLE_IMAGES));
    }

    /**
     *@test
     */
    public function conversions_are_generated_for_the_title_image()
    {
        $post = $this->makePost();

        $image = $post->setTitleImage(UploadedFile::fake()->image('testpic.png'))->fresh();

        $this->assertTrue($image->hasGeneratedConversion('banner'), 'No banner conversion generated');
        $this->assertTrue($image->hasGeneratedConversion('web'), 'No web conversion generated');
        $this->assertTrue($image->hasGeneratedConversion('thumb'), 'No thumb conversion generated');
    }

    /**
     *@test
     */
    public function setting_a_posts_title_image_will_clear_out_any_previous_title_images()
    {
        $post = $this->makePost();
        $post->setTitleImage(UploadedFile::fake()->image('testpic.png'));
        $this->assertCount(1, $post->fresh()->getMedia(Post::TITLE_IMAGES));

        $new_image = $post->setTitleImage(UploadedFile::fake()->image('other_pic.png'));
        $this->assertCount(1, $post->fresh()->getMedia(Post::TITLE_IMAGES));

        $this->assertTrue($post->fresh()->getFirstMedia(Post::TITLE_IMAGES)->is($new_image));
    }

    /**
     *@test
     */
    public function a_title_image_can_be_cleared()
    {
        $post = $this->makePost();
        $post->setTitleImage(UploadedFile::fake()->image('testpic.png'));
        $this->assertCount(1, $post->fresh()->getMedia(Post::TITLE_IMAGES));

        $post->clearTitleImage();

        $this->assertCount(0, $post->fresh()->getMedia(Post::TITLE_IMAGES));
    }
}
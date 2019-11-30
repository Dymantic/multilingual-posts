<?php


namespace Dymantic\MultilingualPosts\Tests\unit;


use Dymantic\MultilingualPosts\Post;
use Dymantic\MultilingualPosts\Tests\UsesModels;
use Dymantic\MultilingualPosts\Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\Models\Media;

class PostTitleImagesTest extends TestCase
{
    use UsesModels;

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
    public function standard_conversions_are_generated_if_no_config()
    {
        config(['multilingual-posts.conversions' => null]);

        $post = $this->makePost();
        $image = $post->setTitleImage(UploadedFile::fake()->image('testpic.png', 3000, 2000))->fresh();

        $this->assertTrue($image->hasGeneratedConversion('web'), 'web conversion not generated');
        $this->assertTrue($image->hasGeneratedConversion('thumb'), 'thumb conversion not generated');
        $this->assertTrue($image->hasGeneratedConversion('banner'), 'banner conversion not generated');

        $thumbSize = getimagesize($image->getPath('thumb'));
        $this->assertEquals(400, $thumbSize[0]);
        $this->assertEquals(300, $thumbSize[1]);

        $webSize = getimagesize($image->getPath('web'));
        $this->assertEquals(800, $webSize[0]);
        $this->assertEquals(533, $webSize[1]);

        $bannerSize = getimagesize($image->getPath('banner'));
        $this->assertEquals(1400, $bannerSize[0]);
        $this->assertEquals(933, $bannerSize[1]);
    }

    /**
     *@test
     */
    public function conversions_can_be_generated_from_config()
    {
        config(['multilingual-posts.conversions' => [
            ['name' => 'thumb', 'manipulation' => 'crop', 'width' => 300, 'height' => 200, 'title' => true, 'post' => false],
            ['name' => 'web', 'manipulation' => 'fit', 'width' => 1600, 'height' => 1000, 'title' => true, 'post' => false],
            ['name' => 'banner', 'manipulation' => 'crop', 'width' => 2000, 'height' => 1000, 'title' => true, 'post' => false],
        ]]);

        $post = $this->makePost();
        $image = $post->setTitleImage(UploadedFile::fake()->image('testpic.png', 3000, 2000))->fresh();

        $this->assertTrue($image->hasGeneratedConversion('web'), 'web conversion not generated');
        $this->assertTrue($image->hasGeneratedConversion('thumb'), 'thumb conversion not generated');
        $this->assertTrue($image->hasGeneratedConversion('banner'), 'banner conversion not generated');

        $thumbSize = getimagesize($image->getPath('thumb'));
        $this->assertEquals(300, $thumbSize[0]);
        $this->assertEquals(200, $thumbSize[1]);

        $webSize = getimagesize($image->getPath('web'));
        $this->assertEquals(1500, $webSize[0]);
        $this->assertEquals(1000, $webSize[1]);

        $bannerSize = getimagesize($image->getPath('banner'));
        $this->assertEquals(2000, $bannerSize[0]);
        $this->assertEquals(1000, $bannerSize[1]);


    }

    /**
     *@test
     */
    public function the_title_image_src_can_be_queried()
    {
        $post = $this->makePost();
        $image = $post->setTitleImage(UploadedFile::fake()->image('testpic.png'))->fresh();

        $post = $post->fresh();

        $this->assertEquals($image->getUrl(), $post->titleImage());
        $this->assertEquals($image->getUrl('banner'), $post->titleImage('banner'));
        $this->assertEquals($image->getUrl('web'), $post->titleImage('web'));
        $this->assertEquals($image->getUrl('thumb'), $post->titleImage('thumb'));
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
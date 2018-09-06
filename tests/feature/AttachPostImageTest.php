<?php


namespace Dymantic\MultilingualPosts\Tests\feature;


use Dymantic\MultilingualPosts\Post;
use Dymantic\MultilingualPosts\Tests\MakesPosts;
use Dymantic\MultilingualPosts\Tests\TestCase;
use Illuminate\Http\UploadedFile;

class AttachPostImageTest extends TestCase
{
    use MakesPosts;

    /**
     *@test
     */
    public function an_image_can_be_attached_to_a_post()
    {
        $this->withoutExceptionHandling();
        $post = $this->makePost();

        $response = $this->asLoggedInUser()->postJson("/multilingual-posts/posts/{$post->id}/images", [
            'image' => UploadedFile::fake()->image('testpic.png')
        ]);
        $response->assertStatus(201);

        $this->assertCount(1, $post->fresh()->getMedia(Post::BODY_IMAGES));
    }

    /**
     *@test
     */
    public function attaching_an_image_responds_with_image_src()
    {
        $this->withoutExceptionHandling();
        $post = $this->makePost();

        $response = $this->asLoggedInUser()->postJson("/multilingual-posts/posts/{$post->id}/images", [
            'image' => UploadedFile::fake()->image('testpic.png')
        ]);
        $response->assertStatus(201);

        $this->assertEquals($post->fresh()->getFirstMedia(Post::BODY_IMAGES)->getUrl('web'), $response->decodeResponseJson('src'));
    }

    /**
     *@test
     */
    public function the_image_is_required()
    {
        $post = $this->makePost();

        $response = $this->asLoggedInUser()->postJson("/multilingual-posts/posts/{$post->id}/images", [
            'image' => null
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('image');
    }

    /**
     *@test
     */
    public function the_image_must_be_an_valid_image_file()
    {
        $post = $this->makePost();

        $response = $this->asLoggedInUser()->postJson("/multilingual-posts/posts/{$post->id}/images", [
            'image' => UploadedFile::fake()->create('not-a-valid-image.txt')
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('image');
    }
}
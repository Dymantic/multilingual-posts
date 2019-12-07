<?php


namespace Dymantic\MultilingualPosts\Tests\feature;


use Dymantic\MultilingualPosts\Post;
use Dymantic\MultilingualPosts\TestMediaBroker;
use Dymantic\MultilingualPosts\Tests\UsesModels;
use Dymantic\MultilingualPosts\Tests\TestCase;
use Illuminate\Http\UploadedFile;

class AttachPostImageTest extends TestCase
{
    use UsesModels;

    /**
     *@test
     */
    public function an_image_can_be_attached_to_a_post()
    {
        config(['multilingual-posts.media-broker' => TestMediaBroker::class]);

        $this->withoutExceptionHandling();
        $post = $this->makePost();

        $response = $this->asLoggedInUser()->postJson("/multilingual-posts/posts/{$post->id}/images", [
            'image' => UploadedFile::fake()->image('testpic.png')
        ]);

        $response->assertStatus(201);


        $this->assertEquals('test-image-from-test-broker.png', $response->decodeResponseJson('src'));
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
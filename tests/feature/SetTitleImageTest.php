<?php


namespace Dymantic\MultilingualPosts\Tests\feature;


use Dymantic\MultilingualPosts\Post;
use Dymantic\MultilingualPosts\TestMediaBroker;
use Dymantic\MultilingualPosts\Tests\UsesModels;
use Dymantic\MultilingualPosts\Tests\TestCase;
use Illuminate\Http\UploadedFile;

class SetTitleImageTest extends TestCase
{
    use UsesModels;

    /**
     * @test
     */
    public function a_title_image_can_be_set_for_the_post()
    {
        config(['multilingual-posts.media-broker' => TestMediaBroker::class]);

        $this->withoutExceptionHandling();
        $post = $this->makePost();

        $response = $this->asLoggedInUser()
                         ->postJson("/multilingual-posts/posts/{$post->id}/title-image", [
                            'image' => UploadedFile::fake()->image('testpic.png')
                         ]);
        $response->assertStatus(200);

        $this->assertEquals('test-image-from-test-broker.png', $response->decodeResponseJson('image_src'));
    }

    /**
     *@test
     */
    public function the_image_is_required()
    {
        $post = $this->makePost();
        $response = $this->asLoggedInUser()
                         ->postJson("/multilingual-posts/posts/{$post->id}/title-image", [
                             'image' => null
                         ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('image');
    }

    /**
     *@test
     */
    public function the_image_must_be_a_valid_image_file()
    {
        $post = $this->makePost();
        $response = $this->asLoggedInUser()
                         ->postJson("/multilingual-posts/posts/{$post->id}/title-image", [
                             'image' => UploadedFile::fake()->create('not-an-image.txt')
                         ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('image');
    }

}
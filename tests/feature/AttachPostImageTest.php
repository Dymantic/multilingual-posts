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
}
<?php


namespace Dymantic\MultilingualPosts\Tests\feature;


use Dymantic\MultilingualPosts\Post;
use Dymantic\MultilingualPosts\Tests\MakesPosts;
use Dymantic\MultilingualPosts\Tests\TestCase;

class UpdatePostTest extends TestCase
{
    use MakesPosts;
    /**
     *@test
     */
    public function an_existing_post_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $post = $this->makePost();

        $response = $this->asLoggedInUser()->postJson("/multilingual-posts/{$post->id}", [
            'title'       => ['en' => 'new title', 'fr' => 'new titley'],
            'intro'       => ['en' => 'new intro', 'fr' => 'new introy'],
            'description' => ['en' => 'new description', 'fr' => 'new descriptiony'],
            'body'        => ['en' => 'new body', 'fr' => 'new bodyy'],
        ]);
        $response->assertStatus(200);

        $this->assertDatabaseHasWithTranslations('multilingual_posts', [
            'id' => $post->id,
            'title'       => ['en' => 'new title', 'fr' => 'new titley'],
            'intro'       => ['en' => 'new intro', 'fr' => 'new introy'],
            'description' => ['en' => 'new description', 'fr' => 'new descriptiony'],
            'body'        => ['en' => 'new body', 'fr' => 'new bodyy'],
        ]);
    }


}
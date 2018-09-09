<?php


namespace Dymantic\MultilingualPosts\Tests\feature;


use Dymantic\MultilingualPosts\Category;
use Dymantic\MultilingualPosts\Post;
use Dymantic\MultilingualPosts\Tests\UsesModels;
use Dymantic\MultilingualPosts\Tests\TestCase;

class UpdatePostTest extends TestCase
{
    use UsesModels;
    /**
     *@test
     */
    public function an_existing_post_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $post = $this->makePost();

        $response = $this->asLoggedInUser()->postJson("/multilingual-posts/posts/{$post->id}", [
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

    /**
     *@test
     */
    public function categories_can_be_set_in_update()
    {
        $categoryA = $this->makeCategory();
        $categoryB = $this->makeCategory();
        $categoryC = $this->makeCategory();
        $this->withoutExceptionHandling();

        $post = $this->makePost();
        $post->addCategory($categoryC);

        $response = $this->asLoggedInUser()->postJson("/multilingual-posts/posts/{$post->id}", [
            'title'       => ['en' => 'new title', 'fr' => 'new titley'],
            'intro'       => ['en' => 'new intro', 'fr' => 'new introy'],
            'description' => ['en' => 'new description', 'fr' => 'new descriptiony'],
            'body'        => ['en' => 'new body', 'fr' => 'new bodyy'],
            'category_id' => [$categoryA->id, $categoryB->id]
        ]);
        $response->assertStatus(200);

        $this->assertDatabaseHasWithTranslations('multilingual_posts', [
            'id' => $post->id,
            'title'       => ['en' => 'new title', 'fr' => 'new titley'],
            'intro'       => ['en' => 'new intro', 'fr' => 'new introy'],
            'description' => ['en' => 'new description', 'fr' => 'new descriptiony'],
            'body'        => ['en' => 'new body', 'fr' => 'new bodyy'],
        ]);

        $this->assertCount(2, $post->fresh()->categories);
        $this->assertTrue($post->fresh()->categories->contains($categoryA));
        $this->assertTrue($post->fresh()->categories->contains($categoryB));
    }


}
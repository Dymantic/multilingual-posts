<?php


namespace Dymantic\MultilingualPosts\Tests\feature;


use Dymantic\MultilingualPosts\Tests\TestCase;
use Dymantic\MultilingualPosts\Tests\UsesModels;

class UpdateCategoryTest extends TestCase
{
    use UsesModels;

    /**
     *@test
     */
    public function an_existing_category_can_be_updated()
    {
        $this->withoutExceptionHandling();
        $category = $this->makeCategory();

        $update_data = [
            'title' => ['en' => 'New title', 'fr' => 'New titley'],
            'intro' => ['en' => 'New intro', 'fr' => 'New introy'],
            'description' => ['en' => 'New description', 'fr' => 'New descriptiony'],
        ];

        $response = $this->asLoggedInUser()
                         ->postJson("/multilingual-posts/categories/{$category->id}", $update_data);
        $response->assertStatus(200);

        $this->assertDatabaseHasWithTranslations('multilingual_categories', array_merge(['id' => $category->id], $update_data));
    }
}
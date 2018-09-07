<?php


namespace Dymantic\MultilingualPosts\Tests\feature;


use Dymantic\MultilingualPosts\Tests\TestCase;

class CreateCategoryTest extends TestCase
{
    /**
     *@test
     */
    public function a_new_category_can_be_created()
    {
        $this->withoutExceptionHandling();

        $category_data = [
            'title' => ['en' => 'Test title', 'fr' => 'Test titley'],
            'intro' => ['en' => 'Test intro', 'fr' => 'Test introy'],
            'description' => ['en' => 'Test description', 'fr' => 'Test descriptiony'],
        ];

        $response = $this->asLoggedInUser()->postJson("/multilingual-posts/categories", $category_data);

        $response->assertStatus(201);

        $this->assertDatabaseHasWithTranslations('multilingual_categories', $category_data);
    }

    /**
     *@test
     */
    public function a_category_needs_a_title_in_at_least_one_language_to_be_valid()
    {
        $response = $this->asLoggedInUser()->postJson("/multilingual-posts/categories", [
            'title' => ['no lang']
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('title');
    }
}
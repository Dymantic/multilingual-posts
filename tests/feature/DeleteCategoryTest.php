<?php


namespace Dymantic\MultilingualPosts\Tests\feature;


use Dymantic\MultilingualPosts\Tests\TestCase;
use Dymantic\MultilingualPosts\Tests\UsesModels;

class DeleteCategoryTest extends TestCase
{
    use UsesModels;

    /**
     *@test
     */
    public function an_existing_category_can_be_deleted()
    {
        $this->withoutExceptionHandling();
        $category = $this->makeCategory();

        $response = $this->asLoggedInUser()->deleteJson("/multilingual-posts/categories/{$category->id}");
        $response->assertStatus(200);

        $this->assertDatabaseMissing('multilingual_categories', ['id' => $category->id]);
    }
}
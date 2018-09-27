<?php


namespace Dymantic\MultilingualPosts\Tests\unit;


use Dymantic\MultilingualPosts\Tests\TestCase;
use Dymantic\MultilingualPosts\Tests\UsesModels;

class CategoriesPostsTest extends TestCase
{
    use UsesModels;

    /**
     *@test
     */
    public function a_categories_posts_can_be_accessed_from_the_category()
    {
        $category = $this->makeCategory();
        $postA = $this->makePost();
        $postB = $this->makePost();
        $postC = $this->makePost();

        $postA->safeUpdate(['category_id' => $category->id]);
        $postB->safeUpdate(['category_id' => $category->id]);

        $categoryPosts = $category->posts;

        $this->assertTrue($categoryPosts->contains($postA));
        $this->assertTrue($categoryPosts->contains($postB));
        $this->assertFalse($categoryPosts->contains($postC));
    }
}
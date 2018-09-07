<?php


namespace Dymantic\MultilingualPosts\Tests\unit;


use Dymantic\MultilingualPosts\Tests\TestCase;
use Dymantic\MultilingualPosts\Tests\UsesModels;

class PostCategoriesTest extends TestCase
{
    use UsesModels;

    /**
     *@test
     */
    public function a_category_can_be_attached_to_a_post()
    {
        $category = $this->makeCategory();
        $post = $this->makePost();

        $post->addCategory($category);

        $this->assertTrue($post->fresh()->categories->contains($category));
    }

    /**
     *@test
     */
    public function a_category_can_be_removed_from_a_post()
    {
        $category = $this->makeCategory();
        $post = $this->makePost();
        $post->addCategory($category);

        $post->fresh()->removeCategory($category);

        $this->assertFalse($post->fresh()->categories->contains($category));
    }

    /**
     *@test
     */
    public function a_collection_of_categories_can_be_set_on_a_post()
    {
        $post = $this->makePost();
        $categories = collect([1,2,3])->map(function($i) {
            return $this->makeCategory();
        });

        $post->setCategories($categories);

        $this->assertCount(3, $post->fresh()->categories);

        $post->fresh()->categories->each(function($post_category) use ($categories) {
            $this->assertContains($post_category->id, $categories->pluck('id'));
        });
    }

    /**
     *@test
     */
    public function setting_the_categories_will_override_any_previously_set_categories()
    {
        $post = $this->makePost();
        $prev_category = $this->makeCategory();
        $post->addCategory($prev_category);

        $categories = collect([1,2,3])->map(function($i) {
            return $this->makeCategory();
        });

        $post->setCategories($categories);

        $this->assertNotContains($prev_category->id, $post->fresh()->categories->pluck('id'));

        $this->assertCount(3, $post->fresh()->categories);

        $post->fresh()->categories->each(function($post_category) use ($categories) {
            $this->assertContains($post_category->id, $categories->pluck('id'));
        });
    }
}
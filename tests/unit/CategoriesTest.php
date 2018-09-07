<?php


namespace Dymantic\MultilingualPosts\Tests\unit;


use Dymantic\MultilingualPosts\Category;
use Dymantic\MultilingualPosts\Tests\TestCase;

class CategoriesTest extends TestCase
{
    /**
     *@test
     */
    public function a_newly_created_category_has_a_slug()
    {
        $category = Category::create([
            'title'       => ['en' => 'test title', 'fr' => 'test titley'],
            'intro'       => ['en' => 'test intro', 'fr' => 'test introy'],
            'description' => ['en' => 'test description', 'fr' => 'test descriptiony'],
        ]);

        $this->assertEquals('test-title', $category->fresh()->slug);
    }
}
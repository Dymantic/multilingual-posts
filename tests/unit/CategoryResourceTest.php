<?php


namespace Dymantic\MultilingualPosts\Tests\unit;


use Dymantic\MultilingualPosts\CategoryResource;
use Dymantic\MultilingualPosts\Tests\TestCase;
use Dymantic\MultilingualPosts\Tests\UsesModels;

class CategoryResourceTest extends TestCase
{
    use UsesModels;

    /**
     *@test
     */
    public function a_category_can_be_presented_as_a_category_resource()
    {
        $category = $this->makeCategory([
            'title' => ['en' => 'test title', 'fr' => 'test titley'],
            'intro' => ['en' => 'test intro', 'fr' => 'test introy'],
            'description' => ['en' => 'test description', 'fr' => 'test descriptiony']
        ]);

        $expected = [
            'id' => $category->id,
            'slug' => 'test-title',
            'title' => ['en' => 'test title', 'fr' => 'test titley'],
            'intro' => ['en' => 'test intro', 'fr' => 'test introy'],
            'description' => ['en' => 'test description', 'fr' => 'test descriptiony']
        ];

        $this->assertEquals($expected, (new CategoryResource($category))->toArray(request()));
    }
}
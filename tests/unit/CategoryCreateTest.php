<?php


namespace Dymantic\MultilingualPosts\Tests\unit;


use Dymantic\MultilingualPosts\InvalidAttributesException;
use Dymantic\MultilingualPosts\Category;
use Dymantic\MultilingualPosts\Tests\TestCase;

class CategoryCreateTest extends TestCase
{
    /**
     *@test
     */
    public function a_category_can_be_created_with_only_a_title()
    {
        $category = Category::create(['title' => ['en' => 'Test category', 'fr' => 'Test titley']]);

        $this->assertInstanceOf(Category::class, $category);

        $category = $category->fresh();

        $this->assertEquals(['en' => 'Test category', 'fr' => 'Test titley'], $category->getTranslations('title'));
        $this->assertEquals(['en' => ''], $category->getTranslations('intro'));
        $this->assertEquals(['en' => ''], $category->getTranslations('description'));
    }

    /**
     *@test
     */
    public function a_category_cannot_be_created_without_a_title()
    {
        try {
            Category::create(['intro' => ['en' => 'Test intro', 'fr' => 'Test introy']]);

            $this->fail('Expected to see exception');
        } catch(\Exception $e) {
            $this->assertInstanceOf(InvalidAttributesException::class, $e);
            $this->assertEquals('a title is required to create a category', $e->getMessage());
        }
    }

    /**
     *@test
     */
    public function a_category_cannot_be_created_with_an_empty_title()
    {
        try {
            Category::create(['title' => ['en' => '']]);

            $this->fail('Expected to see exception');
        } catch(\Exception $e) {
            $this->assertInstanceOf(InvalidAttributesException::class, $e);
            $this->assertEquals('a title is required to create a category', $e->getMessage());
        }

        try {
            Category::create(['title' => '']);

            $this->fail('Expected to see exception');
        } catch(\Exception $e) {
            $this->assertInstanceOf(InvalidAttributesException::class, $e);
            $this->assertEquals('a title is required to create a category', $e->getMessage());
        }
    }

    /**
     *@test
     */
    public function translatable_fields_with_only_string_values_will_be_converted_to_default_translations()
    {
        $category = Category::create([
            'title' => 'Test title',
            'intro' => 'Test intro',
            'description' => 'Test description',
        ]);

        $category = $category->fresh();

        $this->assertEquals(['en' => 'Test title'], $category->getTranslations('title'));
        $this->assertEquals(['en' => 'Test intro'], $category->getTranslations('intro'));
        $this->assertEquals(['en' => 'Test description'], $category->getTranslations('description'));
    }
}
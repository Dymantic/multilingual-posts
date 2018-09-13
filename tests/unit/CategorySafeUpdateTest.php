<?php


namespace Dymantic\MultilingualPosts\Tests\unit;


use Dymantic\MultilingualPosts\InvalidAttributesException;
use Dymantic\MultilingualPosts\Tests\TestCase;
use Dymantic\MultilingualPosts\Tests\UsesModels;

class CategorySafeUpdateTest extends TestCase
{
    use UsesModels;

    /**
     *@test
     */
    public function a_category_cannot_be_updated_without_a_valid_title()
    {
        $category = $this->makeCategory(['title' => ['en' => 'Test title']]);

        try {
            $category->safeUpdate(['title' => ['en' => '']]);

            $this->fail('Expected to see InvalidAttributesException');
        } catch (InvalidAttributesException $e) {
            $this->assertEquals('a title is required for the category', $e->getMessage());
            $this->assertEquals(['en' => 'Test title'], $category->fresh()->getTranslations('title'));
        }
    }

    /**
     *@test
     */
    public function fields_not_included_in_the_update_are_not_overwritten()
    {
        $category = $this->makeCategory(['intro' => ['en' => 'old intro']]);

        $category->safeUpdate([
            'title' => 'new title',
            'description' => 'new description'
        ]);

        $category = $category->fresh();

        $this->assertEquals(['en' => 'new title'], $category->getTranslations('title'));
        $this->assertEquals(['en' => 'old intro'], $category->getTranslations('intro'));
        $this->assertEquals(['en' => 'new description'], $category->getTranslations('description'));
    }

    /**
     *@test
     */
    public function string_attributes_will_be_converted_to_default_lang_translations_and_overwrite_others()
    {
        $category = $this->makeCategory([
            'title'       => ['en' => 'test title', 'fr' => 'test titley'],
            'intro'       => ['en' => 'test intro', 'fr' => 'test introy'],
            'description' => ['en' => 'test description', 'fr' => 'test descriptiony'],
        ]);

        $category->safeUpdate([
            'title' => 'new title',
            'intro' => 'new intro',
            'description' => 'new description',
        ]);

        $category = $category->fresh();

        $this->assertEquals(['en' => 'new title'], $category->getTranslations('title'));
        $this->assertEquals(['en' => 'new intro'], $category->getTranslations('intro'));
        $this->assertEquals(['en' => 'new description'], $category->getTranslations('description'));
    }
}
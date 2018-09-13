<?php


namespace Dymantic\MultilingualPosts\Tests\unit;


use Dymantic\MultilingualPosts\InvalidAttributesException;
use Dymantic\MultilingualPosts\Tests\TestCase;
use Dymantic\MultilingualPosts\Tests\UsesModels;

class PostSafeUpdateTest extends TestCase
{
    use UsesModels;

    /**
     *@test
     */
    public function a_post_cannot_be_safe_updated_with_an_empty_title()
    {
        $post = $this->makePost();

        try {
            $post->safeUpdate(['title' => '']);

            $this->fail('Expected exception to be thrown');
        } catch (\Exception $e) {
            $this->assertInstanceOf(InvalidAttributesException::class, $e);
        }
    }

    /**
     *@test
     */
    public function safe_update_does_a_regular_update()
    {
        $post = $this->makePost();
        $data = [
            'title' => ['en' => 'New title'],
            'intro' => ['en' => 'New intro'],
            'description' => ['en' => 'New description'],
            'body' => ['en' => 'New body'],
        ];

        $post->safeUpdate($data);
        $post = $post->fresh();

        $this->assertEquals(['en' => 'New title'], $post->getTranslations('title'));
        $this->assertEquals(['en' => 'New intro'], $post->getTranslations('intro'));
        $this->assertEquals(['en' => 'New description'], $post->getTranslations('description'));
        $this->assertEquals(['en' => 'New body'], $post->getTranslations('body'));
    }

    /**
     *@test
     */
    public function attributes_not_included_in_update_data_are_not_updated()
    {
        $post = $this->makePost();
        $data = [
            'intro' => ['en' => 'New intro'],
        ];

        $original_title = $post->getTranslations('title');
        $original_description = $post->getTranslations('description');
        $original_body = $post->getTranslations('body');

        $post->safeUpdate($data);
        $post = $post->fresh();

        $this->assertEquals($original_title, $post->getTranslations('title'));
        $this->assertEquals(['en' => 'New intro'], $post->getTranslations('intro'));
        $this->assertEquals($original_description, $post->getTranslations('description'));
        $this->assertEquals($original_body, $post->getTranslations('body'));
    }

    /**
     *@test
     */
    public function safe_updating_with_str_values_for_translatable_fields_will_be_formatted_to_default()
    {
        $post = $this->makePost();
        $data = [
            'title' => 'New title',
            'intro' => 'New intro',
            'description' => 'New description',
            'body' => 'New body',
        ];

        $post->safeUpdate($data);
        $post = $post->fresh();


        $this->assertEquals(['en' => 'New title'], $post->getTranslations('title'));
        $this->assertEquals(['en' => 'New intro'], $post->getTranslations('intro'));
        $this->assertEquals(['en' => 'New description'], $post->getTranslations('description'));
        $this->assertEquals(['en' => 'New body'], $post->getTranslations('body'));
    }

    /**
     *@test
     */
    public function including_category_ids_in_update_data_will_set_categories()
    {
        $post = $this->makePost();
        $categoryA = $this->makeCategory();
        $categoryB = $this->makeCategory();

        $data = [
            'title' => ['en' => 'New title'],
            'intro' => ['en' => 'New intro'],
            'description' => ['en' => 'New description'],
            'body' => ['en' => 'New body'],
            'category_id' => [$categoryA->id, $categoryB->id]
        ];

        $post->safeUpdate($data);
        $post = $post->fresh();

        $this->assertCount(2, $post->categories);
        $this->assertTrue($post->categories->contains($categoryA));
        $this->assertTrue($post->categories->contains($categoryB));

        $this->assertEquals(['en' => 'New title'], $post->getTranslations('title'));
        $this->assertEquals(['en' => 'New intro'], $post->getTranslations('intro'));
        $this->assertEquals(['en' => 'New description'], $post->getTranslations('description'));
        $this->assertEquals(['en' => 'New body'], $post->getTranslations('body'));


    }
}
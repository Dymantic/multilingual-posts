<?php


namespace Dymantic\MultilingualPosts\Tests\unit;


use Dymantic\MultilingualPosts\BadPostDataException;
use Dymantic\MultilingualPosts\Post;
use Dymantic\MultilingualPosts\Tests\TestCase;
use Dymantic\MultilingualPosts\Tests\UsesModels;

class PostCreateTest extends TestCase
{
    use UsesModels;

    /**
     * @test
     */
    public function a_post_created_from_a_valid_array_has_the_correct_properties()
    {
        $data = [
            'title'       => ['en' => 'Test title', 'fr' => 'Test titley'],
            'intro'       => ['en' => 'Test intro', 'fr' => 'Test introy'],
            'description' => ['en' => 'Test description', 'fr' => 'Test descriptiony'],
            'body'        => ['en' => 'Test body', 'fr' => 'Test bodyy']
        ];

        $post = Post::create($data);

        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals(['en' => 'Test title', 'fr' => 'Test titley'], $post->getTranslations('title'));
        $this->assertEquals(['en' => 'Test intro', 'fr' => 'Test introy'], $post->getTranslations('intro'));
        $this->assertEquals(['en' => 'Test description', 'fr' => 'Test descriptiony'], $post->getTranslations('description'));
        $this->assertEquals(['en' => 'Test body', 'fr' => 'Test bodyy'], $post->getTranslations('body'));

        $this->assertPostHasDefaults($post);
    }

    /**
     *@test
     */
    public function a_post_can_be_created_with_only_a_title()
    {
        $data = ['title' => ['en' => 'Test title']];

        $post = Post::create($data);

        $this->assertEquals(['en' => 'Test title'], $post->getTranslations('title'));
        $this->assertEquals(['en' => ''], $post->getTranslations('intro'));
        $this->assertEquals(['en' => ''], $post->getTranslations('description'));
        $this->assertEquals(['en' => ''], $post->getTranslations('body'));

        $this->assertPostHasDefaults($post);
    }

    /**
     * @test
     */
    public function a_post_cannot_be_created_without_a_title()
    {
        try {
            Post::create(['intro' => ['en' => 'Test intro']]);

            $this->fail('Expected exception to be thrown');
        } catch(\Exception $e) {
            $this->assertInstanceOf(BadPostDataException::class, $e);
            $this->assertEquals('a title is required to create a post', $e->getMessage());
        }
    }

    /**
     *@test
     */
    public function a_post_cannot_be_created_with_an_empty_title()
    {
        try {
            Post::create(['title' => '']);

            $this->fail('Expected exception to be thrown');
        } catch(\Exception $e) {
            $this->assertInstanceOf(BadPostDataException::class, $e);
            $this->assertEquals('a title is required to create a post', $e->getMessage());
        }
    }
    
    /**
     *@test
     */
    public function a_string_translatable_field_will_be_automatically_converted_into_a_translation_array()
    {
        $data = [
            'title'       => 'Test title',
            'intro'       => 'Test intro',
            'description' => 'Test description',
            'body'        => 'Test body'
        ];

        $post = Post::create($data);

        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals(['en' => 'Test title'], $post->getTranslations('title'));
        $this->assertEquals(['en' => 'Test intro'], $post->getTranslations('intro'));
        $this->assertEquals(['en' => 'Test description'], $post->getTranslations('description'));
        $this->assertEquals(['en' => 'Test body'], $post->getTranslations('body'));

        $this->assertPostHasDefaults($post);
    }

    /**
     *@test
     */
    public function including_a_category_id_in_post_data_will_sync_post_category()
    {
        $category = $this->makeCategory();
        $data = [
            'title'       => ['en' => 'Test title'],
            'intro'       => ['en' => 'Test intro'],
            'description' => ['en' => 'Test description'],
            'body'        => ['en' => 'Test body'],
            'category_id' => $category->id
        ];

        $post = Post::create($data);

        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals(['en' => 'Test title'], $post->getTranslations('title'));
        $this->assertEquals(['en' => 'Test intro'], $post->getTranslations('intro'));
        $this->assertEquals(['en' => 'Test description'], $post->getTranslations('description'));
        $this->assertEquals(['en' => 'Test body'], $post->getTranslations('body'));

        $this->assertPostHasDefaults($post);

        $this->assertTrue($post->fresh()->categories->contains($category));
    }

    /**
     *@test
     */
    public function an_array_of_category_ids_can_be_used_to_set_multiple_categories()
    {
        $categoryA = $this->makeCategory();
        $categoryB = $this->makeCategory();
        $data = [
            'title'       => ['en' => 'Test title'],
            'intro'       => ['en' => 'Test intro'],
            'description' => ['en' => 'Test description'],
            'body'        => ['en' => 'Test body'],
            'category_id' => [$categoryA->id, $categoryB->id]
        ];

        $post = Post::create($data);

        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals(['en' => 'Test title'], $post->getTranslations('title'));
        $this->assertEquals(['en' => 'Test intro'], $post->getTranslations('intro'));
        $this->assertEquals(['en' => 'Test description'], $post->getTranslations('description'));
        $this->assertEquals(['en' => 'Test body'], $post->getTranslations('body'));

        $this->assertPostHasDefaults($post);

        $this->assertCount(2, $post->fresh()->categories);
        $this->assertTrue($post->fresh()->categories->contains($categoryA));
        $this->assertTrue($post->fresh()->categories->contains($categoryB));
    }

    private function assertPostHasDefaults($post)
    {
        $post = $post->fresh();
        $this->assertNull($post->first_published_on);
        $this->assertNull($post->publish_date);
        $this->assertTrue($post->is_draft);
    }
}
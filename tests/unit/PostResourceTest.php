<?php


namespace Dymantic\MultilingualPosts\Tests\unit;


use Dymantic\MultilingualPosts\Post;
use Dymantic\MultilingualPosts\PostResource;
use Dymantic\MultilingualPosts\Tests\TestCase;

class PostResourceTest extends TestCase
{
    /**
     * @test
     */
    public function a_post_resource_has_the_expected_data_points()
    {
        $post = Post::create([
            'title'       => ['en' => 'test title', 'fr' => 'test titley'],
            'intro'       => ['en' => 'test intro', 'fr' => 'test introy'],
            'description' => ['en' => 'test description', 'fr' => 'test descriptiony'],
            'body'        => ['en' => 'test body', 'fr' => 'test bodyy']
        ]);

        $expected = [
            'id'                   => $post->id,
            'slug'                 => 'test-title',
            'title'                => ['en' => 'test title', 'fr' => 'test titley'],
            'intro'                => ['en' => 'test intro', 'fr' => 'test introy'],
            'description'          => ['en' => 'test description', 'fr' => 'test descriptiony'],
            'body'                 => ['en' => 'test body', 'fr' => 'test bodyy'],
            'created_at'           => $post->created_at->format('d M Y'),
            'updated_at'           => $post->updated_at->format('d M Y'),
            'is_draft'             => true,
            'author'               => null,
            'published_on'         => null,
            'title_image_original' => null,
            'title_image_thumb'    => null
        ];

        $this->assertEquals($expected, (new PostResource($post->fresh()))->toArray(request()));
    }


}
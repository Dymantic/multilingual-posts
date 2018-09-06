<?php


namespace Dymantic\MultilingualPosts\Tests\unit;


use Dymantic\MultilingualPosts\Post;
use Dymantic\MultilingualPosts\PostResource;
use Dymantic\MultilingualPosts\Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;

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

        $image = $post->setTitleImage(UploadedFile::fake()->image('testpic.png'));

        $post->publish();
        $post->publish(Carbon::today()->addWeek());

        $expected = [
            'id'                   => $post->id,
            'slug'                 => 'test-title',
            'title'                => ['en' => 'test title', 'fr' => 'test titley'],
            'intro'                => ['en' => 'test intro', 'fr' => 'test introy'],
            'description'          => ['en' => 'test description', 'fr' => 'test descriptiony'],
            'body'                 => ['en' => 'test body', 'fr' => 'test bodyy'],
            'created_at'           => $post->created_at->format('d M Y'),
            'updated_at'           => $post->updated_at->format('d M Y'),
            'is_draft'             => false,
            'is_live'              => false,
            'publish_date'         => Carbon::today()->addWeek()->format('d M Y'),
            'author'               => null,
            'first_published_on'         => Carbon::today()->format('d M Y'),
            'title_image_original' => $image->getUrl(),
            'title_image_banner'   => $image->getUrl('banner'),
            'title_image_web'      => $image->getUrl('web'),
            'title_image_thumb'    => $image->getUrl('thumb'),
        ];

        $this->assertEquals($expected, (new PostResource($post->fresh()))->toArray(request()));
    }


}
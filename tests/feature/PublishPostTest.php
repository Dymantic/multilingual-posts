<?php


namespace Dymantic\MultilingualPosts\Tests\feature;


use Dymantic\MultilingualPosts\PostResource;
use Dymantic\MultilingualPosts\Tests\ComparesResources;
use Dymantic\MultilingualPosts\Tests\UsesModels;
use Dymantic\MultilingualPosts\Tests\TestCase;
use Illuminate\Support\Carbon;

class PublishPostTest extends TestCase
{
    use UsesModels, ComparesResources;
    /**
     *@test
     */
    public function a_post_may_be_made_into_a_published_post()
    {
        $this->withoutExceptionHandling();
        $post = $this->makePost();

        $response = $this->asLoggedInUser()->postJson("/multilingual-posts/published-posts", [
            "post_id" => $post->id
        ]);
        $response->assertStatus(200);

        $this->assertDatabaseHas('multilingual_posts', ['id' => $post->id, 'is_draft' => false]);
    }

    /**
     *@test
     */
    public function a_post_may_be_scheduled_to_be_published_at_a_given_date()
    {
        $this->withoutExceptionHandling();
        $post = $this->makePost();
        $publish_date = Carbon::today()->addWeek();

        $response = $this->asLoggedInUser()->postJson("/multilingual-posts/published-posts", [
            "post_id" => $post->id,
            'publish_date' => $publish_date->format('Y-m-d')
        ]);
        $response->assertStatus(200);

        $this->assertDatabaseHas('multilingual_posts', [
            'id' => $post->id,
            'is_draft' => false,
            'publish_date' => $publish_date
        ]);
    }

    /**
     *@test
     */
    public function publishing_a_post_responds_with_fresh_post_data()
    {
        $this->withoutExceptionHandling();
        $post = $this->makePost();

        $response = $this->asLoggedInUser()->postJson("/multilingual-posts/published-posts", [
            "post_id" => $post->id
        ]);
        $response->assertStatus(200);

        $expected = $this->getResourceResponseData(new PostResource($post->fresh()));

        $this->assertEquals($expected, $response->decodeResponseJson());

        $this->assertDatabaseHas('multilingual_posts', ['id' => $post->id, 'is_draft' => false]);
    }

    /**
     *@test
     */
    public function the_publish_date_must_be_a_valid_date_string()
    {
        $post = $this->makePost();

        $response = $this->asLoggedInUser()->postJson("/multilingual-posts/published-posts", [
            "post_id" => $post->id,
            'publish_date' => 'not-a-valid-date'
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('publish_date');
    }

    /**
     *@test
     */
    public function the_post_id_is_required_otherwise_response_is_404()
    {
        $post = $this->makePost();

        $response = $this->asLoggedInUser()->postJson("/multilingual-posts/published-posts", [
            "post_id" => null
        ]);
        $response->assertStatus(404);

        $this->assertDatabaseHas('multilingual_posts', ['id' => $post->id, 'is_draft' => true]);
    }
}
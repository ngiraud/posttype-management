<?php

namespace NGiraud\PostType\Test\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use NGiraud\PostType\Models\PostType;
use NGiraud\PostType\Test\Post;
use NGiraud\PostType\Test\TestCase;
use NGiraud\PostType\Test\User;

class PostTypeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_has_a_published_status()
    {
        $value_published = !defined('NGiraud\PostType\Test\Post::STATUS_PUBLISHED') ? 0 : Post::STATUS_PUBLISHED;

        $this->assertNotEmpty($value_published);
    }

    /** @test */
    function it_has_statuses()
    {
        $post = factory(Post::class)->create();

        $this->assertNotEmpty($post->statuses());
    }

    /** @test */
    function it_has_a_rules_method()
    {
        $post = factory(Post::class)->create();

        $this->assertNotEmpty($post->rules());
    }

    /** @test */
    function it_has_an_owner_and_is_the_auth_one()
    {
        $user = factory(User::class)->create();
        $this->signIn($user);

        $post_type = factory(Post::class)->create();
        $this->assertInstanceOf(User::class, $post_type->owner);
        $this->assertEquals($post_type->owner->id, $user->id);
    }

    /** @test */
    function it_has_a_slug()
    {
        $post_type = factory(Post::class)->create();

        $this->assertNotEmpty($post_type->slug);
    }

    /** @test */
    function it_has_a_parent()
    {
        $post_type_parent = factory(Post::class)->create();
        $post_type = factory(Post::class)->create(['parent_id' => $post_type_parent->id]);

        $this->assertInstanceOf(Post::class, $post_type->parent);
    }

    /** @test */
    function it_has_children()
    {
        $post_type_parent = factory(Post::class)->create();
        factory(Post::class)->create(['parent_id' => $post_type_parent->id]);
        factory(Post::class)->create(['parent_id' => $post_type_parent->id]);

        $this->assertCount(2, $post_type_parent->children->keys());
    }

    /** @test */
    function it_has_a_published_date_when_published()
    {
        $post = factory(Post::class)->create(['status' => PostType::STATUS_PUBLISHED]);

        $this->assertNotEmpty($post->published_at);
    }

    /** @test */
    function it_has_not_a_published_date_when_draft()
    {
        $post = factory(Post::class)->create(['status' => PostType::STATUS_DRAFT]);

        $this->assertEmpty($post->published_at);
    }

    /** @test */
    function a_user_select_only_published_posts()
    {
        $published = factory(Post::class, 10)->create(['status' => PostType::STATUS_PUBLISHED]);
        factory(Post::class, 5)->create(['status' => PostType::STATUS_DRAFT]);

        $posts = Post::all();

        $this->assertEquals($posts->count(), $published->count());
    }
}

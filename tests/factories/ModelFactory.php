<?php
use Faker\Generator as Faker;
use NGiraud\PostType\Test\Post;
use NGiraud\PostType\Test\User;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('password'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Post::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'parent_id' => null,
        'name' => $faker->name,
        'status' => Post::STATUS_PUBLISHED,
        'excerpt' => $faker->paragraph,
        'content' => $faker->paragraph,
        'published_at' => $faker->date,
    ];
});
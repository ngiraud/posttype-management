<?php

namespace NGiraud\PostType\Test;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\File;
use NGiraud\PostType\PostTypeServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            PostTypeServiceProvider::class,
        ];
    }

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * Set up the database.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('name');
            $table->string('slug');
            $table->unsignedTinyInteger('status');
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        $this->loadLaravelMigrations(['--database' => 'testing']);
        $this->artisan('migrate', ['--database' => 'testing']);
    }

    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->setUpDatabase($this->app);

        $this->withFactories(__DIR__.'/factories');
    }

    protected function signIn($user = null)
    {
        $user = $user ?: factory('App\User')->create;
        $this->actingAs($user);
        return $this;
    }

    protected function createPostType()
    {
        $this->artisan('posttype:create', ['name' => 'Test', '--migrate' => true, '--no-interaction' => true]);
    }

    protected function removePostType()
    {
        $this->artisan('posttype:remove', ['name' => 'Test', '--no-interaction' => true]);
    }

    protected function createFiles()
    {
        File::put(base_path() . '/app/Test.php', 'lorem');
        File::put(base_path() . '/app/Http/Controllers/TestController.php', 'lorem');
        File::put(database_path() . '/migrations/2018_02_11_102430_create_tests_table.php', 'lorem');
    }

    protected function deleteFiles()
    {
        File::delete(base_path() . '/app/Test.php');
        File::delete(base_path() . '/app/Http/Controllers/TestController.php');
        File::delete(database_path() . '/migrations/2018_02_11_102430_create_tests_table.php');
    }
}

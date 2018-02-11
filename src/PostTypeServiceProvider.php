<?php

namespace NGiraud\PostType;

use Illuminate\Support\ServiceProvider;
use NGiraud\PostType\Commands\CreatePostType;
use NGiraud\PostType\Commands\RemovePostType;

class PostTypeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
//        $this->app->bind('command.posttype:create', CreatePostType::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                CreatePostType::class,
                RemovePostType::class,
            ]);
        }

//        $this->app->singleton(ConsoleOutput::class);
    }
}

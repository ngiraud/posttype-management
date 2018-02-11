<?php

namespace NGiraud\PostType\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

abstract class AbstractPostType extends Command
{
    protected $files;
    protected $post_type_name;
    protected $controller_folder;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->post_type_name = $this->argument('name');
        $this->controller_folder = $this->option('ctrl-folder');
    }

    protected function getModelName()
    {
        return studly_case(rtrim($this->post_type_name, "s"));
    }

    protected function getTableName()
    {
        return str_plural(snake_case($this->post_type_name));
    }

    protected function getStubPath()
    {
        return dirname(__FILE__) . '/../stubs';
    }
}

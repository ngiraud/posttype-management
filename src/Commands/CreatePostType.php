<?php

namespace NGiraud\PostType\Commands;

class CreatePostType extends AbstractPostType
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posttype:create {name : The name of the post type} {--ctrl-folder= : Specify the folder in which the controller should be in} {--m|migrate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create generic post type for our blog application.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        parent::handle();

        $this->createModel();
        $this->createMigration();
        $this->createResourceController();
        $this->createFactory();

        if ($this->option('migrate') == true) {
            $this->call('migrate', ['--no-interaction' => true]);
        }

        $this->info("Post type created {$this->post_type_name} created successfully.");
    }

    protected function createModel()
    {
        $model_name = $this->getModelName();

        $stub = $this->files->get($this->getStubPath() . '/Model.stub');
        $stub = str_replace(
            ['{{class}}', '{{table}}'],
            [$model_name, $this->getTableName()],
            $stub
        );

        $this->files->put(base_path() . '/app/' . $model_name . '.php', $stub);
        $this->info('Model ' . $model_name . ' created successfully.');
    }

    protected function createMigration()
    {
        $table_name = $this->getTableName();

        $stub = $this->files->get($this->getStubPath() . '/Migration.stub');
        $stub = str_replace(
            ['{{class}}', '{{table}}'],
            [ucfirst($table_name), $table_name],
            $stub
        );

        $date = date('Y_m_d_His') . '_';
        $migration_filename = $date . 'create_' . $table_name . '_table';

        $this->files->put(database_path() . '/migrations/' . $migration_filename . '.php', $stub);
        $this->info('Migration ' . $migration_filename . ' created successfully.');
    }

    protected function createResourceController()
    {
        $this->controller_folder = is_null($this->controller_folder) ? '' : ucfirst($this->controller_folder);

        $model_name = $this->getModelName();
        $controller_name = $model_name . 'Controller';

        $stub = $this->files->get($this->getStubPath() . '/ResourceController.stub');
        $stub = str_replace(
            ['{{class}}', '{{model_variable}}', '{{namespace}}'],
            [$model_name, '$' . strtolower($model_name), empty($this->controller_folder) ? '' : '\\' . $this->controller_folder],
            $stub
        );

        $path = base_path() . '/app/Http/Controllers/' . $this->controller_folder . '/';

        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path);
        }

        $this->files->put(
            $path . $controller_name . '.php',
            $stub
        );
        $this->info('Resource Controller ' . $model_name . 'Controller created successfully.');

        // Add resource route
        $route_prefix = "\n\n";
        $route = "Route::resource('" . strtolower($model_name) . "', '{$controller_name}');";
        if (!empty($this->controller_folder)) {
            $route = "Route::namespace('{$this->controller_folder}')->group(function() {\n\t{$route}\n});";
        }

        $this->files->append(base_path() . '/routes/web.php', $route_prefix . $route);
    }

    protected function createFactory()
    {
        $model_name = $this->getModelName();
        $factory_name = $model_name . 'Factory';

        $stub = $this->files->get($this->getStubPath() . '/Factory.stub');
        $stub = str_replace('{{class}}', $model_name, $stub);

        $this->files->put(database_path() . '/factories/' . $factory_name . '.php', $stub);
        $this->info('Factory ' . $factory_name . ' created successfully.');
    }
}

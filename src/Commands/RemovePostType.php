<?php

namespace NGiraud\PostType\Commands;

class RemovePostType extends AbstractPostType
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posttype:remove {name : The name of the post type to remove} {--ctrl-folder= : Specify the folder in which the controller should be removed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove post type by name.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        parent::handle();

        $this->removeModel();
        $this->removeController();
        $this->removeFactory();
        $this->removeMigration();
    }

    protected function removeModel()
    {
        $model_name = $this->getModelName();
        $path = base_path() . '/app/' . $model_name . '.php';

        if (!$this->files->exists($path)) {
            $this->info('Model ' . $model_name . ' does not exist.');
            return false;
        }

        $this->files->delete($path);
        $this->info('Model ' . $model_name . ' deleted successfully.');
    }

    protected function removeController()
    {
        $this->controller_folder = is_null($this->controller_folder) ? '' : ucfirst($this->controller_folder);

        $model_name = $this->getModelName();
        $controller_name = $model_name . 'Controller';
        $path = base_path() . '/app/Http/Controllers/' . $this->controller_folder . '/' . $controller_name . '.php';

        if (!$this->files->exists($path)) {
            $this->info('Controller ' . $controller_name . ' does not exist.');
            return false;
        }

        $this->files->delete($path);
        $this->info('Controller ' . $controller_name . ' deleted successfully.');
    }

    protected function removeFactory()
    {
        $factory_name = $this->getModelName() . 'Factory';
        $path = database_path() . '/factories/' . $factory_name . '.php';

        if (!$this->files->exists($path)) {
            $this->info('Factory ' . $factory_name . ' does not exist.');
            return false;
        }

        $this->files->delete($path);
        $this->info('Factory ' . $factory_name . ' deleted successfully.');
    }

    protected function removeMigration()
    {
        $table_name = $this->getTableName();
        $migration_filename = '*_create_' . $table_name . '_table';
        $path = database_path() . '/migrations/' . $migration_filename . '.php';

        $files = glob($path);

        if (empty($files)) {
            $this->info('Migration ' . $migration_filename . ' does not exist.');
            return false;
        }

        foreach ($files as $file) {
            $this->files->delete($file);
        }
        $this->info('Migrations ' . $migration_filename . ' deleted successfully.');
    }
}

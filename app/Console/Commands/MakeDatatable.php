<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeDatatable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:datatable {name} {--model=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new datatable class inside app/Datatables with the specified model. Usage: php artisan make:datatable {name} --model={model}, e.g., php artisan make:datatable Users/UserDatatable --model=User';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = str_replace('\\', '/', $this->argument('name')); // Support both slashes
        $model = $this->option('model') ?? 'Model'; // Get the model name
        $filesystem = new Filesystem;

        // Extract the full path
        $path = app_path("Datatables/{$name}.php");

        // Extract namespace and class name
        $segments = explode('/', $name);
        $className = array_pop($segments);
        $namespace = 'App\\Datatables'.(! empty($segments) ? '\\'.implode('\\', $segments) : '');

        // Ensure the directory exists
        $directory = dirname($path);
        if (! $filesystem->exists($directory)) {
            $filesystem->makeDirectory($directory, 0755, true);
        }

        // Prevent overwriting existing files
        if ($filesystem->exists($path)) {
            $this->error("Datatable class {$className} already exists!");

            return;
        }

        // Create the action class content
        $stub = <<<PHP
        <?php
        namespace $namespace;
        
        use App\Datatables\BaseDatatable;
        use App\Models\\$model;
        use Yajra\DataTables\DataTableAbstract;

        class $className extends BaseDatatable
        {
            public function __construct(){
                parent::__construct($model::query());
            }

            public function configure(\$datatable): DataTableAbstract
            {
                return \$datatable;
                // Implement your action logic here
            }
        }
        PHP;

        // Write the file
        $filesystem->put($path, $stub);

        $this->info("Datatables class created: app/Datatables/{$name}.php");
    }
}

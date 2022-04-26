<?php

namespace Hexters\Laramodule\Console\Commands\Database\Migrations;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MigrateMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-migration {name} {--module=} {--table=} {--create=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration file in module';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if(is_null($this->option('module'))) {
            $this->error('Option --module= is required!');
            exit();
        }
        
        $namespace = $this->overiteNamespace('\Databases\Migrations');
        $options['name'] = $this->argument('name');
        $options['--path'] = Str::replace('\\', '/', $namespace);
        
        if($this->hasOption('table') && $this->option('table')) {
            $options['--table'] = $this->option('table');
        }

        if($this->hasOption('create') && $this->option('create')) {
            $options['--create'] = $this->option('create');
        }
        
        $this->call('make:migration', $options);
    }

    /**
     * Get module name
     *
     * @return void
     */
    protected function getModuleNameInput()
    {
        $name = ltrim(rtrim($this->option('module'), '/'), '/');
        return ucwords($name);
    }

    /**
     * Overite namespace module
     *
     * @param [type] $path
     * @return void
     */
    protected function overiteNamespace($path)
    {
        return 'Modules\\' . $this->getModuleNameInput() . $path;
    }
}

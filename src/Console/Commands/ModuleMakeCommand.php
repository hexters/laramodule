<?php

namespace Hexters\Laramodule\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ModuleMakeCommand extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a new module';

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
     * Get module name
     *
     * @return void
     */
    protected function getModuleNameInput() {
        return ltrim( rtrim($this->option('module'), '/') , '/');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $structures = [
            'Console' => [
                'Commands'
            ],
            'Exceptions' => [],
            'Http' => [
                'Controllers',
                'Middleware',
            ],
            'Models' => [],
            'Providers' => []
        ];

        $name = Str::of($this->argument('name'))->camel();
        $name = ucwords($name);

        if (!is_dir($this->module_path($name))) {

            mkdir($this->module_path($name));

            foreach ($structures as $item => $items) {
                mkdir($this->module_path("{$name}/{$item}"));

                if (count($items) < 1) {
                    file_put_contents($this->module_path("{$name}/{$item}/.gitkeep"), "");
                }

                foreach ($items as $dir) {
                    mkdir($this->module_path("{$name}/{$item}/{$dir}"));
                    file_put_contents($this->module_path("{$name}/{$item}/{$dir}/.gitkeep"), "");
                }
            }

            $this->call('module:make-controller', [
                'name' => 'Controller',
                '--module' => $name,
                '--type' => 'base'
            ]);

            $this->call('module:make-provider', [
                'name' => $name . 'ServiceProvider',
                '--module' => $name,
                '--type' => 'load'
            ]);

            file_put_contents($this->module_path("{$name}/app.json"), json_encode(
                $this->appjson(), JSON_PRETTY_PRINT
            ));

            $this->info("{$name} module has been created.");

            return;
        }

        $this->error('Module already exists!');
    }

    protected function namespace($module) {
        return 'Modules\\' . $module . '\\';
    }

    protected function appjson() {
        $name = Str::of($this->argument('name'))->camel();
        $name = ucwords($name);

        return [
            'name' => strtolower($name),
            'namespace' => $this->namespace($name),
            'providers' => (Array) [
                $this->namespace($name) . 'Providers\\' . $name . 'ServiceProvider',
            ],
            'status' => 'enabled'
        ];
    }

    protected function module_path($path)
    {
        $path =  ltrim($path, '/');
        return base_path("Modules/{$path}");
    }
}

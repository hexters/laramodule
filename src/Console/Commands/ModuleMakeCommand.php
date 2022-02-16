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
    protected function getModuleNameInput()
    {
        return ltrim(rtrim($this->option('module'), '/'), '/');
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
            'config' => [],
            'lang' => [
                'en'
            ],
            'Resources' => [
                'sass',
                'css',
                'js',
                'views',
            ],
            'routes' => [],
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
                if (in_array($item, ['config', 'routes'])) {

                    if ($item === 'config') {
                        $configFile = file_get_contents(__DIR__ . '/stubs/config.stub');

                        $content = Str::replace('{{ module }}', $name, $configFile);
                        $content = Str::replace('{{ moduleLower }}', Str::lower($name), $content);

                        file_put_contents($this->module_path("{$name}/{$item}/module.php"), $content);
                    } else if ($item === 'routes') {

                        $routeFile = file_get_contents($this->getRouteStub('route.stub'));
                        $content = Str::replace('{{ module }}', $name, $routeFile);
                        $content = Str::replace('{{ moduleLower }}', Str::lower($name), $content);
                        file_put_contents($this->module_path("{$name}/{$item}/web.php"), $content);

                        $apiRouteFile = file_get_contents($this->getRouteStub('route.api.stub'));
                        $content = Str::replace('{{ module }}', $name, $apiRouteFile);
                        $content = Str::replace('{{ moduleLower }}', Str::lower($name), $content);
                        file_put_contents($this->module_path("{$name}/{$item}/api.php"), $content);
                    }
                }

                foreach ($items as $dir) {
                    mkdir($this->module_path("{$name}/{$item}/{$dir}"));

                    if (in_array($dir, ['js', 'css', 'sass'])) {
                        $extention = $dir === 'sass' ? 'scss' : $dir;
                        file_put_contents($this->module_path("{$name}/{$item}/{$dir}/app." . $extention), "");
                    } else {
                        file_put_contents($this->module_path("{$name}/{$item}/{$dir}/.gitkeep"), "");
                    }
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

            $this->call('module:make-provider', [
                'name' => 'RouteServiceProvider',
                '--module' => $name,
                '--type' => 'route'
            ]);


            $this->call('module:make-seeder', [
                'name' => 'DatabaseSeeder',
                '--module' => $name
            ]);

            file_put_contents($this->module_path("{$name}/app.json"), json_encode(
                $this->appjson(),
                JSON_PRETTY_PRINT
            ));

            file_put_contents($this->module_path("{$name}/.gitignore"), "/node_modules\npackage-lock.json\nyarn.lock");


            $loweName = strtolower($name);
            $webpack = file_get_contents(__DIR__ . '/stubs/webpack.stub');
            $webpack = str_replace('{{ module }}', $loweName, $webpack);
            file_put_contents($this->module_path("{$name}/webpack.mix.js"), $webpack);

            $package = file_get_contents(__DIR__ . '/stubs/package.stub');
            file_put_contents($this->module_path("{$name}/package.json"), $package);

            $this->info("Module {$name} created successfully.");

            return;
        }

        $this->error('Module already exists!');
    }

    protected function getRouteStub($stub)
    {

        if (is_file(base_path('stubs/' . $stub))) {
            return base_path('stubs/' . $stub);
        }

        return __DIR__ . '/stubs/' . $stub;
    }

    protected function namespace($module)
    {
        return 'Modules\\' . $module . '\\';
    }

    protected function appjson()
    {
        $name = Str::of($this->argument('name'))->camel();
        $name = ucwords($name);

        return [
            'name' => strtolower($name),
            'namespace' => $this->namespace($name),
            'providers' => (array) [
                $this->namespace($name) . 'Providers\\' . $name . 'ServiceProvider',
                $this->namespace($name) . 'Providers\\' . 'RouteServiceProvider',
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

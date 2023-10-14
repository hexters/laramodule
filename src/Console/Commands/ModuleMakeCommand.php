<?php

namespace Hexters\Laramodule\Console\Commands;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;
use function Laravel\Prompts\warning;

class ModuleMakeCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make 
                            {name? : Name of your module}
                            {--command= : Run the command after successfully creating the module}';

    protected $name;
    
    protected $desc;

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
        $name = Str::slug($this->option('module'));
        return Str::studly($name);
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
                'css',
                'js',
                'views',
            ],
            'routes' => [],
            'Models' => [],
            'Providers' => []
        ];

        $name = Str::studly(Str::slug($this->argument('name')));

        if (in_array($name, [null, ""])) {
            $name = text(label: 'Name the module you want to create!', required: true, validate: fn ($value) => match (true) {
                is_dir(module_path($value)) => 'Module is available!',
                default => null
            });
        }
        $this->name = $name;
        $loweName = strtolower($name);

        $this->desc = text(label: "Write a description for the {$name} module.");

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
                    }
                }
                
                foreach ($items as $dir) {
                    mkdir($this->module_path("{$name}/{$item}/{$dir}"));

                    if (in_array($dir, ['js', 'css', 'sass'])) {
                        $extention = $dir === 'sass' ? 'scss' : $dir;
                        file_put_contents($this->module_path("{$name}/{$item}/{$dir}/{$loweName}." . $extention), "");
                    } else if (in_array($dir, ['views'])) {
                        $blade = file_get_contents($this->getBladeStub('welcome.blade.stub'));
                        $content = Str::replace('{{ module }}', $name, $blade);
                        $content = Str::replace('/{{ module }}/', strtolower($name), $content);
                        $content = Str::replace('{{ moduleLower }}', strtolower($name), $content);
                        file_put_contents($this->module_path("{$name}/{$item}/{$dir}/welcome.blade.php"), $content);
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
                'name' => 'EventServiceProvider',
                '--module' => $name,
                '--type' => 'event'
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

            // Make middleware
            collect([
                __DIR__ . '/stubs/module.status.middleware.stub',
            ])->each(function ($path) use ($name) {
                if (file_exists($path)) {
                    $content = Str::of(file_get_contents($path))
                        ->replace('{{ moduleName }}', $name)
                        ->replace('{{ namespace }}', $this->namespace($name) . 'Http\\Middleware');
                    file_put_contents($this->module_path("{$name}/Http/Middleware/Module{$name}StatusMiddleware.php"), $content);
                }
            });

            $package = file_get_contents(__DIR__ . '/stubs/package.stub');
            $package = str_replace('{{ module }}', strtolower($name), $package);
            file_put_contents($this->module_path("{$name}/package.json"), $package);

            $command = $this->option('command');
            if (in_array($command, [null, ''])) {

                $select = select(label: 'Is there a command that you want to run after the {$name} module is created?', options: ['yes', 'no'], default: 'no');
                if ($select == 'yes') {
                    $command = text(label: "type the command you want to execute!", placeholder: 'E.g : config:clear', hint: "* Option --module={$name} is included in it.");
                }
            }

            $this->runOtherCommand($command);

            $this->components->info("Module {$name} created successfully.");
            $this->line('');
            $this->components->info('visit : ' . url($loweName));
            $this->line('');

            return;
        }

        warning("{$this->name} module is already available!");
    }

    protected function runOtherCommand($command = null)
    {
        if ($command) {
            $name = Str::of($this->name)->camel();

            try {
                try {
                    $this->call($command, [
                        '--module' => $name
                    ]);
                } catch (Exception $e) {
                    $this->call($command);
                }
            } catch (Exception $err) {
                Log::error(['ERROR MAKE MODULE', $err->getMessage()]);
            }
        }
    }

    protected function getBladeStub($stub)
    {
        if (is_file(base_path('stubs/' . $stub))) {
            return base_path('stubs/' . $stub);
        }

        return __DIR__ . '/stubs/' . $stub;
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
        $name = Str::studly($this->name);

        return [
            'name' => strtolower($name),
            'namespace' => $this->namespace($name),
            'description' => $this->desc,
            'providers' => (array) [
                $this->namespace($name) . 'Providers\\' . $name . 'ServiceProvider',
                $this->namespace($name) . 'Providers\\' . 'EventServiceProvider',
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

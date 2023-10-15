<?php

namespace Hexters\Laramodule\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

use function Laravel\Prompts\select;

class InitInertiaReactCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inertia:init-react
    { --module= : Module name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Init InertiaJs components react';

    public function getRouteStub()
    {

        $stub = public_path('/stub/inertia.route.stub');
        if (!file_exists($stub)) {
            $stub = __DIR__ . '/stubs/inertia.route.stub';
        }

        return $stub;
    }

    public function getWelcomeStub()
    {

        $stub = public_path('/stub/inertia.welcome.react.stub');
        if (!file_exists($stub)) {
            $stub = __DIR__ . '/stubs/inertia.welcome.react.stub';
        }

        return $stub;
    }

    protected function moduleName()
    {
        return Str::of($this->option('module'));
    }

    protected function buildClass($name)
    {

        $name = file_get_contents($name);

        $module =  $this->moduleName();
        $name = str_replace([
            '{{ module }}',
            '{{module}}',
            '{{ moduleLower }}',
            '{{moduleLower}}',
        ], [
            $module->studly(),
            $module->studly(),
            $module->lower(),
            $module->lower(),
        ], $name);

        return $name;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $module = $this->option('module');
        if (is_null($module)) {
            $module = select(label: "Select an available module!", options: module_name_lists());
        }

        $this->input->setOption('module', Str::of($module)->slug('-')->studly());


        $route = $this->buildClass(
            $this->getRouteStub()
        );
        $path = module_path($module, 'routes');
        file_put_contents("{$path}/web.php", $route);
        $this->components->info('Inertia route has been created');


        $welcome = $this->buildClass(
            $this->getWelcomeStub()
        );
        $path = module_path($module, 'Resources/pages');
        if (!is_dir($path)) {
            @mkdir($path);
        }
        file_put_contents("{$path}/Welcome.jsx", $welcome);
        $this->components->info('Inertia template has been created');
    }
}

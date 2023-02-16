<?php

namespace Hexters\Laramodule\Console\Commands;

use Illuminate\Foundation\Console\ComponentMakeCommand as ConsoleComponentMakeCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ComponentMakeCommand extends ConsoleComponentMakeCommand
{

    use BaseCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-component';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     */
    protected static $defaultName = 'module:make-component';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new view component class in Module';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Component';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {

        if (is_null($this->option('module'))) {
            $this->error('Option --module= is required!');
            exit();
        }

        if ($this->option('view')) {
            $this->writeView(function () {
                $this->info($this->type . ' created successfully.');
            });

            return;
        }

        if (parent::handle() === false && !$this->option('force')) {
            return false;
        }

        if (!$this->option('inline')) {
            $this->writeView();
        }
    }

    /**
     * Get the first view directory path from the application configuration.
     *
     * @param  string  $path
     * @return string
     */
    protected function viewPath($path = '')
    {
        $views = 'Modules/' . $this->getModuleNameInput() . '/Resources/views';

        return $views . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Write the view for the component.
     *
     * @param  callable|null  $onSuccess
     * @return void
     */
    protected function writeView($onSuccess = null)
    {
        $path = $this->viewPath(
            str_replace('.', '/', 'components.' . $this->getView()) . '.blade.php'
        );

        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
        
        file_put_contents(
            $path,
            '<div>
    <!-- ' . Inspiring::quote() . ' -->
</div>'
        );

        if ($onSuccess) {
            $onSuccess();
        }
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        if ($this->option('inline')) {
            return str_replace(
                ['DummyView', '{{ view }}'],
                "<<<'blade'\n<div>\n    <!-- " . Inspiring::quote() . " -->\n</div>\nblade",
                parent::buildClass($name)
            );
        }

        $moduleName = Str::lower($this->getModuleNameInput());

        return str_replace(
            ['DummyView', '{{ view }}'],
            'view(\'' . $moduleName . '::components.' . $this->getView() . '\')',
            parent::buildClass($name)
        );
    }

    /**
     * Get the view name relative to the components directory.
     *
     * @return string view
     */
    protected function getView()
    {
        $name = str_replace('\\', '/', $this->argument('name'));

        return collect(explode('/', $name))
            ->map(function ($part) {
                return Str::kebab($part);
            })
            ->implode('.');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/view-component.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return __DIR__ . $stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $this->overiteNamespace('\View\Components');
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array_merge(
            parent::getOptions(),
            [['module', 'o', InputOption::VALUE_REQUIRED, 'Add existing module name.']]
        );
    }
}

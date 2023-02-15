<?php

namespace Hexters\Laramodule\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Foundation\Console\ProviderMakeCommand as ConsoleProviderMakeCommand;
use Symfony\Component\Console\Input\InputOption;

class ProviderMakeCommand extends ConsoleProviderMakeCommand
{
    use BaseCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-provider';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     */
    protected static $defaultName = 'module:make-provider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service provider class in Module';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Provider';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($type = $this->option('type')) {
            $stub = $this->resolveStubPath("/stubs/provider.{$type}.stub");
        } else {
            $stub = $this->resolveStubPath('/stubs/provider.stub');
        }
        return $stub;
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
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $class = parent::buildClass($name);
        $moduleName = Str::lower($this->getModuleNameInput());
        $moduleNameOriginal = $this->getModuleNameInput();

        if ($this->option('type') !== false) {
            $class = str_replace(['DummyView', '{{ module }}'], $moduleName, $class);
            $class = str_replace(['DummyView', '{{ moduleUpper }}'], $moduleNameOriginal, $class);
        }

        return $class;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $this->overiteNamespace('\Providers');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array_merge(
            parent::getOptions(),
            [
                ['type', null, InputOption::VALUE_REQUIRED, 'Manually specify the provider stub file to use.'],
                ['module', 'o', InputOption::VALUE_REQUIRED, 'Add existing module name.']
            ]
        );
    }
}

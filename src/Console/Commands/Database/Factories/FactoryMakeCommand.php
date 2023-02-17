<?php

namespace Hexters\Laramodule\Console\Commands\Database\Factories;

use Hexters\Laramodule\Console\Commands\BaseCommandTrait;
use Illuminate\Database\Console\Factories\FactoryMakeCommand as FactoriesFactoryMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class FactoryMakeCommand extends FactoriesFactoryMakeCommand
{
    use BaseCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-factory';

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $this->overiteNamespace('\Databases\Factories');
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $factory = class_basename(Str::ucfirst(str_replace('Factory', '', $name)));

        $namespaceModel = $this->option('model')
            ? $this->qualifyModel($this->option('model'))
            : $this->qualifyModel($this->guessModelName($name));

        $model = class_basename($namespaceModel);

        $namespace = $this->overiteNamespace('\\Databases\\Factories');



        $replace = [
            '{{ factoryNamespace }}' => $namespace,
            'NamespacedDummyModel' => $namespaceModel,
            '{{ namespacedModel }}' => $namespaceModel,
            '{{namespacedModel}}' => $namespaceModel,
            'DummyModel' => $model,
            '{{ model }}' => $model,
            '{{model}}' => $model,
            '{{ module }}' => $this->getModuleNameInput(),
            '{{module}}' => $this->getModuleNameInput(),
            '{{ factory }}' => $factory,
            '{{factory}}' => $factory,
        ];

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
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
     * Get the console command options.
     *
     * @return array
     */
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

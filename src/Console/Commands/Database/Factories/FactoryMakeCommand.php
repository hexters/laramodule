<?php

namespace Hexters\Laramodule\Console\Commands\Database\Factories;

use Hexters\Laramodule\Console\Commands\BaseCommandTrait;
use Illuminate\Console\GeneratorCommand;
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
     * Guess the model name from the Factory name or return a default model name.
     *
     * @param  string  $name
     * @return string
     */
    protected function guessModelName($name)
    {
        if (str_ends_with($name, 'Factory')) {
            $name = substr($name, 0, -7);
        }

        $modelName = $this->qualifyModel(Str::after($name, $this->rootNamespace()));

        if (class_exists($modelName)) {
            return $modelName;
        }

        if (is_dir(app_path('Models/'))) {
            return $this->rootNamespace() . 'Models\Model';
        }

        return $this->rootNamespace() . 'Model';
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

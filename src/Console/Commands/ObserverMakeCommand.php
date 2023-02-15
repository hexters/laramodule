<?php

namespace Hexters\Laramodule\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Foundation\Console\ObserverMakeCommand as ConsoleObserverMakeCommand;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;

class ObserverMakeCommand extends ConsoleObserverMakeCommand
{

    use BaseCommandTrait;
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-observer';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     */
    protected static $defaultName = 'module:make-observer';

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $this->overiteNamespace('\Observers');
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
            [['module', 'o', InputOption::VALUE_REQUIRED, 'Add existing module name.']]
        );
    }
}

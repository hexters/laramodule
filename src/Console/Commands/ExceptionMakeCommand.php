<?php

namespace Hexters\Laramodule\Console\Commands;

use Illuminate\Foundation\Console\ExceptionMakeCommand as ConsoleExceptionMakeCommand;
use Symfony\Component\Console\Input\InputOption;

class ExceptionMakeCommand extends ConsoleExceptionMakeCommand
{
    use BaseCommandTrait;
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-exception';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     */
    protected static $defaultName = 'module:make-exception';

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $this->overiteNamespace('\Exceptions');
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

<?php

namespace Hexters\Laramodule\Console\Commands;

use Illuminate\Foundation\Console\RequestMakeCommand as ConsoleRequestMakeCommand;
use Symfony\Component\Console\Input\InputOption;

class RequestMakeCommand extends ConsoleRequestMakeCommand
{
    use BaseCommandTrait;
    
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-request';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     */
    protected static $defaultName = 'module:make-request';
    
    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $this->overiteNamespace('\Http\Requests');
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

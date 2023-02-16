<?php

namespace Hexters\Laramodule\Console\Commands;

use Illuminate\Foundation\Console\ResourceMakeCommand as ConsoleResourceMakeCommand;
use Symfony\Component\Console\Input\InputOption;

class ResourceMakeCommand extends ConsoleResourceMakeCommand
{

    use BaseCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-resource';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     */
    protected static $defaultName = 'module:make-resource';
    
    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return  $this->overiteNamespace('\Http\Resources');
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

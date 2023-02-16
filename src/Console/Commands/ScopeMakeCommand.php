<?php

namespace Hexters\Laramodule\Console\Commands;

use Illuminate\Foundation\Console\ScopeMakeCommand as ConsoleScopeMakeCommand;
use Symfony\Component\Console\Input\InputOption;

class ScopeMakeCommand extends ConsoleScopeMakeCommand
{

    use BaseCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-scope';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     */
    protected static $defaultName = 'module:make-scope';
    
    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return is_dir(app_path('Models')) ? $this->overiteNamespace('\\Models\\Scopes')  : $this->overiteNamespace('\Scopes');
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

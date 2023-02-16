<?php

namespace Hexters\Laramodule\Console\Commands\Routing;

use Hexters\Laramodule\Console\Commands\BaseCommandTrait;
use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Routing\Console\MiddlewareMakeCommand as ConsoleMiddlewareMakeCommand;
use Symfony\Component\Console\Input\InputOption;

class MiddlewareMakeCommand extends ConsoleMiddlewareMakeCommand
{
    use CreatesMatchingTest, BaseCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-middleware';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     */
    protected static $defaultName = 'module:make-middleware';

    
    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $this->overiteNamespace('\Http\Middleware');
    }

    /**Î
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

<?php

namespace Hexters\Laramodule\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Foundation\Console\TestMakeCommand as ConsoleTestMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class TestMakeCommand extends ConsoleTestMakeCommand
{
    use BaseCommandTrait;
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-test';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     */
    protected static $defaultName = 'module:make-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new test class in Module';
    
    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        if ($this->option('unit')) {
            return $this->overiteNamespace('\Test\Unit');
        } else {
            return $this->overiteNamespace('\Test\Feature');
        }
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

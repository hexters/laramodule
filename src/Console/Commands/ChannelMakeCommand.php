<?php

namespace Hexters\Laramodule\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Foundation\Console\ChannelMakeCommand as ConsoleChannelMakeCommand;
use Symfony\Component\Console\Input\InputOption;

class ChannelMakeCommand extends ConsoleChannelMakeCommand
{

    use BaseCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-channel';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     */
    protected static $defaultName = 'module:make-channel';

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $this->overiteNamespace('\Broadcasting');
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

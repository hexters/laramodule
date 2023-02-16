<?php

namespace Hexters\Laramodule\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Foundation\Console\NotificationMakeCommand as ConsoleNotificationMakeCommand;
use Symfony\Component\Console\Input\InputOption;

class NotificationMakeCommand extends ConsoleNotificationMakeCommand
{
    use CreatesMatchingTest, BaseCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-notification';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     */
    protected static $defaultName = 'module:make-notification';
    
    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $this->overiteNamespace('\Notifications');
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

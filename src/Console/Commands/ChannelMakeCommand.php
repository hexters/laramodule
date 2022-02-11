<?php

namespace Hexters\Laramodule\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class ChannelMakeCommand extends GeneratorCommand
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
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new channel class in Module';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Channel';

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        return str_replace(
            ['DummyUser', '{{ userModel }}'],
            class_basename($this->userProviderModel()),
            parent::buildClass($name)
        );
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/channel.stub';
    }

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
        return [
            ['module', 'o', InputOption::VALUE_REQUIRED, 'Declaration module name for store this controller.']
        ];
    }
}

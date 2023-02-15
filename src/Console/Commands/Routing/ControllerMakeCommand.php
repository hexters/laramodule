<?php

namespace Hexters\Laramodule\Console\Commands\Routing;

use Hexters\Laramodule\Console\Commands\BaseCommandTrait;;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Routing\Console\ControllerMakeCommand as ConsoleControllerMakeCommand;
use Symfony\Component\Console\Input\InputOption;


class ControllerMakeCommand extends ConsoleControllerMakeCommand
{
    use CreatesMatchingTest, BaseCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-controller';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     */
    protected static $defaultName = 'module:make-controller';


    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = null;

        if ($type = $this->option('type')) {
            $stub = "/stubs/controller.{$type}.stub";
            return __DIR__ . $stub;
        } else {
            return parent::getStub();
        }
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $module = $this->getModuleNameInput();
        return "Modules\\" . $module . "\Http\Controllers";
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
            [
                ['type', null, InputOption::VALUE_REQUIRED, 'Manually specify the controller stub file to use.'],
                ['module', 'o', InputOption::VALUE_REQUIRED, 'Add existing module name.']
            ]
        );
    }
}

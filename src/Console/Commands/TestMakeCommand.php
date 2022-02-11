<?php

namespace Hexters\Laramodule\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class TestMakeCommand extends GeneratorCommand
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
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Test';
    
    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $suffix = $this->option('unit') ? '.unit.stub' : '.stub';

        return $this->option('pest')
            ? $this->resolveStubPath('/stubs/pest' . $suffix)
            : $this->resolveStubPath('/stubs/test' . $suffix);
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }

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
        return [
            ['unit', 'u', InputOption::VALUE_NONE, 'Create a unit test.'],
            ['pest', 'p', InputOption::VALUE_NONE, 'Create a Pest test.'],
            ['module', 'o', InputOption::VALUE_REQUIRED, 'Add existing module name.']
        ];
    }
}

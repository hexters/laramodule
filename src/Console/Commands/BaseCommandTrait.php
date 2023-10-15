<?php

namespace Hexters\Laramodule\Console\Commands;

use Illuminate\Support\Str;

use function Laravel\Prompts\select;

trait BaseCommandTrait
{

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        $module = $this->getModuleNameInput();

        if (!is_dir(module_path($module))) {
            $this->error('Module not found!');
            exit();
        }

        $namespace = "Modules\\" . $module . '\\';

        return $namespace;
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        $module = $this->getModuleNameInput();
        $path = base_path('Modules') . '/' . $module . '/' . str_replace('\\', '/', $name) . '.php';
        return $path;
    }

    /**
     * Get module name
     *
     * @return void
     */
    protected function getModuleNameInput()
    {
        $name = Str::slug($this->option('module'));
        return Str::studly($name);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $module = $this->option('module');
        if (is_null($module)) {
            $module = select(label: "Select an available module!", options: module_name_lists());
        }

        $this->input->setOption('module', Str::of($module)->slug('-')->studly());


        parent::handle();
    }

    /**
     * Overite namespace module
     *
     * @param [type] $path
     * @return void
     */
    protected function overiteNamespace($path)
    {
        return 'Modules\\' . $this->getModuleNameInput() . $path;
    }
}

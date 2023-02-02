<?php

use Illuminate\Support\Facades\File;

if (!function_exists('module_path')) {

    function module_path($name, $path = null)
    {

        $name = ucwords($name);
        $folder = 'Modules/';
        $path = $path ? '/' . ltrim($path, '/') : null;
        return base_path("{$folder}{$name}{$path}");
    }
}

if (!function_exists('module_load_all')) {

    function module_load_all()
    {

        $modules = [];
        foreach (File::directories(base_path('Modules')) as $module) {
            $modules[] = $module;
        }

        return $modules;
    }
}

if (!function_exists('module_all')) {
    function module_all()
    {
        $modules = [];
        foreach (scandir(base_path('Modules')) as $module) {
            if (! in_array($module, ['.', '..'])) {
                $modules[] = $module;
            }
        }

        return $modules;
    }
}

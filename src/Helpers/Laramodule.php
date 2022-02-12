<?php

use Illuminate\Support\Facades\File;

if (!function_exists('module_path')) {

    function module_path($name, $path = null)
    {

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
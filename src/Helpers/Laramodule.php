<?php

use Hexters\Laramodule\Events\ModuleDisabled;
use Hexters\Laramodule\Events\ModuleEnabled;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

if (!function_exists('module_path')) {

    function module_path($name, $path = null)
    {
        $name = Str::of($name)->slug()->studly();
        $folder = 'Modules/';
        $path = $path ? '/' . ltrim($path, '/') : null;
        return base_path("{$folder}{$name}{$path}");
    }
}

if (!function_exists('module_path_lists')) {

    function module_path_lists()
    {
        $modules = [];
        foreach (File::directories(base_path('Modules')) as $module) {
            $modules[] = $module;
        }

        return $modules;
    }
}

if (!function_exists('module_name_lists')) {
    function module_name_lists()
    {
        $modules = [];
        foreach (scandir(base_path('Modules')) as $module) {
            if (!in_array($module, ['.', '..']) && $module[0] != '.') {
                $modules[] = $module;
            }
        }

        return $modules;
    }
}

if (!function_exists('module_enable')) {
    function module_enable($name)
    {
        if ($module = module_path($name)) {
            try {
                $jsonfile = $module . '/app.json';
                $appjson = json_decode(file_get_contents($jsonfile), true);
                if (in_array($appjson['status'], ['disabled'])) {
                    $editjson = $appjson;
                    $editjson['status'] = 'enabled';
                    file_put_contents($jsonfile, json_encode($editjson, JSON_PRETTY_PRINT));

                    event(new ModuleEnabled($name));
                }
                return true;
            } catch (Exception $e) {
                Log::error(['Error enabled module', $e->getMessage()]);
                return false;
            }
        }
        Log::error("{$name} module cannot set to enabled because module not found?");
        return false;
    }
}

if (!function_exists('module_disable')) {
    function module_disable($name)
    {
        if ($module = module_path($name)) {
            try {
                $jsonfile = $module . '/app.json';
                $appjson = json_decode(file_get_contents($jsonfile), true);

                if (in_array($appjson['status'], ['enabled'])) {
                    $editjson = $appjson;
                    $editjson['status'] = 'disabled';
                    file_put_contents($jsonfile, json_encode($editjson, JSON_PRETTY_PRINT));

                    event(new ModuleDisabled($name));
                }
                return true;
            } catch (Exception $e) {
                Log::error(['Error disabled module', $e->getMessage()]);
                return false;
            }
        }
        Log::error("{$name} module cannot set to disabled because module not found?");
        return false;
    }
}

if (!function_exists('module_status')) {
    function module_status($module)
    {
        $path = module_path($module);
        $jsonfile = $path . '/app.json';
        $appjson = json_decode(file_get_contents($jsonfile), true);
        return $appjson['status'];
    }
}

if (!function_exists('module_group_status')) {
    function module_group_status($type = null)
    {
        $modules['enabled'] = [];
        foreach (File::directories(base_path('Modules')) as $module) {
            $appjson = $module . '/app.json';
            if (is_file($appjson)) {
                $data = json_decode(file_get_contents($appjson), true);
                if (in_array(strtolower($data['status']), ['enabled', 'active'])) {
                    $modules['enabled'][] = collect(explode('/', $module))->last();;
                }
            }
        }

        $modules['disabled'] = [];
        foreach (File::directories(base_path('Modules')) as $module) {
            $appjson = $module . '/app.json';
            if (is_file($appjson)) {
                $data = json_decode(file_get_contents($appjson), true);
                if (in_array(strtolower($data['status']), ['disabled', 'inactive'])) {
                    $modules['disabled'][] = collect(explode('/', $module))->last();;
                }
            }
        }

        return is_null($type) ? $modules : ($modules[$type] ?? $modules);
    }
}


if (!function_exists('module_details')) {
    function module_details($module)
    {
        if (is_dir($path = module_path($module))) {
            $app = "$path/app.json";
            if (file_exists($app)) {
                return collect(json_decode(file_get_contents($app), true))->filter(function ($item, $index) {
                    return !in_array($index, ['namespace', 'providers']);
                });
            }
        }
        return null;
    }
}


if (!function_exists('module_active')) {
    function module_active($module)
    {
        return in_array(module_status($module), ['enabled']);
    }
}

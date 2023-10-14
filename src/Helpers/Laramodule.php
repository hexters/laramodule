<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

if (!function_exists('module_path')) {

    function module_path($name, $path = null)
    {
        $name = ucwords($name);
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

if (!function_exists('module_set_enabled')) {
    function module_set_enabled($name)
    {
        if ($module = module_path($name)) {
            try {
                $jsonfile = $module . '/app.json';
                $appjson = json_decode(file_get_contents($jsonfile), true);
                $editjson = $appjson;
                $editjson['status'] = 'enabled';
                file_put_contents($jsonfile, json_encode($editjson, JSON_PRETTY_PRINT));
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

if (!function_exists('module_set_disabled')) {
    function module_set_disabled($name)
    {
        if ($module = module_path($name)) {
            try {
                $jsonfile = $module . '/app.json';
                $appjson = json_decode(file_get_contents($jsonfile), true);
                $editjson = $appjson;
                $editjson['status'] = 'disabled';
                file_put_contents($jsonfile, json_encode($editjson, JSON_PRETTY_PRINT));
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

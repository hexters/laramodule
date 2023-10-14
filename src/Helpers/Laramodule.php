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
            if (!in_array($module, ['.', '..']) && $module[0] != '.') {
                $modules[] = $module;
            }
        }

        return $modules;
    }
}

if (!function_exists('enable_module')) {
    function enable_module($name)
    {
        if ($module = module_path($name)) {
            try {
                $jsonfile = $module . '/app.json';
                $appjson = json_decode(file_get_contents($jsonfile), true);
                $editjson = $appjson;
                $editjson['status'] = 'enabled';
                file_put_contents($jsonfile, json_encode($editjson, JSON_PRETTY_PRINT));
                return [
                    'status' => true,
                    'message' => "{$name} enabled"
                ];
            } catch (Exception $e) {
                return [
                    'status' => false,
                    'message' => $e->getMessage()
                ];
            }
        }

        return [
            'status' => false,
            'message' => "{$name} not found?"
        ];
    }
}

if (!function_exists('disabled_module')) {
    function disabled_module($name)
    {
        if ($module = module_path($name)) {
            try {
                $jsonfile = $module . '/app.json';
                $appjson = json_decode(file_get_contents($jsonfile), true);
                $editjson = $appjson;
                $editjson['status'] = 'disabled';
                file_put_contents($jsonfile, json_encode($editjson, JSON_PRETTY_PRINT));
                return [
                    'status' => true,
                    'message' => "{$name} disabled"
                ];
            } catch (Exception $e) {
                return [
                    'status' => false,
                    'message' => $e->getMessage()
                ];
            }
        }

        return [
            'status' => false,
            'message' => "{$name} not found?"
        ];
    }
}

if (!function_exists('module_status')) {
    function module_status( $type = null )
    {
        $modules = [];
        foreach (File::directories(base_path('Modules')) as $module) {
            $appjson = $module . '/app.json';
            if (is_file($appjson)) {
                $data = json_decode(file_get_contents($appjson), true);
                if (in_array(strtolower($data['status']), ['enabled', 'active'])) {
                    $modules['enabled'][] = collect(explode('/', $module))->last();;
                }
            }
        }


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

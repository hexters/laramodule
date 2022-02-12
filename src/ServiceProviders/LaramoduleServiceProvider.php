<?php

namespace Hexters\Laramodule\ServiceProviders;

use Illuminate\Support\ServiceProvider;

class LaramoduleServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->get_all_module_informations() as $info) {
            foreach ($info->providers as $providers) {
                $this->app->register($providers);
            }
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }


    /**
     * Get all module information
     *
     * @return void
     */
    protected function get_all_module_informations()
    {

        $informations = [];
        foreach (module_load_all() as $module) {

            if (file_exists($module . DIRECTORY_SEPARATOR . 'app.json')) {
                $informations[] = json_decode(
                    file_get_contents($module . DIRECTORY_SEPARATOR . 'app.json')
                );
            }
        }

        return $informations;
    }
}

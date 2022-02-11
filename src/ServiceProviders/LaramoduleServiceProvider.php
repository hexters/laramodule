<?php

namespace Hexters\Laramodule\ServiceProviders;

use Illuminate\Support\ServiceProvider;

use Hexters\Laramodule\Console\Commands\ModuleMakeCommand;
use Hexters\Laramodule\Console\Commands\Routing\ControllerMakeCommand;
use Hexters\Laramodule\Console\Commands\Routing\MiddlewareMakeCommand;
use Hexters\Laramodule\Console\Commands\CastMakeCommand;
use Hexters\Laramodule\Console\Commands\ChannelMakeCommand;
use Hexters\Laramodule\Console\Commands\ComponentMakeCommand;
use Hexters\Laramodule\Console\Commands\ConsoleMakeCommand;
use Hexters\Laramodule\Console\Commands\ExceptionMakeCommand;
use Hexters\Laramodule\Console\Commands\JobMakeCommand;
use Hexters\Laramodule\Console\Commands\ListenerMakeCommand;
use Hexters\Laramodule\Console\Commands\MailMakeCommand;
use Hexters\Laramodule\Console\Commands\ModelMakeCommand;
use Hexters\Laramodule\Console\Commands\NotificationMakeCommand;
use Hexters\Laramodule\Console\Commands\ObserverMakeCommand;
use Hexters\Laramodule\Console\Commands\PolicyMakeCommand;
use Hexters\Laramodule\Console\Commands\ProviderMakeCommand;
use Hexters\Laramodule\Console\Commands\RequestMakeCommand;
use Hexters\Laramodule\Console\Commands\ResourceMakeCommand;
use Hexters\Laramodule\Console\Commands\RuleMakeCommand;
use Hexters\Laramodule\Console\Commands\TestMakeCommand;
use Hexters\Laramodule\Console\Commands\ScopeMakeCommand;


class LaramoduleServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerCommand();
    }

    /**
     * Register Command
     *
     * @return void
     */
    private function registerCommand()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ModuleMakeCommand::class,
                ControllerMakeCommand::class,
                MiddlewareMakeCommand::class,
                CastMakeCommand::class,
                ChannelMakeCommand::class,
                ComponentMakeCommand::class,
                ConsoleMakeCommand::class,
                ExceptionMakeCommand::class,
                JobMakeCommand::class,
                ListenerMakeCommand::class,
                MailMakeCommand::class,
                ModelMakeCommand::class,
                NotificationMakeCommand::class,
                ObserverMakeCommand::class,
                PolicyMakeCommand::class,
                ProviderMakeCommand::class,
                RequestMakeCommand::class,
                ResourceMakeCommand::class,
                RuleMakeCommand::class,
                ScopeMakeCommand::class,
                TestMakeCommand::class,
            ]);
        }
    }
}

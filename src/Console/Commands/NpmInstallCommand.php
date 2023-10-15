<?php

namespace Hexters\Laramodule\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

use function Laravel\Prompts\select;

class NpmInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:npm {--install : Install all node module packages} {--update : Update all node module packages}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install or update node module packages.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('install') && !$this->option('update')) {
            $option = select(label: "Choose one of the options below.", required: true, options: ['Install', 'Update']);
        } else {
            $option = $this->option('update') ? 'update' : 'install';
        }

        if (in_array(strtolower($option), ['install'])) $this->installNodeModule();
        if (in_array(strtolower($option), ['update'])) $this->updateNodeModule();
    }

    public function installNodeModule()
    {
        collect(module_path_lists())->each(function ($path) {
            $this->info($command = "cd {$path} && npm install");
            $process = Process::run($command);
            $this->info($process->output());
        });
    }

    public function updateNodeModule()
    {
        collect(module_path_lists())->each(function ($path) {
            $this->info($command = "cd {$path} && npm update");
            $process = Process::run($command);
            $this->info($process->output());
        });
    }
}

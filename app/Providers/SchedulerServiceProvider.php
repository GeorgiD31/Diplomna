<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\Process\Process;

class SchedulerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            if (!$this->isProcessRunning('artisan schedule:work')) {
                $this->startProcess(['php', 'artisan', 'schedule:work']);
            }

            if (!$this->isProcessRunning('artisan queue:work')) {
                $this->startProcess(['php', 'artisan', 'queue:work', '--daemon']);
            }
        }
    }

    public function register(): void {}

    
    protected function isProcessRunning(string $command): bool
    {
        $processList = shell_exec('wmic process where "name=\'php.exe\'" get CommandLine');

        return strpos($processList, $command) !== false;
    }

 
    protected function startProcess(array $command): void
    {
        $process = new Process($command);
        $process->setTimeout(null);
        $process->start();
    }
}

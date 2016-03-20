<?php

namespace Orchestra\Foundation\Console\Commands;

use Illuminate\Console\Command;

class ClearCompiledCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'clear-compiled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove the compiled class file';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $files = [
            $this->laravel->getCachedCompilePath(),
            $this->laravel->getCachedServicesPath(),
            $this->laravel->getCachedExtensionServicesPath(),
        ];

        foreach ($files as $file) {
            if (file_exists($file)) {
                @unlink($file);
            }
        }
    }
}

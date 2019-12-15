<?php

namespace Orchestra\Foundation\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;

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
     * @return int
     */
    public function handle()
    {
        Collection::make([
            $this->laravel->getCachedServicesPath(),
            $this->laravel->getCachedExtensionServicesPath(),
        ])->filter(static function ($file) {
            return \file_exists($file);
        })->each(static function ($file) {
            @\unlink($file);
        });

        return 0;
    }
}

<?php

namespace Orchestra\Foundation\Console\Commands;

use PDOException;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Orchestra\Contracts\Foundation\Foundation;
use Orchestra\Contracts\Memory\Provider as Memory;

class AssembleCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'orchestra:assemble
        {--no-cache : Avoid running route and config caching.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh application setup (during composer install/update)';

    /**
     * Execute the console command.
     *
     * @param  \Orchestra\Contracts\Foundation\Foundation  $foundation
     * @param  \Orchestra\Contracts\Memory\Provider  $memory
     *
     * @return void
     */
    public function handle(Foundation $foundation, Memory $memory)
    {
        $this->setupApplication();

        $this->refreshApplication($foundation, $memory);

        $this->optimizeApplication();
    }

    /**
     * Refresh application for Orchestra Platform.
     *
     * @param  \Orchestra\Contracts\Foundation\Foundation  $foundation
     * @param  \Orchestra\Contracts\Memory\Provider  $memory
     *
     * @return void
     */
    protected function refreshApplication(Foundation $foundation, Memory $memory): void
    {
        if (! $foundation->installed()) {
            $this->error('Abort as application is not installed!');

            return;
        }

        $this->call('extension:detect', ['--quiet' => true]);

        try {
            Collection::make($memory->get('extensions.active', []))
                ->keys()->each(function ($extension) {
                    $options = ['name' => $extension, '--force' => true];

                    $this->call('extension:refresh', $options);
                    $this->call('extension:update', $options);
                });

            $this->laravel->make('orchestra.extension.provider')->writeFreshManifest();
        } catch (PDOException $e) {
            // Skip if application is unable to make connection to the database.
        }
    }

    /**
     * Setup application for Orchestra Platform.
     *
     * @return void
     */
    protected function setupApplication(): void
    {
        $this->info('Publishing assets for Orchestra Platform');

        $this->call('publish:assets', ['package' => 'orchestra/foundation']);
    }

    /**
     * Optimize application for Orchestra Platform.
     *
     * @return void
     */
    protected function optimizeApplication(): void
    {
        $this->info('Optimizing application for Orchestra Platform');

        $this->call('config:clear');
        $this->call('route:clear');

        if ($this->laravel->environment('production') && ! $this->option('no-cache')) {
            $this->call('config:cache');
            $this->call('route:cache');
        }
    }
}

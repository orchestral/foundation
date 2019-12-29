<?php

namespace Orchestra\Foundation\Console\Commands;

use Illuminate\Console\Command;
use Orchestra\Contracts\Foundation\Foundation;
use Orchestra\Foundation\Actions\MailConfigurationUpdater;

class ConfigureMailCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'orchestra:configure-email';

    /**
     * The console command description.
     *
     * @var string|null
     */
    protected $description = 'Configure e-mail sender used by Orchestra Platform';

    /**
     * Handle the command.
     *
     * @param  \Orchestra\Contracts\Foundation\Foundation  $foundation
     *
     * @return int
     */
    public function handle(Foundation $foundation)
    {
        if (! $foundation->installed()) {
            $this->output->error('This command can only be executed when the application has been installed!');

            return 1;
        }

        $memory = $foundation->memory();

        $this->output->section('Email configuration');

        $name = $this->ask('What is the application name?', $memory->get('email.from.name'));
        $email = $this->ask('What is the e-mail address?', $memory->get('email.from.address'));

        \with(new MailConfigurationUpdater($memory), static function ($updater) use ($name, $email) {
            $updater($name, $email);
        });

        $memory->finish();

        $this->output->success('Email configured');

        return 0;
    }
}

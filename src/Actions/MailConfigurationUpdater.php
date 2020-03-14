<?php

namespace Orchestra\Foundation\Actions;

use Illuminate\Support\Arr;
use Orchestra\Contracts\Memory\Provider;

class MailConfigurationUpdater
{
    /**
     * The memory implementation.
     *
     * @var \Orchestra\Contracts\Memory\Provider
     */
    protected $memory;

    /**
     * Construct a new mail configuration updater.
     *
     * @param  \Orchestra\Contracts\Memory\Provider  $memory
     */
    public function __construct(Provider $memory)
    {
        $this->memory = $memory;
    }

    /**
     * Update mail configuration.
     *
     * @param  string  $siteName
     * @param  string  $email
     *
     * @return void
     */
    public function __invoke(string $siteName, string $email): void
    {
        $config = \config('mail');

        $this->memory->put('email', Arr::only($config, ['driver', 'host', 'port', 'encryption', 'sendmail']));

        if ($config['mailer']['smtp']['username'] !== null) {
            $this->memory->securePut('email.username', $config['mailer']['smtp']['username']);
        }

        if ($config['mailer']['smtp']['password'] !== null) {
            $this->memory->securePut('email.password', $config['mailer']['smtp']['password']);
        }

        $this->memory->put('email.from', [
            'name' => $siteName,
            'address' => $email,
        ]);
    }
}

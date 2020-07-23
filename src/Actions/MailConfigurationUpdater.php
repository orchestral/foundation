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

        $this->memory->put('email.driver', Arr::get($config, 'default', 'smtp'));
        $this->memory->put('email.host', Arr::get($config, 'mailers.smtp.host'));
        $this->memory->put('email.port', Arr::get($config, 'mailers.smtp.port'));
        $this->memory->put('email.encryption', Arr::get($config, 'mailers.smtp.encryption'));
        $this->memory->put('email.sendmail', Arr::get($config, 'mailers.sendmail.path'));

        if ($config['mailers']['smtp']['username'] !== null) {
            $this->memory->put('email.username', $config['mailers']['smtp']['username']);
        }

        if ($config['mailers']['smtp']['password'] !== null) {
            $this->memory->securePut('email.password', $config['mailers']['smtp']['password']);
        }

        $this->memory->put('email.from', [
            'name' => $siteName,
            'address' => $email,
        ]);
    }
}

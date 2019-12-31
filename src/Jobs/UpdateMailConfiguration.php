<?php

namespace Orchestra\Foundation\Jobs;

use Illuminate\Support\Arr;
use Orchestra\Contracts\Memory\Provider;

class UpdateMailConfiguration extends Job
{
    /**
     * Site name.
     *
     * @var string
     */
    public $siteName;

    /**
     * E-mail address.
     *
     * @var string
     */
    public $emailAddress;

    /**
     * Construct a new mail configuration updater job.
     *
     * @param  string  $siteName
     * @param  string  $emailAddress
     */
    public function __construct(string $siteName, string $emailAddress)
    {
        $this->siteName = $siteName;
        $this->emailAddress = $emailAddress;
    }

    /**
     * Execute the job.
     *
     * @param  \Orchestra\Contracts\Memory\Provider  $memory
     *
     * @return void
     */
    public function handle(Provider $memory)
    {
        $config = \config('mail');

        $memory->put('email', Arr::only($config, ['driver', 'host', 'port', 'encryption', 'sendmail']));

        if ($config['username'] !== null) {
            $memory->securePut('email.username', $config['username']);
        }

        if ($config['password'] !== null) {
            $memory->securePut('email.password', $config['password']);
        }

        $memory->put('email.from', [
            'name' => $this->siteName,
            'address' => $this->emailAddress,
        ]);
    }
}

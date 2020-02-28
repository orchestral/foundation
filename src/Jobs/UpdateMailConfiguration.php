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

        $driver = $config['default'] ?? 'smtp';
        $smtp = $config['mailers']['smtp'];

        $memory->put('email', [
            'driver' => $driver,
            'host' => $smtp['host'],
            'port' => $smtp['port'],
            'encryption' => $smtp['encryption'],
            'sendmail' => $config['mailers']['sendmail']['path'],
        ]);

        if (! \is_null($smtp['username'])) {
            $memory->securePut('email.username', $smtp['username']);
        }

        if (! \is_null($smtp['password'])) {
            $memory->securePut('email.password', $smtp['password']);
        }

        $memory->put('email.from', [
            'name' => $this->siteName,
            'address' => $this->emailAddress,
        ]);
    }
}

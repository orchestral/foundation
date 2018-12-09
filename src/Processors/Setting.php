<?php

namespace Orchestra\Foundation\Processors;

use Illuminate\Support\Fluent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Config;
use Orchestra\Contracts\Memory\Provider;
use Orchestra\Support\Facades\Foundation;
use Orchestra\Foundation\Validations\Setting as Validator;
use Orchestra\Foundation\Http\Presenters\Setting as Presenter;
use Orchestra\Contracts\Foundation\Command\SystemUpdater as SystemUpdateCommand;
use Orchestra\Contracts\Foundation\Command\SettingUpdater as SettingUpdateCommand;
use Orchestra\Contracts\Foundation\Listener\SystemUpdater as SystemUpdateListener;
use Orchestra\Contracts\Foundation\Listener\SettingUpdater as SettingUpdateListener;

class Setting extends Processor implements SystemUpdateCommand, SettingUpdateCommand
{
    /**
     * The memory provider implementation.
     *
     * @var \Orchestra\Contracts\Memory\Provider
     */
    protected $memory;

    /**
     * Create a new processor instance.
     *
     * @param  \Orchestra\Foundation\Http\Presenters\Setting  $presenter
     * @param  \Orchestra\Foundation\Validations\Setting  $validator
     * @param  \Orchestra\Contracts\Memory\Provider  $memory
     */
    public function __construct(Presenter $presenter, Validator $validator, Provider $memory)
    {
        $this->presenter = $presenter;
        $this->validator = $validator;
        $this->memory = $memory;
    }

    /**
     * View setting page.
     *
     * @param  \Orchestra\Contracts\Foundation\Listener\SettingUpdater  $listener
     *
     * @return mixed
     */
    public function edit(SettingUpdateListener $listener)
    {
        // Orchestra settings are stored using Orchestra\Memory, we need to
        // fetch it and convert it to Fluent (to mimick Eloquent properties).
        $memory = $this->memory;

        $eloquent = new Fluent([
            'site_name' => $memory->get('site.name', ''),
            'site_description' => $memory->get('site.description', ''),
            'site_registrable' => ($memory->get('site.registrable', false) ? 'yes' : 'no'),

            'email_driver' => $memory->get('email.driver', ''),
            'email_address' => $memory->get('email.from.address', ''),
            'email_host' => $memory->get('email.host', ''),
            'email_port' => $memory->get('email.port', ''),
            'email_username' => $memory->get('email.username', ''),
            'email_password' => $memory->secureGet('email.password', ''),
            'email_encryption' => $memory->get('email.encryption', ''),
            'email_sendmail' => $memory->get('email.sendmail', ''),
            'email_queue' => ($memory->get('email.queue', false) ? 'yes' : 'no'),
            'email_key' => $memory->secureGet('email.key', ''),
            'email_secret' => $memory->secureGet('email.secret', ''),
            'email_domain' => $memory->get('email.domain', ''),
            'email_region' => $memory->get('email.region', ''),
        ]);

        $form = $this->presenter->form($eloquent);

        Event::dispatch('orchestra.form: settings', [$eloquent, $form]);

        return $listener->showSettingChanger(compact('eloquent', 'form'));
    }

    /**
     * Update setting.
     *
     * @param  \Orchestra\Contracts\Foundation\Listener\SettingUpdater  $listener
     * @param  array  $input
     *
     * @return mixed
     */
    public function update(SettingUpdateListener $listener, array $input)
    {
        $input = new Fluent($input);
        $driver = $this->getValue($input['email_driver'], 'mail.driver');

        $validation = $this->validator->on($driver)->with($input->toArray());

        if ($validation->fails()) {
            return $listener->settingFailedValidation($validation->getMessageBag());
        }

        $memory = $this->memory;

        $memory->put('site.name', $input['site_name']);
        $memory->put('site.description', $input['site_description']);
        $memory->put('site.registrable', ($input['site_registrable'] === 'yes'));
        $memory->put('email.driver', $driver);

        $memory->put('email.from', [
            'address' => $this->getValue($input['email_address'], 'mail.from.address'),
            'name' => $input['site_name'],
        ]);

        if ((empty($input['email_password']) && $input['enable_change_password'] === 'no')) {
            $input['email_password'] = $memory->secureGet('email.password');
        }

        if ((empty($input['email_secret']) && $input['enable_change_secret'] === 'no')) {
            $input['email_secret'] = $memory->secureGet('email.secret');
        }

        $memory->put('email.host', $this->getValue($input['email_host'], 'mail.host'));
        $memory->put('email.port', $this->getValue($input['email_port'], 'mail.port'));
        $memory->put('email.username', $this->getValue($input['email_username'], 'mail.username'));
        $memory->securePut('email.password', $this->getValue($input['email_password'], 'mail.password'));
        $memory->put('email.encryption', $this->getValue($input['email_encryption'], 'mail.encryption'));
        $memory->put('email.sendmail', $this->getValue($input['email_sendmail'], 'mail.sendmail'));
        $memory->put('email.queue', ($input['email_queue'] === 'yes'));
        $memory->securePut('email.key', $this->getValue($input['email_key'], "services.{$driver}.key"));
        $memory->securePut('email.secret', $this->getValue($input['email_secret'], "services.{$driver}.secret"));
        $memory->put('email.domain', $this->getValue($input['email_domain'], "services.{$driver}.domain"));
        $memory->put('email.region', $this->getValue($input['email_region'], "services.{$driver}.region"));

        Event::dispatch('orchestra.saved: settings', [$memory, $input]);

        return $listener->settingHasUpdated();
    }

    /**
     * Migrate Orchestra Platform components.
     *
     * @param  \Orchestra\Contracts\Foundation\Listener\SystemUpdater  $listener
     *
     * @return mixed
     */
    public function migrate(SystemUpdateListener $listener)
    {
        Foundation::make('orchestra.publisher.asset')->foundation();
        Foundation::make('orchestra.publisher.migrate')->foundation();

        return $listener->systemHasUpdated();
    }

    /**
     * Resolve value or grab from configuration.
     *
     * @param  mixed   $input
     * @param  string  $alternative
     *
     * @return mixed
     */
    private function getValue($input, $alternative)
    {
        if (empty($input)) {
            $input = Config::get($alternative);
        }

        return $input;
    }
}

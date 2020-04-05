<?php

namespace Orchestra\Foundation\Processors;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Fluent;
use Orchestra\Contracts\Foundation\Command\SettingUpdater as SettingUpdateCommand;
use Orchestra\Contracts\Foundation\Command\SystemUpdater as SystemUpdateCommand;
use Orchestra\Contracts\Foundation\Listener\SettingUpdater as SettingUpdateListener;
use Orchestra\Contracts\Foundation\Listener\SystemUpdater as SystemUpdateListener;
use Orchestra\Contracts\Memory\Provider;
use Orchestra\Foundation\Http\Presenters\Setting as Presenter;
use Orchestra\Foundation\Validations\Setting as Validator;
use Orchestra\Support\Facades\Foundation;

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
        $driver = $this->filledOrConfig($input['email_driver'], 'mail.driver');

        $validation = $this->validator->state($driver)->validate($input->toArray());

        if ($validation->fails()) {
            return $listener->settingFailedValidation($validation->getMessageBag());
        }

        $memory = $this->memory;

        $memory->put('site.name', $input['site_name']);
        $memory->put('site.description', $input['site_description']);
        $memory->put('site.registrable', ($input['site_registrable'] === 'yes'));
        $memory->put('email.driver', $driver);

        $memory->put('email.from', [
            'address' => $this->filledOrConfig($input['email_address'], 'mail.from.address'),
            'name' => $input['site_name'],
        ]);

        if ((empty($input['email_password']) && $input['enable_change_password'] === 'no')) {
            $input['email_password'] = $memory->secureGet('email.password');
        }

        if ((empty($input['email_secret']) && $input['enable_change_secret'] === 'no')) {
            $input['email_secret'] = $memory->secureGet('email.secret');
        }

        $memory->put('email.host', $this->filledOrConfig($input['email_host'], 'mail.mailers.smtp.host'));
        $memory->put('email.port', $this->filledOrConfig($input['email_port'], 'mail.mailers.smtp.port'));
        $memory->put('email.username', $this->filledOrConfig($input['email_username'], 'mail.mailers.smtp.username'));
        $memory->securePut('email.password', $this->filledOrConfig($input['email_password'], 'mail.mailers.smtp.password'));
        $memory->put('email.encryption', $this->filledOrConfig($input['email_encryption'], 'mail.mailers.smtp.encryption'));
        $memory->put('email.sendmail', $this->filledOrConfig($input['email_sendmail'], 'mail.mailers.sendmail.path'));
        $memory->put('email.queue', ($input['email_queue'] === 'yes'));

        // API related configuration.
        $memory->securePut('email.key', $this->filledOrConfig($input['email_key'], "services.{$driver}.key"));
        $memory->securePut('email.secret', $this->filledOrConfig($input['email_secret'], "services.{$driver}.secret"));
        $memory->put('email.domain', $this->filledOrConfig($input['email_domain'], "services.{$driver}.domain"));
        $memory->put('email.region', $this->filledOrConfig($input['email_region'], "services.{$driver}.region"));

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
    private function filledOrConfig($input, string $alternative)
    {
        if (\filled($input)) {
            return $input;
        }

        return \config($alternative);
    }
}

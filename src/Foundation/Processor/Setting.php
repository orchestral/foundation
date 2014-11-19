<?php namespace Orchestra\Foundation\Processor;

use Illuminate\Support\Fluent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Config;
use Orchestra\Contracts\Memory\Provider;
use Orchestra\Support\Facades\Foundation;
use Orchestra\Foundation\Presenter\Setting as Presenter;
use Orchestra\Foundation\Validation\Setting as Validator;
use Orchestra\Foundation\Contracts\Command\SystemUpdater as SystemUpdateCommand;
use Orchestra\Foundation\Contracts\Listener\SystemUpdater as SystemUpdateListener;
use Orchestra\Foundation\Contracts\Command\SettingUpdater as SettingUpdateCommand;
use Orchestra\Foundation\Contracts\Listener\SettingUpdater as SettingUpdateListener;

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
     * @param  \Orchestra\Foundation\Presenter\Setting  $presenter
     * @param  \Orchestra\Foundation\Validation\Setting  $validator
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
     * @param  \Orchestra\Foundation\Contracts\Listener\SettingUpdater  $listener
     * @return mixed
     */
    public function edit(SettingUpdateListener $listener)
    {
        // Orchestra settings are stored using Orchestra\Memory, we need to
        // fetch it and convert it to Fluent (to mimick Eloquent properties).
        $memory = $this->memory;

        $eloquent = new Fluent([
            'site_name'        => $memory->get('site.name', ''),
            'site_description' => $memory->get('site.description', ''),
            'site_registrable' => ($memory->get('site.registrable', false) ? 'yes' : 'no'),

            'email_driver'     => $memory->get('email.driver', ''),
            'email_address'    => $memory->get('email.from.address', ''),
            'email_host'       => $memory->get('email.host', ''),
            'email_port'       => $memory->get('email.port', ''),
            'email_username'   => $memory->get('email.username', ''),
            'email_password'   => $memory->get('email.password', ''),
            'email_encryption' => $memory->get('email.encryption', ''),
            'email_sendmail'   => $memory->get('email.sendmail', ''),
            'email_queue'      => ($memory->get('email.queue', false) ? 'yes' : 'no'),
            'email_secret'     => $memory->get('email.secret', ''),
            'email_domain'     => $memory->get('email.domain', ''),
        ]);

        $form = $this->presenter->form($eloquent);

        Event::fire('orchestra.form: settings', [$eloquent, $form]);

        return $listener->showSettingChanger(compact('eloquent', 'form'));
    }

    /**
     * Update setting.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\SettingUpdater  $listener
     * @param  array  $input
     * @return mixed
     */
    public function update(SettingUpdateListener $listener, array $input)
    {
        $input  = new Fluent($input);
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
            'name'    => $input['site_name'],
        ]);

        if ((empty($input['email_password']) && $input['change_password'] === 'no')) {
            $input['email_password'] = $memory->get('email.password');
        }

        $memory->put('email.host', $this->getValue($input['email_host'], 'mail.host'));
        $memory->put('email.port', $this->getValue($input['email_port'], 'mail.port'));
        $memory->put('email.username', $this->getValue($input['email_username'], 'mail.username'));
        $memory->put('email.password', $this->getValue($input['email_password'], 'mail.password'));
        $memory->put('email.encryption', $this->getValue($input['email_encryption'], 'mail.encryption'));
        $memory->put('email.sendmail', $this->getValue($input['email_sendmail'], 'mail.sendmail'));
        $memory->put('email.queue', ($input['email_queue'] === 'yes'));
        $memory->put('email.secret', $this->getValue($input['email_secret'], "services.{$driver}.secret"));
        $memory->put('email.domain', $this->getValue($input['email_domain'], "services.{$driver}.domain"));

        Event::fire('orchestra.saved: settings', [$memory, $input]);

        return $listener->settingHasUpdated();
    }

    /**
     * Migrate Orchestra Platform components.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\SystemUpdater  $listener
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

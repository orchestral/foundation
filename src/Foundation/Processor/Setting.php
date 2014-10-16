<?php namespace Orchestra\Foundation\Processor;

use Illuminate\Support\Fluent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Config;
use Orchestra\Support\Facades\Foundation;
use Orchestra\Foundation\Presenter\Setting as SettingPresenter;
use Orchestra\Foundation\Validation\Setting as SettingValidator;

class Setting extends AbstractableProcessor
{
    /**
     * Create a new processor instance.
     *
     * @param  \Orchestra\Foundation\Presenter\Setting  $presenter
     * @param  \Orchestra\Foundation\Validation\Setting $validator
     */
    public function __construct(SettingPresenter $presenter, SettingValidator $validator)
    {
        $this->presenter = $presenter;
        $this->validator = $validator;
    }

    /**
     * View setting page.
     *
     * @param  object  $listener
     * @return mixed
     */
    public function show($listener)
    {
        // Orchestra settings are stored using Orchestra\Memory, we need to
        // fetch it and convert it to Fluent (to mimick Eloquent properties).
        $memory   = Foundation::memory();
        $eloquent = new Fluent(array(
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
        ));

        $form = $this->presenter->form($eloquent);

        Event::fire('orchestra.form: settings', array($eloquent, $form));

        return $listener->showSucceed(compact('eloquent', 'form'));
    }

    /**
     * Update setting.
     *
     * @param  object  $listener
     * @param  array   $input
     * @return mixed
     */
    public function update($listener, array $input)
    {
        $input  = new Fluent($input);
        $driver = $this->resolveMailConfig($input['email_driver'], 'mail.driver');

        $validation = $this->validator->on($driver)->with($input->toArray());

        if ($validation->fails()) {
            return $listener->updateValidationFailed($validation);
        }
        $memory = Foundation::memory();

        $memory->put('site.name', $input['site_name']);
        $memory->put('site.description', $input['site_description']);
        $memory->put('site.registrable', ($input['site_registrable'] === 'yes'));
        $memory->put('email.driver', $driver);

        $memory->put('email.from', array(
            'address' => $this->resolveMailConfig($input['email_address'], 'mail.from.address'),
            'name'    => $input['site_name'],
        ));

        if ((empty($input['email_password']) && $input['change_password'] === 'no')) {
            $input['email_password'] = $memory->get('email.password');
        }

        $memory->put('email.host', $this->resolveMailConfig($input['email_host'], 'mail.host'));
        $memory->put('email.port', $this->resolveMailConfig($input['email_port'], 'mail.port'));
        $memory->put('email.username', $this->resolveMailConfig($input['email_username'], 'mail.username'));
        $memory->put('email.password', $this->resolveMailConfig($input['email_password'], 'mail.password'));
        $memory->put('email.encryption', $this->resolveMailConfig($input['email_encryption'], 'mail.encryption'));
        $memory->put('email.sendmail', $this->resolveMailConfig($input['email_sendmail'], 'mail.sendmail'));
        $memory->put('email.queue', ($input['email_queue'] === 'yes'));
        $memory->put('email.secret', $this->resolveMailConfig($input['email_secret'], "services.{$driver}.secret"));
        $memory->put('email.domain', $this->resolveMailConfig($input['email_domain'], "services.{$driver}.domain"));

        Event::fire('orchestra.saved: settings', array($memory, $input));

        return $listener->updateSucceed();
    }

    /**
     * Migrate Orchestra Platform components.
     *
     * @param  object  $listener
     * @return mixed
     */
    public function migrate($listener)
    {
        Foundation::make('orchestra.publisher.asset')->foundation();
        Foundation::make('orchestra.publisher.migrate')->foundation();

        return $listener->migrateSucceed();
    }

    /**
     * Resolve mail configuration.
     *
     * @param  mixed   $input
     * @param  string  $alternative
     * @return mixed
     */
    private function resolveMailConfig($input, $alternative)
    {
        if (empty($input)) {
            $input = Config::get($alternative);
        }

        return $input;
    }
}

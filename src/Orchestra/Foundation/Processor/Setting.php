<?php namespace Orchestra\Foundation\Processor;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Fluent;
use Orchestra\Foundation\Routing\BaseController;
use Orchestra\Foundation\Presenter\Setting as SettingPresenter;
use Orchestra\Foundation\Validation\Setting as SettingValidator;
use Orchestra\Support\Facades\App;

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
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @return mixed
     */
    public function show(BaseController $listener)
    {
        // Orchestra settings are stored using Orchestra\Memory, we need to
        // fetch it and convert it to Fluent (to mimick Eloquent properties).
        $memory   = App::memory();
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
        ));

        $form = $this->presenter->form($eloquent);

        Event::fire('orchestra.form: settings', array($eloquent, $form));

        return $listener->showSucceed(compact('eloquent', 'form'));
    }

    /**
     * Update setting.
     *
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @param  array                                           $input
     * @return mixed
     */
    public function update(BaseController $listener, array $input)
    {
        $default = array('email_driver' => 'mail');
        $input = array_merge($default, $input);

        $validation = $this->validator->on($input['email_driver'])->with($input);

        if ($validation->fails()) {
            return $listener->updateValidationFailed($validation);
        }

        $input  = new Fluent($input);
        $memory = App::memory();

        $memory->put('site.name', $input['site_name']);
        $memory->put('site.description', $input['site_description']);
        $memory->put('site.registrable', ($input['site_registrable'] === 'yes'));
        $memory->put('email.driver', $input['email_driver']);

        $memory->put('email.from', array(
            'address' => $input['email_address'],
            'name'    => $input['site_name'],
        ));

        if ((empty($input['email_password']) and $input['change_password'] === 'no')) {
            $input['email_password'] = $memory->get('email.password');
        }

        $memory->put('email.host', $input['email_host']);
        $memory->put('email.port', $input['email_port']);
        $memory->put('email.username', $input['email_username']);
        $memory->put('email.password', $input['email_password']);
        $memory->put('email.encryption', $input['email_encryption']);
        $memory->put('email.sendmail', $input['email_sendmail']);
        $memory->put('email.queue', ($input['email_queue'] === 'yes'));

        Event::fire('orchestra.saved: settings', array($memory, $input));

        return $listener->updateSucceed(
            trans('orchestra/foundation::response.settings.update')
        );
    }

    /**
     * Migrate Orchestra Platform components.
     *
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @return mixed
     */
    public function migrate(BaseController $listener)
    {
        App::make('orchestra.publisher.asset')->foundation();
        App::make('orchestra.publisher.migrate')->foundation();

        return $listener->migrateSucceed(
            trans('orchestra/foundation::response.settings.system-update')
        );
    }
}

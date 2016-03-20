<?php

namespace Orchestra\Foundation\Http\Presenters;

use Orchestra\Contracts\Html\Form\Fieldset;
use Orchestra\Contracts\Html\Form\Grid as FormGrid;
use Orchestra\Contracts\Html\Form\Factory as FormFactory;

class Setting extends Presenter
{
    /**
     * Construct a new User presenter.
     *
     * @param  \Orchestra\Contracts\Html\Form\Factory  $form
     */
    public function __construct(FormFactory $form)
    {
        $this->form = $form;
    }

    /**
     * Form View Generator for Setting Page.
     *
     * @param  \Illuminate\Support\Fluent  $model
     *
     * @return \Orchestra\Contracts\Html\Form\Builder
     */
    public function form($model)
    {
        return $this->form->of('orchestra.settings', function (FormGrid $form) use ($model) {
            $form->setup($this, 'orchestra::settings', $model);

            $this->application($form);
            $this->mailer($form, $model);
        });
    }

    /**
     * Form view generator for application configuration.
     *
     * @param  \Orchestra\Contracts\Html\Form\Grid  $form
     *
     * @return void
     */
    protected function application(FormGrid $form)
    {
        $form->fieldset(trans('orchestra/foundation::label.settings.application'), function (Fieldset $fieldset) {
            $fieldset->control('input:text', 'site_name')
                ->label(trans('orchestra/foundation::label.name'));

            $fieldset->control('textarea', 'site_description')
                ->label(trans('orchestra/foundation::label.description'))
                ->attributes(['rows' => 3]);

            $fieldset->control('select', 'site_registrable')
                ->label(trans('orchestra/foundation::label.settings.user-registration'))
                ->attributes(['role' => 'agreement'])
                ->options([
                    'yes' => 'Yes',
                    'no'  => 'No',
                ]);
        });
    }

    /**
     * Form view generator for email configuration.
     *
     * @param  \Orchestra\Contracts\Html\Form\Grid  $form
     * @param  \Illuminate\Support\Fluent  $model
     *
     * @return void
     */
    protected function mailer(FormGrid $form, $model)
    {
        $form->fieldset(trans('orchestra/foundation::label.settings.mail'), function (Fieldset $fieldset) use ($model) {
            $fieldset->control('select', 'email_driver')
                ->label(trans('orchestra/foundation::label.email.driver'))
                ->options([
                    'mail'     => 'Mail',
                    'smtp'     => 'SMTP',
                    'sendmail' => 'Sendmail',
                    'ses'      => 'Amazon SES',
                    'mailgun'  => 'Mailgun',
                    'mandrill' => 'Mandrill',
                ]);

            $fieldset->control('input:text', 'email_host')
                ->label(trans('orchestra/foundation::label.email.host'));

            $fieldset->control('input:text', 'email_port')
                ->label(trans('orchestra/foundation::label.email.port'));

            $fieldset->control('input:text', 'email_address')
                ->label(trans('orchestra/foundation::label.email.from'));

            $fieldset->control('input:text', 'email_username')
                ->label(trans('orchestra/foundation::label.email.username'));

            $fieldset->control('input:password', 'email_password')
                ->label(trans('orchestra/foundation::label.email.password'))
                ->help(view('orchestra/foundation::settings._hidden', [
                    'value'  => $model['email_password'],
                    'action' => 'change_password',
                    'field'  => 'email_password',
                ]));

            $fieldset->control('input:text', 'email_encryption')
                ->label(trans('orchestra/foundation::label.email.encryption'));

            $fieldset->control('input:text', 'email_key')
                ->label(trans('orchestra/foundation::label.email.key'));

            $fieldset->control('input:password', 'email_secret')
                ->label(trans('orchestra/foundation::label.email.secret'))
                ->help(view('orchestra/foundation::settings._hidden', [
                    'value'  => $model['email_secret'],
                    'action' => 'change_secret',
                    'field'  => 'email_secret',
                ]));

            $fieldset->control('input:text', 'email_domain')
                ->label(trans('orchestra/foundation::label.email.domain'));

            $fieldset->control('select', 'email_region')
                ->label(trans('orchestra/foundation::label.email.region'))
                ->options([
                    'us-east-1' => 'us-east-1',
                    'us-west-2' => 'us-west-2',
                    'eu-west-1' => 'eu-west-1',
                ]);

            $fieldset->control('input:text', 'email_sendmail')
                ->label(trans('orchestra/foundation::label.email.command'));

            $fieldset->control('select', 'email_queue')
                ->label(trans('orchestra/foundation::label.email.queue'))
                ->attributes(['role' => 'agreement'])
                ->options([
                    'yes' => 'Yes',
                    'no'  => 'No',
                ]);
        });
    }
}

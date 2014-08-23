<?php namespace Orchestra\Foundation\Presenter;

use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\Form;

class Setting extends AbstractablePresenter
{
    /**
     * Form View Generator for Setting Page.
     *
     * @param  \Illuminate\Support\Fluent   $model
     * @return \Orchestra\Html\Form\FormBuilder
     */
    public function form($model)
    {
        return Form::of('orchestra.settings', function ($form) use ($model) {
            $form->setup($this, 'orchestra::settings', $model);

            $this->application($form);
            $this->mailer($form, $model);
        });
    }

    /**
     * Form view generator for application configuration.
     *
     * @return \Orchestra\Html\Form\Grid $form
     * @return void
     */
    protected function application($form)
    {
        $form->fieldset(trans('orchestra/foundation::label.settings.application'), function ($fieldset) {
            $fieldset->control('input:text', 'site_name')
                ->label(trans('orchestra/foundation::label.name'));

            $fieldset->control('textarea', 'site_description')
                ->label(trans('orchestra/foundation::label.description'))
                ->attributes(array('rows' => 3));

            $fieldset->control('select', 'site_registrable')
                ->label(trans('orchestra/foundation::label.settings.user-registration'))
                ->attributes(array('role' => 'switcher'))
                ->options(array(
                    'yes' => 'Yes',
                    'no'  => 'No',
                ));
        });
    }

    /**
     * Form view generator for email configuration.
     *
     * @param  \Orchestra\Html\Form\Grid  $form
     * @param  \Illuminate\Support\Fluent $model
     * @return void
     */
    protected function mailer($form, $model)
    {
        $form->fieldset(trans('orchestra/foundation::label.settings.mail'), function ($fieldset) use ($model) {
            $fieldset->control('select', 'email_driver')
                ->label(trans('orchestra/foundation::label.email.driver'))
                ->options(array(
                    'mail'     => 'Mail',
                    'smtp'     => 'SMTP',
                    'sendmail' => 'Sendmail',
                    'mailgun'  => 'Mailgun',
                    'mandrill' => 'Mandrill',
                ));

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
                ->help(View::make('orchestra/foundation::settings.email-password', compact('model')));

            $fieldset->control('input:text', 'email_encryption')
                ->label(trans('orchestra/foundation::label.email.encryption'));

            $fieldset->control('input:text', 'email_secret')
                ->label(trans('orchestra/foundation::label.email.secret'));

            $fieldset->control('input:text', 'email_domain')
                ->label(trans('orchestra/foundation::label.email.domain'));

            $fieldset->control('input:text', 'email_sendmail')
                ->label(trans('orchestra/foundation::label.email.command'));

            $fieldset->control('select', 'email_queue')
                ->label(trans('orchestra/foundation::label.email.queue'))
                ->attributes(array('role' => 'switcher'))
                ->options(array(
                    'yes' => 'Yes',
                    'no'  => 'No',
                ));
        });
    }
}

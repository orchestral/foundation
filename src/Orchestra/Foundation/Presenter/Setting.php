<?php namespace Orchestra\Foundation\Presenter;

use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\Form;

class Setting
{
    /**
     * Form View Generator for Setting Page.
     *
     * @param  \Illuminate\Support\Fluent   $model
     * @return \Orchestra\Html\Form\FormBuilder
     */
    public function form($model)
    {
        $self = $this;

        return Form::of('orchestra.settings', function ($form) use ($self, $model) {
            $form->with($model);
            $form->layout('orchestra/foundation::components.form');
            $form->attributes(array(
                'url'    => handles('orchestra::settings'),
                'method' => 'POST',
            ));

            $self->applicationForm($form);
            $self->mailerForm($form, $model);
        });
    }

    /**
     * Form view generator for application configuration.
     *
     * @return \Orchestra\Html\Form\FormBuilder $form
     * @return void
     */
    public function applicationForm($form)
    {
        $form->fieldset(trans('orchestra/foundation::label.settings.application'), function ($fieldset) {
            $fieldset->control('input:text', 'site_name', function ($control) {
                $control->label(trans('orchestra/foundation::label.name'));
            });

            $fieldset->control('textarea', 'site_description', function ($control) {
                $control->label(trans('orchestra/foundation::label.description'));
                $control->attributes(array('rows' => 3));
            });

            $fieldset->control('select', 'site_registrable', function ($control) {
                $control->label(trans('orchestra/foundation::label.settings.user-registration'));
                $control->attributes(array('role' => 'switcher'));
                $control->options(array(
                    'yes' => 'Yes',
                    'no'  => 'No',
                ));
            });
        });
    }

    /**
     * Form view generator for email configuration.
     *
     * @param  \Orchestra\Html\Form\FormBuilder $form
     * @param  \Illuminate\Support\Fluent       $model
     * @return void
     */
    public function mailerForm($form, $model)
    {
        $form->fieldset(trans('orchestra/foundation::label.settings.mail'), function ($fieldset) use ($model) {
            $fieldset->control('select', 'email_driver', function ($control) {
                $control->label(trans('orchestra/foundation::label.email.driver'));
                $control->options(array(
                    'mail'     => 'Mail',
                    'smtp'     => 'SMTP',
                    'sendmail' => 'Sendmail'
                ));
            });

            $fieldset->control('input:text', 'email_host', function ($control) {
                $control->label(trans('orchestra/foundation::label.email.host'));
            });

            $fieldset->control('input:text', 'email_port', function ($control) {
                $control->label(trans('orchestra/foundation::label.email.port'));
            });

            $fieldset->control('input:text', 'email_address', function ($control) {
                $control->label(trans('orchestra/foundation::label.email.from'));
            });

            $fieldset->control('input:text', 'email_username', function ($control) {
                $control->label(trans('orchestra/foundation::label.email.username'));
            });

            $fieldset->control('input:password', 'email_password', function ($control) use ($model) {
                $control->label(trans('orchestra/foundation::label.email.password'));
                $control->help(View::make('orchestra/foundation::settings.email-password', compact('model')));
            });

            $fieldset->control('input:text', 'email_encryption', function ($control) {
                $control->label(trans('orchestra/foundation::label.email.encryption'));
            });

            $fieldset->control('input:text', 'email_sendmail', function ($control) {
                $control->label(trans('orchestra/foundation::label.email.command'));
            });

            $fieldset->control('select', 'email_queue', function ($control) {
                $control->label(trans('orchestra/foundation::label.email.queue'));
                $control->attributes(array('role' => 'switcher'));
                $control->options(array(
                    'yes' => 'Yes',
                    'no'  => 'No',
                ));
            });
        });
    }
}

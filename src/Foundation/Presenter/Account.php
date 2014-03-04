<?php namespace Orchestra\Foundation\Presenter;

use Orchestra\Support\Facades\Form;

class Account extends AbstractablePresenter
{
    /**
     * Form view generator for User Account.
     *
     * @param  \Orchestra\Model\User    $model
     * @param  string                   $url
     * @return \Orchestra\Html\Form\FormBuilder
     */
    public function profile($model, $url)
    {
        return Form::of('orchestra.account', function ($form) use ($model, $url) {
            $form->setup($this, $url, $model);
            $form->hidden('id');

            $form->fieldset(function ($fieldset) {
                $fieldset->control('input:text', 'email', function ($control) {
                    $control->label(trans('orchestra/foundation::label.users.email'));
                });

                $fieldset->control('input:text', 'fullname', function ($control) {
                    $control->label(trans('orchestra/foundation::label.users.fullname'));
                });
            });
        });
    }

    /**
     * Form view generator for user account edit password.
     *
     * @param  \Orchestra\Model\User    $model
     * @return \Orchestra\Html\Form\FormBuilder
     */
    public function password($model)
    {
        return Form::of('orchestra.account: password', function ($form) use ($model) {
            $form->setup($this, 'orchestra::account/password', $model);
            $form->hidden('id');

            $form->fieldset(function ($fieldset) {
                $fieldset->control('input:password', 'current_password', function ($control) {
                    $control->label(trans('orchestra/foundation::label.account.current_password'));
                });

                $fieldset->control('input:password', 'new_password', function ($control) {
                    $control->label(trans('orchestra/foundation::label.account.new_password'));
                });

                $fieldset->control('input:password', 'confirm_password', function ($control) {
                    $control->label(trans('orchestra/foundation::label.account.confirm_password'));
                });
            });
        });
    }
}

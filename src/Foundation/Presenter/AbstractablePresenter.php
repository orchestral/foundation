<?php namespace Orchestra\Foundation\Presenter;

use Orchestra\Html\Form\PresenterInterface as FormPresenterInterface;

abstract class AbstractablePresenter implements FormPresenterInterface
{
    /**
     * {@inheritdoc}
     */
    public function handles($url)
    {
        return handles($url);
    }

    /**
     * {@inheritdoc}
     */
    public function setupForm($form)
    {
        $form->layout('orchestra/foundation::components.form');
    }
}

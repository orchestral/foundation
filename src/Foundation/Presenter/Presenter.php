<?php namespace Orchestra\Foundation\Presenter;

use Orchestra\Contracts\Html\Form\Grid;
use Orchestra\Contracts\Html\Form\Presenter as PresenterContract;

abstract class Presenter implements PresenterContract
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
    public function setupForm(Grid $form)
    {
        $form->layout('orchestra/foundation::components.form');
    }
}

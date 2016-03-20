<?php

namespace Orchestra\Foundation\Http\Presenters;

use Orchestra\Contracts\Html\Form\Grid;
use Orchestra\Contracts\Html\Form\Presenter as PresenterContract;

abstract class Presenter implements PresenterContract
{
    /**
     * Implementation of form contract.
     *
     * @var \Orchestra\Contracts\Html\Form\Factory
     */
    protected $form;

    /**
     * Implementation of table contract.
     *
     * @var \Orchestra\Contracts\Html\Table\Factory
     */
    protected $table;

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

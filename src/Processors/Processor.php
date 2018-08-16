<?php

namespace Orchestra\Foundation\Processors;

abstract class Processor
{
    /**
     * Presenter instance.
     *
     * @var object
     */
    protected $presenter;

    /**
     * Validator instance.
     *
     * @var object
     */
    protected $validator;
}

<?php namespace Orchestra\Foundation\Processor;

abstract class Authenticate extends Processor
{
    /**
     * The auth guard implementation.
     *
     * @var \Orchestra\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Get user.
     *
     * @return \Orchestra\Model\User|null
     */
    protected function getUser()
    {
        return $this->auth->getUser();
    }
}

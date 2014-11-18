<?php namespace Orchestra\Foundation\Contracts\Listener;

interface AssetPublishing
{
    /**
     * Response to publishing asset failed.
     *
     * @param  array $errors
     * @return mixed
     */
    public function publishFailed(array $errors);

    /**
     * Redirect back to current publisher.
     *
     * @return mixed
     */
    public function redirectToCurrentPublisher();

    /**
     * Response to asset published.
     *
     * @return mixed
     */
    public function publishSucceed();
}

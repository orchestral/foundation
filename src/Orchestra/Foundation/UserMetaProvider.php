<?php namespace Orchestra\Foundation;

use Orchestra\Memory\Provider;

class UserMetaProvider extends Provider
{
    /**
     * Get value of a key
     *
     * @param  string   $key        A string of key to search.
     * @param  mixed    $default    Default value if key doesn't exist.
     * @return mixed
     */
    public function get($key = null, $default = null)
    {
        $key   = str_replace('.', '/user-', $key);
        $value = array_get($this->items, $key);

        // We need to consider if the value pending to be deleted,
        // in this case return the default.
        if ($value === ':to-be-deleted:') {
            return value($default);
        }

        // If the result is available from data, simply return it so we
        // don't have to fetch the same result again from the database.
        if (! is_null($value)) {
            return $value;
        }

        if (is_null($value = $this->handler->retrieve($key))) {
            return value($default);
        }

        $this->put($key, $value);

        return $value;
    }

    /**
     * Set a value from a key.
     *
     * @param  string   $key        A string of key to add the value.
     * @param  mixed    $value      The value.
     * @return mixed
     */
    public function put($key, $value = '')
    {
        $key   = str_replace('.', '/user-', $key);
        $value = value($value);

        $this->set($key, $value);

        return $value;
    }

    /**
     * Delete value of a key.
     *
     * @param  string   $key        A string of key to delete.
     * @return boolean
     */
    public function forget($key = null)
    {
        $key = str_replace('.', '/user-', $key);
        return array_set($this->items, $key, ':to-be-deleted:');
    }
}

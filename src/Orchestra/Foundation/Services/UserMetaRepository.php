<?php namespace Orchestra\Foundation\Services;

use Orchestra\Memory\Drivers\Driver;

class UserMetaRepository extends Driver {
	
	/**
	 * Storage name.
	 * 
	 * @var string  
	 */
	protected $storage = 'user';

	/**
	 * Model name.
	 *
	 * @var \Orchestra\Model\UserMeta
	 */
	protected $model = null;

	/**
	 * Initiate the instance.
	 *
	 * @return void
	 */
	public function initiate() 
	{
		$this->model = $this->app->make('Orchestra\Model\UserMeta');
	}

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
		$value = array_get($this->data, $key);

		// We need to consider if the value pending to be deleted, 
		// in this case return the default.
		if ($value === ':to-be-deleted:') return $default;

		// If the result is available from data, simply return it so we
		// don't have to fetch the same result again from the database.
		if ( ! is_null($value)) return $value;

		return $this->retrieveFromEloquent($key, $default);
	}

	/**
	 * Get value from database.
	 * 
	 * @param  string   $key
	 * @param  mixed    $default
	 * @return mixed
	 */
	protected function retrieveFromEloquent($key, $default = null)
	{
		list($name, $userId) = explode('/user-', $key);

		$userMeta = $this->model->search($name, $userId)->first();

		if ( ! is_null($userMeta))
		{
			$this->put($key, $userMeta->value);

			$this->addKey($key, array(
				'id'    => $userMeta->id,
				'value' => $userMeta->value,
			));

			return $userMeta->value;
		}

		$this->put($key, null);

		return value($default);
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
		array_set($this->data, $key, $value);

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
		return array_set($this->data, $key, ':to-be-deleted:');
	}

	/**
	 * Add a finish event.
	 *
	 * @return void
	 */
	public function finish() 
	{
		foreach ($this->data as $key => $value)
		{
			$this->save($key, $value);
		}
	}

	/**
	 * Save user meta to memory.
	 * 
	 * @param  mixed    $key
	 * @param  mixed    $value
	 * @return void
	 */
	protected function save($key, $value)
	{
		$model = $this->model;
		$isNew = $this->isNewKey($key);

		list($name, $userId) = explode('/user-', $key);

		// We should be able to ignore this if user id is empty or checksum 
		// return the same value (no change occured).
		if ($this->check($key, $value) or empty($userId)) return ;

		$meta = $model->newInstance()->search($name, $userId)->first();

		// Deleting a configuration is determined by ':to-be-deleted:'. It 
		// would be extremely weird if that is used for other purposed.
		if (is_null($value) or $value === ':to-be-deleted:')
		{
			! is_null($meta) and $meta->delete();
			return ;
		}
		
		// If the content is a new configuration, let push it as a insert 
		// instead of an update to Eloquent.
		if (true === $isNew and is_null($meta))
		{
			$meta = $model->newInstance();

			$meta->name    = $name;
			$meta->user_id = $userId;
		}
		
		$meta->value = $value;
		$meta->save();
	}
}

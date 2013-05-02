<?php namespace Orchestra\Services;

use Orchestra\Model\UserMeta;
use Orchestra\Memory\Drivers\Driver;

class UserMetaRepository extends Driver {
	
	/**
	 * Storage name
	 * 
	 * @access  protected
	 * @var     string  
	 */
	protected $storage = 'user';

	/**
	 * Initiate the instance.
	 *
	 * @access  public
	 * @return  void
	 */
	public function initiate() {}

	/**
	 * Get value of a key
	 *
	 * @access  public
	 * @param   string  $key        A string of key to search.
	 * @param   mixed   $default    Default value if key doesn't exist.
	 * @return  mixed
	 */
	public function get($key = null, $default = null)
	{
		$key   = str_replace('.', '/user-', $key);
		$value = array_get($this->data, $key, null);

		if ( ! is_null($value)) return $value;

		list($name, $userId) = explode('/user-', $key);

		$userMeta = UserMeta::search($name, $userId)->first();

		if ( ! is_null($userMeta))
		{
			$this->put($key, $userMeta->value);

			$this->addKey($key, array(
				'id'    => $key,
				'value' => $userMeta->value,
			));

			return $userMeta->value;
		}

		$this->put($key, null);

		return value($default);
	}

	/**
	 * Set a value from a key
	 *
	 * @access  public
	 * @param   string  $key        A string of key to add the value.
	 * @param   mixed   $value      The value.
	 * @return  mixed
	 */
	public function put($key, $value = '')
	{
		$key   = str_replace('.', '/user-', $key);
		$value = value($value);
		array_set($this->data, $key, $value);

		return $value;
	}

	/**
	 * Delete value of a key
	 *
	 * @access  public
	 * @param   string  $key        A string of key to delete.
	 * @return  bool
	 */
	public function forget($key = null)
	{
		$key = str_replace('.', '/user-', $key);
		return array_set($this->data, $key, null);
	}

	/**
	 * Add a shutdown event.
	 *
	 * @access  public
	 * @return  void
	 */
	public function shutdown() 
	{
		foreach ($this->data as $key => $value)
		{
			$isNew    = $this->isNewKey($key);
			$checksum = '';

			list($name, $userId) = explode('/user-', $key);

			if ($this->check($key, $value) or empty($userId)) continue;

			$userMeta = UserMeta::search($name, $userId)->first();

			if (true === $isNew and is_null($userMeta))
			{
				if (is_null($value)) continue;

				// Insert the new key:value
				$userMeta          = new UserMeta;
				$userMeta->value   = $value;
				$userMeta->name    = $name;
				$userMeta->user_id = $userId;

				$userMeta->save();
			}
			else
			{
				if (is_null($value))
				{
					$userMeta->delete();
				}
				else
				{
					$userMeta->value = $value;
					$userMeta->save();
				}
			}
		}
	}
}

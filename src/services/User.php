<?php namespace Orchestra\Services;

use Orchestra\Model\UserMeta;
use Orchestra\Support\Memory\Drivers\Driver;

class User extends Driver {
	
	/**
	 * Storage name
	 * 
	 * @access  protected
	 * @var     string  
	 */
	protected $storage = 'userMeta';

	/**
	 * Cached key value map with md5 checksum
	 *
	 * @access  protected
	 * @var     array
	 */
	protected $keyMap = array();

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

		$userMeta = UserMeta::name($name, $userId);

		if ( ! is_null($user_meta))
		{
			$this->put($key, $userMeta->value);

			$this->keyMap[$key] = array(
				'id'       => $key,
				'checksum' => md5($userMeta->value),
			);

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
			$isNew    = true;
			$checksum = '';
			
			if (array_key_exists($key, $this->keyMap))
			{
				$is_new = false;
				extract($this->keyMap[$key]);
			}

			list($name, $userId) = explode('/user-', $key);

			if ($checksum === md5($value) or empty($user_id)) continue;

			$userMeta = UserMeta::where('name', '=', $name)
						->where('user_id', '=', $userId)->first();

			if (true === $isNew and is_null($userMeta))
			{
				if (is_null($value)) continue;

				// Insert the new key:value
				UserMeta::create(array(
					'name'    => $name,
					'user_id' => $userId,
					'value'   => $value,
				));
			}
			else
			{
				if (is_null($value))
				{
					$userMeta->delete();
				}
				else
				{
					// Update the key:value
					$userMeta->value = $value;
					$userMeta->save();
				}
			}
		}
	}
}

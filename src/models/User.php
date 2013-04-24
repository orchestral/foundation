<?php namespace Orchestra\Model;

use Illuminate\Auth\UserInterface,
	Illuminate\Auth\Reminders\RemindableInterface,
	Illuminate\Database\Eloquent\Model as Eloquent,
	Illuminate\Support\Facades\Hash;

class User extends Eloquent implements UserInterface, RemindableInterface {

	/**
	 * Available user status as constant.
	 */
	const UNVERIFIED = 0;
	const VERIFIED   = 1;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Has many and belongs to relationship with Role.
	 *
	 * @access public
	 * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function roles() 
	{
		return $this->belongsToMany('\Orchestra\Model\Role', 'user_role');
	}

	/**
	 * Set `password` mutator.
	 *
	 * @access public
	 * @param  string   $value
	 * @return void
	 */
	public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}
}
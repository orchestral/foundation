<?php namespace Orchestra\Model;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Hash;

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
	 * Indicates if the model should soft delete.
	 *
	 * @var bool
	 */
	protected $softDelete = true;

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
	 * Search user based on keyword as roles.
	 *
	 * @access public
	 * @param  Illuminate\Database\Eloquent\Builder $query
	 * @param  string                               $keyword
	 * @param  array                                $roles
	 * @return Orchestra\Model\User
	 */
	public function scopeSearch($query, $keyword = '', $roles = array())
	{
		$query->with('roles')->whereNotNull('users.id');
		
		if ( ! empty($roles))
		{
			$query->join('user_role', 'users.id', '=', 'user_role.user_id')
				->whereIn('user_role.role_id', $roles);
		}

		if ( ! empty($keyword))
		{
			$query->where(function ($query) use ($keyword)
			{
				$query->where('email', 'LIKE', $keyword)
					->orWhere('fullname', 'LIKE', $keyword);
			});
		}

		return $query;
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

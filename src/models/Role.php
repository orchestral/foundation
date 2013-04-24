<?php namespace Orchestra\Model;

use Illuminate\Database\Eloquent\Model as Eloquent,
	Orchestra\Acl;

class Role extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'roles';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array(
		'name',
	);

	/**
	 * Has many and belongs to relationship with User.
	 *
	 * @access public
	 * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function users() 
	{
		return $this->belongsToMany('\Orchestra\Model\User', 'user_role');
	}

	/**
	 * The "booting" method of the model.
	 *
	 * @return void
	 */
	protected static function boot()
	{
		parent::boot();

		static::creating(function ($role)
		{
			Acl::addRole($role->name);
		});

		static::updating(function ($role)
		{
			$oldName = $role->original['name'];
			Acl::renameRole($oldName, $role->name);
		});

		static::deleting(function ($role)
		{
			Acl::removeRole($role->name);
		});
	}
}
